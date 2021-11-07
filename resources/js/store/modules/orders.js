import axios from 'axios';

const state = {
	orders: [],
	order: null,
	loading: false,
	current_page: 1,
	total_page: null,
	errors: {}
};

const getters = {
	orders: state => state.orders,
	order: state => state.order,
	loading: state => state.loading,
	errors: state => state.errors,
	current_page: state => state.current_page,
	total_page: state => state.total_page,
};

const actions = {
	placeOrder({ commit }, orderData) {
		return new Promise((resolve, reject) => {
			commit('setLoading', true)
			axios.post('/api/orders/place_order', JSON.stringify(orderData))
				.then(resp => {
					commit('placeOrder', resp.data.data)
					state.errors = {}
					resolve(resp)
				})
				.catch(err => {
					console.log(err.resp);
					if (err.resp.status == 422) {
						commit('setErrors', err.resp.data.errors)
					}
					reject(err)
				})
				.finally(() => {
					commit('setLoading', false)
				})
		})
	},
	makePayment({ commit }, payload) {
		return new Promise((resolve, reject) => {
			commit('setLoading', true)
			axios.post(`/api/orders/make_payment/${payload.id}`, JSON.stringify(payload))
				.then(resp => {
					commit('setOrder', resp.data.data)
					state.errors = {}
					resolve(resp)
				})
				.catch(err => {
					if (err.resp.status == 422) {
						commit('setErrors', err.resp.data.errors)
					}
					reject(err)
				})
				.finally(() => {
					commit('setLoading', false)
				})
		})
	},
	fetchOrders({ commit }, page_no) {
		return new Promise((resolve, reject) => {
			commit('setLoading', true);

			let page = page_no || state.current_page;

			if (page_no) {
				state.init = true;
			}

			axios.get(`/api/orders?page=${page}`)
				.then(response => {
					commit('setOrders', response.data.data)
					commit('setCurrentPage', response.data.meta.current_page)
					commit('setTotalPage', response.data.meta.last_page)
					state.errors = {}
					resolve(response)
				})
				.catch(err => {
					if (err.response.status == 422) {
						commit('setErrors', err.response.data.errors)
					}
					reject(err)
				})
				.finally(() => {
					commit('setLoading', false)
					state.init = false;
				})
		});
	},
	fetchAssignedOrders({ commit }, page_no) {
		return new Promise((resolve, reject) => {
			commit('setLoading', true);

			let page = page_no || state.current_page;

			if (page_no) {
				state.init = true;
			}

			axios.get(`/api/orders/assigned_orders?page=${page}`)
				.then(response => {
					commit('setOrders', response.data.data)
					commit('setCurrentPage', response.data.meta.current_page)
					commit('setTotalPage', response.data.meta.last_page)
					state.errors = {}
					resolve(response)
				})
				.catch(err => {
					if (err.response.status == 422) {
						commit('setErrors', err.response.data.errors)
					}
					reject(err)
				})
				.finally(() => {
					commit('setLoading', false)
					state.init = false;
				})
		});
	},
	makeDelivered({ commit }, orderId) {
		return new Promise((resolve, reject) => {
			commit('setLoading', true)
			axios.post(`/api/orders/mark_as_delivered/${orderId}`)
				.then(resp => {
					commit('setOrder', resp.data.data)
					state.errors = {}
					resolve(resp.data.data)
				})
				.catch(err => {
					if (err.resp.status == 422) {
						commit('setErrors', err.resp.data.errors)
					}
					reject(err)
				})
				.finally(() => {
					commit('setLoading', false)
				})
		})
	},
	getOrder({ commit, state }, orderId) {
		if (state.order == null || state.order.order_id != orderId) {
			return new Promise((resolve, reject) => {
				commit('setLoading', true)
				axios.get(`/api/orders/${orderId}`)
					.then(resp => {
						commit('setOrder', resp.data.data)
						state.errors = {}
						resolve(resp)
					})
					.catch(err => {
						console.log(err.response);
						if (err.response.status == 422) {
							commit('setErrors', err.response.data.errors)
						}
						reject(err)
					})
					.finally(() => {
						commit('setLoading', false)
					})
			})
		}
	}
};

const mutations = {
	setOrders: (state, orders) => {
		state.orders = orders;
	},
	placeOrder: (state, order) => {
		state.order = order;
		const index = state.orders.findIndex(item => item.order_id === order.order_id);
        if (index !== -1) {
            state.orders.splice(index, 1, order);
        }
	},
	setOrder: (state, order) => {
		state.order = order;
		const index = state.orders.findIndex(item => item.order_id === order.order_id);
        if (index !== -1) {
            state.orders.splice(index, 1, order);
        }
	},
	setLoading: (state, loading) => {
		state.loading = loading
	},
	setErrors: (state, errors) => {
		state.errors = errors
	},
	setCurrentPage: (state, current_page) => {
		state.current_page = current_page
	},
	setTotalPage: (state, total_page) => {
		state.total_page = total_page
	}
};


export default {
	namespaced: true,
	state,
	getters,
	actions,
	mutations
};
