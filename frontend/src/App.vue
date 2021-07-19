<template>
  <div class="title">
    <h1>
      <router-link to="/">Hochwasser Ahrtal 2021</router-link>
    </h1>
  </div>
  <router-view/>
</template>

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
  text-align: center;

  h1 {
    font-size: 2em;

    a {
      text-decoration: inherit;
      color: inherit;
    }
  }
}

body {
  padding: 0;
  margin: 0;
}
</style>
