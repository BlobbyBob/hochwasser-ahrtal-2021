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
        <Introduction @mapButtonClick="scrollToMap"/>
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
import Introduction from '@/components/Introduction.vue'
import Map from '@/views/Map.vue'
import Credits from '@/components/Credits.vue'

@Options({
  components: {
    Credits,
    Map,
    Introduction
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
  height: 100%;
  position: absolute;
  top: 0;
  left: 0;
  z-index: -100;
  overflow-x: hidden;

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
}
</style>
