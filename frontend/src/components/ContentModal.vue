<template>
  <Modal id="contentModal" :size="size">
    <ModalHeader>{{ title }}</ModalHeader>
    <ModalBody>
      <TwitterEmbed v-if="type === 'twitter'" :tweet="tweetId" :iframe="twitterIframeUrl"/>
      <RedditEmbed v-if="type === 'reddit'" :url="redditUrl"/>
      <IFrameEmbed v-if="type === 'iframe'" :url="iframeUrl" :height="iframeHeight"/>
      <YoutubeEmbed v-if="type === 'youtube'" :url="youtubeUrl"/>
    </ModalBody>
  </Modal>
</template>

<script lang="ts">
import { Options, Vue } from 'vue-class-component'
import ModalBody from '@/components/ModalBody.vue'
import ModalHeader from '@/components/ModalHeader.vue'
import Modal from '@/components/Modal.vue'
import TwitterEmbed from '@/components/TwitterEmbed.vue'
import RedditEmbed from '@/components/RedditEmbed.vue'
import YoutubeEmbed from '@/components/YoutubeEmbed.vue'
import IFrameEmbed from '@/components/IFrameEmbed.vue'

@Options({
  components: {
    IFrameEmbed,
    YoutubeEmbed,
    RedditEmbed,
    TwitterEmbed,
    Modal,
    ModalHeader,
    ModalBody
  }
})
export default class ContentModal extends Vue {
  size: 'sm' | 'md' | 'lg' | 'xl' = 'lg'
  title = ''
  type: 'twitter' | 'reddit' | 'iframe' | 'youtube' | 'blank' = 'blank'

  tweetId = -1
  twitterIframeUrl = ''
  youtubeUrl = ''
  redditUrl = ''
  iframeUrl = ''
  iframeHeight = 0

  // todo create explicit data type
  // eslint-disable-next-line
  public setContent (type: 'twitter' | 'reddit' | 'iframe' | 'youtube' | 'blank', title: string, data: any): void {
    this.type = type
    this.title = title

    switch (type) {
      case 'twitter':
        this.size = 'md'
        this.tweetId = data.id
        this.twitterIframeUrl = data.iframe
        break
      case 'iframe':
        this.size = 'xl'
        this.iframeUrl = data.url
        this.iframeHeight = data.height
        break
      case 'reddit':
        this.size = 'md'
        this.redditUrl = `https://www.redditmedia.com/r/gifs/comments/${data.postId}?ref_source=embed&amp;ref=share&amp;embed=true`
        break
      case 'youtube':
        this.size = 'xl'
        this.youtubeUrl = `https://www.youtube-nocookie.com/embed/${data.videoId}`
        if (data.start) {
          this.youtubeUrl += `?start=${data.start}`
        }
        break
    }
  }
}
</script>

<style lang="scss" scoped>
* {
  color: black;
}
</style>
