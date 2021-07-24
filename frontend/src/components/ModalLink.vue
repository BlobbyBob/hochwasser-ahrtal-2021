<template>
  <a data-bs-toggle="modal" :data-bs-target="target" href="javascript:void(0)">
    <slot></slot>
  </a>
</template>

<script lang="ts">
import { Options, Vue } from 'vue-class-component'

@Options({
  props: {
    target: String
  }
})
export default class ModalLink extends Vue {
  target!: string
}
</script>

<style lang="scss" scoped>

</style>
