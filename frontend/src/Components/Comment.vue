<template>	
	<!-- <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-md-2">
            <img class="img img-rounded img-fluid" :src="`${baseUrl}/storage/profile_pics/${comment.user.profile_pic}`" alt="">
            <p class="text-secondary text-center">
              <i class="fas fa-calendar-alt"></i> {{comment.created_at}}
            </p>
        </div>
        <div class="col-md-10">
            <div class="clearfix"></div>
            <p>
              {{comment.comment}}
            </p>
        </div>+
      </div>
    </div>
	</div> -->

  <table>
    <!-- user comments -->
    <tr>
      <td class="image-container">
        <img class="img" :src="`${baseUrl}/storage/profile_pics/${comment.user.profile_pic}`" alt="">
      </td>
      <td>
        <span class="badge badge-pill badge-primary">{{comment.user.username}}</span>
        <span class="badge badge-light">{{comment.created_at}}</span>
        <br>
        <label>{{comment.comment}}</label>
      </td>
    </tr>

  </table>
  
</template>

<script>
export default {
  components: {},
  name: "Comment",
  props: ["comment"],
  data(){
    return{
      commentInput: "",
    }
  },
  methods: {
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

  .image-container{
    width: 100px;
  }

  .img{
    background-image: url("https://image.ibb.co/jw55Ex/def_face.jpg");
    background-size: cover;
    border-radius: 100%;
    width: 60px;
    height: 60px;
    margin-right: 30px;
    margin-bottom: 30px;
  }

  table{
    width: 100%;
  }

  .comment-container{
    margin-bottom: 20px;

  }

</style>
