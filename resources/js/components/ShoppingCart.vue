<template>
  <div>
    <!--Sticky Busket-->
    <div id="sticky-busket" class="btn-busket d-md-block d-none">
      <div class="items text-center py-1">
        <i class="icofont-shopping-cart"></i>
        <h6>{{ totalItems }} {{ $lang["ITEMS"] }}</h6>
      </div>
      <div class="price text-center p-1">
        <small>{{ $currency + " " + grandTotal }}</small>
      </div>
    </div>
    <!--End Sticky Busket-->

    <!-- Start Cart Panel -->
    <div class="shopping_cart" id="side_shopping_cart">
      <div class="shp_cart_header">
        <h4>
          <i class="icofont-cart"></i> {{ totalItems }} {{ $lang["ITEMS"] }}
        </h4>
        <div class="offsetmenu_close_btn">
          <a href="#"><i class="icofont-close-squared-alt"></i></a>
        </div>
      </div>
      <p
        class="bg-warning text-center text-dark"
        v-if="$freeDeliveryAmount > 0"
      >
        <b
          >{{ $lang["Free Delivery Shopping Over"] }}
          {{ $currency + $freeDeliveryAmount }}</b
        >
      </p>
      <div class="shopping_cart_inner">
        <div class="shp_cart_wrap">
          <div v-if="totalItems == 0" class="text-center">
            <img src="/theme/img/empty-cart.png" />
            <p class="text-center pt-2 pb-5">
              <b>{{ $lang["Your shopping bag is empty !"] }}</b>
            </p>
          </div>

          <div
            v-for="cartItem in cartItems"
            class="shp_single_product"
            :key="cartItem.id"
          >
            <div class="shp_pro_thumb">
              <img :src="cartItem.product.thumbnail" alt="product images" />
            </div>
            <div class="shp_pro_details">
              <h2>{{ cartItem.product.name }}</h2>
              <div class="product-qnt">
                <input
                  type="number"
                  name="quantity"
                  :value="cartItem.qnty"
                  min="0"
                  :placeholder="$lang['Quantity']"
                  readonly
                />
                <button
                  type="button"
                  class="btn-plus"
                  @click="updateCart(cartItem, 'add')"
                >
                  +
                </button>
                <button
                  type="button"
                  class="btn-minus"
                  @click="updateCart(cartItem, 'remove')"
                >
                  -
                </button>
              </div>
              <span class="shp_price"
                ><span v-html="cartItem.product._price"></span
              ></span>
            </div>
            <div class="remove_btn">
              <button
                type="button"
                class="btn-remove-cartitem"
                @click="removeCartItem(cartItem.id)"
              >
                <i class="icofont-close-line-squared-alt"></i>
              </button>
            </div>
          </div>
        </div>
        <table class="table summary-table">
          <tr>
            <td>{{ $lang["Subtotal"] }}:</td>
            <td class="float-right">{{ $currency + " " + subTotal }}</td>
          </tr>
          <tr>
            <td>{{ $lang["Delivery Charge"] }}:</td>
            <td class="float-right">
              + {{ $currency + " " + deliveryCharge }}
            </td>
          </tr>
          <tr v-if="couponData">
            <td>{{ $lang["Discount"] }}({{ couponData.name }}):</td>
            <td class="float-right">- {{ $currency + " " + discount }}</td>
          </tr>
          <tr>
            <td>
              <b>{{ $lang["Grand Total"] }}:</b>
            </td>
            <td class="float-right">
              <b>{{ $currency + " " + grandTotal }}</b>
            </td>
          </tr>
        </table>

        <div class="card mb-3">
          <div class="card-header p-0">
            <h2 class="mb-0">
              <button
                class="btn btn-block text-dark text-center"
                type="button"
                data-toggle="collapse"
                data-target="#have_coupon_code"
                aria-expanded="true"
                aria-controls="have_coupon_code"
              >
                {{ $lang["Have Coupon Code ?"] }}
              </button>
            </h2>
          </div>

          <div id="have_coupon_code" class="collapse">
            <div class="card-body">
              <form autocomplete="off" @submit.prevent="applyCoupon">
                <input
                  type="text"
                  id="coupon_code"
                  v-model="couponCode"
                  :placeholder="$lang['Coupon Code']"
                />
                <button type="submit" class="btn btn-dark mt-2 btn-block">
                  {{ $lang["Apply"] }}
                </button>
              </form>
            </div>
          </div>
        </div>

        <ul v-if="cartItems.length" class="shopping_btn">
          <li class="shp_checkout">
            <router-link
              :to="{ name: 'checkout', params: { slug: 'address' } }"
              v-on:click.native="closeShoppingCart"
              >{{ $lang["Place Order"] }}</router-link
            >
          </li>
        </ul>
      </div>
    </div>
    <!-- End Cart Panel -->

    <!--Bottom Navigation-->
    <div id="bottom-navigation">
      <ul class="d-flex align-items-center">
        <li>
          <router-link :to="{ name: 'profile' }"
            ><i class="icofont-ui-user"></i
          ></router-link>
        </li>
        <li class="flex-grow-1">
          <router-link
            :to="{ name: 'checkout', params: { slug: 'address' } }"
            v-on:click.native="closeShoppingCart"
            class="text-white"
            ><i class="icofont-check-circled"></i>
            {{ $lang["Place Order"] }}</router-link
          >
        </li>
        <li>
          <a href="#" class="btn-busket"
            ><i class="icofont-cart-alt"></i
            ><span class="notification-count">{{ totalItems }}</span></a
          >
        </li>
      </ul>
    </div>
    <!--End Bottom Navigation-->
  </div>
</template>

<script>
import { mapActions } from "vuex";
export default {
  data() {
    return {
      couponCode: "",
    };
  },
  computed: {
    cartItems: function () {
      return this.$store.getters["cart/cartItems"];
    },
    loading: function () {
      return this.$store.getters["cart/loading"];
    },
    totalItems: function () {
      return this.cartItems.reduce((t_qnt, item) => t_qnt + item.qnty, 0);
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
    ...mapActions("cart", [
      "removeCartItem",
      "updateCartItem",
      "applyCouponCode",
    ]),
    updateCart: function (item, action) {
      if (action == "add") {
        const payload = {
          id: item.product.id,
          product: item.product,
          qnty: item.qnty + 1,
        };
        this.updateCartItem(payload);
      } else if (action == "remove") {
        const payload = {
          id: item.product.id,
          product: item.product,
          qnty: item.qnty - 1,
        };
        this.updateCartItem(payload);
      }
    },
    applyCoupon: function () {
      this.applyCouponCode(this.couponCode)
        .then((response) => {
          this.$awn.success(this.$lang["Coupon applied successfully"]);
          this.couponCode = "";
        })
        .catch((err) => {
          this.$awn.alert(this.$lang["Invalid Coupon Code !"]);
        });
    },
    closeShoppingCart() {
      document
        .getElementById("side_shopping_cart")
        .classList.remove("shopping_cart_on");
    },
  },
};
</script>
