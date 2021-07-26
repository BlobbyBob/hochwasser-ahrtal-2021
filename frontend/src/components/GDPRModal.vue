<template>
  <Modal id="gdprModal" size="xl">
    <ModalHeader>Datenschutzerklärung</ModalHeader>
    <ModalBody>
      <mark>TODO</mark>
    </ModalBody>
  </Modal>
</template>

<script lang="ts">
import { Options, Vue } from 'vue-class-component'
import Modal from '@/components/Modal.vue'
import ModalHeader from '@/components/ModalHeader.vue'
import ModalBody from '@/components/ModalBody.vue'

@Options({
  components: {
    ModalBody,
    ModalHeader,
    Modal
  }
})
export default class GDPRModal extends Vue {
}
</script>

<style lang="scss" scoped>

</style>
