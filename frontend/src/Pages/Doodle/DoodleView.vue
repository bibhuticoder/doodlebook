<template>
  <div class="container">
    <br>
    <div v-if="doodle">
      <div class="container">
        <div class="row">
          <div class="col-md-3">
            <h3>{{doodle.title}}</h3>
            <b-badge variant="light">
              <i class="fas fa-heart"></i> {{doodle.likes}} Likes
            </b-badge>
            <br>
            <b-badge variant="light">
               <i class="fas fa-calendar-alt"></i> {{doodle.created_at}} 
            </b-badge>
            <br>
            <b-badge variant="light">
               <i class="fas fa-user"></i> {{doodle.user.username}} 
            </b-badge>
            <div v-if="loggedIn && !doodle.liked">
              <button class="btn btn-success btn-sm" v-if="!doodle.starred" @click="likeDoodle">
                <i class="fas fa-heart"></i> Like
              </button>
            </div>
            <hr> 
            <div class="panel" v-html="doodle.description"></div>
          </div>
          <div class="col-md-9">
            <div>
              <b-img :src="`${baseUrl}/storage/doodles/${doodle.image}`" thumbnail fluid alt="Responsive image" />
            </div>
          </div>
        </div>
      </div>

      <!-- comments -->
      <div class="comments" v-if="comments && comments.length > 0">
        <hr>
        <Comment 
          v-for="(comment, i) in comments" 
          :comment="comment"
          :key="i" />
      </div>
      <div v-else-if="comments && comments.length === 0" class="alert alert-warning">
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
          this.comments = response.data;
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
          this.$toastr('error', 'Already Liked', 'Error');
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
        self.comments.push(response.data);
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
    margin: 0 auto;
    height: 500px;
    border-style: solid;
    border-color: grey;
  }


</style>
