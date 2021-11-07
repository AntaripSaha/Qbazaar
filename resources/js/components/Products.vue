<template>
  <div>
    <!--If product not found-->
    <div
      v-if="!loading && products.length == 0"
      class="d-flex flex-column align-items-center justify-content-center"
    >
      <img src="/theme/img/no-product-found.webp" class="d-inline-block" />
      <h5 class="py-2 m-0">{{ $lang["No Products found !"] }}</h5>
    </div>
    <!--END If product not found-->

    <div v-if="productType == 'general'" class="row align-self-stretch px-2">
      <div class="flex-item" v-for="product in products" :key="product.id">
        <div class="single-item text-center">
          <img :src="product.thumbnail" />
          <div class="p-2">
            <h6>{{ product.name }}</h6>
            <small>{{ product.unit }}</small>
            <p class="price"><span v-html="product._price"></span></p>
            <button
              v-if="product.in_stock == 1"
              type="button"
              class="btn-cart btn-block"
              @click="addToCart(product)"
            >
              <i class="icofont-cart-alt"></i> {{ $lang["Add to Cart"] }}
            </button>

            <button
              v-else
              type="button"
              class="btn-out-of-stock btn-block"
              disabled
            >
              <i class="icofont-warning"></i> {{ $lang["Out Of Stock"] }}
            </button>

            <button
              type="button"
              class="btn btn-dark btn-block"
              @click="showModal(product)"
            >
              {{ $lang["View Details"] }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <div
      v-if="productType == 'offer' && offerStyle == 'column'"
      class="row d-flex"
    >
      <div class="offer-col" v-for="product in products" :key="product.id">
        <div class="offer-item d-lg-flex">
          <img
            :src="product.banner"
            @click="showModal(product)"
            class="offer-banner mr-auto"
          />
          <div class="text-center">
            <img :src="product.thumbnail" class="offer-thumb" />
            <div class="p-2">
              <h6>{{ product.name }}</h6>
              <small>{{ product.unit }}</small>
              <p class="price"><span v-html="product._price"></span></p>
              <button
                v-if="product.in_stock == 1"
                type="button"
                class="btn btn-cart btn-block"
                @click="addToCart(product)"
              >
                <i class="icofont-cart-alt"></i> {{ $lang["Add to Cart"] }}
              </button>

              <button
                v-else
                type="button"
                class="btn btn-out-of-stock btn-block"
                disabled
              >
                <i class="icofont-warning"></i> {{ $lang["Out Of Stock"] }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!--Slider Offers-->
    <div
      v-if="productType == 'offer' && offerStyle == 'slider'"
      id="slider-outer"
      :style="{
        width: sliderOuterwidth + 'px',
        transform: `translate3d(-${sliderOuterTranslate}px, 0px, 0px)`,
      }"
    >
      <div
        class="single-offer"
        v-for="product in products"
        :key="product.id"
        :style="{ width: offerItemWidth + 'px' }"
      >
        <img :src="product.banner" class="offer-banner m-auto" />
        <div class="text-center">
          <img :src="product.thumbnail" class="offer-thumb" />
          <div class="p-2">
            <h6>{{ product.name }}</h6>
            <small>{{ product.unit }}</small>
            <p class="price"><span v-html="product._price"></span></p>
            <button
              v-if="product.in_stock == 1"
              type="button"
              class="btn btn-cart btn-block"
              @click="addToCart(product)"
            >
              <i class="icofont-cart-alt"></i> {{ $lang["Add to Cart"] }}
            </button>

            <button
              v-else
              type="button"
              class="btn btn-out-of-stock btn-block"
              disabled
            >
              <i class="icofont-warning"></i> {{ $lang["Out Of Stock"] }}
            </button>

            <button
              type="button"
              class="btn btn-dark btn-block"
              @click="showModal(product)"
            >
              {{ $lang["View Details"] }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <modal name="product-details" height="auto" width="800" :adaptive="true">
      <div id="productDetailsModal">
        <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
            <h4 class="modal-title text-white pl-2">
              {{ $lang["Product Details"] }}
            </h4>
            <button type="button" class="close" @click="hideModal">
              <i class="icofont-close-line-squared-alt"></i>
            </button>
          </div>

          <!-- Modal body -->
          <div class="modal-body p-4">
            <div class="row">
              <div class="col-lg-5">
                <div class="mb-2 img_producto_container" data-scale="1.6">
                  <img
                    :src="this.product.thumbnail"
                    class="product-details-img"
                  />
                </div>
              </div>
              <div class="col-lg-7">
                <h4 class="product-title">{{ product.name }}</h4>
                <p class="product-unit">
                  <strong>{{ product.unit }}</strong>
                </p>
                <p class="product-desc">
                  <span v-html="product.description"></span>
                </p>
                <h6>
                  <strong
                    >{{ $lang["Price"] }}: <span v-html="product._price"></span
                  ></strong>
                </h6>
              </div>
            </div>

            <div class="product-qnt">
              <input
                type="number"
                name="quantity"
                v-model="quantity"
                value="1"
                min="0"
                :placeholder="$lang['Quantity']"
              />
              <button type="button" class="btn-plus" @click="incrementQnty">
                +
              </button>
              <button type="button" class="btn-minus" @click="decrementQnty">
                -
              </button>
            </div>
            <button
              v-if="product.in_stock == 1"
              type="button"
              class="btn-cart"
              @click="addToCart(product, quantity)"
            >
              <i class="icofont-cart-alt"></i> {{ $lang["Add to Cart"] }}
            </button>

            <button v-else type="button" class="btn-out-of-stock" disabled>
              <i class="icofont-warning"></i> {{ $lang["Out Of Stock"] }}
            </button>
          </div>
        </div>
      </div>
    </modal>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
export default {
  props: {
    products: Array,
    loading: true,
    productType: {
      type: String,
      default: "general",
    },
    offerStyle: {
      type: String,
      default: "column",
    },
  },
  data() {
    return {
      product: {},
      sliderOuterTranslate: 0,
    };
  },
  computed: {
    ...mapGetters("cart", ["quantity"]),
    containerWidth: function () {
      return document.getElementsByClassName("container")[0].offsetWidth;
    },
    sliderOuterwidth: function () {
      if (this.containerWidth >= 1140) {
        return this.$props.products.length * ((this.containerWidth - 30) / 3);
      } else if (this.containerWidth >= 960 && this.containerWidth < 1140) {
        return this.$props.products.length * ((this.containerWidth - 30) / 2);
      } else if (this.containerWidth <= 960 && this.containerWidth > 720) {
        return this.$props.products.length * ((this.containerWidth - 30) / 2);
      } else if (this.containerWidth <= 720) {
        return this.$props.products.length * (this.containerWidth - 30);
      }
    },
    offerItemWidth: function () {
      return (
        (this.sliderOuterwidth - this.$props.products.length * 10) /
        this.$props.products.length
      );
    },
  },
  methods: {
    ...mapActions("cart", ["getItemQnty", "incrementQnty", "decrementQnty"]),
    showModal(product) {
      this.product = product;
      this.getItemQnty({ id: this.product.id, initial: 1 });
      this.$modal.show("product-details");
    },
    hideModal() {
      this.$modal.hide("product-details");
    },
    addToCart: function (product, qnty) {
      if (qnty == null) {
        this.getItemQnty({ id: product.id, initial: 0 });
        qnty = this.quantity + 1;
      }
      this.$store.dispatch("cart/add_to_cart", {
        product: product,
        qnty: qnty,
      });
      document.getElementById("sticky-busket").classList.add("added_to_cart");
      this.$awn.success("Item added to busket");
      setTimeout(function () {
        document
          .getElementById("sticky-busket")
          .classList.remove("added_to_cart");
      }, 1000);
    },
  },
  mounted: function () {
    if (this.offerStyle == "slider") {
      setInterval(() => {
        if (
          this.sliderOuterwidth - this.sliderOuterTranslate <=
          this.containerWidth
        ) {
          this.sliderOuterTranslate = 0;
        } else {
          this.sliderOuterTranslate += this.offerItemWidth + 10;
        }
      }, 4000);
    }
  },
};
</script>

