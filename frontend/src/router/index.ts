import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router'
import Home from '@/views/Home.vue'
import Town from '@/views/Town.vue'

const routes: Array<RouteRecordRaw> = [
  {
    path: '/',
    name: 'Start',
    component: Home
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
