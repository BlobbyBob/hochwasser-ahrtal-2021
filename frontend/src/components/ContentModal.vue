<template>
  <Modal id="contentModal" :size="size">
    <div class="modal-header">
      <div class="d-flex justify-content-between w-100 align-items-baseline">
        <h5 class="modal-title">{{ title }}</h5>
        <ModalLink target="#complaintModal" class="badge bg-danger report"><small>Beitrag melden</small></ModalLink>
      </div>
      <button type="button" class="btn-close ms-5" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <ModalBody>
      <TwitterEmbed v-if="type === 'twitter'" :tweet="tweetId" :iframe="twitterIframeUrl"/>
      <RedditEmbed v-if="type === 'reddit'" :url="redditUrl"/>
      <IFrameEmbed v-if="type === 'iframe'" :url="iframeUrl" :height="iframeHeight"/>
      <YoutubeEmbed v-if="type === 'youtube'" :url="youtubeUrl"/>
      <ImgEmbed v-if="type === 'img'" :url="imgUrl" :alt="title" :copyright="imgCopyright"/>
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

      <form @submit="sendForm">
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
          <textarea type="text" class="form-control" name="request" maxlength="4000" id="contentInput"
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
          <button type="submit" class="btn btn-primary mt-4">Beitrag einreichen</button>
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
import ModalLink from '@/components/ModalLink.vue'
import { postComplaint } from '@/api'

@Options({
  components: {
    ImgEmbed,
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
  type: 'twitter' | 'reddit' | 'iframe' | 'youtube' | 'link' | 'img' | 'blank' = 'blank'

  mediaId = -1
  tweetId = -1
  twitterIframeUrl = ''
  youtubeUrl = ''
  redditUrl = ''
  iframeUrl = ''
  iframeHeight = 0
  imgUrl = ''
  imgCopyright = ''

  complaintStatus = 0

  // todo create explicit data type
  // eslint-disable-next-line
  public setContent (id: number, type: 'twitter' | 'reddit' | 'iframe' | 'youtube' | 'link' | 'img' | 'blank', title: string, data: any): void {
    this.mediaId = id
    this.type = type
    this.title = title
    this.complaintStatus = 0

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
        this.imgUrl = data.url
        this.imgCopyright = data.copyright ? data.copyright : ''
        break
    }
  }

  sendForm (e: Event): void {
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
