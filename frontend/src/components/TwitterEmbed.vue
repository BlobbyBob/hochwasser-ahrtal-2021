<template>
  <div class="twitterEmbed d-flex justify-content-center">
    <iframe width="550" scrolling="no" allowfullscreen
            :src="`https://platform.twitter.com/embed/Tweet.html?dnt=true&embedId=twitter-widget-0&frame=false&hideCard=false&hideThread=false&id=${tweet}&lang=de&theme=light&widgetsVersion=82e1070%3A1619632193066&width=550px`"/>
  </div>
</template>

<script lang="ts">
import { Options, Vue } from 'vue-class-component'

@Options({
  components: {},
  props: {
    tweet: String // This needs to be a string or we need some BigInt conversion logic
  }
})
export default class TwitterEmbed extends Vue {
  tweet!: string
}
</script>

<style lang="scss" scoped>
iframe {
  min-height: 600px;
}
</style>
