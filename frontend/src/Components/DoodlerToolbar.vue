<template>
  <div class="toolbar">

    <!-- Animation Controls -->
    <div class="frames-control float-left">
      <button size="sm" @click="$emit('frameAdded')">
        <i class="fas fa-plus"></i>
      </button>
      <button size="sm" @click="$emit('frameCloned')">
        <i class="far fa-copy"></i>
      </button>
      <button size="sm" @click="$emit('frameDeleted')">
        <i class="far fa-trash-alt"></i>
      </button>
      <button size="sm" @click="handleAnimationButtonClick">
        <i v-if="!animationState" class="far fa-play-circle"></i>
        <i v-else class="far fa-stop-circle"></i>
      </button>
      <input type="text" placeholder="Duration(1/100)" :value="defaultDuration" @keyup="handleFrameDurationKeyUp" />
      <button size="sm" @click="$emit('saved')">
        <i class="fas fa-save"></i>
      </button>
    </div>

    <!-- Brushes -->
    <ToolButton
      class="float-left" 
      :icon="b.icon"
      :selected="(b.type === selectedBrush)"
      v-for="(b, i) in brushes" 
      :key="i"
      @clicked="handleBrushChanged(b.type)" />

      <div class="seperator float-left"></div>

      <!-- Brush Size -->
      <select @change="handleSizeChanged" class="float-left">
        <option value="1">1</option>
        <option value="3">2</option>
        <option value="5">3</option>
        <option value="7">4</option>
        <option value="10">5</option>
      </select>

      <!-- Clear Board -->
      <i class="fas fa-sync-alt btn-clear float-left" @click="$emit('cleared')"></i>
      
      <div class="seperator float-left"></div>

      <!-- Color Picker -->
      <swatches
        class="float-left"
        v-model="selectedColor" 
        @input="handleColorChanged"
        colors="text-advanced" 
        shapes="circles"
        swatch-size="20"
        :show-border="true"
        :trigger-style="{borderStyle: 'solid', borderWidth: '3px', borderColor: 'whitesmoke', height: '35px', width: '35px', cursor: 'pointer', boxShadow: '1px 1px 2px black'}"
      />

  </div>
</template>

<script>
import Swatches from 'vue-swatches';
import ToolButton from '@/Components/ToolButton';
import "vue-swatches/dist/vue-swatches.min.css"

export default {
  components: {Swatches, ToolButton},
  name: "DoodlerToolbar",
  props:['default-duration'],
  data(){ 
    return{
      brushes: [
        { type: 'pencil', icon: 'fas fa-pen-fancy' },
        { type: 'marker', icon: 'fas fa-pen-alt' },
        { type: 'eraser', icon: 'fas fa-eraser' },
        { type: 'spray', icon: 'fas fa-spray-can' },
        { type: 'chalk', icon: 'fas fa-pencil-alt' },
        { type: 'doubleBrush', icon: 'fas fa-pencil-ruler' },
      ],
      selectedColor: 'black',
      selectedBrush: 'pencil',
      animationState: false
    }
  },

  created(){
    this.duration = this.defaultInterval
  },

  mounted() {
  },

  methods: {
    handleAnimationButtonClick(){
      if(this.animationState) this.$emit('animationStopped');
      else this.$emit('animationPlayed');
      this.animationState = !this.animationState;
    },

    handleBrushChanged(type){
      this.selectedBrush = type;
      this.$emit('brushChanged', type);
    },

    handleSizeChanged(event){
      this.$emit('sizeChanged', event.target.value);
    },

    handleColorChanged(color){
      this.$emit('colorChanged', color);
    },

    handleFrameDurationKeyUp(event){
      this.$emit('durationChanged', event.target.value)
    }

  },
  computed: {
  },

  watch:{
  }

};
</script>

<style scoped lang="scss">

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


.frames-control{ 
  
  margin-top: -7px;
  width: 120px;
  margin-right: 20px;

  button{
    float: left;
    outline: none;
    border-style: solid;
    border-width: 1px;
    border-color: #3f51b5;
    background-color: white;
    font-size: 12px;
    color: #3f51b5;
    margin: 2px;
    transition: all 0.2s ease;
    cursor: pointer;
  }

  button:hover{
    background-color: #3f51b5;
    color: white;
  }

  input{
    float:left;
    margin: 2px;
    margin-right: -3px;
    outline: none;
    border-color: #3f51b5;
    border-width: 1px;
    width: 88px;
    color: #3f51b5;
    font-size: 12px;
    text-align: center;
  }
}

.toolbar{
  // background-color: gainsboro;
  margin-top: 10px;
  margin-bottom: 10px;
  width: 100%;
  height: 70px;
  padding: 20px;
  // border-style: solid;
  // border-color: #3f51b5;
  // border-width: 1px;
}

</style>
