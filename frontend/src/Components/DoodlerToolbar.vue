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
      <input type="text" placeholder="Duration(1/100)" :value="defaultDuration" @keyup="handleFrameDurationKeyUp" />
      <button size="sm" @click="$emit('saved')">
        <i class="fas fa-save"></i>
      </button>
      <button size="sm" @click="handleAnimationButtonClick">
        <i v-if="animationState" class="far fa-eye-slash"></i>
        <i v-else class="far fa-eye"></i>
      </button>
    </div>

  </div>
</template>

<script>
import ToolButton from '@/Components/ToolButton';

export default {
  components: {ToolButton},
  name: "DoodlerToolbar",
  props:['default-duration'],
  data(){ 
    return{
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
      this.$emit('previewToggled');
      this.animationState = !this.animationState;
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

.frames-control{ 
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
    width: 95px;
    color: #3f51b5;
    font-size: 12px;
    text-align: center;
    padding-left: 5px;
    padding-right: 5px;
  }

}

.toolbar{
  position: absolute;
  left: 0;
  bottom: 0;
  width: 100%;
  height: 45px;
  padding: 5px;
  padding-top: 10px;
  background-color: white;
  border-top-style: solid;
  border-top-width: 3px;
  border-top-color: lightgray;
}

</style>
