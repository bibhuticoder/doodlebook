<template>
  <!-- <b-card>
    <b-media>
      <b-img class="round-image" :src="`${baseUrl}/storage/profile_pics/${comment.user.profile_pic}`" slot="aside" width="64" height="64" />
      <h5 class="mt-0">{{comment.user.username}}</h5>
      <p>{{comment.comment}}</p>

      <b-media v-for="(reply, i) in comment.replies" :key="i">
        <b-img class="round-image" :src="`${baseUrl}/storage/profile_pics/${reply.user.profile_pic}`" slot="aside" width="64" height="64" />
          <h5 class="mt-0">{{reply.user.username}}</h5>
          <p class="mb-0">{{reply.reply}}</p>
      </b-media>

      <div v-if="loggedIn">
        <button 
          v-if="!showReplyInput" 
          class="btn btn-primary btn-sm float-right" 
          @click="showReplyInput=true">
            Reply
        </button>

        <div v-if="showReplyInput">
          <textarea class="form-control" rows="3" v-model="reply"></textarea>
          <br>
          <button class="btn btn-success btn-sm" @click="submitReply"> Reply </button>
          <button class="btn btn-default btn-sm" @click="showReplyInput=false">
            <i class="fas fa-chevron-up"></i>
          </button>
        </div>
        
      </div>

    </b-media>
  </b-card> -->
  <div class="comment">
    <div class="comment-user-image">
      <img :src="`${baseUrl}/storage/profile_pics/${comment.user.profile_pic}`" alt="">
    </div>
    <div class="header">
      <label class="username">{{comment.user.username}}</label>
      <label class="datetime">
        <i class="fas fa-calendar-alt"></i> {{comment.created_at}}
      </label>
    </div>
    <div class="body">
      {{comment.comment}}
    </div>
    <div class="footer">
      <button> Replies <i class="fas fa-chevron-down"></i> </button>
    </div>
  </div>
</template>

<script>
export default {
  components: {},
  name: "Comment",
  props: ["comment"],
  data(){
    return{
      showReplyInput: false,
      reply: null
    }
  },
  methods: {

    getReplies(){

    },

    submitReply(){
      if(this.reply === null || this.reply.length <= 0) return;
      this.submitLoading = true;
      var self = this;
      axios
      .post(`${this.baseUrl}/api/reply`, {
        reply: this.reply,
        comment_id: this.comment.id
      }).then(response => {
        self.doodle.comments.push(response.data);
        self.submitLoading = false;
      })
      .catch(e => {
        console.log(e);
      });

    }
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

<style scoped lang="scss">
  $primary-color: #4DB6AC;
  $secondary-color: #80CBC4;
  $ternary-color: #B2DFDB;
  $text-color: #373737;
  $border-color: #373737;
  $border-width: 1.5px;

  // template
  %bordered-style {
    border-style: solid;
    border-width: $border-width;
    border-color: $border-color;
    color: $text-color;
  }

  .comment{
    @extend %bordered-style;
    position: relative;
    width: 100%;
    color: $text-color;
    border-radius: 5px;
    margin-bottom: 40px;
    

    .comment-user-image{
      @extend %bordered-style;
      width: 50px;
      height: 50px;
      position: absolute;
      top: -20px;
      left: -20px;
      border-radius: 100%;
      background-color: white;

      img{
        width: 100%;
        height: 100%;
        border-radius: 100%;
      }

    }

    div.header{
      width: 100%;
      height: 40px;
      padding-left: 30px;
      padding-right: 10px;
      padding-top: 10px;
      font-size: 18px;

      label.username{
        float: left;
        font-size: 16px;
        font-weight: 500;
      }

      label.datetime{
        // @extend %bordered-style;
        float: right;
        font-size: 12px;
        padding: 5px;
        border-radius: 2px;
      }

    }

    div.body{
      width: 100%;
      padding: 10px;
      font-size: 12px;
      text-align: left;
    }

    div.footer{
      width: 100%;
      padding-right: 10px;
      padding-bottom: 10px; 
      text-align: right;

      button{
        @extend %bordered-style;
        outline: none;
        cursor: pointer;
        color: $text-color;
        background-color: white;
        border-radius: 2px;
        font-size: 14px;
        padding: 5px;
        
      }

    }

  }

  .round-image{
    border-radius: 100%;
  }
</style>
