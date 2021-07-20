<template>
  <div class="town">
    <h1>This is the town page for {{ currentRoute.params.name }}</h1>
    <ModalLink target="#tc"><a class="">Twitter</a></ModalLink><br>
    <ModalLink target="#rc"><a class="">Reddit</a></ModalLink><br>
    <ModalLink target="#ic"><a class="">IFrame</a></ModalLink><br>
    <ModalLink target="#yc"><a class="">YouTube</a></ModalLink>
  </div>
  <Modal id="tc" size="lg">
    <ModalHeader>EPA@Twitter</ModalHeader>
    <ModalBody>
      <TwitterEmbed tweet="1416069093975859211"/>
    </ModalBody>
  </Modal>
  <Modal id="rc" size="xl">
    <ModalHeader>German Houses are built differently</ModalHeader>
    <ModalBody>
      <RedditEmbed
        url="https://www.redditmedia.com/r/gifs/comments/onb2qg/german_houses_are_built_differently/?ref_source=embed&amp;ref=share&amp;embed=true"/>
    </ModalBody>
  </Modal>
  <Modal id="ic" size="xl">
    <ModalHeader>Gallery</ModalHeader>
    <ModalBody>
      <IFrameEmbed url="https://ga.de/fotos/region/unwetter-und-hochwasser-im-kreis-ahrweiler-bilder_bid-61312581#100"
                   height="700"/>
    </ModalBody>
  </Modal>
  <Modal id="yc" size="xl">
    <ModalHeader>YouTube</ModalHeader>
    <ModalBody>
      <YoutubeEmbed url="https://www.youtube-nocookie.com/embed/QnCHhE8cYHE?start=15"/>
    </ModalBody>
  </Modal>

  <TownMap :internal-town-name="currentRoute.params.name"></TownMap>
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

@Options({
  components: {
    TownMap,
    YoutubeEmbed,
    IFrameEmbed,
    RedditEmbed,
    TwitterEmbed,
    ModalLink,
    ModalBody,
    ModalHeader,
    Modal
  },
  props: {
    townName: String
  }
})
export default class Town extends Vue {
  currentRoute = useRoute()
}
</script>

<style lang="scss" scoped>
</style>
