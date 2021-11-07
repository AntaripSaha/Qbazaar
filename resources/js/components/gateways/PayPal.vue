<template>
  <paypal-buttons
    :createOrder="createOrder"
    :onApprove="onAuthorize"
  ></paypal-buttons>
</template>

<script>
import { mapGetters } from "vuex";
export default {
  computed: {
    ...mapGetters("orders", ["order", "loading"]),
    ...mapGetters("settings", ["settings"]),
    currency() {
      return this.settings.currency;
    },
  },
  methods: {
    createOrder: function (data, actions) {
      return actions.order.create({
        purchase_units: [
          {
            amount: {
              value: this.order.grand_total,
              currency_code: this.currency,
            },
          },
        ],
      });
    },
    onAuthorize: function (data, actions) {
      const id = this.order.order_id;
      const orderData = data;
      const paymentMethod = this.order.payment_method;
      this.$store
        .dispatch("orders/makePayment", { id, orderData, paymentMethod })
        .then(() => {
          if (this.order.payment_status == "paid") {
            this.$awn.success(this.$lang["Your payment was made sucessfully"]);
          }
        });
      return actions.order.capture();
    },
  },
};
</script>

<style></style>
