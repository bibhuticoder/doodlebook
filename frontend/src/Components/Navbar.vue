<template>
  <nav class="navbar navbar-dark bg-dark">
  <a class="navbar-brand" href="#">DoodleBook</a>

  <router-link to="/new" class="btn btn-primary btn-sm">Create</router-link>

</nav>
</template>

<script>
export default {
  components: {},
  name: 'navbar',
  methods: {

  },
  computed: {

  }
}
</script>

<style scoped>
  .navbar{
    border-radius: 0px !important;
    height: 50px;
    z-index:10;
    background-color: #24292E;
    /* box-shadow: 1px 1px 2px black; */
  }

</style>
