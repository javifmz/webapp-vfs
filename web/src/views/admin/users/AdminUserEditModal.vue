<template>
  <div class="ui modal">
    <div class="header" v-if="this.edit">Editar usuario</div>
    <div class="header" v-else>Crear usuario</div>
    <div class="content">

      <div class="ui form" :class="{ loading }">
        <h4 class="ui dividing header">Datos del usuario</h4>
        <div class="fields">
          <div class="six wide field" :class="{ error: errors.fields.name }">
            <label>Nombre</label>
            <input type="text" v-model="user.name" placeholder="Nombre...">
          </div>
          <div class="ten wide field" :class="{ error: errors.fields.surname }">
            <label>Apellidos</label>
            <input type="text" v-model="user.surname" placeholder="Apellidos...">
          </div>
        </div>
        <div class="fields">
          <div class="ten wide field" :class="{ error: errors.fields.email }">
            <label>Correo electrónico</label>
            <input type="text" v-model="user.email" placeholder="Correo electrónico...">
          </div>
          <div class="six wide field" :class="{ disabled: user.id === currentUser.id }">
            <label>Privilegios</label>
            <dropdown placeholder="Privilegios..." v-model="user.admin" :is-number="true" class="fluid">
              <dropdown-option :value="0">No administrador</dropdown-option>
              <dropdown-option :value="1">Administrador</dropdown-option>
            </dropdown>
          </div>
        </div>
      </div>

      <div class="ui error message" v-if="errors.messages.size > 0">
        <div class="header">Han ocurrido los siguientes errores</div>
        <ul class="list">
          <li v-for="message in errors.messages" :key="message">{{ message }}</li>
        </ul>
      </div>

    </div>
    <div class="actions">
      <a class="ui cancel button" @click="cancel()">Cancelar</a>
      <a class="ui blue ok button" :class="{ loading, disabled: loading }" @click="save()"><i class="checkmark icon"></i> Guardar</a>
    </div>
  </div>
</template>

<script>
import ModalService from '@/services/ModalService'
import ErrorService from '@/services/ErrorService'
import APIAdminUserService from '@/services/APIAdminUserService'
import Dropdown from '@/components/Dropdown'
import DropdownOption from '@/components/DropdownOption'

export default {
  name: 'AdminUserEditModal',
  components: { Dropdown, DropdownOption },
  props: {
    userId: Number,
  },
  data () {
    return {
      currentUser: this.$store.getters.getUser,
      edit: this.userId !== undefined,
      loading: false,
      user: { 
        name: '', 
        surname: '',
        email: '',
        admin: 0,
      },
      errors: ErrorService.init(),
    }
  },
  created () {
    if(this.edit) {
      this.loading = true;
      APIAdminUserService.getUser(this.userId)
      .then(user => {
        this.user = user;
        this.loading = false;
      });
    }
  },
  methods: {
    save () {
      if(!this.loading) {
        this.loading = true;
        const promise = this.edit ? 
          APIAdminUserService.updateUser(this.userId, this.user) :
          APIAdminUserService.addUser(this.user);
        promise.then(user => {
          ModalService.success( user );
        }).catch(response => {
          this.loading = false;
          this.errors = ErrorService.process(response, this.translateError);
        });
      }
    },
    cancel () {
      ModalService.hide();
    },
    translateError (error) {
      switch(error) {
        case 'name must not be empty': return 'No se ha especificado el nombre';
        case 'surname must not be empty': return 'No se han especificado los apellidos';
        case 'email must not be empty': return 'No se ha especificado el correo electrónico';
        case 'email must be valid email': return 'El correo electrónico no es válido';
        case 'email is already taken': return 'El correo electrónico ya se está utilizando';
        default: return error;
      }
    },
  },
}
</script>
