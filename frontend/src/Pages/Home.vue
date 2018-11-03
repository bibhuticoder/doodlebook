<template>
  <div class="container">
    <br>
    <h1 class="center">DoodleBook</h1>
    <p class="lead center">
      A place to draw and share sketches
    </p>
    <hr>
    <div class="row" v-if="doodles && doodles.length > 0">
      <div class="col" v-for="(doodle, i) in doodles" :key="i" >
        <div class="card" style="width: 18rem;">
          <router-link :to="'/doodle/' + doodle.id">
            <img class="card-img-top" :src="`${baseUrl}/storage/doodles/${doodle.image}`" alt="Card image cap">
          </router-link>
          <div class="card-body">
            <h5 class="card-title">{{doodle.title}}</h5>
            <p class="card-text">{{doodle.description}}</p>
          </div>
          <div class="card-footer">
            <small class="text-muted float-left"><i class="fas fa-heart"></i> {{doodle.likes}}</small>
            <small class="text-muted float-right"><i class="fas fa-comments"></i> {{doodle.comments}}</small>
          </div>
        </div>
      </div>   
    </div>
    <div v-else class="alert alert-danger" role="alert">
      No Doodles to show
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  name: "Home",
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
</style>
