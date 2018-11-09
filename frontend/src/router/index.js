import Vue from 'vue'
import Router from 'vue-router'
import auth from '../middleware/auth';
import Home from '@/Pages/Home'
import Dashboard from '@/Pages/User/Dashboard'
import ProfileEdit from '@/Pages/User/ProfileEdit'
import DoodleView from '@/Pages/Doodle/DoodleView'
import DoodleMake from '@/Pages/Doodle/DoodleMake'
import DoodleCreate from '@/Pages/Doodle/DoodleCreate'
import Login from '../Pages/Auth/Login'
import Register from '../Pages/Auth/Register'

Vue.use(Router)

const router = new Router({
  routes: [

    { path: '/login', name:'Login', component: Login },
    { path: '/register', name:'Register', component: Register },

    { path: '/', name:'Home', component: Home },
    { path: '/dashboard', name:'Dashboard', component: Dashboard },
    { path: '/doodle/:id', name:'DoodleView', component: DoodleView },
    
    // { path: '/doodle/:id/edit', name:'DoodleEdit', component: DoodleEdit, meta: {'middleware': auth} },
    { path: '/new', name:'DoodleCreate', component: DoodleCreate },
    { path: '/make/:id', name:'DoodleEdit', component: DoodleMake },

    { path: '/profile-edit', name:'ProfileEdit', component: ProfileEdit },

  ]
})

router.beforeEach((to, from, next) => {
  if(to.meta.middleware) {
    to.meta.middleware(next, router);
  }
  return next();
})

export default router;
