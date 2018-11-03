<template>
<!-- <nav class="navbar navbar-dark bg-dark">
  <a class="navbar-brand" href="#">DoodleBook</a>

  <div v-if="loggedIn">
    <router-link to="/new" class="btn btn-primary btn-sm">Create</router-link>
  </div>
  <div v-else>
    <router-link to="/login" class="btn btn-success btn-sm">Login</router-link>
  </div>

</nav> -->

<b-navbar toggleable="md" type="dark" variant="dark">

  <b-navbar-toggle target="nav_collapse"></b-navbar-toggle>

  <b-navbar-brand href="#/">DoodleBook</b-navbar-brand>

  <b-collapse is-nav id="nav_collapse">

    <!-- Right aligned nav items -->
    <b-navbar-nav class="ml-auto">
      <b-nav-form>
        <div v-if="loggedIn">
          <router-link to="/new" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Create New
          </router-link>

            <b-dropdown id="ddown1" text="User" class="btn-default">
              <b-dropdown-item>Profile</b-dropdown-item>
              <b-dropdown-item @click="logout">Logout</b-dropdown-item>
            </b-dropdown>
        </div>
        <div v-else>
          <router-link to="/login" class="btn btn-success btn-sm">Login</router-link>
        </div>
      </b-nav-form>
    </b-navbar-nav>
  </b-collapse>
</b-navbar>


</template>

<script>
export default {
  components: {},
  name: "navbar",
  // props: [],
  methods: {
    logout(){
      this.$store.commit('setToken', null);
      localStorage.removeItem('_token');
    }
  },
  computed: {
    loggedIn() {
      return this.$store.getters.loggedIn;
    },
    user(){
      return this.$store.getters.user;
    }
  }
};
</script>

<style scoped>
.navbar {
  border-radius: 0px !important;
  height: 50px;
  z-index: 10;
  background-color: #24292e;
  /* box-shadow: 1px 1px 2px black; */
}
</style>
