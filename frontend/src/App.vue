<template>
  <div id="app">
    <navbar></navbar>
    <router-view></router-view>
  </div>
</template>

<script>
import Navbar from "./Components/Navbar.vue";
export default {
  components: {
    Navbar
  },
  name: 'app',
   data () {
    return {
    }
  },
  
  methods:{
    
  }
  
  
}


</script>

<style>
  .center{
    text-align: center;
    margin: 0 auto;
  }
</style>
