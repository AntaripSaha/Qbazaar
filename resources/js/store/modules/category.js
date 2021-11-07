import axios from 'axios';

const state = {
	categories: [],
	products: [],
	category: {},
	loading: false,
	init: true,
	page: null,
	errors: {}
};

const getters = {
	allCategories: state => state.categories,
	products: state => state.products,
	category: state => state.category,
	loading: state => state.loading,
	init: state => state.init,
	errors: state => state.errors,
	page: state => state.page,
};

const actions = {
	fetchCategories({ commit, state }) {
		return new Promise((resolve, reject) => {
			commit('setLoading', true)

			axios.get(`/api/categories`)
				.then(response => {
					commit('setCategories', response.data.data)
					state.errors = {};
					resolve(response)
				})
				.catch(error => {
					console.log(error)
					reject(error)
				})
				.finally(() => {
					commit('setLoading', false)
					state.init = false;
				})
		});

	},
	getCategory({ commit, state }, slug) {
		const index = state.categories.findIndex(category => category.slug === slug);
		if (index !== -1) {
			commit('findCategory', slug)
		} else {
			commit('setLoading', true)
			axios.get(`/api/categories/${slug}`)
				.then(response => {
					commit('getCategory', response.data.data)
					state.errors = {};
				})
				.catch(error => {
					console.log(error)
				})
				.finally(() => {
					commit('setLoading', false)
				})
		}
	},
	getProducts({ commit, state }, params) {
		if (state.page == null && params.slug == null) {
			return false;
		}
		return new Promise((resolve, reject) => {
			commit('setLoading', true)
			let url = params.slug ? `/api/categories/products/${params.slug}` : state.page;
			axios.get(url)
				.then(response => {
					if (params.slug != null) {
						commit('setProducts', response.data.data)
						state.page = null;
					} else {
						commit('addProducts', response.data.data)
					}

					state.page = response.data.links.next;
					state.errors = {};
					resolve(response.data.data)
				})
				.catch(error => {
					console.log(error)
					reject(error)
				})
				.finally(() => {
					commit('setLoading', false)
				})
		});
	},
};

const mutations = {
	setCategories: (state, categories) => {
		state.categories = categories
	},
	findCategory: (state, slug) => {
		state.category = state.categories.find(category => category.slug === slug)
	},
	getCategory: (state, category) => {
		state.category = category
	},
	setProducts: (state, products) => {
		state.products = products
	},
	addProducts: (state, products) => {
		state.products = state.products.concat(products)
	},
	setLoading: (state, loading) => {
		state.loading = loading
	},
	setErrors: (state, errors) => {
		state.errors = errors
	},
};


export default {
	namespaced: true,
	state,
	getters,
	actions,
	mutations
};
