<template>
  <div class="container">
    <div class="row">
      <div class="col-lg-6 mx-auto my-5">
        <div class="card">
          <div class="card-header">
            <h4 class="text-center">{{ $lang["Order Summary"] }}</h4>
          </div>
          <div class="card-body">
            <table v-if="order" class="table">
              <tbody>
                <tr>
                  <td>{{ $lang["Order ID"] }}</td>
                  <td class="text-right">{{ order.order_id }}</td>
                </tr>
                <tr>
                  <td>{{ $lang["Payment Status"] }}</td>
                  <td class="text-right">
                    <span v-html="paymentStatus"></span>
                  </td>
                </tr>
                <tr>
                  <td>{{ $lang["Delivery Status"] }}</td>
                  <td class="text-right">
                    <span v-html="deliveryStatus"></span>
                  </td>
                </tr>
                <tr>
                  <td>{{ $lang["Payment Method"] }}</td>
                  <td class="text-right">
                    {{
                      order.payment_method.replaceAll("_", " ").toUpperCase()
                    }}
                  </td>
                </tr>
                <tr>
                  <td>{{ $lang["Sub Total"] }}</td>
                  <td class="text-right">
                    {{ $currency + " " + order.sub_total }}
                  </td>
                </tr>
                <tr>
                  <td>{{ $lang["Delivery Charge"] }}</td>
                  <td class="text-right">
                    + {{ $currency + " " + order.shipping_cost }}
                  </td>
                </tr>
                <tr>
                  <td>{{ $lang["Discount"] }}</td>
                  <td class="text-right">
                    - {{ $currency + " " + order.discount }}
                  </td>
                </tr>
                <tr>
                  <td>{{ $lang["Grand Total"] }}</td>
                  <td class="text-right">
                    <b>{{ $currency + " " + order.grand_total }}</b>
                  </td>
                </tr>
                <tr
                  v-if="
                    order.payment_method == 'PayPal' &&
                    order.payment_status == 'pending'
                  "
                >
                  <td colspan="2">
                    <PayPalButton />
                  </td>
                </tr>
                <tr
                  v-if="
                    order.payment_method == 'credit_card' &&
                    order.payment_status == 'pending'
                  "
                >
                  <td colspan="2">
                    <StripeButton />
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import PayPalButton from "../components/gateways/PayPal.vue";
import StripeButton from "../components/gateways/Stripe.vue";

export default {
  components: {
    PayPalButton,
    StripeButton,
  },
  computed: {
    ...mapGetters("orders", ["order"]),
    paymentStatus: function () {
      const status = this.order.payment_status
        .replaceAll("_", " ")
        .toUpperCase();
      const rawStatus = this.order.payment_status;
      if (rawStatus == "canceled") {
        return '<span class="badge badge-danger">' + status + "</span>";
      } else if (rawStatus == "completed") {
        return '<span class="badge badge-success">' + status + "</span>";
      } else if (rawStatus == "on_hold") {
        return '<span class="badge badge-warning">' + status + "</span>";
      } else if (rawStatus == "pending") {
        return '<span class="badge badge-danger">' + status + "</span>";
      } else if (rawStatus == "processing") {
        return '<span class="badge badge-info">' + status + "</span>";
      } else if (rawStatus == "refunded") {
        return '<span class="badge badge-danger">' + status + "</span>";
      } else if (rawStatus == "paid") {
        return '<span class="badge badge-success">' + status + "</span>";
      }
    },
    deliveryStatus: function () {
      const status = this.order.delivery_status
        .replaceAll("_", " ")
        .toUpperCase();
      const rawStatus = this.order.delivery_status;
      if (rawStatus == "canceled") {
        return '<span class="badge badge-danger">' + status + "</span>";
      } else if (rawStatus == "completed") {
        return '<span class="badge badge-success">' + status + "</span>";
      } else if (rawStatus == "on_hold") {
        return '<span class="badge badge-warning">' + status + "</span>";
      } else if (rawStatus == "pending") {
        return '<span class="badge badge-danger">' + status + "</span>";
      } else if (rawStatus == "processing") {
        return '<span class="badge badge-info">' + status + "</span>";
      } else if (rawStatus == "refunded") {
        return '<span class="badge badge-danger">' + status + "</span>";
      } else if (rawStatus == "not_delivered") {
        return '<span class="badge badge-danger">' + status + "</span>";
      }
    },
  },
  methods: {
    ...mapActions("orders", ["getOrder"]),
  },
  created() {
    this.getOrder(this.$route.params.id).then(() => {
      if (this.order == null) {
        this.$router.push("/");
      }
    });
  },
};
</script>
