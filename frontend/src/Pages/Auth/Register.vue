<template>
  <div class="container center">
    <br>
    <br>
    <b-form @submit="onSubmit" class="text-left" style="width: 500px; margin: 0 auto; text-align: left; box-shadow: -0.5px -0.5px 10px grey; padding: 20px">
      <p class="lead center">Create DoodleBook Account</p>
      <hr>
      <!-- Email -->
      <b-form-group
                    label="Username"
                    label-for="username"
                    description="">
        <b-form-input id="username"
                      type="text"
                      v-model="formData.username"
                      required
                      :disabled="loading"
                      placeholder="Username">
        </b-form-input>
      </b-form-group>


      <!-- Email -->
      <b-form-group
                    label="Email"
                    label-for="email"
                    description="">
        <b-form-input id="email"
                      type="email"
                      v-model="formData.email"
                      required
                      :disabled="loading"
                      placeholder="Email">
        </b-form-input>
      </b-form-group>

      <!-- Password -->
      <b-form-group
        label="Password"
        label-for="password"
        description="">
        <b-form-input id="password"
          type="password"
          v-model="formData.password"
          required
          :disabled="loading"
          placeholder="Password">
        </b-form-input>
      </b-form-group>

      <!-- Password Confirmation-->
      <b-form-group
        label="Confirm Password"
        label-for="password_confirmation"
        description="">
          <b-form-input id="password_confirmation"
            type="password"
            v-model="formData.password_confirmation"
            required
            :disabled="loading"
            placeholder="Confirm Password">
          </b-form-input>
      </b-form-group>

      <div class="center">
        <b-button type="submit" variant="primary" :disabled="loading">
          <i v-if="loading" class="status fas fa-circle-notch fa-spin"></i>
          Create Account
        </b-button>
      </div>
      
    </b-form>


  </div>
</template>

<script>
import axios from "axios";
export default {
  name: "Register",
  data() {
    return {
      formData: {
        username: "Test User",
        email: "user@gmail.com",
        password: "password123",
        password_confirmation: "password123"
      },
      errors: null,
      loading: false
    };
  },

  methods: {
    onSubmit() {
      this.loading = true;
      var self = this;
      axios
        .post(`${this.baseUrl}/api/auth/register`, self.formData)
        .then(response => {
          if(response.data.token){
            this.$router.push({name: 'Login'});
            self.loading = false;
            self.$toastr('success', "Account Created Successfully", "Success");
          } 
        })
        .catch(e => {
          for(let errorTitle in e.response.data.errors){
            let errors = e.response.data.errors[errorTitle];
            errors.forEach(error => {
              self.$toastr('error', error, errorTitle);
            });
          }
          self.loading = false;
        });
    }
  },

  created: function() {
  },

  computed: {
    baseUrl() {
      return this.$store.getters.baseUrl;
    }
  }
};
</script>

<style scoped>
</style>
