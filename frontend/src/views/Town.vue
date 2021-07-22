<template>
  <div class="townView">
    <Menu/>
    <section id="town">
      <div class="container">
        <div class="town">
          <h1 v-if="mapDataReady">Ort: {{ displayTownName }}</h1>
        </div>
        <div v-if="!mapDataReady && !error" class="alert alert-primary">Lade Karte...</div>
        <div v-if="error" class="alert alert-primary"><strong>Fehler:</strong> Karte kann nicht geladen werden.
          Überprüfe deine Netzwerkverbindung
        </div>
        <TownMap v-if="mapDataReady" :id="town.id" :name="town.name" :latitude="town.latitude"
                 :longitude="town.longitude" :zoom="town.zoom"/>
        <ContentModal/>
      </div>
    </section>
    <Credits/>
  </div>
</template>

<script lang="ts">
import { Options, Vue } from 'vue-class-component'
import { useRoute } from 'vue-router'
import TownMap from '@/components/TownMap.vue'
import Menu from '@/components/Menu.vue'
import Credits from '@/components/Credits.vue'
import ContentModal from '@/components/ContentModal.vue'
import { getTown, TownData } from '@/api'

@Options({
  components: {
    ContentModal,
    Credits,
    TownMap,
    Menu
  },
  props: {}
})
export default class Town extends Vue {
  currentRoute = useRoute()
  internalTown: TownData | undefined
  mapDataReady = false
  error = false

  get displayTownName (): string {
    return this.town ? this.town.name : this.ucfirst(this.routeName)
  }

  get routeName (): string {
    let routeName: string
    if (typeof this.currentRoute.params.name === 'string') {
      routeName = this.currentRoute.params.name
    } else {
      routeName = this.currentRoute.params.name[0]
    }

    return routeName
  }

  get town (): TownData | undefined {
    return this.internalTown
  }

  set town (town: TownData | undefined) {
    this.internalTown = town
  }

  ucfirst (s: string): string {
    return s.charAt(0).toUpperCase() + s.slice(1)
  }

  mounted (): void {
    this.error = false
    getTown(this.routeName).then(town => {
      this.town = town
      this.mapDataReady = true
    }).catch(() => {
      this.error = true
    })
  }
}
</script>

<style lang="scss">
.townView {
  min-height: 100vh;
  background-color: #404040;
  color: white;
}

#town {
  padding-top: 3em;
  padding-bottom: 3em;
}
</style>
