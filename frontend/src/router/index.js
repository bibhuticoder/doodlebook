import Vue from 'vue'
import Router from 'vue-router'
import Home from '@/Pages/Home'
import DoodleView from '@/Pages/DoodleView'
import DoodleCreate from '@/Pages/DoodleCreate'
import DoodleEdit from '@/Pages/DoodleEdit'

Vue.use(Router)

export default new Router({
  routes: [
    { path: '/', name:'Home', component: Home },
    { path: '/doodle/:id', name:'DoodleView', component: DoodleView },
    { path: '/doodle/:id/edit', name:'DoodleEdit', component: DoodleEdit },
    { path: '/new', name:'DoodleCreate', component: DoodleCreate },
  ]
})
