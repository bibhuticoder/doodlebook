<template>
  <div>
  <b-form inline>
    <!-- Brushes -->
    <ToolButton 
      :icon="b.icon"
      :selected="(b.type === brush.type)"
      v-for="(b, i) in brushes" 
      :key="i"
      @clicked="changeBrush(b.type)" />

      <select @change="changeBrushSize" class="form-control" style="width: 70px;">
        <option value="1">1</option>
        <option value="3">2</option>
        <option value="5">3</option>
        <option value="7">4</option>
        <option value="10">5</option>
      </select>
      &nbsp;

    <!-- Color Picker -->
    <swatches
      v-if="canvas"
      v-model="brush.color" 
      @input="changeColor"
      colors="text-advanced" 
      shapes="circles"
      swatch-size="20"
      :show-border="true"
      :trigger-style="{borderStyle: 'solid', borderWidth: '3px', borderColor: 'whitesmoke', height: '35px', width: '35px', cursor: 'pointer', boxShadow: '1px 1px 2px black'}"
      >
    </swatches>
  </b-form>

  <canvas 
    id="canvas" 
    height="500px" 
    width="900px"
    @mousedown="handleMouseDown"
    @mouseup="handleMouseUp"
    @mousemove="handleMouseMove"
  />

  </div>
</template>

<script>
import Brush from './Brush.js';
import Swatches from 'vue-swatches';
import ToolButton from '@/Components/ToolButton'
import Slider from '@/Components/Slider'
import "vue-swatches/dist/vue-swatches.min.css"

export default {
  components: {Swatches, ToolButton, Slider},
  props: ['image'],
  name: "Doodler",
  data(){ 
    return{
      canvas: null,
      ctx: null,
      brush: null,
      mousedown: null,
      pos: {
        ix: null,
        iy: null,
        fx: null,
        fy: null
      },

      brushes: [
        { type: 'pencil', icon: 'fas fa-pen-fancy' },
        { type: 'marker', icon: 'fas fa-pen-alt' },
        { type: 'eraser', icon: 'fas fa-eraser' },
        { type: 'spray', icon: 'fas fa-spray-can' },
        { type: 'chalk', icon: 'fas fa-pencil-alt' },
        { type: 'doubleBrush', icon: 'fas fa-pencil-ruler' },
      ]
    }
  },

  created(){
    this.brush = new Brush(2, "black", 10, 2);
  },

  mounted() {
    this.canvas = document.getElementById("canvas");
    this.ctx = this.canvas.getContext("2d");
    this.drawImage();
    this.brush.setContext(this.ctx);
    this.brush.changeBrush("pencil");
    
    this.ctx.lineJoin = 'round';
    this.ctx.lineCap = 'round';
    this.ctx.strokeStyle = "black";
    this.ctx.lineWidth = this.brush.size;
    this.mousedown = false;
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

    changeColor(color){
      this.brush.changeColor(color);
    },

    changeBrushSize(event){
      this.brush.changeSize(event.target.value);
    },

    changeBrush(type){
      this.brush.changeBrush(type);
    },

    drawImage(){
      console.log('drawn');
      if(!this.image) return ;
      let img = new Image();
      img.src = `${this.baseUrl}/storage/doodles/${this.image}`;
      img['crossOrigin'] = "Anonymous";
      img.onload = () => {
        this.ctx.drawImage(img, 0, 0);
      }
      
    }
  },
  computed: {
    baseUrl() {
      return this.$store.getters.baseUrl;
    },
  },

  watch:{
    image(){
      this.drawImage();
    }
  }


};
</script>

<style scoped>
canvas {
  float: left;
  background-color: #F5F5F5;
}

.btn-brush{
  width: 35px;
  height: 38px;
  font-size: 15px;
  border-style: solid;
  border-color: #E0E0E0;
  border-width: 2px;
  text-align: center;
  line-height: 35px;
  background-color: #E0E0E0;
  margin-left: 2px;
  margin-right: 2px;
  cursor: pointer;
  color: #373737;
}

.btn-brush-selected{
  border-color: #9E9E9E;
}

</style>
