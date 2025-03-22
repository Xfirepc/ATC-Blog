import axios from '../../utils/axios';

const state = {
  user: null,
  isAuthenticated: false,
  token: localStorage.getItem('token') || null,
  loading: false
};

const mutations = {
  SET_USER(state, user) {
    state.user = user;
    state.isAuthenticated = !!user;
  },
  SET_TOKEN(state, token) {
    state.token = token;
    if (token) {
      localStorage.setItem('token', token);
    } else {
      localStorage.removeItem('token');
      state.isAuthenticated = false;
      state.user = null;
    }
  },
  SET_LOADING(state, loading) {
    state.loading = loading;
  }
};

const actions = {
  async login({ commit }, credentials) {
    commit('SET_LOADING', true);
    try {
      const response = await axios.post('/login', credentials);
      const { token, user } = response.data.data;
      commit('SET_TOKEN', token);
      commit('SET_USER', user);
      return user;
    } catch (error) {
      commit('SET_TOKEN', null);
      commit('SET_USER', null);
      throw error;
    } finally {
      commit('SET_LOADING', false);
    }
  },

  async register({ commit }, userData) {
    commit('SET_LOADING', true);
    try {
      const response = await axios.post('/register', userData);
      const { token, user } = response.data.data;
      commit('SET_TOKEN', token);
      commit('SET_USER', user);
      return user;
    } catch (error) {
      commit('SET_TOKEN', null);
      commit('SET_USER', null);
      throw error;
    } finally {
      commit('SET_LOADING', false);
    }
  },

  async logout({ commit }) {
    commit('SET_LOADING', true);
    try {
      await axios.post('/logout');
    } catch (error) {
      console.error('Error al cerrar sesión:', error);
    } finally {
      commit('SET_TOKEN', null);
      commit('SET_USER', null);
      commit('SET_LOADING', false);
    }
  },

  async fetchUser({ commit }) {
    commit('SET_LOADING', true);
    try {
      const response = await axios.get('/user');
      commit('SET_USER', response.data.data);
    } catch (error) {
      commit('SET_TOKEN', null);
      commit('SET_USER', null);
      throw error;
    } finally {
      commit('SET_LOADING', false);
    }
  }
};

const getters = {
  isAuthenticated: state => state.isAuthenticated,
  currentUser: state => state.user,
  isLoading: state => state.loading
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters
}; 