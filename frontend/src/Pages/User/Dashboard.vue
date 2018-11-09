<template>
  <div class="container">
    <br>
    <h1 class="center">Dashboard</h1>
    <hr>
    <div class="row" v-if="doodles && doodles.length > 0">
      <div class="col" v-for="(doodle, i) in doodles" :key="i" >
        <Doodle 
          :doodle="doodle"
          mode="edit"
        />
      </div>   
    </div>
    <div v-else class="alert alert-danger" role="alert">
      No Doodles to show
    </div>
  </div>
</template>

<script>
import axios from "axios";
import Doodle from '@/Components/Doodle';

export default {
  name: "Dashboard",
  components: {Doodle},
  data() {
    return {
      doodles: null
    };
  },

  methods: {
    getDoodles() {
      if(!this.user) return;
      var self = this;
      return axios
        .get(`${this.baseUrl}/api/doodles/user/${this.user.id}`)
        .then(response => {
          this.doodles = response.data;
        })
        .catch(e => {
          console.log(e);
        });
    }
  },

  created: function() {
    this.getDoodles();
  },

  computed: {
    baseUrl() {
      return this.$store.getters.baseUrl;
    },
    user(){
      return this.$store.getters.user;
    }
  },

  watch:{
    user(newVal, oldVal){
      if(newVal !== null) this.getDoodles();
    }
  }
};
</script>

<style scoped>
</style>
