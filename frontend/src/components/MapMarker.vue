<template>
  <router-link :to="'/ort/' + route">
    <g>
      <text class="text-left" v-if="position === 'left'" :x="x - 10" :y="y + 6" text-anchor="end">
        <slot></slot>
      </text>
      <circle :cx="x" :cy="y" :r="6"/>
      <text v-if="position === 'right'" :x="x + 10" :y="y + 6">
        <slot></slot>
      </text>
    </g>
  </router-link>
</template>

<script lang="ts">
import { Options, Vue } from 'vue-class-component'

@Options({
  props: {
    route: String,
    position: String,
    x: Number,
    y: Number
  }
})
export default class MapMarker extends Vue {
  route!: string
  position!: string
  x!: number
  y!: number
}
</script>

<style scoped>
circle {
  fill: red;
}
a {
  text-decoration: none;
  font-size: 1.2em;
}
.text-left {
  text-align: right;
}
</style>
