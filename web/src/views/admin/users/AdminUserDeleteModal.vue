<template>
  <div class="ui basic modal">
    <div class="ui header">Eliminar usuario</div>
    <div class="content">
      <p>¿Realmente desea eliminar el usuario <strong>{{ user.name }} {{ user.surname }}</strong> definitivamente?</p>
      <p>Esta operación no se puede deshacer</p>
    </div>
    <div class="actions">
      <a class="ui cancel button" @click="cancel()">Cancelar</a>
      <a class="ui red ok button" :class="{ loading, disabled: loading }" @click="save()"><i class="trash icon"></i> Eliminar</a>
    </div>
  </div>
</template>

<script>
import ModalService from '@/services/ModalService'
import APIAdminUserService from '@/services/APIAdminUserService'

export default {
  name: 'UserDeleteModal',
  props: {
    userId: Number,
  },
  data () {
    return {
      loading: false,
      user: { 
        name: '...',
        surname: '',
      },
    }
  },
  created () {
    this.loading = true;
    APIAdminUserService.getUser(this.userId)
    .then(user => {
      this.user = user;
      this.loading = false;
    });
  },
  methods: {
    save () {
      if(!this.loading) {
        this.loading = true;
        const promise = APIAdminUserService.deleteUser(this.userId);
        promise.then(() => ModalService.success());
      }
    },
    cancel () {
      ModalService.hide();
    },
  },
}
</script>
