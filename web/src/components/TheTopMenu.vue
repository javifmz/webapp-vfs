<template>
  <nav id="top-menu" class="ui fixed inverted borderless menu" v-if="!$route.meta.hideNavigation">
    <div class="ui container">
      <div class="header item">Webapp</div>
      <router-link class="icon item" :to="{ name: 'home' }"><i class="home icon"></i></router-link>
      <div class="right menu">
        <router-link class="icon item" :to="{ name: 'admin' }" v-if="user && user.admin"><i class="tools icon"></i></router-link>
        <router-link class="icon item" :to="{ name: 'login' }"><i class="sign icon" :class="user ? 'out' : 'in'"></i></router-link>
      </div>
    </div>
  </nav>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  name: 'TopMenu',
  computed: {
    ...mapGetters({ 
      user: 'getUser',
    }),
  },
}
</script>
