<template>
  <button 
    @click="handleClick"
    :class="{'selected': selected}"
    >
      <i :class="icon"></i>
    </button>
</template>

<script>
export default {
  components: {},
  name: "ToolButton",
  props: ["icon", 'selected'],
  methods: {
    handleClick(event) {
      this.$emit('clicked');
    }
  },
  computed: {}
};
</script>

<style scoped>
button {
  border-style: none;
  margin: 2px;
  cursor: pointer;
  outline: none;
  background-color: white;
  color: #3f51b5;
  border-style: solid;
  border-color: #3f51b5;
  border-width: 1px;
  border-radius: 3px;
  transition: all 0.2s ease;
}

.selected{
  background-color: #3F51B5;
  color: white;
}

</style>
