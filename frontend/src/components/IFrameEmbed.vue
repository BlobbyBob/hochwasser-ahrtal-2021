<template>
  <div class="iframeEmbed">
    <iframe sandbox="allow-scripts allow-popups" :src="url" :height="height" allowfullscreen></iframe>
  </div>
</template>

<script lang="ts">
import { Options, Vue } from 'vue-class-component'

@Options({
  props: {
    url: String,
    height: Number
  }
})
export default class IFrameEmbed extends Vue {
  url!: string
  height!: number
}
</script>

<style lang="scss" scoped>
iframe {
  width: 100%;
}
</style>
