<template>
  <div>
    <section class="banner">
      <div
        class="banner-content d-flex align-items-center"
        style="background: url('/theme/img/main-banner.jpg')"
      >
        <div class="container">
          <div class="row text-center">
            <div class="col-lg-8 offset-lg-2">
              <h2>{{ $lang["Special Offers"] }}</h2>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="popular-items section">
      <div class="container-fluid">
        <h3 class="section-heading">{{ $lang["Special Offers"] }}</h3>

        <ProductSkelton v-if="loading && load_more" />

        <Products :products="products" productType="offer" :loading="loading" />
      </div>
    </section>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import ProductSkelton from "../components/skeleton/Products";
import Products from "../components/Products";
export default {
  data() {
    return {
      load_more: true,
    };
  },
  components: {
    ProductSkelton,
    Products,
  },
  computed: {
    ...mapGetters("offers", ["products", "loading", "init"]),
  },
  methods: {
    ...mapActions("offers", ["fetchProducts"]),
    infiniteHandler() {
      window.onscroll = () => {
        let _this = this;
        let bottomOfWindow =
          document.documentElement.scrollTop + window.innerHeight >
          document.documentElement.offsetHeight -
            document.getElementById("footer").offsetHeight;
        if (bottomOfWindow && this.load_more && this.init == true) {
          this.load_more = false;
          this.fetchProducts().then(function () {
            if (_this.init == true) {
              _this.load_more = true;
            }
          });
        }
      };
    },
  },
  created() {
    this.fetchProducts();
  },
  mounted() {
    this.infiniteHandler();
  },
};
</script>
