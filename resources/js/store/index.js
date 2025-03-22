import Vue from 'vue';
import Vuex from 'vuex';
import auth from './modules/auth';
import posts from './modules/posts';
import categories from './modules/categories';
import comments from './modules/comments';
// import comments from './modules/comments';

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    auth,
    posts,
    categories,
    comments
  }
}); 