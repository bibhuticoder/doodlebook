<template>
  <div
    v-if="frame" 
    class="frame" 
    :class="{'frame-selected': selected}"
    @click="$emit('clicked')">
    <label class="sn">{{frame.sn}}</label>
    
    <i 
      v-if="frame.status === 1" 
      class="status fas fa-circle-notch fa-spin" 
      :style="'color: ' + color">
    </i>

    <i 
      v-if="frame.status === 0" 
      class="status fas fa-times-circle" 
      style="color: red">
    </i>

    <img :src="frame.data">
  </div>
</template>

<script>
export default {
  components: {},
  name: "Frame",
  props: ["selected", "frame", 'color'],
  data(){
    return{
    }
  },
  methods: {},
  computed: {}
};
</script>

<style scoped lang="scss">
  .frame{
  position: relative;
  width: 70px;
  height: 50px;
  margin: 10px;
  cursor: pointer;
  border-style: solid;
  border-color: lighten($color: grey, $amount: 20);
  border-width: 2px;
  transition: all 0.2s ease;

  img{
    width: 100%;
    height: 100%;
  }

  label.sn{
    position: absolute;
    top: -8px;
    left: -15px;
    color: #373737;
    color: black;
    font-size: 12px;
    font-weight: bold;
  }

  i.status{
    position: absolute;
    top: calc(50% - 12px);
    left: calc(50% - 12px);
    font-size: 24px;
    color: #FFB300;
    font-weight: bold;
  }

}

.frame:hover{
  border-width: 3px;
}


.frame-selected{
  color: #3f51b5;
  border-color: #3f51b5; 
}
</style>
