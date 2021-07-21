<template>
  <button class="btn-lg btn-primary" @click="emitClick">
    <slot></slot>
  </button>
</template>

<script lang="ts">
import { Options, Vue } from 'vue-class-component'

@Options({
  emits: {
    click: null
  }
})
export default class BigButton extends Vue {
  emitClick (e: Event): void {
    this.$emit('click', e)
  }
}
</script>

<style scoped>
</style>
