<template>
  <l-map ref="maptest" style="height: 70vh" :center="[latitude, longitude]" :zoom="zoom">
    <l-tile-layer
      url="https://{s}.tile.osm.org/{z}/{x}/{y}.png"
      attribution="&copy; <a href='https://osm.org/copyright'>OpenStreetMap</a> contributors"
    />

    <l-marker :lat-lng="[50.4450, 6.8748]">
      <l-popup>hi</l-popup>
    </l-marker>

    <l-marker v-for="[k, group] of groupedMediaData" :lat-lng="group.latLng" :key="k">
      <l-popup>
        <ul>
          <li v-for="popup of group.media" :key="popup.id">
            <ModalLink target="#contentModal" v-on:click="handlePopupClick(popup)">
              <a class="modal-link">{{ popup.id }} {{ popup.title }}</a>
            </ModalLink>
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
import { getMedia, MediaData } from '@/api'
import { LMap, LMarker, LPopup, LTileLayer } from '@vue-leaflet/vue-leaflet'
import ModalLink from '@/components/ModalLink.vue'
import ContentModal from '@/components/ContentModal.vue'

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
  latLng!: [number, number]
  media!: MediaData[]
}

@Options({
  components: {
    ModalLink,
    LMap,
    LTileLayer,
    LMarker,
    LPopup
  },
  props: {
    id: Number,
    name: String,
    latitude: Number,
    longitude: Number,
    zoom: Number
  }
})
export default class TownMap extends Vue {
  groupedMediaData: Map<string, GroupedMediaData> = new Map()
  map!: L.Map

  id!: number
  name!: string
  latitude!: number
  longitude!: number
  zoom!: number

  // townId = 0
  // townName = ''
  // townLatitude = 50
  // townLongitude = 6
  // townZoom = 14

  handlePopupClick (item: MediaData): void {
    console.log(this.$parent)
    console.log(this);
    (this.$parent!.$refs.mainContentModal as ContentModal).setContent(item.type, item.title, JSON.parse(item.data))
  }

  groupAndPlaceMarkers (list: MediaData[]): void {
    const tmpMap: Map<string, GroupedMediaData> = new Map()
    let n = 1
    for (const media of list) {
      const key = [media.latitude, media.longitude].join(',')
      if (!tmpMap.has(key)) {
        tmpMap.set(key, {
          n: n,
          latLng: [media.latitude, media.longitude],
          media: []
        })
      }
      tmpMap.get(key)!.media.push(media)
      n++
    }
    console.log(tmpMap)
    this.groupedMediaData = tmpMap
  }

  mounted (): void {
    getMedia(this.id).then(res => {
      this.groupAndPlaceMarkers(res)
    }).catch(() => {
      // todo
    })
  }

  beforeUnmount (): void {
    if (this.map) {
      // this.map.destroy()
    }
  }
}
</script>

<style scoped>
.modal-link {
  cursor: pointer;
}
</style>
