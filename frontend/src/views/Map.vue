<template>
  <div class="mapContainer">
    <h1>Karte</h1>
    <div class="map">
      <svg viewBox="0 0 2189 1056" xmlns="http://www.w3.org/2000/svg">
        <Ahr/>
        <MapMarker route="schuld" position="right" :x="780" :y="630">Schuld</MapMarker>
        <MapMarker route="insul" position="right" :x="855" :y="650">Insul</MapMarker>
      </svg>
    </div>
  </div>
</template>

<script lang="ts">
import { Options, Vue } from 'vue-class-component'
import Ahr from '@/components/Ahr.vue'
import MapMarker from '@/components/MapMarker.vue'

@Options({
  components: { MapMarker, Ahr }
})
export default class Map extends Vue {
}
</script>
