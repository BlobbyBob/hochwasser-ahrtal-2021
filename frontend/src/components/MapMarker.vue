<template>
  <router-link :to="'/ort/' + route">
    <g>
      <text v-if="position === 'left'" style="">
        <slot></slot>
      </text>
      <circle class="" :cx="x" :cy="y" :r="6"/>
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
</style>
