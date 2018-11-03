import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios';
Vue.use(Vuex)

export const store = new Vuex.Store({
  state:{
    baseUrl: 'http://127.0.0.1:8000'
  },

  getters: {
    baseUrl(state){
      return state.baseUrl;
    }
  },

  mutations:{
    setService(state, service){
      state.service = service;
    },
  },

  actions:{
    getDiaries(context, payload){
      //get all diaries of the given month of the year
      axios.post('http://127.0.0.1:5000/all', {})
        .then(response => {
          context.commit('setDiariesArr', response.data);
        })
        .catch(e => {
          console.log(e);
        })
    },
    getRandomDiary(context){
      axios.get('http://127.0.0.1:5000/random')
        .then(response => {
        context.commit('setRandomDiary', response.data);
      })
      .catch(e => {
        console.log(e);
      });
    },
    addDiary(context, payload){
      axios.post('http://127.0.0.1:5000/add', {

          'title': payload.diary.title,
          'date': payload.diary.date,
          'time': payload.diary.time,
          'tags': payload.diary.tags,
          'content': payload.diary.content,
          'new_things_learned': payload.diary.new_things_learned,
          'extras': payload.diary.extras,

      }).then(response => {
        if(response.data === "success"){
          payload.callback();
          context.commit('changeSync', false);
        }
      });
    },
    removeDiary(context, payload){
      axios.post('http://127.0.0.1:5000/delete', {
        diary_id: payload.diary.diary_id
      }).then(response => {
        if(response.data === "success"){
          payload.callback();
          context.commit('changeSync', false);
        }
      });
    },
    updateDiary(context, payload){
      axios.post('http://127.0.0.1:5000/update', {
        diary_id: payload.diary.diary_id,
        title: payload.diary.title,
        tags: payload.diary.tags,
        content: payload.diary.content,
        new_things_learned: payload.diary.new_things_learned,
        extras: payload.diary.extras
      }).then(response => {
        if(response.data === "success"){
          payload.callback();
          context.commit('changeSync', false);
        }
      });
    },

    searchDiary(context, payload){
      axios.post('http://127.0.0.1:5000/search', {
        query_string: payload.query_string
      }).then(response => {
        console.log(response.data);
        context.commit('setDiariesArr', response.data);
      });
    },

    sync(context, payload){
      Vue.http.get('http://127.0.0.1:5000/sync').then(response => {
        if(response.data === "success"){
          payload.callback();
          context.commit('changeSync', true);
        }
      });
    }
  }
});
