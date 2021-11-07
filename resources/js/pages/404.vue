<template>
  <div
    id="notfound"
    class="d-flex flex-column align-items-center justify-content-center"
  >
    <img src="/theme/img/page-not-found.jpg" />
    <router-link
      class="nav-link"
      v-on:click.native="triggerNav"
      :to="{ name: 'home' }"
      exact
    >
      <i class="icofont-home"></i> {{ $lang["Go to Home"] }}
    </router-link>
  </div>
</template>

<script>
export default {};
</script>