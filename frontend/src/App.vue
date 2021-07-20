<template>
  <div class="title">
    <div class="container d-flex align-items-center justify-content-between">
      <h1>
        <router-link to="/">Hochwasser Ahrtal 2021</router-link>
      </h1>
      <div class="social">
        <a href="https://github.com/BlobbyBob/hochwasser-ahrtal-2021" target="_blank">
          <BootstrapIcon icon="github" size="3x"/>
        </a>
      </div>
    </div>
  </div>
  <div id="content">
    <div class="container">
      <router-view/>
    </div>
  </div>
</template>

<script lang="ts">
import { Options, Vue } from 'vue-class-component'
import BootstrapIcon from '@dvuckovic/vue3-bootstrap-icons'

@Options({
  components: { BootstrapIcon }
})
export default class App extends Vue {
}
</script>

<style lang="scss">
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: left;
  color: #2c3e50;

  display: flex;
  flex-direction: column;
  align-items: center;
}

.title {
  width: 100%;
  background-color: aliceblue;

  h1 {
    font-size: 2em;
    float: left;
    padding: 1em;
    margin: 0;

    a {
      text-decoration: inherit;
      color: inherit;
    }
  }

  .social {
    margin-right: 1em;

    a {
      text-decoration: inherit;
      color: inherit;
      padding: 5px;

      :hover {
        color: darkblue;
        transition: color 1s ease-out;
      }
    }
  }
}

body {
  padding: 0;
  margin: 0;
}

#content {
  background-color: tomato;
  width: 100%;
  min-height: calc(100vh - 6em);

  .container {
    background-color: whitesmoke;
  }
}
</style>
