<template>
  <div id="townMap">
    <l-map ref="maptest" style="height: 70vh" :center="[latitude, longitude]" :zoom="zoom" minZoom="13" maxZoom="19">
      <l-tile-layer
        url="https://{s}.tile.osm.org/{z}/{x}/{y}.png"
        attribution="&copy; <a href='https://osm.org/copyright'>OpenStreetMap</a> contributors"
      />

      <l-marker v-for="[k, group] of groupedMediaData" :lat-lng="group.latLng" :key="k">
        <l-popup :options="{maxWidth: 2000, className: 'popup-custom'}">
          <div class="d-flex flex-column align-items-start">
            <div v-for="popup of group.media" :key="popup.id">
              <ModalLink target="#contentModal" @click="handlePopupClick(popup)">
                <ContentRef :image="popup.format === 'image'" :video="popup.format === 'video'" :type="popup.type" :data="popup.data" class="modal-link">
                  {{ popup.title }} ({{ dateFormat(new Date(popup.timestamp)) }})
                </ContentRef>
              </ModalLink>
            </div>
          </div>
        </l-popup>
      </l-marker>
    </l-map>
  </div>
</template>

<script lang="ts">
import { Options, Vue } from 'vue-class-component'
import 'leaflet/dist/leaflet.css'
import L from 'leaflet'
import { getMedia, MediaData } from '@/api'
import { LMap, LMarker, LPopup, LTileLayer } from '@vue-leaflet/vue-leaflet'
import ModalLink from '@/components/ModalLink.vue'
import ContentModal from '@/components/ContentModal.vue'
import ContentRef from '@/components/ContentRef.vue'

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
    ContentRef,
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

  handlePopupClick (item: MediaData): void {
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
    this.groupedMediaData = tmpMap
  }

  dateFormat (date: Date): string {
    return new Intl.DateTimeFormat('de-DE', { dateStyle: 'short' }).format(date)
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

<style lang="scss">
#townMap .modal-link {
  cursor: pointer;
}

#townMap ul {
  padding: 0;
  list-style: none;
}

.popup-custom {
  font-size: 1.2em;
}
</style>
