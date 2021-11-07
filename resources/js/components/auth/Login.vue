<template>
  <div class="container">
    <div class="row">
      <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
        <div class="card card-signin my-5">
          <div class="card-body">
            <h5 class="card-title text-center pt-2 pb-3">
              {{ $lang["Login to your account"] }}
            </h5>
            <form class="form-signin" @submit.prevent="login">
              <div class="form-label-group mb-3">
                <input
                  type="email"
                  id="email"
                  class="form-control"
                  v-model="email"
                  :placeholder="$lang['Email address']"
                  required
                />
              </div>

              <div class="form-label-group mb-3">
                <input
                  type="password"
                  id="password"
                  class="form-control"
                  v-model="password"
                  :placeholder="$lang['Password']"
                  required
                />
              </div>

              <button
                type="submit"
                class="
                  btn btn-lg btn-primary btn-block
                  text-uppercase
                  btn-login
                  py-3
                  mt-2
                "
              >
                <span
                  v-if="loading"
                  class="spinner-border spinner-border-sm"
                ></span>
                <span v-if="!loading">{{ $lang["Sign in"] }}</span>
              </button>

              <button
                v-if="isActiveGoogleLogin"
                type="button"
                @click="AuthProvider('google')"
                class="
                  btn btn-lg btn-google btn-block
                  text-uppercase
                  btn-login
                  py-3
                  mt-2
                "
              >
                <i class="icofont-brand-google"></i> {{ $lang["Google Login"] }}
              </button>

              <button
                v-if="isActiveFacebookLogin"
                type="button"
                @click="AuthProvider('facebook')"
                class="
                  btn btn-lg btn-facebook btn-block
                  text-uppercase
                  btn-login
                  py-3
                  mt-2
                "
              >
                <i class="icofont-facebook"></i> {{ $lang["Facebook Login"] }}
              </button>

              <router-link
                :to="{ name: 'register' }"
                class="
                  btn btn-lg btn-light
                  text-dark
                  btn-block
                  text-uppercase
                  py-3
                  mb-1
                "
                >{{ $lang["Create an Account"] }}</router-link
              >
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  data() {
    return {
      email: "",
      password: "",
    };
  },
  computed: {
    ...mapGetters("auth", ["errors", "loading", "requestPage"]),
    isActiveGoogleLogin() {
      let google_login = this.$store.getters["settings/settings"].google_login;

      if (google_login == "enabled") {
        return true;
      }
      return false;
    },
    isActiveFacebookLogin() {
      let google_login =
        this.$store.getters["settings/settings"].facebook_login;
      if (google_login == "enabled") {
        return true;
      }
      return false;
    },
  },
  methods: {
    login: function () {
      let email = this.email;
      let password = this.password;
      this.$store
        .dispatch("auth/login", { email, password })
        .then(() => {
          if (this.requestPage == null) {
            this.$router.push("/");
          } else {
            this.$router.push(this.requestPage);
          }
        })
        .catch((err) => {
          this.$awn.alert(err.response.data.message);
        });
    },

    AuthProvider(provider) {
      var self = this;

      this.$auth
        .authenticate(provider)
        .then((response) => {
          this.$store
            .dispatch("auth/socialLogin", {
              provider: provider,
              response: response,
            })
            .then(() => {
              if (this.requestPage == null) {
                this.$router.push("/");
              } else {
                this.$router.push(this.requestPage);
              }
            })
            .catch((err) => {
              this.$awn.alert(err.response.data.message);
            });
        })
        .catch((err) => {
          console.log({ err: err });
        });
    },
  },
};
</script>
