<template>
  <div class="modal-header">
    <h5 class="modal-title">
      <slot></slot>
    </h5>
    <button v-if="closeButton" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
  </div>
</template>

<script lang="ts">
import { Options, Vue } from 'vue-class-component'

@Options({
  props: {
    closeButton: {
      type: Boolean,
      default: true
    }
  }
})
export default class ModalHeader extends Vue {
  closeButton!: boolean
}
</script>

<style lang="scss" scoped>

</style>
