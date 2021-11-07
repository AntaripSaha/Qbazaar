<template>
  <div>
    <SidebarSkelton v-if="loading && init" />
    <div v-else id="sidebar">
      <router-link
        class="nav-link"
        v-on:click.native="triggerNav"
        :to="{ name: 'home' }"
        exact
      >
        <i class="icofont-home"></i> {{ $lang["Home"] }}
      </router-link>

      <router-link
        class="nav-link"
        v-on:click.native="triggerNav"
        :to="{ name: 'offersProducts' }"
        exact
      >
        <i class="icofont-gift"></i> {{ $lang["Special Offers"] }}
      </router-link>

      <span v-for="category in allCategories" :key="category.id">
        <span
          v-if="category.sub_categories.length && category.parent_id == null"
        >
          <a
            class="nav-link collapsed"
            href="#"
            data-toggle="collapse"
            :data-target="'#' + category.slug"
            aria-expanded="false"
            aria-controls="collapseLayouts"
          >
            <span v-html="category.icon"></span> {{ category.name }}
            <span class="sidenav-collapse-arrow"
              ><i class="icofont-rounded-down"></i
            ></span>
          </a>
          <div class="collapse" :id="category.slug" data-parent="#sidebar">
            <nav
              class="sidenav-menu-nested"
              v-for="sub_category in category.sub_categories"
              :key="sub_category.id"
            >
              <router-link
                class="nav-link"
                v-on:click.native="triggerNav"
                :to="{
                  name: 'categoryProducts',
                  params: { slug: sub_category.slug },
                }"
                >{{ sub_category.name }}</router-link
              >
            </nav>
          </div>
        </span>

        <span
          v-if="
            category.sub_categories.length == 0 && category.parent_id == null
          "
        >
          <router-link
            class="nav-link"
            v-on:click.native="triggerNav"
            :to="{ name: 'categoryProducts', params: { slug: category.slug } }"
          >
            <span v-html="category.icon"></span> {{ category.name }}
          </router-link>
        </span>
      </span>
    </div>
  </div>
</template>

<script>
import SidebarSkelton from "./skeleton/Sidebar";
export default {
  components: {
    SidebarSkelton,
  },
  computed: {
    allCategories: function () {
      return this.$store.getters["category/allCategories"];
    },

    loading: function () {
      return this.$store.getters["category/loading"];
    },

    init: function () {
      return this.$store.getters["category/init"];
    },
  },
  methods: {
    triggerNav() {
      document.getElementById("content-area").classList.add("mobile-nav");
    },
  },
};
</script>
