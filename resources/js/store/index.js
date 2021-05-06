import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)
import auth from "./modules/auth";
import settings from "./modules/settings";

const debug = process.env.NODE_ENV !== "production";

export default new Vuex.Store({
  modules: {
    auth,
    settings   
  },
  strict: debug
});