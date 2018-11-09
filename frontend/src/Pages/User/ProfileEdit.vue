<template>
  <div class="container">
    <br>
    <h1 class="center">User Profile</h1>
    <hr>
    <div v-if="user" class="row">
      <div class="col">
        <div class="form-input">
          <label for="email">Email</label>
          <b-form-input id="email" size="sm" type="email" placeholder="Email" v-model="email" :disabled="loading"></b-form-input>
        </div>
        <br>
        <div class="form-input">
          <label for="title">Username</label>
          <b-form-input id="title" size="sm" type="text" placeholder="New Doodle Title" v-model="username" :disabled="loading"></b-form-input>
        </div>
        <br>
        <input type="file" name="image" accept="image/*" @change="setImage" class="btn btn-default" :disabled="loading" />
        <vue-cropper
          style="width: 300px; height: 200px; border-style: dotted; border-color: grey"
          ref="cropper"
          :src="`${baseUrl}/storage/profile_pics/${imgSrc}`"
          alt="Choose an image"
          :img-style="{ 'width': '300px', 'height': '200px' }"
        >
        </vue-cropper>
      </div>

      <div class="col">
        <div class="form-input">
          <label for="bio">User's short bio</label>
          <textarea id="bio" class="form-control" rows="10" placeholder="New Doodle Title" v-model="bio" :disabled="loading"></textarea>
        </div>
        <br>
        <button class="btn btn-success" @click="updateProfile" :disabled="loading">
          <i v-if="loading" class="status fas fa-circle-notch fa-spin"></i>
          <i class="fas fa-save"></i> Save Details
        </button>
      </div>
    </div>

  </div>
</template>

<script>
import axios from "axios";
import VueCropper from 'vue-cropperjs';

export default {
  name: "ProfileEdit",
  components: {VueCropper},
  data() {
    return {
      email: null,
      username: null,
      bio: null,
      image: null,
      user: null,
      loading: false,
      imgSrc: null
    };
  },

  methods: {

    setImage(e) {
      const file = e.target.files[0];
      if (!file.type.includes('image/')) {
        alert('Please select an image file');
        return;
      }
      if (typeof FileReader === 'function') {
        const reader = new FileReader();
        reader.onload = (event) => {
          // rebuild cropperjs with the updated source
          this.$refs.cropper.replace(event.target.result);
        };
        reader.readAsDataURL(file);
      } else {
        alert('Sorry, FileReader API not supported');
      }
    },

    updateProfile() {
      this.loading = true;
      if(this.$refs.cropper.getCroppedCanvas()) this.image = this.$refs.cropper.getCroppedCanvas().toDataURL();
      var self = this;
      axios.put(`${this.baseUrl}/api/auth/update-profile`, {
        image: self.image,
        email: self.email,
        username: self.username,
        bio: self.bio
      })
      .then(response => {
        self.$toastr('success', "Profile Updated", 'Success');
        self.$store.commit('setUser', response.data.user);
        self.getUser();
        self.loading = false;
      })
      .catch(e => {
        self.loading = false;
        this.$toastr('error', e.response.data.message, 'Error');
        console.log(e);
      });
    },

    getUser(){
      this.$store.dispatch('getUser', {callback: (user)=>{
        this.email = user.email;
        this.username = user.username;
        this.bio = user.bio;
        this.user = user;
        this.imgSrc = user.profile_pic;
      }});
    }

  },

  created: function() {
    this.getUser();
  },

  computed: {
    baseUrl() {
      return this.$store.getters.baseUrl;
    },
  }
};
</script>

<style scoped>
</style>
