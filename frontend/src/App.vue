<template>
  <div class="title">
    <div class="container">
      <h1>
        <router-link to="/">Hochwasser Ahrtal 2021</router-link>
      </h1>
    </div>
  </div>
  <div id="content">
    <div class="container">
      <router-view/>
    </div>
  </div>
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
  padding-top: 2em;
  padding-bottom: 1em;

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

#content {
  background-color: tomato;
  width: 100%;
  min-height: calc(100vh - 6em);

  .container {
    background-color: whitesmoke;
  }
}
</style>
