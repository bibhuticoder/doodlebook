<template>
  <div class="container center">
    <br>
    <h1>DoodleBook</h1>
    <p class="lead">
      A place to draw and share sketches
    </p>
    <hr>

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
      <b-button type="reset" variant="danger">Reset</b-button>
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
        email: null,
        password: null
      }
    };
  },

  methods: {
    onSubmit() {
      var self = this;
      axios
        .post(`${this.baseUrl}/auth/login`, self.formData)
        .then(response => {
          console.log(response);
        })
        .catch(e => {
          console.log(e);
        });
    }
  },

  created: function() {
    this.getDoodles();
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
