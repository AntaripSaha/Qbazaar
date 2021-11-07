<template>
  <div>
    <section class="banner">
      <div
        class="banner-content d-flex align-items-center"
        :style="{ background: `url(${bannerImage})` }"
      >
        <div class="container">
          <div class="row text-center">
            <div class="col-lg-8 offset-lg-2">
              <h2>{{ settings.main_heading }}</h2>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="category section">
      <div class="container">
        <h3 class="section-heading">{{ $lang["Product Categories"] }}</h3>

        <div class="row">
          <template v-for="category in allCategories">
            <div
              v-if="category.parent_id == null"
              class="col-lg-3 col-md-6"
              :key="category.id"
            >
              <router-link
                :to="{
                  name: 'categoryProducts',
                  params: { slug: category.slug },
                }"
              >
                <div class="category-item">
                  <img :src="category.image" />
                  <p>{{ category.name }}</p>
                </div>
              </router-link>
            </div>
          </template>
        </div>
      </div>
    </section>

    <section v-if="products.length > 0" class="popular-items section">
      <div class="container">
        <h3 class="section-heading">{{ $lang["Special Offers"] }}</h3>
        <div id="slider">
          <Products
            :products="products"
            productType="offer"
            offerStyle="slider"
          />
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import Products from "../components/Products";
import { mapGetters, mapActions } from "vuex";

export default {
  data() {
    return {};
  },
  components: {
    Products,
  },
  computed: {
    ...mapGetters("offers", ["products"]),
    settings: function () {
      return this.$store.getters["settings/settings"];
    },
    bannerImage() {
      if (this.settings.home_banner != "") {
        return "/uploads/media/" + this.settings.home_banner;
      }
      return "/theme/img/main-banner.jpg";
    },
    allCategories: function () {
      return this.$store.getters["category/allCategories"];
    },
    loading: function () {
      return this.$store.getters["category/loading"];
    },
    init: function () {
      return this.$store.getters["category/init"];
    },
  },
  methods: {
    ...mapActions("offers", ["fetchProducts"]),
  },
  created() {
    this.fetchProducts();
  },
};
</script>
