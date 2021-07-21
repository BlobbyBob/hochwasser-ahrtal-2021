<template>
  <div id="mapContainer" style="height: 70vh"></div>
</template>

<script lang="ts">
import 'leaflet/dist/leaflet.css'
import L from 'leaflet'
import { defineComponent } from 'vue'

// https://github.com/Leaflet/Leaflet/issues/4968
// ...
type D = L.Icon.Default & {
  _getIconUrl?: string;
}
delete (L.Icon.Default.prototype as D)._getIconUrl
L.Icon.Default.mergeOptions({
  iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
  iconUrl: require('leaflet/dist/images/marker-icon.png'),
  shadowUrl: require('leaflet/dist/images/marker-shadow.png')
})

export default defineComponent({
  name: 'TownMap',
  props: {
    internalTownName: {
      type: String,
      required: false
    }
  },
  data () {
    return {
      townData: {},
      media: []
    }
  },
  mounted () {
    const map = L.map('mapContainer').setView([50.4450, 6.8748], 12)
    L.tileLayer('https://{s}.tile.osm.org/{z}/{x}/{y}.png', {
      attribution:
        '&copy; <a href="https://osm.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map)

    const marker = L.marker([50.4450, 6.8748]).addTo(map)
    marker.on('click', function (e) { console.log(e) })
  },
  beforeUnmount () {
    // TODO: clear map
  }
})
</script>

<style scoped>

</style>
