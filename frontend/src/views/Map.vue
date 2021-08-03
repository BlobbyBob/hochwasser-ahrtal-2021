<template>
  <div class="container mapContainer">
    <h1 class="text-center">Karte</h1>
    <div class="map">
      <svg viewBox="0 0 2189 1056" xmlns="http://www.w3.org/2000/svg">
        <Ahr/>
        <MapMarker v-for="town of computedTowns" v-bind:key="town"
                   :route="town.route" :position="town.label" :x="town.x" :y="town.y">
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
import { getTowns, TownData } from '@/api'

@Options({
  components: {
    MapMarker,
    Ahr
  },
  props: {
    towns: {
      type: Array
    }
  }
})
export default class Map extends Vue {
  towns!: TownData[]
  internalTowns: TownData[] = []

  beforeCreate (): void {
    if (this.towns.length > 0) {
      this.internalTowns = this.towns
    } else {
      getTowns().then(t => {
        this.internalTowns = t
      })
    }
  }

  get computedTowns (): TownData[] {
    return this.internalTowns
  }
}
</script>

<style lang="scss" scoped>
h1 {
  color: white;
}

.mapContainer {
  padding-top: 3em;
}

.map {
  overflow-x: auto;
}

svg {
  background-color: lightgreen;
  margin: 1em 0;
  border-radius: 20px;
  min-height: 30rem;
}
</style>
