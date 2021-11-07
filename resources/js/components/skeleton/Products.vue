<template>
  <div class="row align-self-stretch px-2">
    <div class="flex-item" v-for="product in 5" :key="product">
      <div class="skeleton-card">
        <div class="header">
          <div class="img"></div>
          <div class="details">
            <span class="name"></span>
            <span class="quantity"></span>
            <span class="price"></span>
          </div>
        </div>
        <div class="btns">
          <div class="btn btn-1"></div>
          <div class="btn btn-1 mt-2"></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {};
</script>

<style scoped>
.skeleton-card {
  max-width: 350px;
  width: 100%;
  background: #fff;
  padding: 10px;
  margin-bottom: 30px;
}
.header .img {
  height: 212px;
  width: 100%;
  background: #e9e8e8;
  position: relative;
  overflow: hidden;
}
.header .details {
  margin-top: 20px;
}
.details span {
  display: block;
  background: #e9e8e8;
  border-radius: 10px;
  overflow: hidden;
  position: relative;
}
.details .name {
  height: 15px;
  width: 200px;
  margin: auto;
}
@media (max-width: 767px) {
  .details .name {
    width: 100%;
  }
}
.details .quantity {
  height: 10px;
  width: 80px;
  margin: auto;
  margin-top: 40px;
}
.details .price {
  height: 13px;
  width: 100px;
  margin: auto;
  margin-top: 10px;
}
.skeleton-card .description {
  margin: 25px 0;
}

.skeleton-card .btns {
  margin-top: 15px;
}
.skeleton-card .btns .btn {
  height: 45px;
  width: 100%;
  background: #e9e8e8;
  border-radius: 4px;
  position: relative;
  overflow: hidden;
}
.header .img::before,
.details span::before,
.description .line::before,
.btns .btn::before {
  position: absolute;
  content: "";
  height: 100%;
  width: 100%;
  background-image: linear-gradient(
    to right,
    #e9e8e8 0%,
    rgba(0, 0, 0, 0.05) 20%,
    #e0e0e0 40%,
    #dddddd 100%
  );
  background-repeat: no-repeat;
  background-size: 450px 400px;
  animation: shimmer 2s linear infinite;
  left: 0;
  top: 0;
}
.header .img::before {
  background-size: 650px 600px;
}
.details span::before {
  animation-delay: 1s;
}

@keyframes shimmer {
  0% {
    background-position: -450px 0;
  }
  100% {
    background-position: 450px 0;
  }
}
</style>
