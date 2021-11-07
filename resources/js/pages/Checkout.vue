<template>
	<div class="container">
		<div class="row">
			<div class="col-lg-6 mx-auto my-5">
				<Address v-if="step == 1" />
				<Delivery v-if="step == 2" />
				<Payment v-if="step == 3" />
				<Confirm v-if="step == 4" />
			</div>
		</div>
	</div>
</template>

<script>
	import Address from "../components/checkout/Address.vue";
	import Delivery from "../components/checkout/Delivery.vue";
	import Payment from "../components/checkout/Payment.vue";
	import Confirm from "../components/checkout/Confirm.vue";

	export default {
		components: {
			Address,
			Delivery,
			Payment,
			Confirm,
		},
		computed: {
			step: function() {
				return this.$store.getters["checkout/step"];
			},
			cartItems: function() {
				return this.$store.getters["cart/cartItems"];
			},
		},
		created() {
			if (this.cartItems.length == 0) {
				this.$router.push({ name: "home" });
			} else {
				if (this.step == 1) {
					if (this.$route.params.slug != "address") {
						this.$router.push({ name: "checkout", params: { slug: "address" } });
					}
				}
			}
		},
		watch: {
			step: function() {
				if (this.step == 1) {
					if (this.$route.params.slug != "address") {
						this.$router.push({ name: "checkout", params: { slug: "address" } });
					}
				} else if (this.step == 2) {
					this.$router.push({ name: "checkout", params: { slug: "delivery" } });
				} else if (this.step == 3) {
					this.$router.push({ name: "checkout", params: { slug: "payment" } });
				} else if (this.step == 4) {
					this.$router.push({ name: "checkout", params: { slug: "confirm" } });
				}
			},
			cartItems: function() {
				if (this.cartItems.length == 0) {
					this.$router.push({ name: "home" });
				}
			}
		},
	};
</script>
