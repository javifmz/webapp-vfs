<template>
  <main class="ui container">

    <div class="ui breadcrumb">
      <router-link class="section" :to="{ name: 'home' }"><i class="home icon"></i></router-link>
      <i class="right angle icon divider"></i>
      <router-link class="section" :to="{ name: 'admin' }">Administración</router-link>
    </div>

    <table class="ui striped unstackable table">
      <tbody>
        <tr>
          <td class="collapsing"><router-link :to="{ name: 'admin-users' }" class="ui tiny blue compact icon button"><i class="users icon"></i></router-link></td>
          <td><router-link :to="{ name: 'admin-users' }"><strong>Administrar usuarios</strong></router-link></td>
          <td class="collapsing right aligned"><router-link :to="{ name: 'admin-users' }" class="ui tiny blue compact icon button"><i class="right chevron icon"></i></router-link></td>
        </tr>
      </tbody>
    </table>

  </main>
</template>

<script>
export default {
  name: 'Admin',
}
</script>
