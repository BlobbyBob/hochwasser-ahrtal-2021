<template>
  <div class="redditEmbed">
    <iframe sandbox="allow-scripts allow-popups" :src="url" height="600" allowfullscreen></iframe>
  </div>
</template>

<script lang="ts">
import { Options, Vue } from 'vue-class-component'

@Options({
  props: {
    url: String
  }
})
export default class RedditEmbed extends Vue {
  url!: string
}
</script>

<style lang="scss" scoped>
iframe {
  width: 100%
}
</style>
