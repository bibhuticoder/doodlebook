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

        <div class="seperator"></div>

        <select @change="changeBrushSize">
          <option value="1">1</option>
          <option value="3">2</option>
          <option value="5">3</option>
          <option value="7">4</option>
          <option value="10">5</option>
        </select>

        <i class="fas fa-sync-alt btn-clear" @click="clearBoard"></i>
        
        <div class="seperator"></div>

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

    <table>
      <tr>
        <td>

          <!-- Controls -->
          <div class="frames-control">
            <b-button-group>
              <b-button size="sm" @click="addFrame(true)">
                <i class="fas fa-plus"></i>
              </b-button>
              <b-button size="sm" @click="addFrame(false)">
                <i class="fas fa-plus-square"></i>
              </b-button>
              <b-button size="sm" @click="(timer === null) ? playAnimation() : stopAnimation()">
                <i v-if="timer === null" class="fas fa-play-circle"></i>
                <i v-else class="fas fa-stop-circle"></i>
              </b-button>
            </b-button-group>
            <b-form-input size="sm" type="text" placeholder="Apeed" v-model="speed"></b-form-input>
          </div>

          <draggable v-model="frames" class="frames-container" :options="{draggable:'.frame'}">
            <!-- Frames -->
            <div 
              v-for="(frame, i) in frames" 
              :key="i" 
              class="frame" 
              :class="{'frame-selected': i === currentFrame}"
              @click="switchFrame(i)"
            >
              <img :src="frame">
            </div>


          </draggable>
        </td>
        <td>
          <canvas 
            id="canvas" 
            height="400px" 
            width="900px"
            @mousedown="handleMouseDown"
            @mouseup="handleMouseUp"
            @mousemove="handleMouseMove"
          />
        </td>
      </tr>
    </table>

  </div>
</template>

<script>
import Brush from './Brush.js';
import Swatches from 'vue-swatches';
import ToolButton from '@/Components/ToolButton'
import Slider from '@/Components/Slider'
import "vue-swatches/dist/vue-swatches.min.css"
import draggable from 'vuedraggable'

export default {
  components: {Swatches, ToolButton, Slider, draggable},
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
      ],
      currentFrame: 0,
      frames: [],
      speed: 500,
      infinite: true,
      timer: null
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

    this.addFrame();
  },

  methods: {
    handleMouseDown(e) {
      this.mousedown = true;
      this.pos.ix = this.getMousePos(this.canvas, e).x;
      this.pos.iy = this.getMousePos(this.canvas, e).y;
    },

    handleMouseUp(e) {
      this.mousedown = false;
      this.updateCurrentFrame();
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
      if(!this.image) return ;
      let img = new Image();
      img.src = `${this.baseUrl}/storage/doodles/${this.image}`;
      img['crossOrigin'] = "Anonymous";
      img.onload = () => {
        this.ctx.drawImage(img, 0, 0);
      }
      
    },

    clearBoard(event){
      this.brush.clear();
      // event.target.style.transform = "rotate(90deg)";
    },

    addFrame(emptyFrame){
      if(emptyFrame)this.clearBoard();
      this.frames.push(this.canvas.toDataURL("image/png"));
      this.currentFrame = this.frames.length-1;
    },

    updateCurrentFrame(){
      this.frames[this.currentFrame] = this.canvas.toDataURL("image/png");
    },

    switchFrame(index){
      this.clearBoard();
      let image = this.frames[index];
      this.currentFrame = index;

      let img = new Image();
      img.src = image;
      img.onload = () => {
        this.ctx.drawImage(img, 0, 0);
      }
    },

    playAnimation(){
      console.log("sadas");
      var self = this;
      var play = true;
      var count = 0;
      var limit = (this.infinite) ? this.frames.length * 100 : this.frames.length;
      this.timer = setInterval(()=>{
        self.switchFrame(count);
        count++;
        count %= self.frames.length;
        if(count >= limit) clearInterval(self.timer);
      }, this.speed);
    },

    stopAnimation(){
      clearInterval(this.timer);
      this.timer = null;
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
  float: right;
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

.frames-container{
  padding-left: 0;
  float: left;
  height: 400px;
  overflow: auto;
  cursor: pointer;
    /* border-style: solid; */
  /* border-color: grey;
  border-width: 1px; */
  margin-right: 10px;
}

.frames-control{
  width: 70px;
}

.frame{
  width: 70px;
  height: 50px;
  margin: 10px;
  list-style-type: none;
  text-align: center;
  line-height: 50px;
  border-style: solid;
  cursor: pointer;
  border-color: grey;
  border-width: 1px;
  color: grey;
  font-weight: bold;
  font-size: 24px;
  transition: all 0.2s ease;
}

.frame img{
  width: 100%;
  height: 100%;
}

.frame-selected{
  /* border-color: #3f51b5; */
  color: #3f51b5;
  box-shadow: -1px -1px 10px #3f51b5; 
}

</style>
