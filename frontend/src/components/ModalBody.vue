<template>
  <div class="modal-body">
    <slot></slot>
  </div>
</template>

<script lang="ts">
import { Vue } from 'vue-class-component'

export default class ModalBody extends Vue {
}
</script>

<style lang="scss" scoped>

</style>
