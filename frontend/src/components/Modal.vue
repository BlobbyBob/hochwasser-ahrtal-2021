<template>
  <div class="modal fade" :id="id" tabindex="-1">
    <div class="modal-dialog"
         :class="{ 'modal-xl': size === 'xl', 'modal-lg': size === 'lg', 'modal-sm': size === 'sm', modalMinHeight: minHeight }">
      <div class="modal-content">
        <slot></slot>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { Options, Vue } from 'vue-class-component'

@Options({
  props: {
    id: String,
    size: {
      type: String,
      default: 'md'
    },
    minHeight: {
      type: Boolean,
      default: false
    }
  }
})
export default class Modal extends Vue {
  id!: string
  size!: 'sm' | 'md' | 'lg' | 'xl'
  minHeight!: boolean
}
</script>

<style lang="scss" scoped>
.modalMinHeight .modal-content {
  min-height: 80vh;
}
</style>
