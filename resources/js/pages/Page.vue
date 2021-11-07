<template>
  <div v-if="!loading" class="container">
    <div class="row">
      <div class="col-lg-12 mx-auto my-5">
        <div id="page">
          <h3>{{ page.title }}</h3>
          <p v-html="page.body"></p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      loading: true,
      page: {},
    };
  },
  methods: {
    getPage(slug) {
      return new Promise((resolve, reject) => {
        this.loading = true;
        axios
          .post(`/api/pages`, { slug: slug })
          .then((response) => {
            this.page = response.data.data;
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
  mounted() {
    this.getPage(this.$route.params.slug).catch((error) => {
      if (error.response.status == 404) {
        this.$router.push({
          name: "not_found",
        });
      }
    });
  },
  watch: {
    "$route.params.slug": function () {
      this.getPage(this.$route.params.slug).catch((error) => {
        if (error.response.status == 404) {
          this.$router.push({
            name: "not_found",
          });
        }
      });
    },
  },
};
</script>
