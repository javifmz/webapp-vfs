import Home from '@/views/Home.vue'
import Login from '@/views/login/Login.vue'

export default [
  { path: '/', name: 'root', redirect: { name: 'home' }  },

  { path: '/home', name: 'home', component: Home },

  { path: '/login', name: 'login', component: Login, meta: { hideNavigation: true } },
  { path: '/logged', name: 'logged', redirect: { name: 'home' } },
]
