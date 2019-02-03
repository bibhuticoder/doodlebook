<template>
  <div class="doodle">
    <div class="header">
      {{doodle.title}}
    </div>
    <div class="image">
      <router-link :to="(mode === 'view') ? `/doodle/${doodle.id}` : `/make/${doodle.id}`">
        <img :src="`${this.baseUrl}/storage/doodles/${doodle.image}`" />
      </router-link>
    </div>
    <div class="footer">
      <div class="author">
        <div class="author-pic">
          <img :src="`${baseUrl}/storage/profile_pics/${doodle.user.profile_pic}`" alt="">
        </div>
        {{doodle.user.username}}
      </div>
      <span class="badge badge-pill badge-primary"></span>
      
      <span class="float-left">
        <i class="far fa-comments"></i> {{doodle.comments}} comments
      </span>
      <span class="float-right">
        {{doodle.likes}} likes <i class="far fa-heart"></i>
      </span>
    </div>

  </div>
</template>

<script>
export default {
  components: {},
  name: "Doodle",
  props: ["doodle", 'mode'],
  data(){
    return{
      mousedown: false
    }
  },
  methods: {
  },
  computed: {
    baseUrl() {
      return this.$store.getters.baseUrl;
    }
  }
};
</script>

<style scoped lang="scss">
 .doodle {
    position: relative;
    float: left;
    width: 300px;
    height: 300px;
    margin: 20px;
    box-shadow: -0.5px -0.5px 10px grey;

  .header{
    width: 100%;
    height: 40px;
    font-size: 18px;
    color: #373737;
    text-align: center;
    line-height: 50px;
  }

  .image{
    width: calc(100% - 20px);
    height: 160px;
    margin: 10px;
    text-align: center;
    border-style: dotted;
    border-width: 2px;
    border-color: lightgray;
    cursor: pointer;

    img{
      width: 100%;
      max-height: 200px;
    }
  }

  .footer{
    width: 100%;
    height: 40px;
    line-height: 40px;
    padding-left: 10px;
    padding-right: 10px;

    div.author{
      width: 100%;
      height: 40px;
      text-align: left;
      font-size: 12px;
      // border-style: solid;
      // border-width: 1px;
      
      img{
        width: 30px;
        height: 30px;
        border-radius: 50%;
        float: left;
        margin-right: 5px;
        margin-top: 5px;
      }

    }


  }

}
</style>
