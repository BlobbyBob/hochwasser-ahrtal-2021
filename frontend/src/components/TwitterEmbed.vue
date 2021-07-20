<template>
  <div class="twitterEmbed d-flex justify-content-center">
    <iframe width="550" src="https://platform.twitter.com/embed/Tweet.html?dnt=true&embedId=twitter-widget-0&features=eyJ0ZndfZXhwZXJpbWVudHNfY29va2llX2V4cGlyYXRpb24iOnsiYnVja2V0IjoxMjA5NjAwLCJ2ZXJzaW9uIjpudWxsfSwidGZ3X2hvcml6b25fdHdlZXRfZW1iZWRfOTU1NSI6eyJidWNrZXQiOiJodGUiLCJ2ZXJzaW9uIjpudWxsfX0%3D&frame=false&hideCard=false&hideThread=false&id=1416069093975859211&lang=de&origin=http%3A%2F%2Flocalhost%3A8080%2Fort%2Fschuld&sessionId=744d262e8cd629d400947b6236457fd4e27c8221&theme=light&widgetsVersion=82e1070%3A1619632193066&width=550px"/>
  </div>
</template>

<script lang="ts">
import { Options, Vue } from 'vue-class-component'

@Options({
  components: {},
  props: {
    tweet: Number
  }
})
export default class TwitterEmbed extends Vue {
  tweet!: number
}
</script>

<style lang="scss" scoped>
iframe {
  min-height: 600px;
}
</style>
