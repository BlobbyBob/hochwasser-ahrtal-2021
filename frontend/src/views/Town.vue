<template>
  <div class="town">
    <h1>This is the town page for {{ currentRoute.params.name }}</h1>
  </div>
</template>

<script lang="ts">
import { Options, Vue } from 'vue-class-component'
import { useRoute } from 'vue-router'

@Options({})
export default class Town extends Vue {
  currentRoute = useRoute();
}
</script>
