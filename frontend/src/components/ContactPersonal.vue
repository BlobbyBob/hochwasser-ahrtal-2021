<template>
  <section class="row justify-content-center">
    <div class="col-sm12 col-md-10 col-lg-8">
      <h2>Eigene Dateien einsenden</h2>
      <p>Wir freuen uns sehr, wenn du uns deine von dir persönlich erstellten Medien zur Verfügung stellen möchtest.
        Um deine Inhalte hochzuladen, bitten wir dich um eine kurze Beschreibung der Inhalte (Orte, Zeiten, ...) über das
        Formular unten. Wir melden uns dann per E-Mail bei dir, um das Hochladen der Daten zu organisieren.</p>
      <form @submit="sendForm">
        <div v-show="formStatus === 1" class="alert alert-success">Einreichung abgesendet</div>
        <div v-show="formStatus === 2" class="alert alert-warning">Einreichung enthält ungültige Daten</div>
        <div v-show="formStatus === 3" class="alert alert-danger">
          Einreichung konnte nicht gesendet werden. Bitte überprüfe deine Netzwerkverbindung.
        </div>
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
            Inhalt:
          </label>
          <textarea type="text" class="form-control" name="request" maxlength="4000" id="contentInput" required></textarea>
        </div>
        <div class="form-check pt-4">
          <input class="form-check-input" type="checkbox" name="gdpr" id="gdprInput" required>
          <label class="form-check-label pt-0" for="gdprInput">
            Ich willige in die Verarbeitung meiner persönlichen Daten auf Basis der
            <ModalLink target="#gdprModal">Datenschutzerklärung</ModalLink>
            ein. Die Erfassung der persönlichen Daten dient ausschließlich zur Klärung im Falle von gemeldeten
            Urheberrechtsverstößen.
          </label>
        </div>
        <div>
          <button type="submit" class="btn btn-primary mt-4">Nachricht absenden</button>
        </div>
      </form>
    </div>
  </section>
</template>

<script lang="ts">
import { Options, Vue } from 'vue-class-component'
import { postPersonalMedia } from '@/api'
import ModalLink from '@/components/ModalLink.vue'

@Options({
  components: {
    ModalLink
  }
})
export default class ContactPersonal extends Vue {
  formStatus = 0

  sendForm (e: Event): void {
    e.preventDefault()
    if (e.target instanceof HTMLFormElement) {
      const formData = new FormData(e.target)
      const formObject = Object.fromEntries(formData)
      postPersonalMedia(formObject).then(response => {
        if (response.status < 400) {
          this.formStatus = 1
          const form = document.querySelector('#complaintModal form')
          if (form instanceof HTMLFormElement) {
            form.reset()
          }
        } else {
          this.formStatus = 2
        }
      }).catch(() => {
        this.formStatus = 3
      })
    }
  }
}

</script>

<style scoped>
</style>
