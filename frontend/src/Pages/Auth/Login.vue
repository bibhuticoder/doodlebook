<template>
  <div class="container center">
    <br>
    <h1>DoodleBook</h1>
    <p class="lead">
      A place to draw and share sketches
    </p>
    <hr>

    <div style="width: 400px; margin: 0 auto; text-align: left; box-shadow: -0.5px -0.5px 10px grey; padding: 20px">
      <b-form @submit="onSubmit">

        <!-- Email -->
        <b-form-group
                      label="Email"
                      label-for="email"
                      description="We'll never share your email with anyone else.">
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
                      description="Input strong password to secure your account.">
          <b-form-input id="password"
                        type="password"
                        v-model="formData.password"
                        required
                        :disabled="loading"
                        placeholder="Password">
          </b-form-input>
        </b-form-group>

        <div class="center">
          <b-button type="submit" variant="success" :disabled="loading">
            <i v-if="loading" class="status fas fa-circle-notch fa-spin"></i>
            Proceed To Doodlebook
          </b-button>
        </div>
        
      </b-form>
    </div>
    <br>
    <p class="center">
      Don't have an account. <router-link to="/register">Register here.</router-link>
    </p>


  </div>
</template>

<script>
import axios from "axios";
export default {
  name: "Login",
  data() {
    return {
      formData: {
        email: 'user@gmail.com',
        password: 'password123'
      },
      loading: false, 
    };
  },

  methods: {
    onSubmit() {
      this.loading = true;
      var self = this;
      axios
        .post(`${this.baseUrl}/api/auth/login`, self.formData)
        .then(response => {
          if(response.data.token){
            axios.defaults.headers.common['Authorization'] = "Bearer " + response.data.token;
            localStorage.setItem('_token', response.data.token);
            this.$store.commit('setToken', response.data.token);
            this.$store.dispatch('getUser');
            this.$router.push({name: 'Home'});
            this.loading = false;
          } 
        })
        .catch(e => {
          if(e.response) this.$toastr('error', e.response.data.message, 'Error');
          else this.$toastr('error', 'Sorry! There was an error.', 'Error');
          
          this.loading = false;
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
