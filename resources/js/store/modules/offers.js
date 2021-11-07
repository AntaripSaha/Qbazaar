import axios from 'axios';

const state = {
	products: [],
	loading: false,
	init: true,
	page_no: 1,
	errors: {}
};

const getters = {
	products: state => state.products,
	loading: state => state.loading,
	init: state => state.init,
	errors: state => state.errors,
	page_no: state => state.page_no,
};

const actions = {
	fetchProducts({ commit, state }) {
		return new Promise((resolve, reject) => {
			commit('setLoading', true)
			axios.get(`/api/products/offers?page=${state.page_no}`)
				.then(response => {
					if (state.page_no == 1) {
						commit('setProducts', response.data.data) 			
					} else {
						commit('addProducts', response.data.data)
					}

                    if(response.data.meta.last_page > state.page_no){
                        state.page_no++;
                    }else{
                        state.init = false;
                    }

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
