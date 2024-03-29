<template>
  <Modal id="contentModal" :size="size">
    <div class="modal-header">
      <div class="d-flex justify-content-between w-100 align-items-baseline">
        <h5 class="modal-title">{{ title }}</h5>
        <div>
          <ModalLink target="#correctionModal" class="badge bg-primary report ms-3">
            <small>Beitrag korrigieren</small>
          </ModalLink>
          <ModalLink target="#complaintModal" class="badge bg-danger report ms-3">
            <small>Beitrag melden</small>
          </ModalLink>
        </div>
      </div>
      <button type="button" class="btn-close ms-5" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <ModalBody>
      <TwitterEmbed v-if="type === 'twitter'" :tweet="tweetId" :iframe="twitterIframeUrl"/>
      <RedditEmbed v-if="type === 'reddit'" :url="redditUrl"/>
      <IFrameEmbed v-if="type === 'iframe'" :url="iframeUrl" :height="iframeHeight"/>
      <YoutubeEmbed v-if="type === 'youtube'" :url="youtubeUrl"/>
      <ImgEmbed v-if="type === 'img'" :url="imgUrl" :alt="title" :copyright="imgCopyright"
                :copyrightUrl="imgCopyrightUrl"/>
      <VidEmbed v-if="type === 'vid'" :url="vidUrl" :alt="title" :copyright="vidCopyright"
                :copyrightUrl="vidCopyrightUrl"/>
    </ModalBody>
  </Modal>

  <Modal id="complaintModal" size="xl">
    <ModalHeader>Beitrag melden</ModalHeader>
    <ModalBody>
      <p>Über dieses Formular können Sie einen Urheberrechtsverstoß melden. Legen Sie hierzu hinreichend dar, dass Sie
        oder ein Dritter Urheber sind. Wir kümmern uns zeitnah ein entsprechendes Verfahren in die Wege zu leiten.</p>

      <div v-show="complaintStatus === 1" class="alert alert-success">Beschwerde abgesendet</div>
      <div v-show="complaintStatus === 2" class="alert alert-warning">Beschwerde enthält ungültige Daten</div>
      <div v-show="complaintStatus === 3" class="alert alert-error">
        Beschwerde konnte nicht gesendet werden. Überprüfen Sie Ihre Netzwerkverbindung
      </div>

      <form @submit="sendComplaint">
        <input type="hidden" name="media" :value="mediaId">
        <div>
          <label class="form-label" for="nameInput">
            Name:
          </label>
          <input type="text" class="form-control" name="name" maxlength="128" id="nameInput" required>
        </div>
        <div>
          <label class="form-label" for="emailInput">
            E-Mail:
          </label>
          <input type="email" class="form-control" name="email" maxlength="128" id="emailInput" required>
        </div>
        <div>
          <label class="form-label" for="contentInput">
            Beschwerde:
          </label>
          <textarea class="form-control" name="request" maxlength="4000" id="contentInput"
                    required></textarea>
        </div>
        <div class="form-check pt-4">
          <input class="form-check-input" type="checkbox" name="gdpr" id="gdprInput" required>
          <label class="form-check-label pt-0" for="gdprInput">
            Ich willige in die Verarbeitung meiner persönlichen Daten auf Basis der
            <ModalLink target="#gdprModal">Datenschutzerklärung</ModalLink>
            ein. Die Erfassung der persönlichen Daten dient ausschließlich zur Klärung des gemeldeten
            Urheberrechtsverstoßes.
          </label>
        </div>
        <div>
          <button type="submit" class="btn btn-primary mt-4">Beschwerde einreichen</button>
        </div>
      </form>
    </ModalBody>
  </Modal>

  <Modal id="correctionModal" size="lg">
    <ModalHeader>Beitrag korrigieren</ModalHeader>
    <ModalBody>
      <p>Über dieses Formular kannst du einen Korrekturvorschlag für den Titel des Beitrags oder die Position
        einreichen. Da die Korrekturvorschläge manuell überprüft werden müssen, kann es wenige Tage dauern bis die
        Änderungen vorgenommen werden.</p>
      <p>Alle Positionen der Marker sind so gewählt, dass sie entweder die Position der Aufnahme oder des aufgenommenen
        Objekts wiederspiegeln. Bei Videos soll der Marker in der Regel an der Aufnahmeposition zum Startpunkt des
        Videos zu finden sein.</p>

      <div v-show="correctionStatus === 1" class="alert alert-success">Korrektur abgesendet</div>
      <div v-show="correctionStatus === 2" class="alert alert-warning">Korrektur enthält ungültige Daten</div>
      <div v-show="correctionStatus === 3" class="alert alert-error">
        Korrektur konnte nicht gesendet werden. Überprüfe deine Netzwerkverbindung
      </div>

      <form @submit="sendCorrection">
        <input type="hidden" name="media" :value="mediaId">

        <div>
          <label class="form-label" for="mediaInput">
            Beitragsname:
          </label>
          <input type="text" class="form-control" name="name" maxlength="256" id="mediaInput" v-model="title" required>

          <label class="form-label">
            Position:
          </label>
          <input type="hidden" name="latitude" :value="latitudeNew">
          <input type="hidden" name="longitude" :value="longitudeNew">
          <l-map ref="leafletCorrectionMap" style="height: 40vh; z-index: 20" :center="[latitude, longitude]" :zoom="14"
                 :minZoom="13" :maxZoom="19">
            <l-tile-layer
              url="https://{s}.tile.osm.org/{z}/{x}/{y}.png"
              attribution="&copy; <a href='https://osm.org/copyright'>OpenStreetMap</a> contributors"
            />
            <l-marker :lat-lng="[latitudeNew, longitudeNew]" :draggable="true" @move="correctionMarkerMove"/>
          </l-map>
        </div>
        <div>
          <button type="submit" class="btn btn-primary mt-4">Korrektur einreichen</button>
        </div>
      </form>
    </ModalBody>
  </Modal>
</template>

<script lang="ts">
import { Options, Vue } from 'vue-class-component'
import ModalBody from '@/components/ModalBody.vue'
import ModalHeader from '@/components/ModalHeader.vue'
import Modal from '@/components/Modal.vue'
import TwitterEmbed from '@/components/TwitterEmbed.vue'
import RedditEmbed from '@/components/RedditEmbed.vue'
import YoutubeEmbed from '@/components/YoutubeEmbed.vue'
import IFrameEmbed from '@/components/IFrameEmbed.vue'
import ImgEmbed from '@/components/ImgEmbed.vue'
import VidEmbed from '@/components/VidEmbed.vue'
import ModalLink from '@/components/ModalLink.vue'
import { postComplaint, postCorrection } from '@/api'
import { LMap, LMarker, LTileLayer } from '@vue-leaflet/vue-leaflet'
import { LatLngLiteral, LatLngTuple } from 'leaflet'

@Options({
  components: {
    LMap,
    LTileLayer,
    LMarker,
    ImgEmbed,
    VidEmbed,
    IFrameEmbed,
    YoutubeEmbed,
    RedditEmbed,
    TwitterEmbed,
    Modal,
    ModalHeader,
    ModalBody,
    ModalLink
  }
})
export default class ContentModal extends Vue {
  size: 'sm' | 'md' | 'lg' | 'xl' = 'lg'
  title = ''
  type: 'twitter' | 'reddit' | 'iframe' | 'youtube' | 'link' | 'img' | 'vid' | 'blank' = 'blank'

  mediaId = -1
  tweetId = -1
  twitterIframeUrl = ''
  youtubeUrl = ''
  redditUrl = ''
  iframeUrl = ''
  iframeHeight = 0
  imgUrl = ''
  imgCopyright = ''
  imgCopyrightUrl = ''
  vidUrl = ''
  vidCopyright = ''
  vidCopyrightUrl = ''

  latitude = 50.446
  longitude = 6.87647
  latitudeNew = this.latitude
  longitudeNew = this.longitude

  complaintStatus = 0
  correctionStatus = 0

  // todo create explicit data type
  // eslint-disable-next-line
  public setContent (id: number, type: 'twitter' | 'reddit' | 'iframe' | 'youtube' | 'link' | 'img' | 'vid' | 'blank', title: string, latlng: LatLngTuple, data: any): void {
    this.mediaId = id
    this.type = type
    this.title = title
    this.complaintStatus = 0

    this.latitude = this.latitudeNew = latlng[0]
    this.longitude = this.longitudeNew = latlng[1]

    switch (type) {
      case 'twitter':
        this.size = 'md'
        this.tweetId = data.id
        this.twitterIframeUrl = data.iframe
        break
      case 'iframe':
        this.size = 'xl'
        this.iframeUrl = data.url
        this.iframeHeight = data.height
        break
      case 'reddit':
        this.size = 'md'
        this.redditUrl = `https://www.redditmedia.com/r/${data.sub}/comments/${data.postId}?ref_source=embed&amp;ref=share&amp;embed=true`
        break
      case 'youtube':
        this.size = 'xl'
        this.youtubeUrl = `https://www.youtube-nocookie.com/embed/${data.videoId}`
        if (data.start) {
          this.youtubeUrl += `?start=${data.start}`
        }
        break
      case 'img':
        this.size = 'xl'
        this.imgUrl = process.env.VUE_APP_STATIC_URL + data.url
        this.imgCopyright = data.copyright ? data.copyright : ''
        this.imgCopyrightUrl = data.copyrightUrl ? data.copyrightUrl : ''
        break
      case 'vid':
        this.size = 'xl'
        this.vidUrl = process.env.VUE_APP_STATIC_URL + data.url
        this.vidCopyright = data.copyright ? data.copyright : ''
        this.vidCopyrightUrl = data.copyrightUrl ? data.copyrightUrl : ''
    }
  }

  sendComplaint (e: Event): void {
    e.preventDefault()
    if (e.target instanceof HTMLFormElement) {
      const formData = new FormData(e.target)
      const formObject = Object.fromEntries(formData)
      postComplaint(formObject).then(response => {
        if (response.status < 400) {
          this.complaintStatus = 1
          const form = document.querySelector('#complaintModal form')
          if (form instanceof HTMLFormElement) {
            form.reset()
          }
        } else {
          this.complaintStatus = 2
        }
      }).catch(() => {
        this.complaintStatus = 3
      })
    }
  }

  sendCorrection (e: Event): void {
    e.preventDefault()
    if (e.target instanceof HTMLFormElement) {
      const formData = new FormData(e.target)
      const formObject = Object.fromEntries(formData)
      postCorrection(formObject).then(response => {
        if (response.status < 400) {
          this.correctionStatus = 1
          const form = document.querySelector('#correctionModal form')
          if (form instanceof HTMLFormElement) {
            form.reset()
          }
        } else {
          this.correctionStatus = 2
        }
      }).catch(() => {
        this.correctionStatus = 3
      })
    }
  }

  correctionMarkerMove (e: { latlng: LatLngLiteral }): void {
    this.latitudeNew = e.latlng.lat
    this.longitudeNew = e.latlng.lng
  }

  mounted (): void {
    const m = document.getElementById('contentModal')
    // eslint-disable-next-line
    const component = this
    if (m) {
      m.addEventListener('hidden.bs.modal', () => {
        component.setContent(-1, 'blank', '', [50.44881, 6.88879], {})
      })
    }

    // Recalculate map container with invalidateSize() after the correction modal is opened
    const correctionModal = document.getElementById('correctionModal')
    if (correctionModal) {
      correctionModal.addEventListener('shown.bs.modal', () => {
        var correctionMap = (this.$refs.leafletCorrectionMap as LMap).leafletObject
        correctionMap.invalidateSize()
      })
    }
  }
}
</script>

<style lang="scss" scoped>
* {
  color: black;
}

.report, .report > * {
  color: white;
  text-decoration: none;
}
</style>
