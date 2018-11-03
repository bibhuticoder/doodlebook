<template>
  <div class="slider" ref="slider" @mousemove="handleMouseMove" @mouseout="handleMouseUp">
    <div 
      ref="ball"
      class="ball" 
      @mousedown="handleMouseDown"
      @mouseup="handleMouseUp"
      >
    </div>
    <div class="line"></div>
  </div>
</template>

<script>
export default {
  components: {},
  name: "Slider",
  props: ["min", "max"],
  data(){
    return{
      mousedown: false
    }
  },
  methods: {
    handleMouseDown(){
      this.mousedown = true;
    },
    handleMouseUp(){
      this.mousedown = false;
    },
    handleMouseMove(event){
      if(!this.mousedown) return;

      this.$refs.ball.style.left = event.clientX - this.$refs.slider.getBoundingClientRect().x - 7 + "px";
      console.log(event.clientX - this.$refs.slider.getBoundingClientRect().x);// - );
    },
    handleClick(event) {
      this.$emit("clicked");
    }
  },
  computed: {}
};
</script>

<style scoped>
.slider {
  position: relative;
  background-color: aquamarine;
  padding-top: 20px;
  padding-bottom: 20px;
}

.line {
  height: 5px;
  width: 200px;
  background-color: #9fa8da;
}

.ball {
  position: absolute;
  top: 15px;
  left: 5px;
  height: 15px;
  width: 15px;
  border-radius: 100%;
  background-color: #3f51b5;
  cursor: pointer;
}
</style>
