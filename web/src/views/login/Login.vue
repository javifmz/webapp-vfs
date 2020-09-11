<template>
  <main class="ui container">

    <div class="login">
      <div class="ui warning message" v-if="!error && expired"><i class="warning sign icon"></i> Su sesión ha expirado</div>
      <div class="ui error message" v-if="error"><i class="warning sign icon"></i> {{ error }}</div>
      <form class="ui form" @submit.prevent="login">
        <div class="ui stacked segment">
          <div class="field">
            <div class="ui left icon input">
              <i class="mail icon"></i>
              <input type="text" placeholder="Correo electrónico" v-model="credentials.email">
            </div>
          </div>
          <div class="field">
            <div class="ui left icon input">
              <i class="lock icon"></i>
              <input type="password" placeholder="Contraseña" v-model="credentials.password">
            </div>
          </div>
          <button type="submit" class="ui fluid button" :class="{ loading }">Entrar</button>
        </div>
      </form>
    </div>

  </main>
</template>

<script>
import APILoginService from '@/services/APILoginService'

export default {
  name: 'Login',
  data () {
    return {
      expired: this.$route.query.expired == 1,
      error: undefined,
      loading: false,
      credentials: {},
    }
  },
  created () {
    this.$store.dispatch('logout');
  },
  methods: {
    login () {
      const { email, password } = this.credentials;
      APILoginService.login(email, password)
      .then(({ user, token }) => {
        this.$store.dispatch('login', { user, token });
        this.$router.push({ name: 'logged' }).catch(error => error);
      })
      .catch(res => {
        switch(res.status) {
          case 401: this.error = 'Usuario no autorizado'; break;
          default:  this.error = 'Error en el servidor'; break;
        }
      });
    },
  },
}
</script>
