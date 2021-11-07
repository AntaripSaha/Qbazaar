<template>
  <div></div>
</template>


<script>
export default {
  name: "event-hub",
  data() {
    return {
      // Code...
    };
  },
  created() {
    this.$eventHub.$on("create-success", this.itemCreated);
    this.$eventHub.$on("update-success", this.itemUpdated);
    this.$eventHub.$on("delete-success", this.itemDeleted);
  },
  methods: {
    itemCreated() {
      this.$awn.success(this.$lang["Your item has been saved successfully."]);
    },
    itemUpdated() {
      this.$awn.success(this.$lang["Your item has been updated successfully."]);
    },
    itemDeleted() {
      this.$awn.success(this.$lang["Your item has been deleted successfully."]);
    },
  },
};
</script>

