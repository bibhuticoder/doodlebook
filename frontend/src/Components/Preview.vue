<template>
<div>
  <div class="back"></div>
  <div class="preview">
    <img :src="doodleSrc" id="preview-image">
  </div>
</div>
  
</template>

<script>
export default {
  components: {},
  name: "Preview",
  props: ["doodleSrc"],
  data(){
    return{
    }
  },
  mounted(){
    document.getElementById('preview-image').src = document.getElementById('preview-image').src + "/"; 
  },
  methods: {},
  computed: {}
};
</script>

<style scoped lang="scss">

  .back{
    position: absolute;
    width: 100vw;
    height: calc(100vh - 45px);
    left: 0;
    top: 0;
    background-color: black;
    opacity: 0.5;
    z-index: 5;
  }

  .preview{
    position: absolute;
    width: 80vw;
    height: 80vh;
    left: 10vw;
    top: 10vh;
    z-index: 6;
    text-align: center;

    img{
      margin-top: 50px;
    }

  }

</style>
