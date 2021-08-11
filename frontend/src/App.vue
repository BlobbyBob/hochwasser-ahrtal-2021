<template>
  <router-view/>
  <GDPRModal/>
</template>

<script lang="ts">
import { Options, Vue } from 'vue-class-component'
import GDPRModal from '@/components/GDPRModal.vue'

@Options({
  components: {
    GDPRModal
  }
})
export default class App extends Vue {
}
</script>

<style lang="scss">
body {
  padding: 0;
  margin: 0;
  overflow-x: unset;
}

.text-justify {
  text-align: justify !important;
}
</style>
