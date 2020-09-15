<template>
  <main class="ui container">

    <div class="ui breadcrumb">
      <router-link class="section" :to="{ name: 'home' }"><i class="home icon"></i></router-link>
      <i class="right angle icon divider"></i>
      <router-link class="section" :to="{ name: 'admin' }">Administración</router-link>
      <i class="right angle icon divider"></i>
      <router-link class="section active" :to="{ name: 'admin-users' }">Usuarios</router-link>
    </div>

    <div class="ui form">
      <div class="fields">
        <div class="four wide field">
          <dropdown placeholder="Estado" v-model="view.status" :is-number="true" class="fluid">
            <dropdown-option :value="1"><i class="check icon"></i> Habilitados</dropdown-option>
            <dropdown-option :value="0"><i class="ban icon"></i> Deshabilitados</dropdown-option>
          </dropdown>
        </div>
        <div class="four wide field">
          <dropdown placeholder="Tipo" v-model="view.admin" :is-number="true" class="fluid">
            <dropdown-option :value="0"><i class="user grey icon"></i> Todos</dropdown-option>
            <dropdown-option :value="1"><i class="wrench grey icon"></i> Administradores</dropdown-option>
          </dropdown>
        </div>
        <div class="seven wide field">
          <delayed-input placeholder="Buscar..." icon="search" v-model="view.string" class="fluid"></delayed-input>
        </div>
        <div class="one wide field">
          <a class="ui primary icon fluid button" @click="editUser()"><i class="plus icon"></i></a>
        </div>
      </div>
    </div>

    <ul class="ui tablist">
      <li class="header">
        <div class="column">
          <i class="user icon"></i>
          <span v-if="loading">Cargando...</span>
          <span v-else-if="failure">Ha ocurrido un error en el servidor</span>
          <span v-else-if="total"><span class="page" v-if="page">{{ page }} </span>{{ total }} {{ total == 1 ? 'usuario' : 'usuarios' }}</span>
          <span v-else>No existen usuarios</span>
        </div>
        <div class="column">
          <div class="ui active tiny primary inline loader" v-if="loading"></div>
          <a class="action" @click="reload()" v-else><i class="blue repeat icon"></i></a>
        </div>
      </li>
      <li v-for="user in users" :key="user.id">
        <div class="column ellipsis">
          <i class="user icon"></i>
          <router-link :to="{ name: 'admin-user', params: { userId: user.id } }">{{ user.name }} {{ user.surname }}</router-link>
        </div>
        <div class="column">
          <a class="action" @click="editUser(user.id)"><i class="blue pencil icon"></i></a>
          <a class="action" @click="changeUserPassword(user.id)" v-if="user.status !== 0"><i class="blue key icon"></i></a>
          <a class="action" @click="changeUserStatus(user.id, 0)" v-if="user.status !== 0 && user.id !== currentUser.id"><i class="red ban icon"></i></a>
          <a class="action" @click="changeUserStatus(user.id, 1)" v-if="user.status !== 1 && user.id !== currentUser.id"><i class="green check icon"></i></a>
          <a class="action disabled" v-if="user.status !== 0 && user.id === currentUser.id"><i class="light-grey ban icon"></i></a>
          <a class="action disabled" v-if="user.status !== 1 && user.id === currentUser.id"><i class="light-grey check icon"></i></a>
          <a class="action" @click="deleteUser(user.id)" v-if="user.status === 0 && user.id !== currentUser.id"><i class="red trash icon"></i></a>
        </div>
      </li>
      <li class="footer" v-if="showPagination">
        <div class="column">
          <a class="ui tiny compact icon blue button" @click="firstPage()"><i class="angle double left icon"></i></a>
          <a class="ui tiny compact icon blue button" @click="previousPage()"><i class="angle left icon"></i></a>
        </div>
        <div class="column">
          <a class="ui tiny compact icon blue button" @click="nextPage()"><i class="angle right icon"></i></a>
          <a class="ui tiny compact icon blue button" @click="lastPage()"><i class="angle double right icon"></i></a>
        </div>
      </li>
    </ul>

  </main>
</template>

<script>
import view from '@/mixins/view.js'
import ModalService from '@/services/ModalService'
import APIAdminUserService from '@/services/APIAdminUserService'
import DelayedInput from '@/components/DelayedInput'
// import ButtonDropdown from '@/components/ButtonDropdown'
import Dropdown from '@/components/Dropdown'
import DropdownOption from '@/components/DropdownOption'
import AdminUserEditModal from '@/views/admin/users/AdminUserEditModal'
import AdminUserStatusModal from '@/views/admin/users/AdminUserStatusModal'
import AdminUserDeleteModal from '@/views/admin/users/AdminUserDeleteModal'
import AdminUserPasswordModal from '@/views/admin/users/AdminUserPasswordModal'

export default {
  name: 'AdminUserList',
  components: { DelayedInput, Dropdown, DropdownOption },
  mixins: [ view ],
  data () {
    return {
      currentUser: this.$store.getters.getUser,
      users: [],
      total: 0,
      size: 10,
    }
  },
  created () {
    this.initView({
      status: { isArray: false, isNumber: true, defaultValue: 1 },
      string: { isArray: false, isNumber: false, defaultValue: '' },  
      admin: { isArray: false, isNumber: true, defaultValue: 0 },
      from: { isArray: false, isNumber: true, defaultValue: 0 }, 
    }, true);
  },
  methods: {
    viewChanged () {
      this.setLoading();
      let view = { ...this.view };
      if(!view.admin) delete view.admin;
      APIAdminUserService.getUsers(view, this.size)
      .then(data => {
        this.users = data.items || [];
        this.total = data.total || 0;
        this.setSuccess();
      })
      .catch(() => this.setFailure());
    },
    reload () {
      this.viewChanged();
    },
    editUser (userId) {
      ModalService.show(AdminUserEditModal, { userId }, this.reload, this.$store);
    },
    changeUserPassword (userId) {
      ModalService.show(AdminUserPasswordModal, { userId }, this.reload);
    },
    changeUserStatus (userId, status) {
      ModalService.show(AdminUserStatusModal, { userId, status }, this.reload);
    },
    deleteUser (userId) {
      ModalService.show(AdminUserDeleteModal, { userId }, this.reload);
    },
  },
}
</script>
