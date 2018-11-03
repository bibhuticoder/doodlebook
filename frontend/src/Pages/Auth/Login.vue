<template>
  <div class="container center">
    <br>
    <h1>DoodleBook</h1>
    <p class="lead">
      A place to draw and share sketches
    </p>
    <hr>

    <div style="width: 300px; margin: 0 auto; text-align: left">
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
                        placeholder="Email">
          </b-form-input>
        </b-form-group>

        <!-- Password -->
        <b-form-group
                      label="Password"
                      label-for="password"
                      description="We'll never share your email with anyone else.">
          <b-form-input id="password"
                        type="password"
                        v-model="formData.password"
                        required
                        placeholder="Password">
          </b-form-input>
        </b-form-group>


        <b-button type="submit" variant="primary">Submit</b-button>
      </b-form>
    </div>


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
      }
    };
  },

  methods: {
    onSubmit() {
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
          } 
        })
        .catch(e => {
          console.log(e);
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
