<template>
  <div class="ui modal">
    <div class="header">Cambiar contraseña de usuario</div>
    <div class="content">

      <div class="ui form" :class="{ loading }">
        <div class="fields">
          <div class="eight wide field" :class="{ error: errors.fields.password }">
            <label>Contraseña</label>
            <input type="password" v-model="password" placeholder="Contraseña...">
          </div>
          <div class="eight wide field" :class="{ error: errors.fields.password }">
            <label>Repita la contraseña</label>
            <input type="password" v-model="passwordConfirmation" placeholder="Contraseña...">
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
      <a class="ui blue ok button" :class="{ loading, disabled }" @click="save()"><i class="checkmark icon"></i> Guardar</a>
    </div>
  </div>
</template>

<script>
import ModalService from '@/services/ModalService'
import ErrorService from '@/services/ErrorService'
import APIAdminUserService from '@/services/APIAdminUserService'

export default {
  name: 'AdminUserPasswordModal',
  props: {
    userId: Number,
  },
  data () {
    return {
      loading: false,
      password: '',
      passwordConfirmation: '',
      errors: ErrorService.init(),
    }
  },
  created () {},
  computed: {
    disabled () {
      return this.loading || this.password !== this.passwordConfirmation;
    }
  },
  methods: {
    save () {
      if(!this.loading) {
        this.loading = true;
        APIAdminUserService.updateUserPassword(this.userId, this.password, this.passwordConfirmation)
        .then(user => {
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
        case 'password must contain only letters (a-z) and digits (0-9)': return 'La contraseña solo puede contener letras y números';
        case 'password must have a length greater than 8': return 'La contraseña debe contener al menos 8 letras y números';
        default: return error;
      }
    },
  },
}
</script>
