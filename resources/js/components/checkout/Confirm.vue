<template>
  <div class="card">
    <div class="card-header">
      <h4 class="text-center">{{ $lang["Order Preview"] }}</h4>
    </div>
    <div class="card-body">
      <form @submit.prevent="makeOrder" autocomplete="off">
        <table class="table">
          <tbody>
            <tr>
              <td colspan="2" class="text-center">
                <b>{{ $lang["Delivery Details"] }}</b>
              </td>
            </tr>
            <tr>
              <td>{{ $lang["Delivery Address"] }}</td>
              <td>{{ deliveryDetails.deliveryAddress.address }}</td>
            </tr>
            <tr>
              <td>{{ $lang["Delivery Type"] }}</td>
              <td>
                {{ deliveryDetails.deliveryTiming.delivery_type.toUpperCase() }}
              </td>
            </tr>
            <tr
              v-if="deliveryDetails.deliveryTiming.delivery_type == 'preorder'"
            >
              <td>{{ $lang["Delivery Time"] }}</td>
              <td>{{ deliveryDate }}</td>
            </tr>
            <tr>
              <td>{{ $lang["Payment Method"] }}</td>
              <td>
                {{
                  deliveryDetails.paymentMethod
                    .replaceAll("_", " ")
                    .toUpperCase()
                }}
              </td>
            </tr>
            <tr v-if="deliveryDetails.deliveryTiming.order_instructions">
              <td>{{ $lang["Order Note"] }}</td>
              <td>{{ deliveryDetails.deliveryTiming.order_instructions }}</td>
            </tr>
          </tbody>
        </table>

        <table class="table">
          <tbody>
            <tr>
              <td colspan="2" class="text-center">
                <b>{{ $lang["Order Summary"] }}</b>
              </td>
            </tr>
            <tr>
              <td>{{ $lang["Sub Total"] }}</td>
              <td class="text-right">{{ $currency + " " + subTotal }}</td>
            </tr>
            <tr>
              <td>{{ $lang["Delivery Charge"] }}</td>
              <td class="text-right">
                + {{ $currency + " " + deliveryCharge }}
              </td>
            </tr>
            <tr>
              <td>{{ $lang["Discount"] }}</td>
              <td class="text-right">- {{ $currency + " " + discount }}</td>
            </tr>
            <tr>
              <td>{{ $lang["Grand Total"] }}</td>
              <td class="text-right">
                <b>{{ $currency + " " + grandTotal }}</b>
              </td>
            </tr>
          </tbody>
        </table>

        <button type="submit" class="btn btn-primary btn-block mt-2">
          <span v-if="loading" class="spinner-border spinner-border-sm"></span>
          <span v-if="!loading">{{ $lang["Place Order"] }}</span>
        </button>

        <button
          type="button"
          @click="previousStep"
          class="btn btn-light text-dark btn-block"
        >
          {{ $lang["Back"] }}
        </button>
      </form>
    </div>
  </div>
</template>

<script>
import { mapActions } from "vuex";
import moment from "moment";

export default {
  computed: {
    loading: function () {
      return this.$store.getters["orders/loading"];
    },
    deliveryDetails: function () {
      return this.$store.getters["checkout/delivery_details"];
    },
    deliveryDate: function () {
      return moment(this.deliveryDetails.deliveryTiming.delivery_date).format(
        "MMM DD, YYYY hh:mm a"
      );
    },
    cartItems: function () {
      return this.$store.getters["cart/cartItems"];
    },
    couponData: function () {
      return this.$store.getters["cart/couponData"];
    },
    subTotal: function () {
      return this.cartItems
        .reduce(
          (s_total, item) => s_total + item.qnty * item.product.current_price,
          0
        )
        .toFixed(2);
    },
    deliveryCharge: function () {
      const deliveryCharge = this.subTotal > 0 ? this.$deliveryCharge : 0;
      if (
        this.$freeDeliveryAmount > 0 &&
        this.subTotal > parseFloat(this.$freeDeliveryAmount)
      ) {
        return parseFloat(0).toFixed(2);
      }
      return parseFloat(deliveryCharge).toFixed(2);
    },
    discount: function () {
      return parseFloat(this.$store.getters["cart/discount"]).toFixed(2);
    },
    grandTotal: function () {
      const total =
        parseFloat(this.subTotal) +
        parseFloat(this.deliveryCharge) -
        parseFloat(this.discount);
      return total.toFixed(2);
    },
  },
  methods: {
    ...mapActions("checkout", ["resetStep", "previousStep"]),
    ...mapActions("orders", ["placeOrder"]),
    makeOrder() {
      let data = {
        cartItems: this.cartItems.flatMap((item) => [
          { id: item.id, qnty: item.qnty },
        ]),
        couponCode: this.couponData ? this.couponData.code : null,
        deliveryDetails: this.deliveryDetails,
      };
      this.placeOrder(data).then((response) => {
        this.resetStep();
        this.$store.dispatch("cart/emptyCart");
        this.$router.push({
          name: "view_order",
          params: { id: response.data.data.order_id },
        });
        if (this.deliveryDetails.paymentMethod == "cash_on_delivery") {
          this.$awn.success(this.$lang["Your Order placed sucessfully"]);
        }
      });
    },
  },
};
</script>
