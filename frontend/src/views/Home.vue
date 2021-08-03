<template>
  <section id="home">
    <div class="bg">
      <img class="d-md-none" src="../assets/bg.jpg" alt="Überflutungen im Ahrtal"/>
      <video class="d-none d-md-block" autoplay loop muted poster>
        <source src="../assets/bg.mp4" type="video/mp4">
      </video>
    </div>
    <div class="container">
      <div class="col-12 col-md-9 col-lg-7">
        <h1>Hochwasser Ahrtal 2021</h1>

        <div class="introduction">
          <p>
            Im Jahr 2021 gab es im Südwesten Deutschlands starke Regenfälle, die zu historischen Hochwassern in mehreren
            Gegenden geführt haben. Das Ahrtal ist eines der am härtesten betroffenen Gebiete. Auf dieser Seite sind
            verschiedene Beiträge gesammelt, die die Ausmaße der Zerstörungen dokumentieren.
          </p>

          <div>
            <BigButton @click="scrollToMap">Karte ansehen</BigButton>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section id="map" class="d-flex flex-column justify-content-between">
    <Map :towns="[]"/>
    <Credits/>
  </section>
</template>

<script lang="ts">
import { Options, Vue } from 'vue-class-component'
import Map from '@/views/Map.vue'
import BigButton from '@/components/BigButton.vue'
import Credits from '@/components/Credits.vue'

@Options({
  components: {
    BigButton,
    Credits,
    Map
  }
})
export default class Home extends Vue {
  scrollToMap (): void {
    const map = document.getElementById('map')
    if (map) map.scrollIntoView()
  }
}
</script>

<style lang="scss">
.bg {
  background-color: black;
  width: 100%;
  height: 100vh;
  position: absolute;
  top: 0;
  left: 0;
  z-index: -100;
  overflow: hidden;

  img, video {
    min-height: 100%;
    min-width: 100%;
    filter: blur(1px) grayscale(60%) opacity(0.6);
  }
}

#home {
  height: 100vh;
  padding-top: 35vh;
  color: white;
  max-width: 100vw;
  overflow-x: hidden;
}

#map {
  min-height: 100vh;
  background-color: #404040;
  background-image: linear-gradient(to bottom, #404040, #101010);
}

.introduction {
  font-size: 1.5em
}
</style>
