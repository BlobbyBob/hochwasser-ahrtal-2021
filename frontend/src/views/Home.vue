<template>
  <div class="bg"></div>
  <div class="home">
    <Introduction/>
  </div>
</template>

<script lang="ts">
import { Options, Vue } from 'vue-class-component'
import Introduction from '@/components/Introduction.vue'

@Options({
  components: {
    Introduction
  }
})
export default class Home extends Vue {}
</script>

<style lang="scss">
.bg {
  background-image: url("../assets/bg.jpg");
  filter: blur(5px);
  width: 100vw;
  height: 100vh;
  position: absolute;
  top: 0;
  left: 0;
  z-index: -100;
}

.home {
  background-color: rgba(255, 255, 255, 0.8);
  max-width: 1200px;
  padding: 3em;
  font-size: 1.3em;
}
</style>
