<template>
  <div class="introduction">

    <p>
      Im Jahr 2021 gab es im Südwesten Deutschlands starke Regenfälle, die zu historischen Hochwassern in mehreren
      Gegenden geführt haben. Das Ahrtal ist eines der am härtesten betroffenen Gebiete. Auf dieser Seite sind
      verschiedene Beiträge gesammelt, die die Ausmaße der Zerstörungen dokumentieren.
    </p>

    <div>
      <BigButton @click="emitMapButtonClick">Karte ansehen</BigButton>
    </div>
  </div>
</template>

<script lang="ts">
import { Options, Vue } from 'vue-class-component'
import BigButton from '@/components/BigButton.vue'

@Options({
  components: { BigButton },
  emits: {
    mapButtonClick: null
  }
})
export default class Introduction extends Vue {
  emitMapButtonClick (e: Event): void {
    this.$emit('mapButtonClick', e)
  }
}
</script>

<style scoped lang="scss">
</style>
