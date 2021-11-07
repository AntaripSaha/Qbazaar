import axios from 'axios';
import CryptoJS from 'crypto-js'

let cartItemsFromStorage = [];
try {
    cartItemsFromStorage = localStorage.getItem('_bIt')
        ? JSON.parse(CryptoJS.AES.decrypt(localStorage.getItem('_bIt'), 'KIEeS2gzUZ8rDAlYJt').toString(CryptoJS.enc.Utf8))
        : []
} catch (err) {
    localStorage.removeItem('_bIt')
}

let couponDataFromStorage = null;
try {
    couponDataFromStorage = localStorage.getItem('_cc')
        ? JSON.parse(CryptoJS.AES.decrypt(localStorage.getItem('_cc'), 'KIEeS2gzUZ8rDAlYJt').toString(CryptoJS.enc.Utf8)) : null
} catch (err) {
    localStorage.removeItem('_cc')
}

let discountFromStorage = 0;
try {
    discountFromStorage = localStorage.getItem('discount')
        ? CryptoJS.AES.decrypt(localStorage.getItem('discount'), 'KIEeS2gzUZ8rDAlYJt').toString(CryptoJS.enc.Utf8) : 0
} catch (err) {
    localStorage.removeItem('discount')
}

const state = {
    cartItems: cartItemsFromStorage,
    quantity: 1,
    couponData: couponDataFromStorage,
    discount: discountFromStorage,
    loading: false,
};

const getters = {
    cartItems: state => state.cartItems,
    quantity: state => state.quantity,
    couponData: state => state.couponData,
    discount: state => state.discount,
    loading: state => state.loading,
};

const actions = {
    add_to_cart({ commit, state }, postData) {
        const item = {
            id: postData.product.id,
            product: postData.product,
            qnty: postData.qnty,
        }
        commit('addToCart', item)
        commit('setCoupon', state.couponData)

        localStorage.setItem('_bIt', CryptoJS.AES.encrypt(JSON.stringify(state.cartItems), 'KIEeS2gzUZ8rDAlYJt').toString())
    },
    removeCartItem({ commit, state }, id) {
        commit('removeCartItem', id)
        commit('setCoupon', state.couponData)
        localStorage.setItem('_bIt', CryptoJS.AES.encrypt(JSON.stringify(state.cartItems), 'KIEeS2gzUZ8rDAlYJt').toString())
    },
    updateCartItem({ commit, state }, payload) {
        if (payload.qnty == 0) {
            commit('removeCartItem', payload.id)
        } else {
            commit('updateCartItem', payload)
        }
        commit('setCoupon', state.couponData)
        localStorage.setItem('_bIt', CryptoJS.AES.encrypt(JSON.stringify(state.cartItems), 'KIEeS2gzUZ8rDAlYJt').toString())
    },
    applyCouponCode({ commit }, code) {
        return new Promise((resolve, reject) => {
            commit('setLoading', true)
            axios.post(`/api/coupons/getCoupon`, { code: code })
                .then(response => {
                    commit('setCoupon', response.data.data)
                    localStorage.setItem('_cc', CryptoJS.AES.encrypt(JSON.stringify(response.data.data), 'KIEeS2gzUZ8rDAlYJt').toString())
                    resolve(response)
                })
                .catch(error => {
                    commit('setCoupon', null)
                    localStorage.removeItem('_cc')
                    reject(error)
                })
                .finally(() => {
                    commit('setLoading', false)
                })
        });
    },
    emptyCart({ state }) {
        state.cartItems = [];
        state.couponData = null;
        state.discount = 0;

        localStorage.removeItem('_bIt')
        localStorage.removeItem('_cc')
        localStorage.removeItem('discount')
    },
    getItemQnty({ state }, payload) {
        const Item = state.cartItems.find((product) => product.id === payload.id)
        if (Item) {
            state.quantity = Item.qnty;
        } else {
            state.quantity = payload.initial;
        }
    },
    incrementQnty() {
        state.quantity++;
    },
    decrementQnty() {
        if (state.quantity > 1) {
            state.quantity--;
        }
    },
};

const mutations = {
    addToCart: (state, item) => {
        const existItem = state.cartItems.find((product) => product.id === item.id)

        if (existItem) {
            state.cartItems = state.cartItems.map((product) =>
                product.id === existItem.id ? item : product
            )
        } else {
            state.cartItems = [...state.cartItems, item]
        }
    },
    removeCartItem(state, id) {
        state.cartItems = state.cartItems.filter(item => item.id !== id)
    },
    updateCartItem(state, payload) {
        const index = state.cartItems.findIndex(item => item.id === payload.id);
        if (index !== -1) {
            state.cartItems.splice(index, 1, payload);
        }
    },
    setCoupon(state, couponData) {
        state.couponData = couponData
        let couponProducts = []
        if (state.couponData != null) {
            if (couponData.categories.length > 0) {
                const categories = couponData.categories.map((category) => category.id);

                couponProducts = state.cartItems.filter(function (cartItem) {
                    return categories.indexOf(cartItem.product.cat_id) !== -1;
                });

            } else if (couponData.products.length > 0) {
                const products = couponData.products.map((product) => product.id);

                couponProducts = state.cartItems.filter(function (cartItem) {
                    return products.indexOf(cartItem.product.id) !== -1;
                });
            } else {
                couponProducts = state.cartItems;
            }

            const coupon_amount = couponProducts.reduce((s_total, item) => s_total + item.qnty * item.product.current_price, 0).toFixed(2)

            //Apply Coupon  
            state.discount = state.couponData.is_percent == 1 ? (state.couponData.value / 100) * coupon_amount : state.couponData.value;
            localStorage.setItem('discount', CryptoJS.AES.encrypt(state.discount.toString(), 'KIEeS2gzUZ8rDAlYJt'))
        }

        if (state.cartItems.length == 0) {
            state.couponData = null;
            state.discount = 0;
            localStorage.removeItem('_cc')
            localStorage.removeItem('discount')
        }

    },
    setLoading(state, loading) {
        state.loading = loading
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
};
