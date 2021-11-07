<template>
  <div>
    <h6 v-if="keyword" class="text-center pt-3">
      {{ $lang["Search result for"] }} <b>{{ keyword }}</b>
    </h6>
    <h6 v-else class="text-center pt-3">{{ $lang["Search Product"] }}</h6>

    <section class="popular-items section">
      <div class="container-fluid">
        <Products v-if="!loading && products.length > 0" :products="products" />

        <!--If product not found-->
        <div v-else class="d-flex flex-column align-items-center text-center">
          <img src="/theme/img/no-product-found.webp" class="d-inline-block" />
          <h5 class="py-2 m-0">{{ $lang["Search No Result"] }}</h5>
          <p>
            {{
              $lang[
                "We are sorry. We cannot find any matches for your search term"
              ]
            }}
          </p>
          <div class="home-search secondary-search">
            <input
              type="text"
              @keyup="searchProduct($event)"
              name="search"
              autocomplete="off"
              :placeholder="$lang['Search Products']"
            />
            <button type="button" class="search-btn">
              <i class="icofont-search"></i>
            </button>
          </div>
        </div>
        <!--END If product not found-->
      </div>
    </section>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import Products from "../components/Products";
export default {
  components: {
    Products,
  },
  computed: {
    ...mapGetters("search", ["products", "loading", "keyword"]),
  },
  methods: {
    searchProduct: function ($event) {
      let keyword = $event.target.value;
      this.$store.dispatch("search/setKeyword", keyword);
    },
  },
  created: function () {
    if (this.keyword == null) {
      this.$router.push({ name: "home" });
    }
  },
};
</script>
