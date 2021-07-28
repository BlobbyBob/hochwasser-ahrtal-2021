<template>
  <div class="contact">
    <Menu/>
    <div class="container">
      <section class="row justify-content-center">
        <form class="col-sm-12 col-md-10 col-lg-8" @submit="sendForm">
          <h1 class="text-center">Beiträge Einreichen</h1>
          <p>
            Das Projekt freut sich stetig über neue Beiträge. Bitte beachtet, dass wir aus Urheberrechtsgründen nicht
            alle Inhalte direkt einbinden dürfen. Referenzen auf externe Webseiten sind hingegen immer gestattet,
            reduzieren in Masse aber die Qualität des Projekts. Bei eingebundenen Inhalten geben wir sehr gerne den
            Urheber an. Wenn ihr dies bei euren eigenen Inhalten wünscht, vermerkt dies bitte explizit in der
            Beschreibung.
          </p>
          <p>
            Zusätzlich bitten wir bei allen Einreichungen um die Angabe von (zumindest groben) Koordinaten. Unsere
            Ortskenntnisse sind leider limitiert und erlauben uns nicht jeden Beitrag selbst zu lokalisieren. Die
            Koordinaten könnt ihr bspw. über <a href="https://www.openstreetmap.org" target="_blank">Open Street Map</a>
            ermitteln. Schiebt hierzu einfach die Karte an die richtige Stelle und lest Längen- und Breitengrad als
            letzte beiden Zahlen in der URL ab.
          </p>

          <div v-show="formStatus === 1" class="alert alert-success">Einreichung abgesendet</div>
          <div v-show="formStatus === 2" class="alert alert-warning">Einreichung enthält ungültige Daten</div>
          <div v-show="formStatus === 3" class="alert alert-error">
            Einreichung konnte nicht gesendet werden. Überprüfen deine Netzwerkverbindung
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
          <div>
            <label class="form-label" for="latitudeInput">
              Koordinaten:
            </label>
            <div class="row">
              <div class="col-6">
                <input type="number" class="form-control" min="50.35" max="50.6" placeholder="Breitengrad"
                       id="latitudeInput" required>
              </div>
              <div class="col-6">
                <input type="number" class="form-control" min="6.6" max="7.25" placeholder="Längengrad" required>
              </div>
            </div>
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
          <div class="form-check pt-4">
            <input class="form-check-input" type="radio" name="copyright" value="embed" id="copyrightInputEmbed">
            <label class="form-check-label pt-0" for="copyrightInputEmbed">
              Hiermit erlaube ich explizit die Einbindung der Inhalte auf hochwasser-ahrtal-2021.de (falls es sich um
              eigene Inhalte handelt), oder übernehme die Verantwortung, dass die Einbindung der Inhalte rechtens ist
              (falls es sich um Inhalte Dritter handelt).
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="copyright" value="ref" id="copyrightInputRef" checked>
            <label class="form-check-label pt-0" for="copyrightInputRef">
              Die eingereichten Inhalte dürfen über Links referenziert werden. Eine direkte Einbindung ist, sofern es
              sich um eigene Inhalte handelt, explizit nicht gestattet.
            </label>
          </div>
          <div>
            <button type="submit" class="btn btn-primary mt-4">Beitrag einreichen</button>
          </div>
        </form>
      </section>
    </div>
  </div>
</template>

<script lang="ts">
import { Options, Vue } from 'vue-class-component'
import Menu from '@/components/Menu.vue'
import ModalLink from '@/components/ModalLink.vue'
import { postContact } from '@/api'

@Options({
  components: {
    ModalLink,
    Menu
  }
})
export default class Contact extends Vue {
  formStatus = 0

  sendForm (e: Event): void {
    e.preventDefault()
    if (e.target instanceof HTMLFormElement) {
      const formData = new FormData(e.target)
      const formObject = Object.fromEntries(formData)
      postContact(formObject).then(response => {
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

<style lang="scss">
.contact {
  min-height: 100vh;
  background-color: #404040;
  background-image: linear-gradient(to bottom, #3b3b3b, #202020);
  color: white;

  h1 {
    padding-top: 2.5rem;
    padding-bottom: 1rem;
  }
}

label {
  padding-top: 1.5rem;
}
</style>
