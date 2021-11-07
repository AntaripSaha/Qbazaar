import axios from 'axios';

const state = {
	delivery_details: {},
	loading: false,
	errors: {},
	step: 1,
};

const getters = {
	delivery_details: state => state.delivery_details,
	order: state => state.order,
	loading: state => state.loading,
	errors: state => state.errors,
	step: state => state.step,
};

const actions = {
	storeAddress({ commit }, address) {
		commit('setAddress', address)
	},
	nextStep({ commit }) {
		commit('nextStep')
	},
	previousStep({ commit }) {
		commit('previousStep')
	},
	resetStep({ commit }) {
		commit('resetStep')
	},
	storeTiming({ commit }, deliveryTiming) {
		commit('setTiming', deliveryTiming)
	},
	storePaymentMethod({ commit }, paymentMethod) {
		commit('setPaymentMethod', paymentMethod)
	},
};

const mutations = {
	setAddress: (state, address) => {
		state.delivery_details['deliveryAddress'] = address
	},
	setTiming: (state, delivery_timing) => {
		state.delivery_details['deliveryTiming'] = delivery_timing
	},
	setPaymentMethod: (state, paymentMethod) => {
		state.delivery_details['paymentMethod'] = paymentMethod
	},
	nextStep: (state) => {
		state.step = state.step + 1
	},
	previousStep: (state) => {
		state.step = state.step - 1
	},
	resetStep: (state) => {
		state.step = 0
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
