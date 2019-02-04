<template>
  <div>

    <DoodlerMenubar
      @sizeChanged="(size)=> this.size = size"
      @colorChanged="(color)=> this.color = color"
      @brushChanged="(brush)=> this.brush = brush"
      @cleared="clearBoard"
    />
      
    <table>
      <tr>
        <td>
          <draggable
            v-if="frames.length > 0"
            v-model="frames" 
            class="frames-container" 
            :options="{draggable:'.frame'}"
            @end="updateAnimationDetail">
              <!-- Frames -->
              <Frame 
                v-for="(frame, i) in frames"
                :key="i"
                :frame="frame" 
                :selected="i === currentFrame"
                :color="color"
                @clicked="switchFrame(i)" />
          </draggable>

          <div v-else class="msg-no-frame" @click="addFrame(true)">
            <i class="fas fa-plus"></i>
          </div>

        </td>
        <td>
          <Canvas
            ref="canvas"
            height="400px" 
            width="900px"
            :brushType="brush"
            :size="size"
            :color="color"
            @change="handleCanvasChange"
          />
        </td>
      </tr>
    </table>

    <DoodlerToolbar
      :default-duration="(frames[currentFrame]) ? (frames[currentFrame].duration) : 0"
      @frameAdded="addFrame(true)"
      @frameCloned="addFrame(false)"
      @frameDeleted="deleteCurrentFrame"
      @durationChanged="handleDurationChange"
      @saved="updateCurrentFrame"
      @previewToggled="handlePreviewToggle"
    />

  </div>
</template>

<script>
import Canvas from '@/Components/Canvas';
import DoodlerToolbar from '@/Components/DoodlerToolbar';
import DoodlerMenubar from '@/Components/DoodlerMenubar';
import Frame from '@/Components/Frame';
import draggable from 'vuedraggable';
import axios from "axios";
import helper from '../helper'

export default {
  components: {draggable, Canvas, DoodlerMenubar, DoodlerToolbar, Frame},
  props: ['doodle-id', 'initFrames'],
  name: "Doodler",
  data(){ 
    return{
      brush: 'pencil',
      size: 1,
      color: 'black',

      currentFrame: 0,
      frames: [],
      sequence: null,
      addingFrame: false,
      changeFrameKeyEvent: true
    }
  },

  created(){

  },

  mounted() {
    
    if(this.initFrames.length <= 0) return;

    /*
     framesArr: used to Preserve array order
     doneProcessing: only asign 'frames' after all processing is done
     IIFE is used to add to specific index of array from inside
    */

    var framesArr = new Array(this.initFrames.length);
    var doneProcessing = framesArr.length;
    this.initFrames.forEach((frame, i) => {
      
      // use index inside callback
      ((index) => {
        helper.urlToCanvasData(`${this.baseUrl}/getImage?type=frame&filename=${frame.image}`, (canvasData) => {
          framesArr[index] = ({
            ...frame,
            sn: index+1,
            data: canvasData,
            status: 2,
          });
          doneProcessing--;
          
          // after all procesing is done
          if(doneProcessing === 0){
            this.frames = framesArr;
            this.switchFrame(0);
            console.log('assignFrame');
          }
          
        })
      })(i);
    });


    // change frame on keypress
    const UP = 38;
    const DOWN = 40;
    document.onkeyup = (e) => {
      if(!this.changeFrameKeyEvent) return;
      if(e.keyCode === UP && this.currentFrame > 0){
        this.currentFrame--;
        this.switchFrame(this.currentFrame);
      }
      else if(e.keyCode === DOWN && this.currentFrame < this.frames.length-1){
        this.currentFrame++;
        this.switchFrame(this.currentFrame);
      }
    }
  },

  methods: {
    
    handleCanvasChange(data) {
      if(this.frames.length === 0) return;
      this.updateCurrentFrame(data);
      this.$emit('statusChange', 0);
    },

    clearBoard(){
      this.$refs.canvas.clear();
    },

    handleDurationChange(duration){
      this.frames[this.currentFrame].duration = duration;
      this.frames[this.currentFrame].status = 0;
      this.$emit('statusChange', 0);
    },

    addFrame(emptyFrame){
      if(this.addingFrame) return;
      if(emptyFrame)this.clearBoard();

      let frameData = this.$refs.canvas.getData();
      this.frames.push({id: null, sn: this.frames.length, data: frameData, status: 1, duration: 10});
      this.addingFrame = true;
      
      axios.post(`${this.baseUrl}/api/frame`, {
        doodle_id: this.doodleId,
        image: frameData
      })
      .then(response => {
        this.frames[this.frames.length-1].id = response.data;
        this.frames[this.frames.length-1].status = 2;
        this.switchFrame(this.frames.length-1);
        this.addingFrame = false;
        this.updateAnimationDetail();
      })
      .catch(e => {
        console.log(e);
      });
    },

    updateCurrentFrame(data){
      this.frames[this.currentFrame].status = 1;
      if(data) this.frames[this.currentFrame].data = data;
      axios.put(`${this.baseUrl}/api/frame/${this.frames[this.currentFrame].id}`, {
        image: this.frames[this.currentFrame].data,
        duration: this.frames[this.currentFrame].duration
      })
      .then(response => {
        this.frames[this.currentFrame].status = 2;
      })
      .catch(e => {
        console.log(e);
      });
    },

    deleteCurrentFrame(){
      var self = this;
      this.frames[this.currentFrame].status = 1;
      axios.delete(`${this.baseUrl}/api/frame/${this.frames[this.currentFrame].id}`)
      .then(response => {
        this.frames.splice(this.currentFrame, 1);
        this.updateAnimationDetail();
        this.switchFrame(this.currentFrame-1);
      })
      .catch(e => {
        console.log(e);
      });
    },

    switchFrame(index){
      this.clearBoard();

      // just clear canvas if frames are empty
      if(index < 0) return;

      let image = this.frames[index].data;
      this.currentFrame = index;
      this.$refs.canvas.drawImage(image);
    },

    handlePreviewToggle(){
      this.$emit('previewToggled');
    },

    updateAnimationDetail(e){

      // switch to dragged frame on DRAG_END 
      if(e) this.switchFrame(e.newIndex);

      // generate sequence string
      let frameIds = this.frames.map(frame => frame.id);
      this.sequence = frameIds.join(',');
      if(frameIds.length === 1) this.sequence = this.sequence + ','; //append comma if only one frame

      this.$emit('animationDetailChange', this.sequence);
    },

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

<style scoped lang="scss">

.frames-container{
  padding-left: 10px;
  float: left;
  height: 400px;
  overflow: auto;
  cursor: pointer;

  div.adding-frame{
    text-align: center;
  }

}

.frames-control{
  width: 80px;
  margin-right: 10px;
}

.msg-no-frame{
  padding: 10px;
  margin: 10px;
  width: 70px;
  height: 50px;
  border-style: dotted;
  border-width: 2px;
  border-color: gray;
  color: gray;
  text-align: center;
  cursor: pointer;
}
</style>
