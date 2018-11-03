<template>
  <div class="container-fluid center">
    <br>
    <div class="row">
      <div class="col col-2">
        <p class="lead">
          Edit Doodle
        </p>
        <div class="form-input">
          <input type="text" class="form-control" placeholder="New Doodle Title" v-model="doodle.title">
        </div>
        <br>
        <div class="form-input">
          <textarea class="form-control" v-model="doodle.description" placeholder="New Doodle Description">
          </textarea>
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
      <div class="col col-10">
        <!-- Paint Tool Goes here -->
        <Doodler @change="handleChange" :image="doodle.image" />
      </div>
    </div> 
  </div>
</template>

<script>
import axios from "axios";
import Doodler from '@/Components/Doodler'
export default {
  name: "DoodleEdit",
  components: {Doodler},
  data() {
    return {
      doodle: {
        title: null,
        description: null,
        image: null
      }
    };
  },

  created(){
    this.getDoodle();
  },

  methods: {
    getDoodle() {
      var self = this;
      axios
        .get(`${this.baseUrl}/api/doodle/${this.$route.params.id}`)
        .then(response => {
          this.doodle = response.data;
        })
        .catch(e => {
          console.log(e);
        });
    },

    saveDoodle() {
      if(this.title === null) return;
      var self = this;
      axios
        .post(`${this.baseUrl}/api/doodle`, {
          'title'       : self.doodle.title,
          'description' : self.doodle.description,
          'image'       : self.doodle.image
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
