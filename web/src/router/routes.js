import Home from '@/views/Home.vue'
import Login from '@/views/login/Login.vue'

import Admin from '@/views/admin/Admin.vue'
import AdminUserList from '@/views/admin/users/AdminUserList.vue'

export default [
  { path: '/', name: 'root', redirect: { name: 'home' }  },

  { path: '/home', name: 'home', component: Home },

  { path: '/login', name: 'login', component: Login, meta: { hideNavigation: true } },
  { path: '/logged', name: 'logged', redirect: { name: 'home' } },

  { path: '/admin', name: 'admin', component: Admin },
  { path: '/admin/users', name: 'admin-users', component: AdminUserList, meta: { requiresAuth: true, onlyAdmin: true } },
]
