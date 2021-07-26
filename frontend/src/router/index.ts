import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router'
import Home from '@/views/Home.vue'
import Town from '@/views/Town.vue'
import Contact from '@/views/Contact.vue'
import Legal from '@/components/Legal.vue'

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
  },
  {
    path: '/kontakt',
    name: 'Kontakt',
    component: Contact
  },
  {
    path: '/impressum',
    name: 'Impressum',
    component: Legal
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router
