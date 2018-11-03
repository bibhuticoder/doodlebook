<template>
  <div class="container center">
    <br>
    <div class="col" v-if="doodle">
      <img class="card-img-top" :src="`${baseUrl}/storage/doodles/${doodle.image}`" alt="Card image cap">
      <h5 class="card-title">{{doodle.title}}</h5>
      <p class="card-text">{{doodle.description}}</p>
      <p>
        <small class="text-muted float-left"><i class="fas fa-heart"></i> {{doodle.likes}} Likes</small>
      </p>
      <hr>
      <ul class="list-group" v-if="doodle.comments.length > 0">
        <li class="list-group-item" v-for="(comment, i) in doodle.comments" :key="i">
          {{comment.comment}}
        </li>
      </ul>
    </div> 
    <br>
    <br>
    <br>  
  </div>
</template>

<script>
import axios from "axios";
export default {
  name: "DoodleView",
  data() {
    return {
      doodle: null
    };
  },

  methods: {
    getDoodle() {
      var self = this;
      axios
        .get(`${this.baseUrl}/api/doodle/${this.$route.params.id}`)
        .then(response => {
          this.doodle = response.data;
        })
        .catch(e => {
          console.log(e);
        });
    }
  },

  created: function() {
    this.getDoodle();
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
