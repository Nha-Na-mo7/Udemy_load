// ====================
// ルート用Store
// ====================
import Vue from 'vue';
import Vuex from 'vuex';

import auth from './auth';
import error from './error';
// import authenticate from './authenticate';

Vue.use(Vuex);

const store = new Vuex.Store({
  modules: {
    auth,
    error,
    // authenticate,
  },
});

export default store;
