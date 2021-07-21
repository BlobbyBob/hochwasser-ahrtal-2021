<template>
  <div class="credits container">
    <div class="d-flex justify-content-between align-items-center">
      <div>
        Ein Projekt von Ben Swierzy und Daniel Meyer.
      </div>
      <div>
        <a href="https://github.com/BlobbyBob/hochwasser-ahrtal-2021">
          <BootstrapIcon icon="github" size="3x"/>
        </a>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { Options, Vue } from 'vue-class-component'
import BootstrapIcon from '@dvuckovic/vue3-bootstrap-icons'

@Options({
  components: {
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
}
</style>
