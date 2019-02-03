<template>
  <div class="container">
    <br>
    <h1 class="center">DoodleBook</h1>
    <p class="lead center">
      A place to draw and share doodles
    </p>
    <hr>
    <div class="row" v-if="doodles && doodles.length > 0">
      <div class="col" v-for="(doodle, i) in doodles" :key="i" >
        <Doodle 
          :doodle="doodle"
          mode="view"
        />
      </div>   
    </div>
    <div v-else-if="doodles && doodles.length === 0" class="alert alert-warning">
      <p class="lead">No doodles to show</p>
    </div>
    <div v-else class="loading center">
      <br><br>
      <i class="fas fa-sync fa-spin spinner"></i>
      <br><br>
      Loading doodles...
    </div>
  </div>
</template>

<script>
import axios from "axios";
import Doodle from '@/Components/Doodle';

export default {
  name: "Home",
  components: {Doodle},
  data() {
    return {
      doodles: null
    };
  },

  methods: {
    getDoodles() {
      var self = this;
      axios
        .get(`${this.baseUrl}/api/doodles`)
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
    }
  }
};
</script>

<style scoped>

  .spinner{
    font-size: 50px;
  }

</style>
