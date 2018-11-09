<template>
  <div>
    <div class="overlay"></div>

      <div class="form-container container-fluid">
        <div class="form-input">
          <label for="title">Doodle Title</label>
          <b-form-input 
            :disabled="loading"
            id="title" 
            size="sm" 
            type="text" 
            placeholder="New Doodle Title"
            v-model="doodle.title">
          </b-form-input>
        </div>
        <br>
        <b-form-group label="Doodle Visibility">
          <b-form-radio-group v-model="doodle.visibility">
            <b-form-radio value="1" :disabled="loading">Private</b-form-radio>
            <b-form-radio value="0" :disabled="loading">Public</b-form-radio>
          </b-form-radio-group>
        </b-form-group>
        <!-- <br> -->
        <div class="form-input">
          <label for="title">Frame Size (width X height)</label>
          <div class="row">
            <div class="col">
              <b-form-input 
                :disabled="loading"
                size="sm" 
                type="text" 
                placeholder="Width" 
                v-model="doodle.frame_width" />
              </div>
              <div class="col">
                <b-form-input 
                  :disabled="loading"
                  size="sm" 
                  type="text" 
                  placeholder="Height" 
                  v-model="doodle.frame_height" />
              </div>
          </div>
        </div>
        <br>
        <div class="form-input">
          <label for="title">Doodle Description</label>
          <wysiwyg id="description" v-model="doodle.description" :disabled="loading" />
        </div>
        <br>

        <br>

        <b-btn
          class="btn-sm" 
          variant="outline-success" 
          @click="saveDoodle" 
          :disabled="loading || !allowSave"
        >
          <i v-if="loading" class="fas fa-circle-notch fa-spin"></i>
          Save <i class="far fa-save"></i>
        </b-btn>

        <b-btn
          class="btn-sm right" 
          variant="outline-danger" 
          @click="$router.push({name: 'Dashboard'})"
        >
          <i class="far fa-frown"></i>
          I don't want to Doodle
        </b-btn>

      </div>

  </div>
</template>

<script>
import axios from "axios";
// import DoodleCreateDialog from '@/Components/DoodleCreateDialog';


export default {
  name: "DoodleCreate",
  components: {},
  data() {
    return {
      doodle: {
        id: null,
        title: null,
        description: null,
        visibility: 0,
        frame_width: 400,
        frame_height: 400
      },
      loading: false,
    };
  },

  methods: {

    saveDoodle() {
      var self = this;
      self.loading = true;
      axios
        .post(`${this.baseUrl}/api/doodle`, self.doodle)
        .then(response => {
          self.loading = false;
          self.$router.push(`/make/${response.data}`);
        })
        .catch(e => {
          this.$toastr('error', e.response.data.message, 'Error');
          self.loading = false;
          console.log(e);
        });
    },

  },

  created(){
    if(this.$route.params.id){
      this.getDoodle(this.$route.params.id);
      this.createDialog = false;
    }
  },

  mounted: function() {
  },

  computed: {
    baseUrl() {
      return this.$store.getters.baseUrl;
    },

    allowSave(){
      return (this.doodle.title !== null);
    }
  }
};
</script>

<style scoped lang="scss">

.form-container{
  float: right;
  margin-top: 20px;
  margin-right: 20px;
  width: 500px;
  background-color: whitesmoke;
  padding: 20px;
  font-size: 14px;
  box-shadow: -0.5px -0.5px 10px grey;
}

.overlay{
  position: absolute;
  top: 50px;
  left: 0px;
  opacity: 0.8;
  width: 100vw;
  height: calc(100vh - 50px);
  background-image: url('/static/images/wallpaper.png');
  background-position-x: -220px;
  background-position-y: center;
  z-index: -100;
}

</style>
