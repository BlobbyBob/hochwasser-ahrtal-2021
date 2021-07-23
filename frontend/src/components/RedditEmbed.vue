<template>
  <div class="redditEmbed ratio ratio-16x9">
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

</style>
