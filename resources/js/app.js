require("./bootstrap");

import Vue from "vue";
import store from "./store";
import router from "./router";
import VueAxios from "vue-axios";
import axios from "axios";
import VueAWN from "vue-awesome-notifications";
import VueSocialauth from "vue-social-auth";

import App from "./App.vue";
import SpinnerButtonComponent from "./components/SpinnerButton.vue";

/** UI Components **/
import VueSweetalert2 from "vue-sweetalert2";
import "sweetalert2/dist/sweetalert2.min.css";
import vSelect from "vue-select";
import VModal from "vue-js-modal";

window.Vue = require("vue");
Vue.prototype.$eventHub = new Vue();
Vue.prototype.$http = axios;
Vue.prototype.$currency = _currency_symbol;
Vue.prototype.$deliveryCharge = _dc;
Vue.prototype.$freeDeliveryAmount = _fda;
Vue.prototype.$baseUrl = _url;

Vue.use(VueAxios, axios);
Vue.use(VueSweetalert2);
Vue.use(VueAWN, {
  position: "top-right",
  durations: {
    global: 2000,
  },
});
Vue.use(VModal);

store.dispatch("settings/getSettings").then(() => {
  Vue.prototype.$lang = store.getters["settings/lang"];

  const googleClientId = store.getters["settings/settings"].GOOGLE_CLIENT_ID;
  const facebookClientId =
    store.getters["settings/settings"].FACEBOOK_CLIENT_ID;

  Vue.use(VueSocialauth, {
    providers: {
      google: {
        clientId: googleClientId,
        redirectUri: Vue.prototype.$baseUrl,
      },
      facebook: {
        clientId: facebookClientId,
        redirectUri: Vue.prototype.$baseUrl,
      },
    },
  });

  const paypalActive = store.getters["settings/settings"].paypal_active;

  if (paypalActive == "Yes") {
    const PayPalButton = paypal.Buttons.driver("vue", Vue);
    Vue.component("paypal-buttons", PayPalButton);
  }
});

//Global UI Components
Vue.component("SpinnerButton", SpinnerButtonComponent);
Vue.component("v-select", vSelect);

const token = localStorage.getItem("token");
if (token) {
  axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
}

Vue.config.devtools = false;
Vue.config.productionTip = false;

const app = new Vue({
  el: "#app",
  router: router,
  store,
  render: (h) => h(App),
});
