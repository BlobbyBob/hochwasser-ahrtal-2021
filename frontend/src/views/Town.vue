<template>
  <div class="townView">
    <Menu/>
    <section id="town">
      <div class="container">
        <div class="row align-items-center mb-2" id="town-navigation">
          <div class="col-md-3 text-center text-md-start px-2">
            <router-link :to="'/ort/'+prevTown.route" class="align-middle fs-5" v-if="prevTown">
              <BootstrapIcon icon="arrow-up-short" class="d-md-none"/>
              <BootstrapIcon icon="arrow-left-short" class="d-none d-md-inline"/>
              {{ prevTown.name }}
            </router-link>
          </div>
          <div class="col-md-6 text-center py-2">
            <h1 v-if="mapDataReady" class="m-0">{{ currentTownName }}</h1>
          </div>
          <div class="col-md-3 text-center text-md-end py-2">
            <router-link :to="'/ort/'+nextTown.route" class="align-middle fs-5" v-if="nextTown">
              <BootstrapIcon icon="arrow-down-short" class="d-md-none"/>
              {{ nextTown.name }}
              <BootstrapIcon icon="arrow-right-short" class="d-none d-md-inline"/>
            </router-link>
          </div>
        </div>

        <div v-show="mapDataReady" id="filter">
          <div class="nav nav-tabs col-12">
            <div class="nav-item">
              <h5 @click="filterVisible = !filterVisible" class="cursor-pointer nav-link active">
                <BootstrapIcon icon="sliders"/>
                <span class="ms-3">{{ !filterVisible ? 'Filter anzeigen' : 'Filter ausblenden' }}</span>
              </h5>
            </div>
          </div>
          <div v-show="filterVisible" @click="updateBinaryFilters" class="flex-column flex-lg-row filter-body py-3 px-4"
               style="display: flex">
            <div class="d-flex col-12 col-lg-6 flex-column align-items-center justify-content-center">
              <div class="row col-12 align-items-center">
                <span class="col-3 col-lg-2">Format:</span>
                <div class="col-9 col-lg-10">
                  <button class="btn btn-success" @click="toggleButton" data-toggle="format:image">
                    <BootstrapIcon icon="image"/>
                  </button>
                  <button class="btn btn-success" @click="toggleButton" data-toggle="format:video">
                    <BootstrapIcon icon="camera-video"/>
                  </button>
                </div>
              </div>
              <div class="col-12 mt-2 row align-items-center">
                <span class="col-3 col-lg-2">Typ:</span>
                <div class="col-9 col-lg-10">
                  <button class="btn btn-success" @click="toggleButton" data-toggle="type:img">
                    <BootstrapIcon icon="camera"/>
                  </button>
                  <button class="btn btn-success" @click="toggleButton" data-toggle="type:twitter">
                    <BootstrapIcon icon="twitter"/>
                  </button>
                  <button class="btn btn-success" @click="toggleButton" data-toggle="type:reddit">
                    <BootstrapIcon icon="reddit"/>
                  </button>
                  <button class="btn btn-success" @click="toggleButton" data-toggle="type:iframe">
                    <BootstrapIcon icon="newspaper"/>
                  </button>
                  <button class="btn btn-success" @click="toggleButton" data-toggle="type:youtube">
                    <BootstrapIcon icon="youtube"/>
                  </button>
                  <button class="btn btn-success" @click="toggleButton" data-toggle="type:link">
                    <BootstrapIcon icon="box-arrow-up-right"/>
                  </button>
                </div>
              </div>
            </div>
            <div class="col-12 col-lg-6">
              <div class="row align-items-center">
                <div class="flex-grow-1 px-5 py-4">
                  <NoUiSlider class="py-5" :config="generateSliderConfig()" @update="updateSlider"/>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div v-if="!mapDataReady && !error" class="alert alert-primary">Lade Karte...</div>
        <div v-if="error" class="alert alert-primary">
          <strong>Fehler:</strong> Karte kann nicht geladen werden. Überprüfe deine Netzwerkverbindung
        </div>
        <TownMap v-if="mapDataReady" :id="town.id" :name="town.name" :latitude="town.latitude"
                 :longitude="town.longitude" :zoom="town.zoom" :filter="filter"/>
        <ContentModal ref="mainContentModal"/>
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
import { getTown, MediaFilter, TownData } from '@/api'
import { defineComponent } from 'vue'
import NoUiSlider from '@/components/NoUiSlider.vue'
import { CountPips, Options, PipsMode } from 'nouislider'

export default defineComponent({
  components: {
    BootstrapIcon,
    ContentModal,
    Credits,
    TownMap,
    Menu,
    NoUiSlider
  },

  data () {
    return {
      currentRoute: useRoute(),
      mapDataReady: false,
      filterVisible: false,
      filter: new MediaFilter(),
      error: false,
      town: undefined as TownData | undefined,
      prevTown: undefined as TownData | undefined,
      nextTown: undefined as TownData | undefined,
      initialDate: new Date('2021-07-13T00:00:00')
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
    },
    updateBinaryFilters (): void {
      this.filter.format = []
      if (document.querySelector('#filter button.btn-success[data-toggle="format:image"]')) this.filter.format.push('image')
      if (document.querySelector('#filter button.btn-success[data-toggle="format:video"]')) this.filter.format.push('video')
      this.filter.type = []
      if (document.querySelector('#filter button.btn-success[data-toggle="type:img"]')) this.filter.type.push('img')
      if (document.querySelector('#filter button.btn-success[data-toggle="type:twitter"]')) this.filter.type.push('twitter')
      if (document.querySelector('#filter button.btn-success[data-toggle="type:reddit"]')) this.filter.type.push('reddit')
      if (document.querySelector('#filter button.btn-success[data-toggle="type:iframe"]')) this.filter.type.push('iframe')
      if (document.querySelector('#filter button.btn-success[data-toggle="type:youtube"]')) this.filter.type.push('youtube')
      if (document.querySelector('#filter button.btn-success[data-toggle="type:link"]')) this.filter.type.push('link')
      this.filter.triggerEvent()
    },
    toggleButton (e: Event): void {
      let button = e.target as HTMLElement
      while (button && button.tagName.toLowerCase() !== 'button') button = button.parentElement as HTMLElement
      if (!button) return
      button.classList.toggle('btn-success')
      button.classList.toggle('btn-danger')
    },
    generateSliderConfig (): Options {
      const dayOffset = (date: Date) => Math.floor((date.getTime() - this.initialDate.getTime()) / 86400000)
      const days = dayOffset(new Date())
      const formatter = {
        to: (num: number) => {
          const d = new Date(this.initialDate.getTime() + 86400000 * num)
          return `${String(d.getDate()).padStart(2, '0')}.${String(d.getMonth() + 1).padStart(2, '0')}.${d.getFullYear()}`
        },
        from: (str: string) => {
          const parts = str.split('.')
          const d = new Date()
          d.setFullYear(Number.parseInt(parts[2], 10))
          d.setMonth(Number.parseInt(parts[1], 10) - 1)
          d.setDate(Number.parseInt(parts[0], 10))
          return dayOffset(d)
        }
      }
      const options = {
        start: [0, days],
        step: 1,
        connect: true,
        range: {
          min: 0,
          max: days
        },
        tooltips: [formatter, formatter],
        pips: {
          mode: PipsMode.Count,
          density: 6,
          values: 4,
          format: formatter
        } as CountPips
      }
      console.log(options)
      return options
    },
    updateSlider (vals: string[2]): void {
      const since = new Date(this.initialDate.getTime() + 86400000 * Number.parseInt(vals[0]))
      const before = new Date(this.initialDate.getTime() + 86400000 * Number.parseInt(vals[1]))
      this.filter.since = since
      this.filter.before = before
      this.filter.triggerEvent()
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

#filter {
  .filter-body {
    border: 1px solid #dee2e6;
    border-top: none;
  }

  button {
    margin: 5px;
  }

  .noUi-value {
    font-size: 0.8em;
  }
}
</style>
