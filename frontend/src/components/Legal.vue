<template>
  <div class="legal">
    <Menu/>
    <div class="container">
      <section class="row justify-content-center">
        <div class="col-sm-12 col-md-10 col-lg-8">
          <h1 class="text-center">Impressum</h1>
          <h3>Kontaktadresse</h3>
          <h3>Haftungsausschluss</h3>
          <h3>Haftung für Links und eingebettete Inhalte</h3>
          <h3>Urheberrechte</h3>
        </div>
      </section>
    </div>
  </div>
</template>

<script lang="ts">
import { Options, Vue } from 'vue-class-component'
import Menu from '@/components/Menu.vue'
import ModalLink from '@/components/ModalLink.vue'

@Options({
  components: { ModalLink, Menu }
})
export default class Legal extends Vue {
}
</script>

<style lang="scss">
.legal {
  min-height: 100vh;
  background-color: #404040;
  background-image: linear-gradient(to bottom, #3b3b3b, #202020);
  color: white;

  h1 {
    padding-top: 2.5rem;
    padding-bottom: 1rem;
  }
}
</style>
