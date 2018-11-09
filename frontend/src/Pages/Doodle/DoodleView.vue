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

      <div class="comments" v-if="comments && comments.length > 0">
        <br>
        <i class="fas fa-comments"></i> {{comments.length}} Comments
        <hr>
        <Comment 
          v-for="(comment, i) in comments" 
          :comment="comment"
          :key="i" />
      </div>
      <div v-else-if="comments && comments.length === 0">
        No comments. Be the first one to comment.
      </div>
      <div class="center" v-else>
        <hr>
        <i class="status fas fa-circle-notch fa-spin"></i>
        <br>
        <p class="lead">Loading Comments</p>
      </div>

      <div v-if="loggedIn">
        <br>
        <!-- Comment Box -->
        <textarea 
          class="form-control" 
          placeholder="Write Some Comments here...." 
          :disabled="submitLoading"
          v-model="comment"></textarea>
        <br>
        <button 
          :disabled="submitLoading"
          class="btn btn-success btn-sm" 
          @click="submitComment">
            <i v-if="submitLoading" class="status fas fa-circle-notch fa-spin"></i>
            Submit
        </button>
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
import Comment from '@/Components/Comment';

export default {
  name: "DoodleView",
  components: {Comment},
  data() {
    return {
      doodle: null,
      comments: null,
      comment: null,
      submitLoading: false,
    };
  },

  methods: {
    getDoodle(callback) {
      var self = this;
      axios
        .get(`${this.baseUrl}/api/doodle/${this.$route.params.id}`)
        .then(response => {
          this.doodle = response.data;
          callback();
        })
        .catch(e => {
          console.log(e);
        });
    },

    getComments(){
      var self = this;
      axios
        .get(`${this.baseUrl}/api/doodle/${this.$route.params.id}/comments`)
        .then(response => {
          this.comments = response.data.data;
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
    },

    submitComment(){
      if(this.comment === null || this.comment.length <= 0) return;
      this.submitLoading = true;
      var self = this;
      axios
      .post(`${this.baseUrl}/api/comment`, {
        comment: this.comment,
        doodle_id: this.doodle.id
      }).then(response => {
        self.doodle.comments.push(response.data);
        self.submitLoading = false;
      })
      .catch(e => {
        console.log(e);
      });

    }

  },

  created: function() {
    this.getDoodle(() => this.getComments());
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
