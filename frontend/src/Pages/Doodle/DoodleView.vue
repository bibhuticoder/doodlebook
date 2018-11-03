<template>
  <div class="container">
    <br>
    <div v-if="doodle">

      <h5>{{doodle.title}}</h5>
      <i class="fas fa-heart"></i> {{doodle.likes}} Likes
      <br> 
      <i class="fas fa-calendar-alt"></i> {{doodle.created_at}} 
      
      <div v-if="loggedIn">
        <br>

        <button class="btn btn-default btn-sm" v-if="!doodle.starred" @click="likeDoodle">
          <i class="fas fa-star"></i> Star
        </button>
        <button class="btn btn-default btn-sm">
          <i class="fas fa-anchor"></i> Fork
        </button>
      </div>
      
      <br>
      <img class="doodle-image" :src="`${baseUrl}/storage/doodles/${doodle.image}`">
      
      
      <div v-html="doodle.description"></div>

      
      <br>
      <i class="fas fa-comments"></i> {{doodle.comments.length}} Comments
      <hr>

      <ul class="list-group" v-if="doodle.comments.length > 0">
        <li class="list-group-item" v-for="(comment, i) in doodle.comments" :key="i">
          {{comment.comment}}
        </li>
      </ul>

      <div v-if="loggedIn">
        <!-- Comment Box -->
        <textarea class="form-control" placeholder="Write Some Comments here...."></textarea>
        <br>
        <button class="btn btn-success btn-sm">Submit</button>
      </div>

      <b-alert v-else show >
        Login to comment
      </b-alert>

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
    },

    likeDoodle(){
      var self = this;
      axios
        .post(`${this.baseUrl}/api/doodle/${this.doodle.id}/like`)
        .then(response => {
          console.log(response.data.message);
          if(response.data.message === "success"){
            doodle.likes++;
            doole.starred = true;
          }
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
    },
    loggedIn(){
      return (this.$store.getters.loggedIn);
    }
  }
};
</script>

<style scoped>

  .doodle-image{
    height: 200px;
    border-style: solid;
    border-color: grey;
  }


</style>
