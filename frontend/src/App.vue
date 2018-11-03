<template>
  <div id="app">
    <navbar></navbar>
    <router-view></router-view>
  </div>
</template>

<script>
import Navbar from "./Components/Navbar.vue";
import axios from 'axios';

export default {
  components: {
    Navbar
  },
  name: 'app',
  data () {
    return {
      authenticated: false
    }
  },

  methods: {
  },

  created(){
    axios.defaults.headers.common['Authorization'] = "Bearer " + localStorage.getItem('_token');
    this.$store.dispatch('getUser');
  },

  computed: {
    baseUrl() {
      return this.$store.getters.baseUrl;
    },
  }

}


</script>

<style>
  .center{
    text-align: center;
    margin: 0 auto;
  }
</style>
