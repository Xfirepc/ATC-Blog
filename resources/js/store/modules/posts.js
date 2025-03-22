import axios from '../../utils/axios';

const state = {
  items: [],
  currentPost: null,
  loading: false,
  error: null
};

const mutations = {
  SET_POSTS(state, posts) {
    state.items = posts;
  },
  SET_CURRENT_POST(state, post) {
    state.currentPost = post;
  },
  SET_LOADING(state, loading) {
    state.loading = loading;
  },
  SET_ERROR(state, error) {
    state.error = error;
  }
};

const actions = {
  async fetchPosts({ commit }) {
    commit('SET_LOADING', true);
    commit('SET_ERROR', null);
    try {
      const response = await axios.get('/posts');
      const posts = response.data?.data || [];
      commit('SET_POSTS', posts);
      return posts;
    } catch (error) {
      commit('SET_ERROR', error.response?.data?.message || 'Error al cargar los posts');
      commit('SET_POSTS', []);
      throw error;
    } finally {
      commit('SET_LOADING', false);
    }
  },

  async fetchPost({ commit }, id) {
    commit('SET_LOADING', true);
    commit('SET_ERROR', null);
    try {
      const response = await axios.get(`/posts/${id}`);
      const post = response.data?.data;
      commit('SET_CURRENT_POST', post);
      return post;
    } catch (error) {
      commit('SET_ERROR', error.response?.data?.message || 'Error al cargar el post');
      commit('SET_CURRENT_POST', null);
      throw error;
    } finally {
      commit('SET_LOADING', false);
    }
  },

  async createPost({ commit, dispatch }, postData) {
    commit('SET_LOADING', true);
    commit('SET_ERROR', null);
    try {
      const response = await axios.post('/posts', postData);
      const post = response.data?.data;
      await dispatch('fetchPosts');
      return post;
    } catch (error) {
      commit('SET_ERROR', error.response?.data?.message || 'Error al crear el post');
      throw error;
    } finally {
      commit('SET_LOADING', false);
    }
  },

  async updatePost({ commit, dispatch }, { id, data }) {
    commit('SET_LOADING', true);
    commit('SET_ERROR', null);
    try {
      const response = await axios.put(`/posts/${id}`, data);
      const post = response.data?.data;
      await dispatch('fetchPosts');
      return post;
    } catch (error) {
      commit('SET_ERROR', error.response?.data?.message || 'Error al actualizar el post');
      throw error;
    } finally {
      commit('SET_LOADING', false);
    }
  },

  async deletePost({ commit, dispatch }, id) {
    commit('SET_LOADING', true);
    commit('SET_ERROR', null);
    try {
      await axios.delete(`/posts/${id}`);
      await dispatch('fetchPosts');
    } catch (error) {
      commit('SET_ERROR', error.response?.data?.message || 'Error al eliminar el post');
      throw error;
    } finally {
      commit('SET_LOADING', false);
    }
  },

  async fetchPostsByCategory({ commit }, categoryId) {
    commit('SET_LOADING', true);
    commit('SET_ERROR', null);
    try {
      const response = await axios.get(`/categories/${categoryId}/posts`);
      const posts = response.data?.data.data || [];
      commit('SET_POSTS', posts);
      return posts;
    } catch (error) {
      commit('SET_ERROR', error.response?.data?.message || 'Error al cargar los posts de la categoría');
      commit('SET_POSTS', []);
      throw error;
    } finally {
      commit('SET_LOADING', false);
    }
  }
};

const getters = {
  posts: state => state.items,
  currentPost: state => state.currentPost,
  loading: state => state.loading,
  error: state => state.error
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters
}; 