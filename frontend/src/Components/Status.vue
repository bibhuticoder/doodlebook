<template>
 <button class="status" @click="handleClick">

    <div v-if="status == 0" class="unsaved">
      <div class="icon">
        <i class="fas fa-times-circle"></i>
      </div>
    </div>
    
    <div v-if="status == 1" class="saving">
      <div class="icon">
        <i class="fas fa-sync fa-spin"></i>
      </div>
    </div>
    
    <div v-if="status == 2" class="saved">
      <div class="icon">
       <i class="fas fa-check-circle"></i>
      </div>
    </div>

    <div v-if="status == 3" class="building">
      <div class="icon">
       <i class="fas fa-cog fa-spin"></i>
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
    width: 50px;
    height: 50px;
    bottom: 10px;
    right: 20px;
    background-color: lighten($color: lightgray, $amount: 30);
   
    text-align: center;
    cursor: pointer;
    font-size: 20px;
    border-radius: 100%;
    border-style: solid;
    border-width: 3px;
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

  .unsaved{color: salmon}

  .saving{color: #373737}

  .building{color: #5D4037}

  .saved{color: #43A047}

</style>
