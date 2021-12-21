<template>
  <div class="imgEmbed">
    <div ref="container" class="img" :class="{zoomed}" @mouseenter="zoom" @mousemove="move" @mouseleave="unzoom"
         @touchstart="touchzoom">
      <img ref="normal" class="normal" :src="url" :alt="alt">
      <img ref="zoom" class="zoom" :src="url" :alt="alt">
    </div>
    <div v-show="copyright.length > 0" class="copyrightNotice">
      &copy; {{ copyright }}
      <span v-show="copyrightUrl.length > 0"> | <a :href="copyrightUrl">{{ copyrightUrl }}</a></span>
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
    },
    copyrightUrl: {
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
  copyrightUrl!: string
  zoomed = false
  scaleFactor = 1

  zoom (): void {
    this.zoomed = true
  }

  move (e: MouseEvent | Touch): void {
    if (!this.zoomed) return
    const offset = this.pageOffset(this.$refs.container as HTMLElement)

    const zoom = this.$refs.zoom as HTMLElement
    const normal = this.$refs.normal as HTMLElement
    const relativeX = e.clientX - offset.x + window.pageXOffset
    const relativeY = e.clientY - offset.y + window.pageYOffset
    const normalFactorX = relativeX / normal.offsetWidth
    const normalFactorY = relativeY / normal.offsetHeight
    const x = normalFactorX * (zoom.offsetWidth * this.scaleFactor - normal.offsetWidth)
    const y = normalFactorY * (zoom.offsetHeight * this.scaleFactor - normal.offsetHeight)
    zoom.style.left = -x + 'px'
    zoom.style.top = -y + 'px'
  }

  unzoom (): void {
    this.zoomed = false
  }

  touchzoom (e: TouchEvent): void {
    const touch = e.touches.item(0)
    if (touch) {
      this.move(touch)
      this.zoomed = !this.zoomed
    }
  }

  pageOffset (el: HTMLElement): { x: number, y: number } {
    const rect = el.getBoundingClientRect()
    const scrollLeft = window.pageXOffset || document.documentElement.scrollLeft
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop
    return {
      y: rect.top + scrollTop,
      x: rect.left + scrollLeft
    }
  }
}
</script>

<style lang="scss" scoped>
.imgEmbed {
  position: relative;
  min-height: 4em;
}

.img {
  position: relative;
  overflow: hidden;
}

.img .normal {
  width: 100%;
}

.img .zoom {
  position: absolute;
  opacity: 0;
  transform-origin: top left;
}

.img.zoomed .normal {
  opacity: 0;
}

.img.zoomed .zoom {
  opacity: 1;
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
