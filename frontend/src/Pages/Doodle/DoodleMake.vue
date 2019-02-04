<template>
  <div class="container-fluid">
    
    <!-- Sidebar -->
    <div v-if="!createDialog" class="sidebar-opener" @click="createDialog = true">
      <i class="fas fa-chevron-left"></i>
    </div>
    <DoodleCreateDialog
      v-if="createDialog"
      :loading="createDialogLoading"
      :doodle-prop="doodle"
      @onSave="handleSave"
      @onCancel="createDialog = false"
      @statusChange="changeStatus"
    />

    <Doodler
      v-if="doodle.id"
      ref="doodler"
      @animationDetailChange="handleAnimationDetailChange"
      @statusChange="changeStatus"
      @previewToggled="showPreview = !showPreview"
      :initFrames="doodle.frames"
      :doodle-id="doodle.id" />

    <Status v-if="doodle.id" :status="status" @clicked="handleSave" />

    <Preview
      v-if="showPreview"
      :doodleSrc="`${this.baseUrl}/storage/doodles/${doodle.image}`"
     />

  </div>
</template>

<script>
import axios from "axios";
import Doodler from '@/Components/Doodler';
import DoodleCreateDialog from '@/Components/DoodleCreateDialog';
import Status from '@/Components/Status';
import Preview from '@/Components/Preview';

export default {
  name: "DoodleMake",
  components: {Doodler, Status, DoodleCreateDialog, Preview},
  data() {
    return {
      doodle: {
          id: null,
          title: null,
          description: null,
          visibility: 0,
          frame_width: 400,
          frame_height: 400,
          image: null,
        },
      createDialog: true,
      createDialogLoading: false,
      status: 0,
      showPreview: false
    };
  },

  methods: {

    handleSave(doodle){
      console.log('saving...');

      // return if not in unsaved mode
      if(this.status !== 0) return;

      // set directly if doodle isn't set
      if(doodle) this.doodle = doodle;

      // save updated values
      this.createDialogLoading = true;
      this.updateDoodle();
    },

    updateDoodle() {
      var self = this;
      self.changeStatus(1);
      axios
        .put(`${this.baseUrl}/api/doodle/${this.doodle.id}`, {
          id: self.doodle.id,
          title: self.doodle.title,
          description: self.doodle.description,
          visibility: self.doodle.visibility,
          frame_width: self.doodle.animation_detail.frame_width,
          frame_height: self.doodle.animation_detail.frame_height,
          sequence: self.doodle.animation_detail.sequence
        })
        .then(response => {
          if(response.data.message === "success"){
            self.createDialog = false;
            self.createDialogLoading = false;
            self.changeStatus(2);
          }
        })
        .catch(e => {
          self.$toastr('error', e.response.data.message, 'Error');
          self.changeStatus(0);
          self.createDialogLoading = false;
          console.log(e);
        });
    },

    getDoodle(id){
      var self = this;
      axios
        .get(`${this.baseUrl}/api/doodle-with-frames/${id}`)
        .then(response => {
          this.doodle = response.data;
          this.createDialog = false;
          self.changeStatus(2);
        })
        .catch(e => {
          console.log(e);
          this.$toastr('error', e.response.data.message, 'Error');
        });
    },

    handleAnimationDetailChange(sequence){
      this.doodle.animation_detail.sequence = sequence;
      this.changeStatus(0);
    },

    changeStatus(status){
      this.status = status;
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

<style scoped>

  .sidebar-opener{
    position: absolute;
    top: calc(50% - 50px);
    right: -5px;
    height: 100px;
    width: 25px;
    line-height: 100px;
    background-color: #3f51b5;
    color: white;
    text-align: left;
    padding-left: 5px;
    border-top-left-radius: 15px;
    border-bottom-left-radius: 15px;
    cursor: pointer;
    box-shadow: 1px 1px 2px grey;
    transition: all 0.2s ease;
  }

  .sidebar-opener:hover{
    right: 0px;
  }

</style>
