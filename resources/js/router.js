import Vue from "vue";
import VueRouter from "vue-router";
import store from "./store/index";

//Auth
import Login from "./components/auth/Login.vue";
import Register from "./components/auth/Register.vue";

//All Pages
import HomePage from "./pages/Home.vue";
import CategoryProducts from "./pages/CategoryProducts.vue";
import OffersProducts from "./pages/OffersProducts.vue";
import SearchProducts from "./pages/Search.vue";
import ProfilePage from "./pages/Profile.vue";
import CheckoutPage from "./pages/Checkout.vue";
import OrderSummary from "./pages/OrderSummary.vue";
import Page from "./pages/Page.vue";
import NotFoundPage from "./pages/404.vue";
import VerificationPage from "./pages/verification.vue";

Vue.use(VueRouter);

const route = [
  {
    path: "/",
    name: "home",
    component: HomePage,
  },
  {
    path: "/category/:slug",
    name: "categoryProducts",
    component: CategoryProducts,
  },
  {
    path: "/search",
    name: "searchProducts",
    component: SearchProducts,
  },
  {
    path: "/offers",
    name: "offersProducts",
    component: OffersProducts,
  },
  {
    path: "/account/:activeTab?",
    name: "profile",
    component: ProfilePage,
    meta: {
      requiresAuth: true,
    },
  },
  {
    path: "/checkout/:slug?",
    name: "checkout",
    component: CheckoutPage,
    meta: {
      requiresAuth: true,
    },
  },
  {
    path: "/orders/:id",
    name: "view_order",
    component: OrderSummary,
    meta: {
      requiresAuth: true,
    },
  },
  {
    path: "/login",
    name: "login",
    component: Login,
    meta: {
      guest: true,
    },
  },
  {
    path: "/register",
    name: "register",
    component: Register,
    meta: {
      guest: true,
    },
  },
  {
    name: "verification",
    path: "/verification",
    component: VerificationPage,
  },
  {
    path: "/:slug?",
    name: "page",
    component: Page,
  },
  {
    name: "not_found",
    path: "*",
    component: NotFoundPage,
  },
];

let router = new VueRouter({
  mode: "history",
  linkActiveClass: "active",
  routes: route,
  scrollBehavior(to, from, savedPosition) {
    return { x: 0, y: 0 };
  },
});

router.beforeEach((to, from, next) => {
  if (to.matched.some((record) => record.meta.requiresAuth)) {
    let authenticated = store.getters["auth/isLoggedIn"];
    let user = store.getters["auth/user"];
    store.dispatch("auth/requestPage", to.path);

    if (authenticated) {
      if (user.email_verified_at === null) {
        next({ name: "verification" });
      }
      next();
      return;
    }

    next("/login");
  } else if (to.matched.some((record) => record.meta.guest)) {
    let authenticated = store.getters["auth/isLoggedIn"];
    if (authenticated) {
      next({ name: "home" });
    } else {
      next();
    }
  } else {
    next();
  }
});

export default router;
