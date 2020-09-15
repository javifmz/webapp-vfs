<template>
  <div class="ui basic modal">
    <div class="ui header">
      <template v-if="status === 0">Deshabilitar usuario</template>
      <template v-if="status === 1">Rehabilitar usuario</template>
    </div>
    <div class="content">
      <p v-if="status === 0">¿Realmente desea deshabilitar al usuario <strong>{{ user.name }} {{ user.surname }}</strong>?</p>
      <p v-if="status === 1">¿Desea rehabilitar al usuario <strong>{{ user.name }} {{ user.surname }}</strong>?</p>
    </div>
    <div class="actions">
      <a class="ui cancel button" @click="cancel()">Cancelar</a>
      <a class="ui red ok button" :class="{ loading, disabled: loading }" @click="save()" v-if="status === 0"><i class="ban icon"></i> Deshabilitar</a>
      <a class="ui green ok button" :class="{ loading, disabled: loading }" @click="save()" v-if="status === 1"><i class="check icon"></i> Rehabilitar</a>
    </div>
  </div>
</template>

<script>
import ModalService from '@/services/ModalService'
import APIAdminUserService from '@/services/APIAdminUserService'

export default {
  name: 'AdminUserStatusModal',
  props: {
    userId: Number,
    status: Number,
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
        const promise = APIAdminUserService.updateUserStatus(this.userId, this.status);
        promise.then(user => {
          ModalService.success( user );
        });
      }
    },
    cancel () {
      ModalService.hide();
    },
  },
}
</script>
