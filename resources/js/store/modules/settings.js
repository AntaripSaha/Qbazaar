import axios from 'axios';

const state = {
    settings: [],
    pages: [],
    lang: [],
    errors: {},
    loading: false
};

const getters = {
    settings: state => state.settings,
    lang: state => state.lang,
    pages: state => state.pages,
    loading: state => state.loading,
    errors: state => state.errors,
};

const actions = {
    getSettings({ commit, state }) {
        return new Promise((resolve, reject) => {
            commit('setLoading', true)
            axios.post('/api/get_settings')
                .then(response => {
                    state.settings = response.data.data
                    state.lang = response.data.language
                    state.pages = response.data.pages
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
