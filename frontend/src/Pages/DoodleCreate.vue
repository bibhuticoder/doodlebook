<template>
  <div class="container-fluid">
    <br>
    <div class="row">
      <div class="col col-3">
        <p class="lead">
          Create New Doodle
        </p>

        <hr>

        <div class="form-input">
          <label for="title">Doodle Title</label>
          <b-form-input id="title" size="sm" type="text" placeholder="New Doodle Title" v-model="doodle.title"></b-form-input>
        </div>

        <br>

        <b-form-group label="Doodle Visibility">
          <b-form-radio-group v-model="doodle.visibility">
            <b-form-radio value="1">Private</b-form-radio>
            <b-form-radio value="0">Public</b-form-radio>
          </b-form-radio-group>
        </b-form-group>

        <br>  

        <div class="form-input">
          <label for="title">Doodle Description</label>
          <wysiwyg id="description" v-model="doodle.description" />
        </div>

        <hr>
        <b-btn 
          class="btn-sm" 
          variant="outline-primary" 
          @click="saveDoodle" 
          :disabled="!allowSave"
          :title="(allowSave) ? 'Save Doodle' : 'Fill required fields to save'"
        >
          Save <i class="far fa-save"></i>
        </b-btn>
      </div>
      <div class="col col-9">
        <!-- Paint Tool Goes here -->
        <Doodler @change="handleChange" />
      </div>
    </div> 
  </div>
</template>

<script>
import axios from "axios";
import Doodler from '@/Components/Doodler'
export default {
  name: "DoodleCreate",
  components: {Doodler},
  data() {
    return {
      doodle: {
        title: null,
        description: null,
        image: null,
        visibility: 0
      }
    };
  },

  methods: {
    saveDoodle() {

      if(this.title === null) return;

      var self = this;
      axios
        .post(`${this.baseUrl}/api/doodle`, {
          'title'       : self.doodle.title,
          'description' : self.doodle.description,
          'image'       : self.doodle.image,
          'visibility'  : self.doodle.visibility
        })
        .then(response => {
          this.doodle = response.data;
        })
        .catch(e => {
          console.log(e);
        });
    },

    updateImage(){

    },

    handleChange(image){
      this.doodle.image = image;
      console.log("changed");
    }
  },

  mounted: function() {
  },

  computed: {
    baseUrl() {
      return this.$store.getters.baseUrl;
    },

    allowSave(){
      return (this.doodle.title !== null && this.doodle.image !== null);
    }
  }
};
</script>

<style scoped>
</style>
