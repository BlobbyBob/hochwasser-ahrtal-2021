<template>
  <div class="mapContainer">
    <h1 class="text-center">Karte</h1>
    <div class="map">
      <svg viewBox="0 0 2189 1056" xmlns="http://www.w3.org/2000/svg">
        <Ahr/>
        <MapMarker v-for="town of towns" v-bind:key="town" :route="town.route" position="right" :x="town.x" :y="town.y">
          {{ town.name }}
        </MapMarker>
      </svg>
    </div>
  </div>
</template>

<script lang="ts">
import { Options, Vue } from 'vue-class-component'
import Ahr from '@/components/Ahr.vue'
import MapMarker from '@/components/MapMarker.vue'
import { TownData } from '@/api'

@Options({
  components: {
    MapMarker,
    Ahr
  },
  props: {
    towns: Array
  }
})
export default class Map extends Vue {
  towns!: TownData[]
}
</script>

<style lang="scss" scoped>
.mapContainer {
  padding-top: 3em;
}

svg {
  background-color: lightgreen;
  margin: 1em 0;
}
</style>
