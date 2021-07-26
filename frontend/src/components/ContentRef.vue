<template>
  <div class="d-flex align-items-baseline">
    <span class="icons">
      <BootstrapIcon v-if="image" icon="image"/>
      <BootstrapIcon v-if="video" icon="camera-video"/>
      <BootstrapIcon v-if="type === 'twitter'" icon="twitter" style="color: #1da1f2"/>
      <BootstrapIcon v-if="type === 'reddit'" icon="reddit" style="color: #ff4500"/>
      <BootstrapIcon v-if="type === 'iframe'" icon="newspaper"/>
      <BootstrapIcon v-if="type === 'youtube'" icon="youtube" style="color: #ff0404"/>
      <BootstrapIcon v-if="type === 'link'" icon="box-arrow-up-right"/>
      <BootstrapIcon v-if="type === 'img'" icon="file-earmark-image"/>
    </span>
    <slot></slot>
  </div>
</template>

<script lang="ts">
import { Options, Vue } from 'vue-class-component'
import BootstrapIcon from '@dvuckovic/vue3-bootstrap-icons'

@Options({
  components: { BootstrapIcon },
  props: {
    type: String,
    image: {
      type: Boolean,
      required: false,
      default: false
    },
    video: {
      type: Boolean,
      required: false,
      default: false
    },
    data: {
      required: false,
      default: {}
    }
  }
})
export default class ContentRef extends Vue {
  type!: string
  image!: boolean
  video!: boolean
  // eslint-disable-next-line
  data!: any
}
</script>

<style lang="scss" scoped>
.icons {
  font-size: 1.1em;
  white-space: nowrap;
}

.bi {
  margin-right: 5px;
}
</style>
