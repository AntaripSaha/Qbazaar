<template>
  <section class="row payment-form">
    <div class="col-12" v-if="stripeValidationError != ''">
      <div class="alert alert-danger">
        <span> {{ stripeValidationError }}</span>
      </div>
    </div>

    <div class="col-12 card-element">
      <label>{{ $lang["Card Number"] }}</label>
      <div id="card-number-element" class="input-value"></div>
    </div>

    <div class="col-6 card-element">
      <label>{{ $lang["Expiry Date"] }}</label>
      <div id="card-expiry-element"></div>
    </div>

    <div class="col-6 card-element">
      <label>{{ $lang["CVC"] }}</label>
      <div id="card-cvc-element"></div>
    </div>

    <div class="col-12 mt-4">
      <button
        class="btn btn-primary btn-block"
        @click="placeOrderButtonPressed"
      >
        <span v-if="loading" class="spinner-border spinner-border-sm"></span>
        <span v-if="!loading">{{ $lang["Pay Now"] }}</span>
      </button>
    </div>
  </section>
</template>

<script>
import { mapGetters } from "vuex";
export default {
  data() {
    return {
      stripe: null,
      cardNumberElement: null,
      cardExpiryElement: null,
      cardCVCElement: null,
      stripeValidationError: "",
    };
  },
  computed: {
    ...mapGetters("settings", ["settings"]),
    ...mapGetters("orders", ["order", "loading"]),
  },
  mounted() {
    this.stripe = Stripe(this.settings.stripe_publishable_key);
    this.createAndMountFormElements();
  },
  methods: {
    createAndMountFormElements() {
      var elements = this.stripe.elements();
      this.cardNumberElement = elements.create("cardNumber");
      this.cardNumberElement.mount("#card-number-element");
      this.cardExpiryElement = elements.create("cardExpiry");
      this.cardExpiryElement.mount("#card-expiry-element");
      this.cardCvcElement = elements.create("cardCvc");
      this.cardCvcElement.mount("#card-cvc-element");
      this.cardNumberElement.on("change", this.setValidationError);
      this.cardExpiryElement.on("change", this.setValidationError);
      this.cardCvcElement.on("change", this.setValidationError);
    },
    setValidationError(event) {
      this.stripeValidationError = event.error ? event.error.message : "";
    },
    placeOrderButtonPressed() {
      this.stripe.createToken(this.cardNumberElement).then((result) => {
        if (result.error) {
          this.stripeValidationError = result.error.message;
        } else {
          const id = this.order.order_id;
          const token = result.token.id;
          const paymentMethod = this.order.payment_method;
          this.$store
            .dispatch("orders/makePayment", { id, token, paymentMethod })
            .then(() => {
              if (this.order.payment_status == "paid") {
                this.$awn.success(
                  this.$lang["Your payment was made sucessfully"]
                );
              }
            });
        }
      });
    },
  },
};
</script>


<style>
.card-element {
  margin-top: 10px;
}
#card-number-element,
#card-expiry-element,
#card-cvc-element {
  background: white;
  padding: 11px;
  border: 1px solid #ececec;
  height: 40px;
}
</style>
