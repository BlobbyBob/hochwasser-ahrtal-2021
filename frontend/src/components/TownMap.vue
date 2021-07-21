<template>
  <l-map ref="maptest" style="height: 70vh" v-bind:center="center" v-bind:zoom="zoomLevel">
    <l-tile-layer
      url="https://{s}.tile.osm.org/{z}/{x}/{y}.png"
      attribution="&copy; <a href='https://osm.org/copyright'>OpenStreetMap</a> contributors"
    />

    <l-marker :lat-lng="[50.4450, 6.8748]">
      <l-popup>hi</l-popup>
    </l-marker>

  </l-map>
</template>

<script lang="ts">
import { Options, Vue } from 'vue-class-component'
import 'leaflet/dist/leaflet.css'
import L from 'leaflet'
import { MediaData, TownData } from '@/api'
import { LMap, LTileLayer, LMarker, LPopup } from '@vue-leaflet/vue-leaflet'

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

class GroupedMediaData {
  lat!: number;
  lng!: number;
  media!: MediaData[]
}

@Options({
  components: {
    LMap,
    LTileLayer,
    LMarker,
    LPopup
  },
  props: {
    mediaList: {
      type: Array
    },
    zoomLevel: {
      type: Number,
      required: true
    },
    center: {
      type: Array,
      required: true
    }
  }
})
export default class TownMap extends Vue {
  groupedMediaData: GroupedMediaData[] = []
  town?: TownData
  map!: L.Map

  data () {
    return {
      zoomLevel: 14,
      center: [50.4450, 6.8748]
    }
  }

  // groupAndPlaceMarkers (list : MediaData[]): void {
  // const marker = L.marker([50.4450, 6.8748]).addTo(this.map)
  // marker.bindPopup('Ich bin ein Test-Popup')
  // marker.on('click', function (e) { console.log(e) })
  // for (const media of list) {
  //  const marker = L.marker([media.latitude, media.longitude]).addTo(this.map)
  //  marker.bindPopup('<strong>' + media.title + '</strong><em>' + media.type + '</em>' + media.data)
  // }
  // console.log(list)
  // }

  mounted (): void {
    // query towninfo for coordinates
    // query media
    // getMedia(1).then(res => { this.groupAndPlaceMarkers(res) })
  }

  beforeUnmount () {
    if (this.map) {
      // this.map.destroy()
    }
  }
}
</script>

<style scoped>

</style>
