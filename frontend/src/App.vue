<template>
  <div id="app">
    <navbar></navbar>
    <br>
    <br>
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
    getUser(){

    }
  },

  created(){
    axios.defaults.headers.common['Authorization'] = "Bearer " + localStorage.getItem('_token');
    if(!this.loggedIn) this.$store.dispatch('getUser');
  },

computed: {
    baseUrl() {
      return this.$store.getters.baseUrl;
    },
    loggedIn(){
      return this.$store.getters.loggedIn;
    }
  }

}


</script>

<style>

  body{
    overflow-x: hidden;
  }

  .center{
    text-align: center;
    margin: 0 auto;
  }
  .text-left{
    text-align: left;
  }

  .float-left{
    float: left;
  }

</style>
