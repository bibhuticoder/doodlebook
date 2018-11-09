<template>
  <canvas 
    id="canvas" 
    :height="height" 
    :width="width"
    @mousedown="handleMouseDown"
    @mouseup="handleMouseUp"
    @mousemove="handleMouseMove"
  />
</template>

<script>
import Brush from './Brush.js';

export default {
  components: {},
  props: ['height', 'width', 'brushType', 'size', 'color', 'imgSrc'],
  name: "Doodler",
  data(){ 
    return{
      canvas: null,
      ctx: null,
      mousedown: false,
      pos: {
        ix: null,
        iy: null,
        fx: null,
        fy: null
      }
    }
  },

  created(){
    this.brush = new Brush(this.size, this.color);
    this.brush.changeBrush(this.brushType);
  },

  mounted() {
    this.canvas = document.getElementById("canvas");
    this.ctx = this.canvas.getContext("2d");
    this.brush.setContext(this.ctx);
  },

  methods: {
    handleMouseDown(e) {
      this.mousedown = true;
      this.pos.ix = this.getMousePos(this.canvas, e).x;
      this.pos.iy = this.getMousePos(this.canvas, e).y;
    },

    handleMouseUp(e) {
      this.mousedown = false;
      this.$emit('change', this.canvas.toDataURL("image/png"))
    },

    handleMouseMove(e) {
      if (this.mousedown) {
        this.pos.fx = this.getMousePos(canvas, e).x;
        this.pos.fy = this.getMousePos(canvas, e).y;

        this.brush.draw(this.pos.ix, this.pos.iy, this.pos.fx, this.pos.fy);

        this.pos.ix = this.pos.fx;
        this.pos.iy = this.pos.fy;
      }
    },

    getMousePos(canvas, evt) {
      var rect = canvas.getBoundingClientRect();
      return {
        x: evt.clientX - rect.left,
        y: evt.clientY - rect.top
      };
    },

    clear(event){
      this.brush.clear();
    },

    getData(){
      return this.canvas.toDataURL("image/png");
    },

    drawImage(image){
      this.brush.drawImage(image);
    }

  },
  computed: {
    baseUrl() {
      return this.$store.getters.baseUrl;
    },
  },

  watch:{
    brushType(value){
      this.brush.changeBrush(value);
    },
    size(value){
      this.brush.changeSize(value);
    },
    color(value){
      this.brush.changeColor(value);
    },
    imgSrc(value){
      this.brush.drawImageFromSrc(value);
    }
  }


};
</script>

<style scoped>
canvas {
  float: left;
  background-color: white;
  box-shadow: 1px 1px 5px grey;
}
select{
  margin: 2px;
  padding: 2px;
  cursor: pointer;
  outline: none;
  background-color: white;
  color: #3f51b5;
  border-style: solid;
  border-color: #3f51b5;
  border-width: 1px;
  border-radius: 3px;
}

.seperator{
  height: 30px;
  width: 1px;
  margin-left: 20px;
  margin-right: 20px;
  background-color: #3f51b5;
}

.btn-clear{
  cursor: pointer;
  margin: 10px;
  color: #3f51b5;
}

</style>
