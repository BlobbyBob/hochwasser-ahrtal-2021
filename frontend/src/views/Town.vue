<template>
  <div class="townView">
    <Menu/>
    <section id="town">
      <div class="container">
        <div class="town">
          <h1>Ort: {{ displayTownName }}</h1>
        </div>

        <TownMap :internal-town-name="currentRoute.params.name"/>
        <ContentModal/>
      </div>
    </section>
    <Credits/>
  </div>
</template>

<script lang="ts">
import { Options, Vue } from 'vue-class-component'
import { useRoute } from 'vue-router'
import Modal from '@/components/Modal.vue'
import ModalHeader from '@/components/ModalHeader.vue'
import ModalBody from '@/components/ModalBody.vue'
import ModalLink from '@/components/ModalLink.vue'
import TwitterEmbed from '@/components/TwitterEmbed.vue'
import RedditEmbed from '@/components/RedditEmbed.vue'
import IFrameEmbed from '@/components/IFrameEmbed.vue'
import YoutubeEmbed from '@/components/YoutubeEmbed.vue'
import TownMap from '@/components/TownMap.vue'
import Menu from '@/components/Menu.vue'
import Credits from '@/components/Credits.vue'
import ContentModal from '@/components/ContentModal.vue'

@Options({
  components: {
    ContentModal,
    Credits,
    TownMap,
    YoutubeEmbed,
    IFrameEmbed,
    RedditEmbed,
    TwitterEmbed,
    ModalLink,
    ModalBody,
    ModalHeader,
    Modal,
    Menu
  },
  props: {
    townName: {
      type: String,
      default: ''
    }
  }
})
export default class Town extends Vue {
  currentRoute = useRoute()
  townName!: string

  get displayTownName (): string {
    let routeName: string
    if (typeof this.currentRoute.params.name === 'string') {
      routeName = this.currentRoute.params.name
    } else {
      routeName = this.currentRoute.params.name[0]
    }

    return this.townName ? this.townName : this.ucfirst(routeName)
  }

  ucfirst (s: string): string {
    return s.charAt(0).toUpperCase() + s.slice(1)
  }
}
</script>

<style lang="scss">
.townView {
  min-height: 100vh;
  background-color: #404040;
  color: white;
}

#town {
  padding-top: 3em;
  padding-bottom: 3em;
}
</style>
