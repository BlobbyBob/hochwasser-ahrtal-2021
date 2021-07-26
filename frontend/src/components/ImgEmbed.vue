<template>
  <div class="imgEmbed">
    <img :src="url" :alt="alt">
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
    }
  }
})
export default class ImgEmbed extends Vue {
  url!: string
  alt!: string
}
</script>

<style lang="scss" scoped>
img {
  width: 100%;
}
</style>
