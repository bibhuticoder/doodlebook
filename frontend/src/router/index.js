import Vue from 'vue'
import Router from 'vue-router'
import auth from '../middleware/auth';
import Home from '@/Pages/Home'
import DoodleView from '@/Pages/Doodle/DoodleView'
import DoodleCreate from '@/Pages/Doodle/DoodleCreate'
import DoodleEdit from '@/Pages/Doodle/DoodleEdit'
import Login from '../Pages/Auth/Login'
import Register from '../Pages/Auth/Register'

Vue.use(Router)

const router = new Router({
  routes: [

    { path: '/login', name:'Login', component: Login },
    { path: '/register', name:'Register', component: Register },

    { path: '/', name:'Home', component: Home },
    { path: '/doodle/:id', name:'DoodleView', component: DoodleView },
    
    { path: '/doodle/:id/edit', name:'DoodleEdit', component: DoodleEdit, meta: {'middleware': auth} },
    { path: '/new', name:'DoodleCreate', component: DoodleCreate },
  ]
})

router.beforeEach((to, from, next) => {
  if(to.meta.middleware) {
    to.meta.middleware(next, router);
  }
  return next();
})

export default router;
