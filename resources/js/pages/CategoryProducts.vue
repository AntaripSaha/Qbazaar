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
              <h2>{{ category.name }}</h2>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="popular-items section">
      <div class="container-fluid">
        <h3 class="section-heading">{{ category.name }}</h3>

        <ProductSkelton v-if="loading && load_more" />

        <div class="category">
          <div class="d-flex justify-content-center">
            <div
              class="flex-item"
              v-for="sub_category in category.sub_categories"
              :key="sub_category.id"
            >
              <router-link
                :to="{
                  name: 'categoryProducts',
                  params: { slug: sub_category.slug },
                }"
              >
                <div class="category-item">
                  <img :src="sub_category.image" />
                  <p>{{ sub_category.name }}</p>
                </div>
              </router-link>
            </div>
          </div>
        </div>

        <Products :products="products" :loading="loading" />
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
    ...mapGetters("category", ["category", "products", "loading", "page"]),
  },
  methods: {
    ...mapActions("category", ["getCategory", "getProducts"]),
    infiniteHandler() {
      window.onscroll = () => {
        let _this = this;
        let bottomOfWindow =
          document.documentElement.scrollTop + window.innerHeight >
          document.documentElement.offsetHeight -
            document.getElementById("footer").offsetHeight;
        if (bottomOfWindow && this.load_more && this.page != null) {
          this.load_more = false;
          this.getProducts({ slug: null }).then(function () {
            _this.load_more = true;
          });
        }
      };
    },
  },
  created() {
    this.getCategory(this.$route.params.slug);
    this.getProducts({ slug: this.$route.params.slug });
  },
  watch: {
    "$route.params.slug": function (id) {
      this.getCategory(this.$route.params.slug);
      this.getProducts({ slug: this.$route.params.slug });
    },
  },
  mounted() {
    this.infiniteHandler();
  },
};
</script>
