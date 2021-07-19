<template>
  <button>
    <slot></slot>
  </button>
</template>

<script lang="ts">
import { Options, Vue } from 'vue-class-component'

@Options()
export default class BigButton extends Vue {
}
</script>

<style scoped>
button {
  background-color: brown;
  padding: 1em;
  border-radius: 1px;
  border: 0;
  color: white;
  cursor: pointer;
}
</style>
