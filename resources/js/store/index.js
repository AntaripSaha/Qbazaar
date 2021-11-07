import Vuex from 'vuex';
import Vue from 'vue';

//Import Modules
import auth from './modules/auth';
import category from './modules/category';
import search from './modules/search';
import cart from './modules/cart';
import offers from './modules/offers';
import checkout from './modules/checkout';
import orders from './modules/orders';
import settings from './modules/settings';

// Load Vuex
Vue.use(Vuex);

// Create store
export default new Vuex.Store({
	modules: {
		auth,
		category,
		search,
		cart,
		offers,
		checkout,
		orders,
		settings
	}
});
