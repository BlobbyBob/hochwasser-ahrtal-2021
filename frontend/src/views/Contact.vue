<template>
  <div class="contact">
    <Menu/>
    <div class="container pb-4">
      <section class="row justify-content-center">
        <div class="col-sm-12 col-md-10 col-lg-8">
          <h1 class="text-center">Beiträge einreichen</h1>
          <p>
            Das Projekt freut sich stetig über neue Beiträge. Bitte beachtet, dass wir aus Urheberrechtsgründen nicht
            alle Inhalte direkt einbinden dürfen. Referenzen auf externe Webseiten sind hingegen immer gestattet,
            reduzieren in Masse aber die Qualität des Projekts. Bei eingebundenen Inhalten geben wir sehr gerne den
            Urheber an. Wenn ihr dies bei euren eigenen Inhalten wünscht, vermerkt dies bitte explizit in der
            Beschreibung.
          </p>
          <p>
            Wähle bitte zunächst, ob du selbsterstellte Inhalte direkt zu uns hochladen möchtest, oder ob du uns nur
            einen Link auf bereits zum Beispiel in sozialen Medien oder Nachrichten-Webseiten veröffentlichtes
            externes Material übermitteln möchtest.
          </p>
          <div class="row justify-content-around my-2">
            <div class="col-6 col-md-5 col-xl-4 p-1">
              <div class="text-center border border-white rounded-1 entry-mode-box"
                   v-bind:class="{ active: entryMode==='personal'}"
                   @click="entryMode='personal'">
                <BootstrapIcon icon="image-fill" size="2x" />
                <p class="pt-3 m-0">Eigene Dateien einsenden</p>
              </div>
            </div>

            <div class="col-6 col-md-5 col-xl-4 p-1">
              <div class="text-center border border-white  rounded-1 entry-mode-box"
                   v-bind:class="{ active: entryMode==='external'}"
                   @click="entryMode='external'">
                <BootstrapIcon icon="box-arrow-up-right" size="2x" />
                <p class="pt-3 m-0">Externe Inhalte einreichen</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <ContactPersonal id="contact-personal" v-show="entryMode === 'personal'" />
      <ContactExternal id="contact-external" v-show="entryMode === 'external'" />
    </div>
  </div>
</template>

<script lang="ts">
import { Options, Vue } from 'vue-class-component'
import BootstrapIcon from '@dvuckovic/vue3-bootstrap-icons'
import Menu from '@/components/Menu.vue'
import ContactExternal from '@/components/ContactExternal.vue'
import ContactPersonal from '@/components/ContactPersonal.vue'

@Options({
  components: {
    BootstrapIcon,
    ContactExternal,
    ContactPersonal,
    Menu
  }
})
export default class Contact extends Vue {
  entryMode = ''
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

.entry-mode-box {
  cursor: pointer;
  padding: 1rem;
}

.entry-mode-box:hover {
  background-color: #202020;
}

.entry-mode-box.active {
  background-color: #202020;
  font-weight: bold;
}

label {
  padding-top: 1.5rem;
}
</style>
