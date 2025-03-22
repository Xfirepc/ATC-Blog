import axios from '../../utils/axios';

const state = {
  user: null,
  isAuthenticated: false,
  token: localStorage.getItem('token') || null
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
  }
};

const actions = {
  async login({ commit }, credentials) {
    try {
      const response = await axios.post('/login', credentials);
      const { token, user } = response.data.data;
      commit('SET_TOKEN', token);
      commit('SET_USER', user);
      return user;
    } catch (error) {
      throw error;
    }
  },

  async register({ commit }, userData) {
    try {
      const response = await axios.post('/register', userData);
      const { token, user } = response.data.data;
      commit('SET_TOKEN', token);
      commit('SET_USER', user);
      return user;
    } catch (error) {
      throw error;
    }
  },

  async logout({ commit }) {
    try {
      await axios.post('/logout');
    } catch (error) {
      console.error('Error al cerrar sesión:', error);
    } finally {
      commit('SET_TOKEN', null);
      commit('SET_USER', null);
    }
  },

  async fetchUser({ commit }) {
    try {
      const response = await axios.get('/user');
      commit('SET_USER', response.data.data);
    } catch (error) {
      commit('SET_TOKEN', null);
      commit('SET_USER', null);
      throw error;
    }
  }
};

const getters = {
  isAuthenticated: state => state.isAuthenticated,
  currentUser: state => state.user
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters
}; 