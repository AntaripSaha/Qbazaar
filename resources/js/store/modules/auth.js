import axios from "axios";

const state = {
  status: "",
  token: localStorage.getItem("token") || "",
  user: {},
  errors: {},
  requestPage: null,
  loading: false,
};

const getters = {
  isLoggedIn: (state) => !!state.token,
  authStatus: (state) => state.status,
  loading: (state) => state.loading,
  user: (state) => state.user,
  errors: (state) => state.errors,
  requestPage: (state) => state.requestPage,
};

const actions = {
  login({ commit }, user) {
    return new Promise((resolve, reject) => {
      commit("setLoading", true);
      axios
        .post("/api/auth/login", user)
        .then((resp) => {
          const token = resp.data.token;
          const user = resp.data.user;
          localStorage.setItem("token", token);

          axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
          commit("auth_success", { token, user });
          state.errors = {};
          resolve(resp);
        })
        .catch((err) => {
          if (err.response.status == 422) {
            commit("setErrors", err.response.data.errors);
          }
          localStorage.removeItem("token");
          reject(err);
        })
        .finally(() => {
          commit("setLoading", false);
        });
    });
  },
  socialLogin({ commit }, payLoad) {
    return new Promise((resolve, reject) => {
      commit("setLoading", true);
      axios
        .post("/api/auth/social_login/" + payLoad.provider, payLoad.response)
        .then((resp) => {
          const token = resp.data.token;
          const user = resp.data.user;
          localStorage.setItem("token", token);

          axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
          commit("auth_success", { token, user });
          state.errors = {};
          resolve(resp);
        })
        .catch((err) => {
          if (err.response.status == 422) {
            commit("setErrors", err.response.data.errors);
          }
          localStorage.removeItem("token");
          reject(err);
        })
        .finally(() => {
          commit("setLoading", false);
        });
    });
  },
  register({ commit }, user) {
    return new Promise((resolve, reject) => {
      commit("setLoading", true);
      axios
        .post("/api/auth/register", user)
        .then((resp) => {
          const token = resp.data.token;
          const user = resp.data.user;
          localStorage.setItem("token", token);

          axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
          commit("auth_success", { token, user });
          state.errors = {};
          resolve(resp);
        })
        .catch((err) => {
          console.log(err);
          if (err.response.status == 422) {
            commit("setErrors", err.response.data.errors);
          }
          localStorage.removeItem("token");
          reject(err);
        })
        .finally(() => {
          commit("setLoading", false);
        });
    });
  },
  getProfile({ commit, state }) {
    return new Promise((resolve, reject) => {
      commit("setLoading", true);
      axios
        .post("/api/profile")
        .then((resp) => {
          state.user = resp.data.data;
          state.errors = {};
          resolve(resp);
        })
        .catch((err) => {
          if (err.response.status == 422) {
            commit("setErrors", err.response.data.errors);
          }
          reject(err);
        })
        .finally(() => {
          commit("setLoading", false);
        });
    });
  },
  updateProfile({ commit, state }) {
    return new Promise((resolve, reject) => {
      commit("setLoading", true);
      axios
        .post("/api/update_profile", state.user)
        .then((resp) => {
          state.user = resp.data.data;
          state.errors = {};
          resolve(resp);
        })
        .catch((err) => {
          if (err.response.status == 422) {
            commit("setErrors", err.response.data.errors);
          }
          reject(err);
        })
        .finally(() => {
          commit("setLoading", false);
        });
    });
  },
  updateProfilePicture({ commit, state }, payLoad) {
    commit("setLoading", true);

    return new Promise((resolve, reject) => {
      const config = {
        headers: {
          "content-type": "multipart/form-data",
        },
      };
      axios
        .post("/api/update_profile_picture", payLoad, config)
        .then((response) => {
          state.user = response.data.data;
          state.errors = {};
          resolve();
        })
        .catch((error) => {
          if (error.response.status == 422) {
            commit("setErrors", error.response.data.errors);
          }
          reject(error);
        })
        .finally(() => {
          commit("setLoading", false);
        });
    });
  },
  requestVerificationLink({ commit, state }) {
    return new Promise((resolve, reject) => {
      commit("setLoading", true);
      axios
        .post("/api/auth/request_verification_link")
        .then((resp) => {
          state.errors = {};
          resolve(resp);
        })
        .catch((err) => {
          if (err.response.status == 422) {
            commit("setErrors", err.response.data.errors);
          }
          reject(err);
        })
        .finally(() => {
          commit("setLoading", false);
        });
    });
  },
  logout({ commit }) {
    return new Promise((resolve, reject) => {
      commit("logout");
      localStorage.removeItem("token");
      delete axios.defaults.headers.common["Authorization"];
      resolve();
    });
  },
  requestPage({ state }, page) {
    state.requestPage = page;
  },
};

const mutations = {
  auth_request(state) {
    state.status = "loading";
  },
  auth_success(state, userData) {
    state.status = "success";
    state.token = userData.token;
    state.user = userData.user;
  },
  auth_error(state) {
    state.status = "error";
  },
  logout(state) {
    state.status = "";
    state.token = "";
    state.user = {};
  },
  setLoading: (state, loading) => {
    state.loading = loading;
  },
  setErrors: (state, errors) => {
    state.errors = errors;
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
