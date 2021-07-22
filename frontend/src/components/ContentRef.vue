<template>
  <div @click="onClick" class="d-flex align-items-baseline">
    <span class="icons">
      <BootstrapIcon v-if="image" icon="image"/>
      <BootstrapIcon v-if="video" icon="camera-video"/>
      <BootstrapIcon v-if="type === 'twitter'" icon="twitter" style="color: #1da1f2"/>
      <BootstrapIcon v-if="type === 'reddit'" icon="reddit" style="color: #ff4500"/>
      <BootstrapIcon v-if="type === 'iframe'" icon="newspaper"/>
      <BootstrapIcon v-if="type === 'youtube'" icon="youtube" style="color: #ff0404"/>
      <BootstrapIcon v-if="type === 'link'" icon="box-arrow-up-right"/>
    </span>
    <a v-if="type !== 'link'" href="#" class="ms-2" @click="e => e.preventDefault()">
      <slot></slot>
    </a>
    <a v-if="type === 'link'" :href="JSON.parse(data).url" class="ms-2" @click="openLink">
      <slot></slot>
    </a>
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
  },
  emits: {
    click: null
  }
})
export default class ContentRef extends Vue {
  type!: string
  image!: boolean
  video!: boolean
  // eslint-disable-next-line
  data!: any

  onClick (e: Event): void {
    this.$emit('click', e)
  }

  openLink (e: Event): void {
    e.stopImmediatePropagation()
    window.open(JSON.parse(this.data).url, '_blank')
  }
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
