<template>
  <div class="credits container">
    <div class="d-md-flex justify-content-between align-items-center d-none as-row">
      <div class="mt-3 d-flex flex-column footer-links">
        <div>
          <router-link to="/kontakt">Beitrag einreichen</router-link>
        </div>
        <div>
          <ModalLink target="#gdprModal">Datenschutzerklärung</ModalLink>
        </div>
        <div>
          <router-link to="/impressum">Impressum</router-link>
        </div>
      </div>
      <div class="d-inline-flex align-items-center">
        <span>&copy; hochwasser-ahrtal-2021.de</span>
        <div class="credit-names">
          Ein Projekt von Ben Swierzy und Daniel Meyer.<br>
          Mit freundlicher Unterstützung von Annika.
        </div>
      </div>
      <div class="social">
        <a href="https://twitter.com/HochwasserAhr21" target="_blank" class="ms-3"
           title="Projekt auf Twitter besuchen">
          <BootstrapIcon icon="twitter" size="3x"/>
        </a>
        <a href="https://github.com/BlobbyBob/hochwasser-ahrtal-2021" target="_blank" class="ms-3"
           title="Projekt auf GitHub besuchen">
          <BootstrapIcon icon="github" size="3x"/>
        </a>
      </div>
    </div>

    <!-- Mobile layout -->
    <div class="mt-4 d-md-none justify-content-between align-items-center d-flex row text-center">
      <div class="mt-4 d-inline-flex flex-column align-items-center col-12">
        <span>&copy; hochwasser-ahrtal-2021.de</span>
        <div class="credit-names-sm">
          Ein Projekt von Ben Swierzy und Daniel Meyer.<br>
          Mit freundlicher Unterstützung von Annika.
        </div>
      </div>
      <div class="mt-4 social col-12">
        <a href="https://twitter.com/HochwasserAhr21" target="_blank" class="m-2"
           title="Projekt auf Twitter besuchen">
          <BootstrapIcon icon="twitter" size="3x"/>
        </a>
        <a href="https://github.com/BlobbyBob/hochwasser-ahrtal-2021" target="_blank" class="m-2"
           title="Projekt auf GitHub besuchen">
          <BootstrapIcon icon="github" size="3x"/>
        </a>
      </div>
      <div class="mt-4 col-12 d-flex justify-content-center flex-column footer-links">
        <router-link to="/kontakt">Beitrag einreichen</router-link>
        <ModalLink target="#gdprModal">Datenschutzerklärung</ModalLink>
        <router-link to="/impressum">Impressum</router-link>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { Options, Vue } from 'vue-class-component'
import BootstrapIcon from '@dvuckovic/vue3-bootstrap-icons'
import ModalLink from '@/components/ModalLink.vue'

@Options({
  components: {
    ModalLink,
    BootstrapIcon
  }
})
export default class Credits extends Vue {
}
</script>

<style lang="scss" scoped>
$creditColor: #ffffff;

.credits {
  color: $creditColor;
  padding-bottom: 3em;

  a {
    text-decoration: none;
    color: inherit;

    :hover {
      color: darken($creditColor, 45%)
    }
  }

  .footer-links {
    padding: 5px 0;

    :hover {
      text-decoration: underline;
    }
  }

  .credit-names {
    border-left: 1px solid white;
    margin-left: 10px;
    padding-left: 10px;
  }

  .credit-names-sm {
    border-top: 1px solid white;
    margin-top: 10px;
    padding-top: 10px;
  }
}
</style>
