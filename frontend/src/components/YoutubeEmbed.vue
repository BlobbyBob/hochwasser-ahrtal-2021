<template>
  <div class="youtubeEmbed ratio ratio-16x9">
    <iframe :src="url" allow="encrypted-media; picture-in-picture" allowfullscreen></iframe>
  </div>
</template>

<script lang="ts">
import { Options, Vue } from 'vue-class-component'

@Options({
  props: {
    url: String
  }
})
export default class YoutubeEmbed extends Vue {
  url!: string
}
</script>

<style lang="scss" scoped>
.youtubeEmbed {
  display: flex;
  justify-content: center;
}
</style>
