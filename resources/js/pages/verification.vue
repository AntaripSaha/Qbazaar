<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card card-signin my-5">
          <div class="card-header text-center">
            {{ $lang["Verify your email address"] }}
          </div>

          <div class="card-body text-center">
            <p>
              {{
                $lang[
                  "Before proceeding, please check your email for a verification link."
                ]
              }}
              <br />
              {{ $lang["If you did not receive the email"] }}
            </p>
            <button v-if="loading" type="subbuttonmit" class="btn btn-primary">
              <span class="spinner-border spinner-border-sm"></span>
            </button>

            <button
              v-else
              type="button"
              @click="requestAnother"
              class="btn btn-primary"
            >
              {{ $lang["click here to request another"] }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
export default {
  computed: {
    ...mapGetters("auth", ["isLoggedIn", "user", "loading"]),
  },
  methods: {
    ...mapActions("auth", ["requestVerificationLink"]),
    requestAnother() {
      this.requestVerificationLink().then(() => {
        this.$awn.success(this.$lang["Verification link send to your email"]);
      });
    },
  },
  created() {
    if (this.isLoggedIn) {
      if (
        this.user.email_verified_at != null ||
        typeof this.user.email_verified_at == "undefined"
      ) {
        this.$router.push({
          name: "not_found",
        });
      }
    } else {
      this.$router.push({
        name: "not_found",
      });
    }
  },
};
</script>