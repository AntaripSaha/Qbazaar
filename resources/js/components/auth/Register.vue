<template>
  <div class="container">
    <div class="row">
      <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
        <div class="card card-signin my-5">
          <div class="card-body">
            <h5 class="card-title text-center pt-2 pb-3">
              {{ $lang["Create an Account"] }}
            </h5>
            <form class="form-signup" @submit.prevent="register">
              <div class="form-label-group mb-3">
                <input
                  type="text"
                  id="name"
                  v-bind:class="
                    errors && errors.name
                      ? 'form-control is-invalid'
                      : 'form-control'
                  "
                  v-model="name"
                  :placeholder="$lang['Name']"
                />
                <span class="text-danger" v-if="errors && errors.name">
                  {{ errors.name[0] }}
                </span>
              </div>

              <div class="form-label-group mb-3">
                <input
                  type="email"
                  id="email"
                  v-bind:class="
                    errors && errors.email
                      ? 'form-control is-invalid'
                      : 'form-control'
                  "
                  v-model="email"
                  :placeholder="$lang['Email address']"
                />
                <span class="text-danger" v-if="errors && errors.email">
                  {{ errors.email[0] }}
                </span>
              </div>

              <div class="form-label-group mb-3">
                <input
                  type="text"
                  id="phone"
                  v-bind:class="
                    errors && errors.phone
                      ? 'form-control is-invalid'
                      : 'form-control'
                  "
                  v-model="phone"
                  :placeholder="$lang['Mobile']"
                />
                <span class="text-danger" v-if="errors && errors.phone">
                  {{ errors.phone[0] }}
                </span>
              </div>

              <div class="form-label-group mb-3">
                <input
                  type="password"
                  id="password"
                  v-bind:class="
                    errors && errors.password
                      ? 'form-control is-invalid'
                      : 'form-control'
                  "
                  v-model="password"
                  :placeholder="$lang['Password']"
                />
                <span class="text-danger" v-if="errors && errors.password">
                  {{ errors.password[0] }}
                </span>
              </div>

              <div class="form-label-group mb-3">
                <input
                  type="password"
                  id="password_confirmation"
                  class="form-control"
                  v-model="password_confirmation"
                  :placeholder="$lang['Confirm Password']"
                />
              </div>

              <button
                type="submit"
                class="
                  btn btn-lg btn-primary btn-block btn-login
                  text-uppercase
                  py-3
                  mt-2
                "
              >
                <span
                  v-if="loading"
                  class="spinner-border spinner-border-sm"
                ></span>
                <span v-if="!loading">{{ $lang["Sign Up"] }}</span>
              </button>
              <router-link
                :to="{ name: 'login' }"
                class="
                  btn btn-lg btn-light
                  text-dark
                  btn-block
                  text-uppercase
                  py-3
                  mb-1
                "
                >{{ $lang["Already have an account?"] }}</router-link
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
      name: "",
      email: "",
      phone: "",
      password: "",
      password_confirmation: "",
    };
  },
  computed: {
    ...mapGetters("auth", ["errors", "loading"]),
  },
  methods: {
    register: function () {
      let data = {
        name: this.name,
        email: this.email,
        phone: this.phone,
        password: this.password,
        password_confirmation: this.password_confirmation,
      };
      this.$store
        .dispatch("auth/register", data)
        .then(() => {
          this.$awn.success(this.$lang["Registration successfully"]);
          this.$router.push("/");
        })
        .catch((err) => console.log(err));
    },
  },
};
</script>
