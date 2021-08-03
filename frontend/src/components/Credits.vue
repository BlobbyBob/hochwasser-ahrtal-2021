<template>
  <div class="credits">
    <div class="container mt-3">
      <div class="row align-items-center">
        <div class="col-lg-2">
          <div class="d-flex flex-column footer-links">
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
        </div>

        <div class="col-lg-8">
          <div class="d-flex flex-column flex-lg-row flex-lg-col align-items-lg-center justify-content-center">
            <span>&copy; hochwasser-ahrtal-2021.de</span>
            <div class="credit-names">
              Ein Projekt von Ben Swierzy und Daniel Meyer.<br>
              Mit freundlicher Unterstützung von Annika.
            </div>
          </div>
        </div>

        <div class="col-lg-2">
          <div class="social text-lg-end">
            <a href="https://twitter.com/HochwasserAhr21" target="_blank"
              title="Projekt auf Twitter besuchen">
              <BootstrapIcon icon="twitter" size="3x"/>
            </a>
            <a href="https://github.com/BlobbyBob/hochwasser-ahrtal-2021" target="_blank"
              title="Projekt auf GitHub besuchen">
              <BootstrapIcon icon="github" size="3x"/>
            </a>
          </div>
        </div>
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

  // grid lg breakpoint
  @media (min-width: 992px) {
    .credit-names {
      border-left: 1px solid white;
      margin-left: 10px;
      padding-left: 10px;
    }
  }

  .social a:not(:first-child) {
    margin-left: 1.5em;
  }
}
</style>
