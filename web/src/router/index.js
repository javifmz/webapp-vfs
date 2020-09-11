import Vue from 'vue'
import VueRouter from 'vue-router'

import ModalService from '@/services/ModalService'

import routes from '@/router/routes'
import store from '@/store'

Vue.use(VueRouter)

const router = new VueRouter({
  mode: 'history',
  base: process.env.VUE_APP_WEB_BASE,
  linkActiveClass: 'active',
  linkExactActiveClass: 'active',
  routes
})

router.beforeEach((to, from, next) => {
  // Authentication
  if ( to.matched.some(record => record.meta.requiresAuth) ) {
    if ( !store.getters.isAuthenticated ) {
      next('/login');
      return;
    }
  }
  // Admin
  if ( to.matched.some(record => record.meta.onlyAdmin) ) {
    let user = store.getters.getUser;
    if ( !user || !user.admin ) {
      next('/login');
      return;
    }
  }
  // Manager
  if ( to.matched.some(record => record.meta.onlyManager) ) {
    let user = store.getters.getUser;
    if ( !user || !user.teamRole === 2 ) {
      next('/login');
      return;
    }
  }
  // Modal close
  if ( ModalService.isOpen() ) {
    ModalService.hide();
    next(false);
    return;
  }
  // Continue normally
  next();
});

export default router
