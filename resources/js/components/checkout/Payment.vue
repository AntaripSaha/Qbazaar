<template>
  <div class="card">
    <div class="card-header">
      <h4 class="text-center">{{ $lang["Select Payment Method"] }}</h4>
    </div>
    <div class="card-body">
      <form @submit.prevent="savePaymentMethod" autocomplete="off">
        <div class="form-label-group mb-3">
          <label for="payment_method">{{ $lang["Payment Method"] }} *</label>
          <select
            id="payment_method"
            v-bind:class="
              errors && errors.phone
                ? 'form-control is-invalid'
                : 'form-control'
            "
            v-model="payment_method"
          >
            <option value="cash_on_delivery">
              {{ $lang["Cash On Delivery"] }}
            </option>
            <option v-if="paypal_active" value="PayPal">
              {{ $lang["Paypal"] }}
            </option>
            <option v-if="stripe_active" value="credit_card">
              {{ $lang["Credit Card"] }}
            </option>
          </select>
          <span class="text-danger" v-if="errors && errors.payment_method">
            {{ errors.payment_method[0] }}
          </span>
        </div>

        <button type="submit" class="btn btn-primary mt-2">
          {{ $lang["Next: Confirm Order"] }}
        </button>
        <button
          type="button"
          @click="previousStep"
          class="btn btn-light text-dark mt-2 float-right"
        >
          {{ $lang["Back"] }}
        </button>
      </form>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";

export default {
  data() {
    return {
      payment_method: "cash_on_delivery",
      paypal_active: _paypal_active == "Yes" ? true : false,
      stripe_active: _stripe_active == "Yes" ? true : false,
    };
  },
  computed: {
    ...mapGetters("checkout", ["errors", "delivery_details"]),
  },
  methods: {
    ...mapActions("checkout", [
      "storePaymentMethod",
      "nextStep",
      "previousStep",
    ]),
    savePaymentMethod() {
      this.storePaymentMethod(this.payment_method).then(() => {
        this.nextStep();
      });
    },
  },
  created() {
    this.payment_method = this.delivery_details.paymentMethod
      ? this.delivery_details.paymentMethod
      : "cash_on_delivery";
  },
};
</script>
