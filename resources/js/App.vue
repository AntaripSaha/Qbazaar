<template>
  <div v-if="!isSettingsLoading">
    <header>
      <div class="main-menu-area">
        <div class="container-fluid">
          <div class="d-flex align-items-center">
            <div class="left-header">
              <span id="sidebarToggle">
                <div class="lines">
                  <span></span>
                  <span></span>
                  <span></span>
                  <span></span>
                </div>
              </span>
              <a href="">
                <img
                  src="/theme/img/website-logo.png"
                  class="logo d-none d-md-inline-block"
                />
              </a>
            </div>

            <div class="search-area flex-grow-1">
              <div class="home-search">
                <input
                  type="text"
                  name="search"
                  id="search"
                  @keyup="searchProduct($event)"
                  :value="keyword"
                  autocomplete="off"
                  placeholder="Search Products"
                />
                <button type="button" class="search-btn d-none d-md-block">
                  <i class="icofont-search"></i>
                </button>
              </div>
            </div>

            <div class="right-header ml-lg-2">
              <div class="menu-extra float-right d-none d-lg-inline-block">
                <ul>
                  <li v-if="isLoggedIn">
                    <router-link :to="{ name: 'profile' }"
                      ><i class="icofont-ui-user"></i>
                      {{ $lang["Account"] }}</router-link
                    >
                  </li>

                  <li v-if="isLoggedIn">
                    <a href="#" @click="logout" class="btn-signin"
                      ><i class="icofont-exit"></i> {{ $lang["Logout"] }}</a
                    >
                  </li>

                  <li v-if="!isLoggedIn">
                    <router-link
                      :to="{ name: 'register' }"
                      exact
                      class="btn-signup"
                      ><i class="icofont-user-alt-7"></i>
                      {{ $lang["Sign Up"] }}</router-link
                    >
                  </li>

                  <li v-if="!isLoggedIn">
                    <router-link
                      :to="{ name: 'login' }"
                      exact
                      class="btn-signin"
                      ><i class="icofont-sign-in"></i>
                      {{ $lang["Sign In"] }}</router-link
                    >
                  </li>
                </ul>
              </div>
              <router-link
                class="btn btn-action btn-sm d-lg-none ml-2"
                :to="{ name: 'profile' }"
                ><i class="icofont-ui-user"></i
              ></router-link>
            </div>
          </div>
        </div>
      </div>
    </header>

    <ShoppingCart />

    <div id="content-area" class="mobile-nav">
      <Sidebar />
      <div id="content">
        <event-hub></event-hub>
        <div class="main-content">
          <router-view></router-view>
        </div>
        <Footer></Footer>
      </div>
    </div>
  </div>
</template>

<script>
import EventHub from "./components/EventHub.vue";
import Footer from "./components/Footer.vue";
import ShoppingCart from "./components/ShoppingCart.vue";
import Sidebar from "./components/Sidebar.vue";

export default {
  components: {
    EventHub,
    Sidebar,
    ShoppingCart,
    Footer,
  },
  computed: {
    isLoggedIn: function () {
      return this.$store.getters["auth/isLoggedIn"];
    },
    user: function () {
      return this.$store.getters["auth/user"];
    },
    keyword: function () {
      return this.$store.getters["search/keyword"];
    },
    isSettingsLoading: function () {
      return this.$store.getters["settings/loading"];
    },
  },
  methods: {
    logout: function () {
      this.$store.dispatch("auth/logout").then(() => {
        this.$router.push("/login");
      });
    },
    searchProduct: function ($event) {
      const keyword = $event.target.value;
      this.$store.dispatch("search/setKeyword", keyword);
    },
  },
  created: function () {
    //this.$store.dispatch("settings/getSettings");
    this.$store.dispatch("category/fetchCategories");

    this.$http.interceptors.response.use(
      (response) => {
        return response;
      },
      (error) => {
        if (
          error.response.status === 401 &&
          error.response.config &&
          !error.response.config.__isRetryRequest
        ) {
          this.$store.dispatch("auth/logout").then(() => {
            if (this.$route.meta.requiresAuth) {
              this.$router.push("/login");
            }
          });
        }
        return Promise.reject(error);
      }
    );

    const token = localStorage.getItem("token");
    if (token) {
      this.$store.dispatch("auth/getProfile");
    }
  },
  watch: {
    keyword: function () {
      this.$store.dispatch("search/search");
      document.getElementById("search").focus();
      if (this.$route.name !== "searchProducts") {
        this.$router.push({ name: "searchProducts" });
      }

      if (this.keyword == "") {
        if (this.$route.name !== "home") {
          this.$router.push({ name: "home" });
        }
      }
    },
  },
};
</script>
