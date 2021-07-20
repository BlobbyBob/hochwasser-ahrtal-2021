<template>
  <div class="town">
    <h1>This is the town page for {{ currentRoute.params.name }}</h1>
    Click
    <ModalLink target="#embedContent"><a class="">here</a></ModalLink>
    to see some content.
  </div>
  <Modal id="embedContent" size="xl">
    <ModalHeader>German Houses are built differently</ModalHeader>
    <ModalBody>
      <div class="ratio ratio-1x1">
        <iframe sandbox="allow-scripts allow-popups"
                src="https://www.redditmedia.com/r/gifs/comments/onb2qg/german_houses_are_built_differently/?ref_source=embed&amp;ref=share&amp;embed=true"></iframe>
      </div>
    </ModalBody>
  </Modal>
</template>

<script lang="ts">
import { Options, Vue } from 'vue-class-component'
import { useRoute } from 'vue-router'
import Modal from '@/components/Modal.vue'
import ModalHeader from '@/components/ModalHeader.vue'
import ModalBody from '@/components/ModalBody.vue'
import ModalLink from '@/components/ModalLink.vue'

@Options({
  components: {
    ModalLink,
    ModalBody,
    ModalHeader,
    Modal
  }
})
export default class Town extends Vue {
  currentRoute = useRoute()
}
</script>

<style lang="scss" scoped>
</style>
