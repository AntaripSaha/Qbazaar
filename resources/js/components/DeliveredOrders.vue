<template>
  <div>
    <div class="card">
      <div class="card-header">
        <h4 class="header-title">{{ $lang["Delivered Orders"] }}</h4>
      </div>
      <div class="card-body">
        <div v-if="loading" class="text-center py-5">
          <span class="spinner-border"></span>
        </div>

        <div v-else class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <th>{{ $lang["ID"] }}</th>
              <th>{{ $lang["Customer"] }}</th>
              <th>{{ $lang["Amount"] }}</th>
              <th>{{ $lang["Payment"] }}</th>
              <th>{{ $lang["Method"] }}</th>
              <th class="text-center">{{ $lang["Action"] }}</th>
            </thead>
            <tbody>
              <tr v-if="!loading && orders.length == 0">
                <td colspan="6" class="text-center py-4">
                  {{ $lang["No orders found"] }}
                </td>
              </tr>
              <tr v-for="order in orders" :key="order.id">
                <template v-if="order.delivery_status == 'completed'">
                  <td>#{{ order.order_id }}<br />{{ order.created_at }}</td>
                  <td>{{ order.name }}<br />{{ order.phone }}</td>
                  <td>{{ $currency + " " + order.grand_total }}</td>
                  <td><span v-html="paymentStatus(order)"></span></td>
                  <td>
                    {{
                      order.payment_method.replaceAll("_", " ").toUpperCase()
                    }}
                  </td>
                  <td class="text-center">
                    <div class="dropdown">
                      <button
                        class="btn btn-light text-dark btn-sm dropdown-toggle"
                        type="button"
                        id="dropdownMenuButton"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                      >
                        {{ $lang["Action"] }}
                      </button>
                      <div
                        class="dropdown-menu"
                        aria-labelledby="dropdownMenuButton"
                      >
                        <router-link
                          :to="{
                            name: 'view_order',
                            params: { id: order.order_id },
                          }"
                          class="dropdown-item"
                        >
                          {{ $lang["Order details"] }}
                        </router-link>
                        <button class="dropdown-item" @click="showModal(order)">
                          {{ $lang["Customer details"] }}
                        </button>
                      </div>
                    </div>
                  </td>
                </template>
              </tr>
            </tbody>
          </table>
        </div>
        <nav>
          <ul class="pagination pagination-bordered">
            <li
              v-bind:class="[current_page == 1 ? 'disabled' : '', 'page-item']"
            >
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

    <modal name="customer-details" height="auto" :adaptive="true">
      <div id="productDetailsModal">
        <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
            <h4 class="modal-title text-white pl-2">
              {{ $lang["Customer details"] }}
            </h4>
            <button type="button" class="close" @click="hideModal">
              <i class="icofont-close-line-squared-alt"></i>
            </button>
          </div>

          <!-- Modal body -->
          <div class="modal-body p-4">
            <table class="table">
              <tr>
                <td>{{ $lang["Name"] }}</td>
                <td>{{ this.order.name }}</td>
              </tr>
              <tr>
                <td>{{ $lang["Email"] }}</td>
                <td>{{ this.order.email }}</td>
              </tr>
              <tr>
                <td>{{ $lang["Phone"] }}</td>
                <td>{{ this.order.phone }}</td>
              </tr>
              <tr>
                <td>{{ $lang["Address"] }}</td>
                <td>{{ this.order.shipping_address }}</td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </modal>
  </div>
</template>

<script>
import axios from "axios";
export default {
  data() {
    return {
      current_page: 1,
      total_page: null,
      loading: true,
      orders: {},
      order: {},
    };
  },
  methods: {
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
    showModal(order) {
      this.order = order;
      this.$modal.show("customer-details");
    },
    hideModal() {
      this.$modal.hide("customer-details");
    },
    paginate(page_no) {
      if (page_no != this.current_page) {
        this.fetchDeliveredOrders(page_no);
      }
    },
    fetchDeliveredOrders(page_no) {
      return new Promise((resolve, reject) => {
        this.loading = true;

        let page = page_no || this.current_page;

        axios
          .get(`/api/orders/delivered_orders?page=${page}`)
          .then((response) => {
            this.orders = response.data.data;

            this.current_page = response.data.meta.current_page;
            this.total_page = response.data.meta.last_page;
            resolve(response);
          })
          .catch((err) => {
            reject(err);
          })
          .finally(() => {
            this.loading = false;
          });
      });
    },
  },
  created() {
    this.fetchDeliveredOrders();
  },
};
</script>
