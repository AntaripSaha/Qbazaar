<template>
	<div>
		<div class="skeleton-card">			
			<div class="menu">
				<span class="icon"></span>
				<span class="name"></span>
			</div>	
			<div class="menu">
				<span class="icon"></span>
				<span class="name"></span>
			</div>	
			<div class="menu">
				<span class="icon"></span>
				<span class="name"></span>
			</div>	
			<div class="menu">
				<span class="icon"></span>
				<span class="name"></span>
			</div>	
			<div class="menu">
				<span class="icon"></span>
				<span class="name"></span>
			</div>	
			<div class="menu">
				<span class="icon"></span>
				<span class="name"></span>
			</div>	
			<div class="menu">
				<span class="icon"></span>
				<span class="name"></span>
			</div>	
			<div class="menu">
				<span class="icon"></span>
				<span class="name"></span>
			</div>	
		</div>
	</div>
</template>

<script>
	export default {};
</script>

<style scoped>
	.skeleton-card {
		width: 250px;
		box-shadow: 1px 1px 0px #e7e7e7;
		z-index: 9;
		position: fixed;
		height: 100%;
		background: #FFF;
		padding: 30px 15px;
	}
	.menu{
		display: flex;
  		align-items: center;
		margin-bottom: 20px;
	}
	.menu span {
		display: inline-block;
		background: #d9d9d9;
		overflow: hidden;
		position: relative;
	}
	.menu .icon {
		height: 15px;
		width: 15px;
        border-radius: 50%;
	}
	.menu .name {
		height: 12px;
		width: 160px;
		margin-left: 15px;
		border-radius: 10px;
	}	
	.menu span::before{
		position: absolute;
		content: "";
		height: 100%;
		width: 100%;
		background-image: linear-gradient(
			to right,
			#d9d9d9 0%,
			rgba(0, 0, 0, 0.05) 20%,
			#d9d9d9 40%,
			#d9d9d9 100%
		);
		background-repeat: no-repeat;
		background-size: 450px 400px;
		animation: shimmer 1s linear infinite;
        left: 0;
        top: 0;
	}
	.menu span::before {
		animation-delay: 0.5s;
	}
	.skeleton-card .menu:nth-child(2) > .name{
		width: 120px;
	}
	.skeleton-card .menu:nth-child(3) > .name{
		width: 140px;
	}
	.skeleton-card .menu:nth-child(4) > .name{
		width: 170px;
	}
	.skeleton-card .menu:nth-child(5) > .name{
		width: 150px;
	}
	.skeleton-card .menu:nth-child(7) > .name{
		width: 140px;
	}
	.skeleton-card .menu:nth-child(8) > .name{
		width: 150px;
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
