import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router'
import Home from '@/views/Home.vue'
import Map from '@/views/Map.vue'
import Town from '@/views/Town.vue'

const routes: Array<RouteRecordRaw> = [
  {
    path: '/',
    name: 'Start',
    component: Home
  },
  {
    path: '/karte',
    name: 'Karte',
    component: Map,
    props: {
      towns: []
    }
  },
  {
    path: '/ort/:name',
    name: 'Ort',
    component: Town
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router
