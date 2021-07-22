<template>
  <nav id="menu" class="navbar pl-md-3 pl-lg-5 pr-md-3 pr-lg-5">
    <div class="container">
      <div>
        <router-link class="navbar-brand" to="/">
          Hochwasser Ahrtal 2021
        </router-link>
      </div>
      <div>
        <a @click="back" class="pe-auto">
          <BootstrapIcon icon="chevron-left" size="1" class="pr-3"/>
          Zurück zur Karte
        </a>
      </div>
    </div>
  </nav>
</template>

<script lang="ts">
import { Options, Vue } from 'vue-class-component'
import BootstrapIcon from '@dvuckovic/vue3-bootstrap-icons'

@Options({
  components: { BootstrapIcon }
})
export default class Menu extends Vue {
  back (): void {
    history.back()
  }
}
</script>

<style lang="scss" scoped>
#menu {
  background-color: black;
  padding-top: 1em;
  padding-bottom: 1em;

  a {
    color: inherit;
    text-decoration: none;
    cursor: pointer;
  }
}
</style>
