<template>
  <div class="vidEmbed">
    <div class="vid">
      <video controls>
        <source :src="url" type="video/mp4">
      </video>
    </div>
    <div v-show="copyright.length > 0" class="copyrightNotice">
      &copy; {{ copyright }}
    </div>
  </div>
</template>

<script lang="ts">
import { Options, Vue } from 'vue-class-component'

@Options({
  props: {
    url: String,
    alt: {
      type: String,
      required: false,
      default: ''
    },
    copyright: {
      type: String,
      required: false,
      default: ''
    }
  }
})
export default class ImgEmbed extends Vue {
  url!: string
  alt!: string
  copyright!: string
}
</script>

<style lang="scss" scoped>
.vidEmbed {
  position: relative;
  min-height: 4em;
}
video {
  width: 100%;
}
.copyrightNotice {
  position: absolute;
  left: 0;
  bottom: 0;
  width: 100%;
  background-color: rgba(255, 255, 255, 0.7);
  padding: 3px .5rem;
}
</style>
