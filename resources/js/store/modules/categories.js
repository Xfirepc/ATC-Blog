import axios from '../../utils/axios';

export default {
  namespaced: true,
  
  state: {
    items: [],
    currentCategory: null,
    loading: false,
    error: null
  },
  
  mutations: {
    SET_ITEMS(state, items) {
      state.items = items;
    },
    SET_CURRENT_CATEGORY(state, category) {
      state.currentCategory = category;
    },
    SET_LOADING(state, loading) {
      state.loading = loading;
    },
    SET_ERROR(state, error) {
      state.error = error;
    }
  },
  
  actions: {
    async fetchCategories({ commit }) {
      commit('SET_LOADING', true);
      commit('SET_ERROR', null);
      
      try {
        const response = await axios.get('/categories');
        commit('SET_ITEMS', response.data.data || []);
        return response.data.data || [];
      } catch (error) {
        commit('SET_ERROR', error.response?.data?.message || 'Error al cargar las categorías');
        commit('SET_ITEMS', []);
        throw error;
      } finally {
        commit('SET_LOADING', false);
      }
    },
    
    async fetchCategory({ commit }, id) {
      commit('SET_LOADING', true);
      commit('SET_ERROR', null);
      
      try {
        const response = await axios.get(`/api/v1/categories/${id}`);
        commit('SET_CURRENT_CATEGORY', response.data.data);
        return response.data.data;
      } catch (error) {
        commit('SET_ERROR', error.response?.data?.message || 'Error al cargar la categoría');
        commit('SET_CURRENT_CATEGORY', null);
        throw error;
      } finally {
        commit('SET_LOADING', false);
      }
    },
    
    async createCategory({ commit, dispatch }, categoryData) {
      commit('SET_LOADING', true);
      commit('SET_ERROR', null);
      
      try {
        const response = await axios.post('/api/v1/categories', categoryData);
        await dispatch('fetchCategories');
        return response.data.data;
      } catch (error) {
        commit('SET_ERROR', error.response?.data?.message || 'Error al crear la categoría');
        throw error;
      } finally {
        commit('SET_LOADING', false);
      }
    },
    
    async updateCategory({ commit, dispatch }, { id, data }) {
      commit('SET_LOADING', true);
      commit('SET_ERROR', null);
      
      try {
        const response = await axios.put(`/api/v1/categories/${id}`, data);
        await dispatch('fetchCategories');
        return response.data.data;
      } catch (error) {
        commit('SET_ERROR', error.response?.data?.message || 'Error al actualizar la categoría');
        throw error;
      } finally {
        commit('SET_LOADING', false);
      }
    },
    
    async deleteCategory({ commit, dispatch }, id) {
      commit('SET_LOADING', true);
      commit('SET_ERROR', null);
      
      try {
        await axios.delete(`/api/v1/categories/${id}`);
        await dispatch('fetchCategories');
      } catch (error) {
        commit('SET_ERROR', error.response?.data?.message || 'Error al eliminar la categoría');
        throw error;
      } finally {
        commit('SET_LOADING', false);
      }
    }
  },
  
  getters: {
    allCategories: state => state.items,
    currentCategory: state => state.currentCategory,
    isLoading: state => state.loading,
    error: state => state.error
  }
} 