<template>
  <div id="app">
    <the-top-menu/>
    <router-view/>
  </div>
</template>

<script>
import TheTopMenu from '@/components/TheTopMenu'

export default {
  name: 'App',
  components: { TheTopMenu },
}
</script>
