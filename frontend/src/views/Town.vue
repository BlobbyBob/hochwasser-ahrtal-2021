<template>
  <div class="townView">
    <Menu/>
    <section id="town">
      <div class="container">
        <div class="row align-items-center mb-2" id="town-navigation">
          <div class="col-md-3 text-center text-md-start px-2">
            <router-link :to="'/ort/'+prevTown.route" class="align-middle fs-5" v-if="prevTown">
              <BootstrapIcon icon="arrow-up-short" class="d-md-none" />
              <BootstrapIcon icon="arrow-left-short" class="d-none d-md-inline" />
              {{ prevTown.name }}
            </router-link>
          </div>
          <div class="col-md-6 text-center py-2">
            <h1 v-if="mapDataReady" class="m-0">{{ currentTownName }}</h1>
          </div>
          <div class="col-md-3 text-center text-md-end py-2">
            <router-link :to="'/ort/'+nextTown.route" class="align-middle fs-5" v-if="nextTown">
              <BootstrapIcon icon="arrow-down-short" class="d-md-none" />
              {{ nextTown.name }}
              <BootstrapIcon icon="arrow-right-short" class="d-none d-md-inline" />
            </router-link>
          </div>
        </div>
        <div v-if="!mapDataReady && !error" class="alert alert-primary">Lade Karte...</div>
        <div v-if="error" class="alert alert-primary"><strong>Fehler:</strong> Karte kann nicht geladen werden.
          Überprüfe deine Netzwerkverbindung
        </div>
        <TownMap v-if="mapDataReady" :id="town.id" :name="town.name" :latitude="town.latitude"
                 :longitude="town.longitude" :zoom="town.zoom"/>
        <ContentModal ref="mainContentModal" />
      </div>
    </section>
    <Credits/>
  </div>
</template>

<script lang="ts">
import { useRoute } from 'vue-router'
import BootstrapIcon from '@dvuckovic/vue3-bootstrap-icons'
import TownMap from '@/components/TownMap.vue'
import Menu from '@/components/Menu.vue'
import Credits from '@/components/Credits.vue'
import ContentModal from '@/components/ContentModal.vue'
import { getTown, TownData } from '@/api'
import { defineComponent } from 'vue'

export default defineComponent({
  components: {
    BootstrapIcon,
    ContentModal,
    Credits,
    TownMap,
    Menu
  },

  data () {
    return {
      currentRoute: useRoute(),
      mapDataReady: false,
      error: false,
      town: undefined as TownData | undefined,
      prevTown: undefined as TownData | undefined,
      nextTown: undefined as TownData | undefined
    }
  },
  computed: {
    currentTownName (): string {
      return this.town ? this.town.name : this.ucfirst(this.routeName)
    },

    routeName (): string {
      let routeName: string
      if (typeof this.$route.params.name === 'string') {
        routeName = this.$route.params.name
      } else {
        routeName = this.$route.params.name[0]
      }

      return routeName
    }
  },

  methods: {
    drawHeader (): void {
      this.error = false
      getTown(this.routeName).then(town => {
        this.town = town
        this.mapDataReady = true

        // prev town name
        getTown(town.prev).then(prevTown => {
          this.prevTown = prevTown
        })

        // next town name
        getTown(town.next).then(nextTown => {
          this.nextTown = nextTown
        })
      }).catch(() => {
        this.error = true
      })
    },
    ucfirst (s: string): string {
      return s.charAt(0).toUpperCase() + s.slice(1)
    }
  },

  created (): void {
    // watch for route update if navigating between towns
    this.$watch(
      () => this.$route.params,
      () => {
        this.drawHeader()
      },
      {
        // trigger once on component creation
        immediate: true,
        // https://github.com/vuejs/vue-next/issues/2291 *sigh*
        flush: 'post'
      }
    )
  }
})
</script>

<style lang="scss">
.townView {
  min-height: 100vh;
  background-color: #404040;
  background-image: linear-gradient(to bottom, #404040, #101010);
  color: white;
}

#town {
  padding-top: 3em;
  padding-bottom: 3em;
}

#town-navigation {
  a {
    color: inherit;
    text-decoration: none;
  }
}
</style>
