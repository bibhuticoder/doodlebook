<template>
  <div class="sidebar">

    <div class="header">
      Doodle Details
    </div>

    <div class="body container-fluid">
      <div class="form-input">
        <label for="title">Doodle Title</label>
        <b-form-input 
          :disabled="loading"
          id="title" 
          size="sm" 
          type="text" 
          placeholder="New Doodle Title" 
          @keyup="handleChange"
          v-model="doodle.title">
        </b-form-input>
      </div>
      <br>
      <b-form-group label="Doodle Visibility">
        <b-form-radio-group v-model="doodle.visibility"  @change="handleChange">
          <b-form-radio value="1" :disabled="loading">Private</b-form-radio>
          <b-form-radio value="0" :disabled="loading">Public</b-form-radio>
        </b-form-radio-group>
      </b-form-group>
      <br>
      <div class="form-input">
        <label for="title">Doodle Description</label>
        <wysiwyg id="description" v-model="doodle.description" @keyup="handleChange" :disabled="loading" />
      </div>
      <br>
      <div class="form-input">
        <label for="title">Frame Size (width X height)</label>
        <div class="row">
          <div class="col">
            <b-form-input 
              :disabled="loading"
              size="sm" 
              type="text" 
              placeholder="Width" 
              @keyup="handleChange"
              v-model="doodle.animation_detail.frame_width" />
            </div>
            <div class="col">
              <b-form-input 
                :disabled="loading"
                size="sm" 
                type="text" 
                placeholder="Height" 
                @keyup="handleChange"
                v-model="doodle.animation_detail.frame_height" />
            </div>
        </div>
      </div>
      <br>
    </div>

    <div class="footer">
      <b-btn
        class="btn-sm" 
        variant="outline-success" 
        @click="handleSaveClick" 
        :disabled="loading || !allowSave">
          <i v-if="loading" class="fas fa-circle-notch fa-spin"></i>
          Save <i class="far fa-save"></i>
      </b-btn>

      <b-btn
        class="btn-sm" 
        variant="outline-danger" 
        @click="$emit('onCancel')">
          Cancel 
          <i class="fas fa-chevron-right"></i>
      </b-btn>
    </div>

  </div>
</template>

<script>
export default {
  name: "DoodleCreateDialog",
  props: ['doodle-prop', 'loading'],
  components: {},
  data() {
    return {
    };
  },

  methods: {
    handleSaveClick(){
      if(!this.doodle.title || this.doodle.title.length <= 0) return;
      this.$emit('onSave', this.doodle);
    },

    handleChange(){
      this.$emit('statusChange', 0);
    }

  },

  created(){
    this.doodle = this.doodleProp;
  },

  mounted: function() {
  },

  computed: {
    allowSave(){
      return (this.doodle.title !== null);
    }
  }
};
</script>

<style scoped lang="scss">
  .overlay{
    position: absolute;
    top: 50px;
    left: 0px;
    background-color: #3f51b5;
    opacity: 0.8;
    width: 100vw;
    height: calc(100vh - 50px);
    background-image: url('/static/images/wallpaper.png');
    background-position-x: -220px;
    background-position-y: center;
    z-index: 100;
  }

  .sidebar{
    position: absolute;
    top: 50px;
    left: calc(100% - 400px);
    background-color: white;
    width: 400px;
    height: calc(100vh - 50px);
    z-index: 1000;
    box-shadow: -1px -1px 5px gray;
    /* color: white; */
    opacity: 0.99;
    z-index: 101;

    div.header{
      position: fixed;
      width: 100%;
      height: 50px;
      background-color: whitesmoke;
      padding-left: 10px;
      line-height: 50px;
      font-size: 24px;
      box-shadow: 1px 1px 2px gray;
    }

    div.body{
      overflow: auto;
      margin-top: 50px;
      padding-top: 20px;
      height: calc(100vh - 50px - 50px - 50px);
    }

    div.footer{
      position: fixed;
      width: 100%;
      height: 50px;
      background-color: whitesmoke;
      padding-left: 10px;
      padding-top: 10px;
      box-shadow: -1px -1px 2px gray;
    }


  }

</style>
