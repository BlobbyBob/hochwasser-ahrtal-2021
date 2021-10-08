<template>
  <div id="townMap">
    <l-map ref="maptest" style="height: 70vh; z-index: 20" :center="[latitude, longitude]" :zoom="zoom" :minZoom="13"
           :maxZoom="19">
      <l-tile-layer
        url="https://{s}.tile.osm.org/{z}/{x}/{y}.png"
        attribution="&copy; <a href='https://osm.org/copyright'>OpenStreetMap</a> contributors"
      />
      <l-marker v-for="[k, group] of groupedMediaData" :lat-lng="group.latLng" :key="k">
        <l-popup :options="{maxWidth: 2000, className: 'popup-custom'}">
          <div class="d-flex flex-column align-items-start">
            <div v-for="popup of group.media" :key="popup.id">
              <ContentRef :image="popup.format === 'image'" :video="popup.format === 'video'" :type="popup.type"
                          :data="popup.data" class="modal-link">
                <div v-if="popup.type === 'link'">
                  <a :href="JSON.parse(popup.data).url" target="_blank">{{ popup.title }}
                    ({{ dateFormat(new Date(popup.timestamp)) }})</a>
                </div>
                <ModalLink v-if="popup.type !== 'link'" target="#contentModal" @click="handlePopupClick(popup, group.latLng)">
                  {{ popup.title }} ({{ dateFormat(new Date(popup.timestamp)) }})
                </ModalLink>
              </ContentRef>
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
import L, { LatLngTuple } from 'leaflet'
import { getMedia, MediaData, MediaFilter } from '@/api'
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
  latLng!: LatLngTuple
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
    zoom: Number,
    filter: MediaFilter
  }
})
export default class TownMap extends Vue {
  groupedMediaData: Map<string, GroupedMediaData> = new Map()
  map!: L.Map

  id!: number
  filter!: MediaFilter
  name!: string
  latitude!: number
  longitude!: number
  zoom!: number

  handlePopupClick (item: MediaData, latlng: LatLngTuple): void {
    if (this.$parent) (this.$parent.$refs.mainContentModal as ContentModal).setContent(item.id, item.type, item.title, latlng, JSON.parse(item.data))
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
      const v = tmpMap.get(key)
      if (v) v.media.push(media)
      n++
    }
    this.groupedMediaData = tmpMap
  }

  dateFormat (date: Date): string {
    return new Intl.DateTimeFormat('de-DE', { dateStyle: 'short' }).format(date)
  }

  drawMap (): void {
    getMedia(this.id, this.filter).then(res => {
      this.groupAndPlaceMarkers(res)
    }).catch(() => {
      // todo
    })
  }

  filterUpdate (filter: MediaFilter): void {
    getMedia(this.id, filter).then(res => {
      this.groupAndPlaceMarkers(res)
    }).catch(() => {
      // todo
    })
  }

  beforeCreate (): void {
    this.filter.addListener((filter) => {
      this.filterUpdate(filter)
    })
  }

  mounted (): void {
    this.drawMap()
  }

  updated (): void {
    // Possible leaflet or vue-leaflet bug:
    // Sometimes map center is not correctly set when updating to another location
    const map = (this.$refs.maptest as LMap).leafletObject
    map.setView([this.latitude, this.longitude])
    this.drawMap()
  }

  beforeUnmount (): void {
    if (this.map) {
      this.map.remove()
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
