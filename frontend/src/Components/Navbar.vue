<template>
  <b-navbar toggleable="md" type="dark" variant="dark" fixed="top" class="_navbar">

    <b-navbar-toggle target="nav_collapse"></b-navbar-toggle>

    <b-navbar-brand href="#/">
      <i class="fas fa-book"></i> DoodleBook
    </b-navbar-brand>

    <b-collapse is-nav id="nav_collapse">

      <!-- Right aligned nav items -->
      <b-navbar-nav class="ml-auto">
        <b-nav-form>
          <div v-if="loggedIn">
            <router-link to="/new" class="btn btn-primary btn-sm">
              <i class="fas fa-plus"></i> Create New Doodle
            </router-link>

            <b-dropdown variant="link" size="lg">
              <template slot="button-content">
                <img class="dropdown-avatar" :src="`${baseUrl}/storage/profile_pics/${user.profile_pic}`" alt="">
              </template>
              <b-dropdown-item @click="$router.push({name: 'Dashboard'})">
                <i class="fas fa-book"></i> My Book
              </b-dropdown-item>
              <b-dropdown-item @click="$router.push({name: 'ProfileEdit'})">
                <i class="fas fa-user-circle"></i> Profile
              </b-dropdown-item>
              <b-dropdown-item @click="logout">
                <i class="fas fa-sign-out-alt"></i> Logout
              </b-dropdown-item>
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
      this.$store.commit('setUser', null);
      localStorage.removeItem('_token');
      this.$router.push({name: 'Login'});
    }
  },
  computed: {
    loggedIn() {
      return this.$store.getters.loggedIn;
    },
    user(){
      return this.$store.getters.user;
    },
    baseUrl() {
      return this.$store.getters.baseUrl;
    }
  }

};
</script>

<style scoped>
._navbar {
  border-radius: 0px !important;
  z-index: 500;
  background-color: #24292e;
  margin-bottom: 10px;
  box-shadow: -1px 1px 2px gray;
  height: 50px;
}

.dropdown-avatar{
  height: 30px;
  width: 30px;
  border-radius: 100%;
}
</style>
