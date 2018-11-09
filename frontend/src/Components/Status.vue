<template>
 <button class="status" @click="handleClick">

    <div v-if="status == 0" class="unsaved">
      <div class="icon">
        <i class="fas fa-times-circle"></i>
      </div>
      <div class="text">
        Gif Unsaved
      </div>
    </div>
    
    <div v-if="status == 1" class="saving">
      <div class="icon">
        <i class="fas fa-sync fa-spin"></i>
      </div>
      <div class="text">
        Saving
      </div>
    </div>
    
    <div v-if="status == 2" class="saved">
      <div class="icon">
       <i class="fas fa-check-circle"></i>
      </div>
      <div class="text">
        Saved
      </div>
    </div>

    <div v-if="status == 3" class="building">
      <div class="icon">
       <i class="fas fa-cog fa-spin"></i>
      </div>
      <div class="text">
        Building Gif
      </div>
    </div>

      

 </button>
</template>

<script>
export default {
  components: {},
  name: "Status",
  props: ["status"],
  data(){
    return{
    }
  },
  methods: {
    handleClick(){
      this.$emit('clicked');
    }
  },
  computed: {}
};
</script>

<style scoped lang="scss">
  .status{
    position: absolute;
    width: 80px;
    height: 55px;
    background-color: lighten($color: lightgray, $amount: 30);
    bottom: 20px;
    right: 20px;
    text-align: center;
    cursor: pointer;
    font-size: 20px;
    border-radius: 5px;
    border-style: solid;
    border-width: 1px;
    border-color: lightgray;
    transition: all 0.5s ease;
    outline: none;

    .text{
      font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      font-size: 12px;
    }
  }

  .status:hover{
    border-color: gray;
  }

  .unsaved{color: #373737}

  .saving{color: #FFB300}

  .building{color: #5D4037}

  .saved{color: #43A047}

</style>
