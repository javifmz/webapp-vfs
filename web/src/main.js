import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import axios from 'axios'

window.$ = window.jQuery = require('jquery')
require('fomantic-ui-css/semantic.js')
import 'fomantic-ui-css/semantic.css'
import '@/assets/scss/style.scss'

axios.defaults.baseURL = process.env.VUE_APP_API_BASE
Vue.config.productionTip = false
store.dispatch('init');

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
