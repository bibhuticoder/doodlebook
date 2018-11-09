<template>
  <div>

    <DoodlerToolbar
      :default-duration="(frames[currentFrame]) ? (frames[currentFrame].duration) : 0"
      @sizeChanged="(size)=> this.size = size"
      @colorChanged="(color)=> this.color = color"
      @brushChanged="(brush)=> this.brush = brush"
      @cleared="clearBoard"
      @frameAdded="addFrame(true)"
      @frameCloned="addFrame(false)"
      @frameDeleted="deleteCurrentFrame"
      @animationPlayed="playAnimation"
      @animationStopped="stopAnimation"
      @durationChanged="handleDurationChange"
      @saved="updateCurrentFrame"
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

          <div v-if="addingFrame" class="adding-frame" :style="'color: ' + color">
            <i class="fas fa-sync fa-spin"></i>
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

  </div>
</template>

<script>
import Canvas from '@/Components/Canvas';
import DoodlerToolbar from '@/Components/DoodlerToolbar';
import Frame from '@/Components/Frame';
import draggable from 'vuedraggable';
import axios from "axios";

export default {
  components: {draggable, Canvas, DoodlerToolbar, Frame},
  props: ['doodle-id', 'initFrames'],
  name: "Doodler",
  data(){ 
    return{
      brush: 'pencil',
      size: 1,
      color: 'black',

      currentFrame: 0,
      frames: [],
      addingFrame: false,
      timer: null
    }
  },

  created(){

  },

  mounted() {

    this.frames = this.initFrames.map((frame, i) => {
      return({
        ...frame,
        sn: i,
        data: `${this.baseUrl}/getImage?type=frame&filename=${frame.image}`,
        status: 2,
      });
    });

    for(var i=0; i<frames.length; i++){
      var self = this;
      this.urlToData(this.frames[i].data, function(data){
        self.frames[i].data = data;
      })
    }
  
  },

  methods: {

    urlToData(url, callback){
      var c = document.createElement('canvas');
      var ctx = c.getContext('2d');
      let img = new Image;
      img.crossOrigin  = "Anonymous";
      img.onload = () => {
        c.height = img.height;
        c.width = img.width;
        ctx.drawImage(img, 0, 0);
        callback(c.toDataURL("image/png"));
      };
      img.src = url;
    },
    
    handleCanvasChange(data) {
      this.updateCurrentFrame(data);
    },

    clearBoard(){
      this.$refs.canvas.clear();
    },

    handleDurationChange(duration){
      this.frames[this.currentFrame].duration = duration;
      this.frames[this.currentFrame].status = 0;
    },

    addFrame(emptyFrame){
      if(this.addingFrame) return;
      if(emptyFrame)this.clearBoard();
      let frameData = this.$refs.canvas.getData();
      var self = this;
      self.addingFrame = true;
      axios.post(`${this.baseUrl}/api/frame`, {
        doodle_id: self.doodleId,
        image: frameData
      })
      .then(response => {
        self.frames.push({id: response.data, sn: self.frames.length, data: frameData, status: 2, duration: 100});
        self.currentFrame = self.frames.length-1;
        self.addingFrame = false;
      })
      .catch(e => {
        console.log(e);
      });
    },

    updateCurrentFrame(data){
      var self = this;
      self.frames[self.currentFrame].status = 1;
      if(data) self.frames[self.currentFrame].data = data;
      axios.put(`${this.baseUrl}/api/frame/${self.frames[self.currentFrame].id}`, {
        image: self.frames[self.currentFrame].data,
        duration: self.frames[self.currentFrame].duration
      })
      .then(response => {
        self.frames[self.currentFrame].status = 2;
        self.$emit('statusChange', 0);
      })
      .catch(e => {
        console.log(e);
      });
    },

    deleteCurrentFrame(){
      var self = this;
      self.frames[self.currentFrame].status = 1;
      axios.delete(`${this.baseUrl}/api/frame/${self.frames[self.currentFrame].id}`)
      .then(response => {
        self.frames.splice(self.currentFrame, 2);
      })
      .catch(e => {
        console.log(e);
      });
    },

    switchFrame(index){
      this.clearBoard();
      let image = this.frames[index].data;
      this.currentFrame = index;
      this.$refs.canvas.drawImage(image);
    },

    playAnimation(){
      var self = this;
      var count = 0;
      var limit = this.frames.length * 100;

      while(limit !== 0){
        this.timer = setTimeout(()=>{
          self.switchFrame(count);
          count++;
          count %= self.frames.length;
        }, self.frames[self.currentFrame].duration * 10);
        limit--;
      }

      // this.timer = setInterval(()=>{
      //   self.switchFrame(count);
      //   count++;
      //   count %= self.frames.length;
      //   if(count >= limit) clearInterval(self.timer);
      // }, this.animation_detail.interval);

    },

    stopAnimation(){
      clearTimeout(this.timer);
      this.timer = null;
    },

    updateAnimationDetail(){
      this.animation_detail.sequence = this.frames.map(frame => frame.sn).join(',');
      this.$emit('animationDetailChange', this.animation_detail);
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
