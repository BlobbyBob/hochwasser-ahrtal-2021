<template>
  <button class="btn-lg btn-primary">
    <slot></slot>
  </button>
</template>

<script lang="ts">
import { Vue } from 'vue-class-component'

export default class BigButton extends Vue {
}
</script>

<style scoped>
</style>
