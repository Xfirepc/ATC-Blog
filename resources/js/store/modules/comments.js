import axios from '../../utils/axios';


// Estado inicial
const state = {
  comments: [],
  loading: false,
  error: null
};

// Mutaciones
const mutations = {
  SET_LOADING(state, status) {
    state.loading = status;
  },
  SET_ERROR(state, error) {
    state.error = error;
  },
  SET_COMMENTS(state, comments) {
    state.comments = comments;
  },
  ADD_COMMENT(state, comment) {
    state.comments.push(comment);
  },
  REMOVE_COMMENT(state, commentId) {
    state.comments = state.comments.filter(comment => comment.id !== commentId);
  }
};

// Acciones
const actions = {
  // Crear un nuevo comentario
  async createComment({ commit }, { post_id, content }) {
    commit('SET_LOADING', true);
    commit('SET_ERROR', null);
    
    try {
      const response = await axios.post(`/comments`, {
        post_id,
        content
      });
      
      commit('ADD_COMMENT', response.data.data);
      return response.data.data;
    } catch (error) {
      const errorMessage = error.response?.data?.message || 'Error al crear el comentario';
      commit('SET_ERROR', errorMessage);
      throw error;
    } finally {
      commit('SET_LOADING', false);
    }
  },

  // Eliminar un comentario
  async deleteComment({ commit }, commentId) {
    commit('SET_LOADING', true);
    commit('SET_ERROR', null);
    
    try {
      await axios.delete(`/comments/${commentId}`);
      commit('REMOVE_COMMENT', commentId);
    } catch (error) {
      const errorMessage = error.response?.data?.message || 'Error al eliminar el comentario';
      commit('SET_ERROR', errorMessage);
      throw error;
    } finally {
      commit('SET_LOADING', false);
    }
  },

  // Obtener comentarios de un post
  async fetchComments({ commit }, postId) {
    commit('SET_LOADING', true);
    commit('SET_ERROR', null);
    
    try {
      const response = await axios.get(`/posts/${postId}/comments`);
      commit('SET_COMMENTS', response.data.data);
      return response.data.data;
    } catch (error) {
      const errorMessage = error.response?.data?.message || 'Error al cargar los comentarios';
      commit('SET_ERROR', errorMessage);
      throw error;
    } finally {
      commit('SET_LOADING', false);
    }
  }
};

// Getters
const getters = {
  getCommentsByPostId: (state) => (postId) => {
    return state.comments.filter(comment => comment.post_id === postId);
  },
  isLoading: state => state.loading,
  hasError: state => state.error !== null,
  getError: state => state.error
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters
}; 