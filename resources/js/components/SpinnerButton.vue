<template>
	<button type="submit" class="btn btn-primary">
		<span v-if="loading" class="spinner-border spinner-border-sm"></span>
		{{ title }}
	</button>
</template>


<script>
export default {
	props: {
		title: String,
		loading: Boolean
	},
    data() {
        return {
            // Code...
        }
    },
    created() {
         // Code...
    },
    methods: {
		// Code...
    }
}
</script>


<style scoped>

</style>
