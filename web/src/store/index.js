import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'
import router from '@/router'

Vue.use(Vuex)

function configureAxios(token) {
  if(token) axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
  return axios.interceptors.response.use(response => response, error => {
    if (error.response.status === 401 || error.response.status === 403) {
      router.push({ name: 'login', query: { expired: 1 } });
    }
    return Promise.reject(error);
  });
}

function resetAxios(interceptor) {
  delete axios.defaults.headers.common['Authorization'];
  if(interceptor !== null) {
    axios.interceptors.response.eject(interceptor);
  }
}

export default new Vuex.Store({
  state: {
    user: {},
    token: null,
    interceptor: null,
  },
  mutations: {
    login (state, { user, token, interceptor }) {
      Object.assign(state, { user, token, interceptor });
    },
    logout (state) {
      Object.assign(state, { user: null, token: null, interceptor: null });
    },
  },
  getters: {
    getUser: state => state.user,
    getToken: state => state.token,
    isAuthenticated: state => !!state.user,
  },
  actions: {
    init ({ commit }) {  
      let token = localStorage.getItem('token') || null;
      let user = localStorage.getItem('token') ? JSON.parse(localStorage.getItem('user')) : null;
      let interceptor = configureAxios(token);
      commit('login', { user, token, interceptor });
    },
    login ({ commit }, { user, token }) {
      localStorage.setItem('user', JSON.stringify(user));
      localStorage.setItem('token', token);
      let interceptor = configureAxios(token);
      commit('login', { user, token, interceptor });
    },
    logout ({ commit, state }) {
      let interceptor = state.interceptor;
      localStorage.removeItem('user');
      localStorage.removeItem('token');
      resetAxios(interceptor);
      commit('logout');
    },
  },
})
