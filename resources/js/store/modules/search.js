import axios from 'axios';

const state = {
    products: [],
    errors: {},
    keyword: null,
    loading: false
};

const getters = {
    products: state => state.products,
    keyword: state => state.keyword,
    loading: state => state.loading,
    errors: state => state.errors
};

const actions = {
    search({ commit, state }) {
        return new Promise((resolve, reject) => {
            commit('setLoading', true)
            axios.post('/api/products/search', {keyword: state.keyword})
                .then(response => {
                    state.products = response.data.data
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
                })
        })
    },
    setKeyword({state}, keyword){
        state.keyword = keyword;
    }
};

const mutations = {
    setLoading: (state, loading) => {
        state.loading = loading
    },
    setErrors: (state, errors) => {
        state.errors = errors
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
};
