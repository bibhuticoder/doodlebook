import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios';
Vue.use(Vuex)

export const store = new Vuex.Store({
  state:{
    baseUrl: 'https://doodlebook.herokuapp.com',
    token: localStorage.getItem('_token') || null,
    user: null
  },

  getters: {
    baseUrl(state){
      return state.baseUrl;
    },

    user(state){
      return state.user;
    },

    loggedIn(state){
      return (state.user !== null);
    }

  },

  mutations:{
    setUser(state, user){
      state.user = user;
    },
    setToken(state, token){
      state.token = token;
    }
  },

  actions:{
    getUser(context, payload){
      axios.get(`${context.getters.baseUrl}/api/auth/me`)
        .then(response => {
          context.commit('setUser', response.data);
          if(payload) payload.callback(response.data);
        })
        .catch(e => {
          console.log(e);
        })
    },
  }
});
