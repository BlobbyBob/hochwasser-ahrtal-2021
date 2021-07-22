<template>
  <l-map ref="maptest" style="height: 70vh" :center="center" :zoom="zoom">
    <l-tile-layer
      url="https://{s}.tile.osm.org/{z}/{x}/{y}.png"
      attribution="&copy; <a href='https://osm.org/copyright'>OpenStreetMap</a> contributors"
    />

    <l-marker :lat-lng="[50.4450, 6.8748]">
      <l-popup>hi</l-popup>
    </l-marker>

    <l-marker v-for="group in groupedMediaData" :lat-lgn="[group.lat, group.lng]" :key="group.n">
      <l-popup>
        <ul>
          <li v-for="popup in group.media" :key="popup.id">
            {{ popup.title }}
          </li>
        </ul>
      </l-popup>
    </l-marker>

  </l-map>
</template>

<script lang="ts">
import { Options, Vue } from 'vue-class-component'
import 'leaflet/dist/leaflet.css'
import L from 'leaflet'
import { getMedia, getTown, MediaData, TownData } from '@/api'
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
  n!: number
  lat!: number
  lng!: number
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
  }
})
export default class TownMap extends Vue {
  groupedMediaData: Map<string, GroupedMediaData> = new Map()
  internalTown?: TownData
  map!: L.Map
  zoom = 14
  center = [50, 6]

  groupAndPlaceMarkers (list: MediaData[]): void {
    const tmpMap: Map<string, GroupedMediaData> = new Map()
    let n = 1
    for (const media of list) {
      const key = [media.latitude, media.longitude].join(',')
      if (!tmpMap.has(key)) {
        tmpMap.set(key, {
          n: n,
          lat: media.latitude,
          lng: media.longitude,
          media: []
        })
      }
      console.log(tmpMap)
      console.log(key)
      tmpMap.get(key)!.media.push(media)
      n++
    }
    console.log('Finished grouping')
    console.log(tmpMap)
    this.groupedMediaData = tmpMap
  }

  //  const marker = L.marker([50.4450, 6.8748]).addTo(this.map)
  // marker.bindPopup('Ich bin ein Test-Popup')
  // marker.on('click', function (e) { console.log(e) })
  // for (const media of list) {
  //  const marker = L.marker([media.latitude, media.longitude]).addTo(this.map)
  //  marker.bindPopup('<strong>' + media.title + '</strong><em>' + media.type + '</em>' + media.data)
  // }
  // console.log(list)
  // }

  set town (town: TownData | undefined) {
    this.internalTown = town
    if (this.internalTown) {
      console.log('Found town!')

      this.center = [this.internalTown.latitude, this.internalTown.longitude]
      this.zoom = this.internalTown.zoom

      // query media
      getMedia(this.internalTown.id).then(res => {
        this.groupAndPlaceMarkers(res)
      })
    } else {
      console.log('Did not find town')
    }
  }

  get town (): TownData | undefined {
    return this.internalTown
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
