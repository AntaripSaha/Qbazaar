<template>
  <div class="card">
    <div class="card-header">
      <h4 class="header-title">{{ $lang["My Orders"] }}</h4>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead>
            <th>{{ $lang["ID"] }}</th>
            <th>{{ $lang["Date"] }}</th>
            <th>{{ $lang["Amount"] }}</th>
            <th>{{ $lang["Payment"] }}</th>
            <th>{{ $lang["Delivery"] }}</th>
            <th class="text-center">{{ $lang["Details"] }}</th>
          </thead>
          <tbody>
            <tr v-if="!loading && orders.length == 0">
              <td colspan="6" class="text-center py-4">
                {{ $lang["No orders found"] }}
              </td>
            </tr>
            <tr v-for="order in orders" :key="order.id">
              <td>{{ order.order_id }}</td>
              <td>{{ order.created_at }}</td>
              <td>{{ $currency + " " + order.grand_total }}</td>
              <td><span v-html="paymentStatus(order)"></span></td>
              <td><span v-html="deliveryStatus(order)"></span></td>
              <td class="text-center">
                <router-link
                  :to="{
                    name: 'view_order',
                    params: { id: order.order_id },
                  }"
                  class="btn btn-primary px-4 py-2"
                >
                  {{ $lang["View"] }}
                </router-link>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <nav>
        <ul class="pagination pagination-bordered">
          <li v-bind:class="[current_page == 1 ? 'disabled' : '', 'page-item']">
            <button class="page-link" @click="paginate(current_page - 1)">
              <i class="icofont-rounded-left"></i>
            </button>
          </li>
          <li
            v-for="page in total_page"
            v-bind:key="page"
            v-bind:class="[current_page == page ? 'active' : '', 'page-item']"
          >
            <button class="page-link" @click="paginate(page)">
              {{ page }}
            </button>
          </li>
          <li
            v-bind:class="[
              current_page == total_page ? 'disabled' : '',
              'page-item',
            ]"
          >
            <button class="page-link" @click="paginate(current_page + 1)">
              <i class="icofont-rounded-right"></i>
            </button>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
export default {
  computed: {
    ...mapGetters("orders", [
      "orders",
      "init",
      "loading",
      "total_page",
      "current_page",
    ]),
  },
  methods: {
    ...mapActions("orders", ["fetchOrders"]),
    paymentStatus: function (order) {
      const status = order.payment_status.replaceAll("_", " ").toUpperCase();
      const rawStatus = order.payment_status;
      if (rawStatus == "canceled") {
        return '<span class="badge badge-danger">' + status + "</span>";
      } else if (rawStatus == "completed") {
        return '<span class="badge badge-success">' + status + "</span>";
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
    deliveryStatus: function (order) {
      const status = order.delivery_status.replaceAll("_", " ").toUpperCase();
      const rawStatus = order.delivery_status;
      if (rawStatus == "canceled") {
        return '<span class="badge badge-danger">' + status + "</span>";
      } else if (rawStatus == "completed") {
        return '<span class="badge badge-success">' + status + "</span>";
      } else if (rawStatus == "pending") {
        return '<span class="badge badge-danger">' + status + "</span>";
      } else if (rawStatus == "processing") {
        return '<span class="badge badge-info">' + status + "</span>";
      } else if (rawStatus == "refunded") {
        return '<span class="badge badge-danger">' + status + "</span>";
      }
    },
    paginate(page_no) {
      if (page_no != this.current_page) {
        this.fetchOrders(page_no);
      }
    },
  },
  created() {
    this.fetchOrders();
  },
};
</script>
