<template>
  <div class="card">
    <div class="card-header">
      <h4 class="text-center">{{ $lang["Delivery Address"] }}</h4>
    </div>
    <div class="card-body">
      <form @submit.prevent="saveAddress" autocomplete="off">
        <div class="form-label-group mb-3">
          <label for="name">{{ $lang["Name"] }} *</label>
          <input
            type="text"
            id="name"
            v-bind:class="
              errors && errors.name ? 'form-control is-invalid' : 'form-control'
            "
            v-model="user.name"
            :placeholder="$lang['Name']"
            required
          />
          <span class="text-danger" v-if="errors && errors.name">
            {{ errors.name[0] }}
          </span>
        </div>

        <div class="form-label-group mb-3">
          <label for="phone">{{ $lang["Mobile Number"] }} *</label>
          <input
            type="text"
            id="phone"
            v-bind:class="
              errors && errors.phone
                ? 'form-control is-invalid'
                : 'form-control'
            "
            v-model="user.phone"
            :placeholder="$lang['Mobile Number']"
            required
          />
          <span class="text-danger" v-if="errors && errors.phone">
            {{ errors.phone[0] }}
          </span>
        </div>

        <div class="form-label-group mb-3">
          <label for="address">{{ $lang["Shipping Address"] }} *</label>
          <textarea
            id="address"
            v-bind:class="
              errors && errors.address
                ? 'form-control is-invalid'
                : 'form-control'
            "
            v-model="user.address"
            :placeholder="$lang['Shipping Address']"
            required
          ></textarea>
          <span class="text-danger" v-if="errors && errors.address">
            {{ errors.address[0] }}
          </span>
        </div>

        <button type="submit" class="btn btn-primary">
          {{ $lang["Next: Delivery Timing"] }}
        </button>
      </form>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";

export default {
  computed: {
    ...mapGetters("auth", ["user", "loading"]),
    ...mapGetters("checkout", ["errors"]),
  },
  methods: {
    ...mapActions("checkout", ["storeAddress", "nextStep"]),
    saveAddress() {
      let data = {
        name: this.user.name,
        phone: this.user.phone,
        address: this.user.address,
      };
      this.storeAddress(data).then(() => {
        this.nextStep();
      });
    },
  },
};
</script>
