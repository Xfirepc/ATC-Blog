<template>
  <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
      <div class="bg-white rounded-lg shadow-lg p-8">
        <div class="text-center mb-8">
          <h2 class="text-3xl font-bold text-gray-900">Editar Post</h2>
        </div>
        <form @submit.prevent="handleSubmit" v-if="post" class="space-y-6">
          <div>
            <label for="title" class="block text-sm font-medium text-gray-700">Título</label>
            <input
              type="text"
              id="title"
              v-model="form.title"
              required
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
          <div>
            <label for="content" class="block text-sm font-medium text-gray-700">Contenido</label>
            <textarea
              id="content"
              v-model="form.content"
              rows="10"
              required
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
            ></textarea>
          </div>
          <div>
            <label for="category" class="block text-sm font-medium text-gray-700">Categoría</label>
            <select
              id="category"
              v-model="form.category_id"
              required
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="">Selecciona una categoría</option>
              <option v-for="category in categories" :key="category.id" :value="category.id">
                {{ category.name }}
              </option>
            </select>
          </div>
          <div v-if="error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md">
            {{ error }}
          </div>
          <div class="flex justify-end space-x-4">
            <router-link 
              to="/posts" 
              class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              Cancelar
            </router-link>
            <button
              type="submit"
              :disabled="loading"
              class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              {{ loading ? 'Guardando...' : 'Guardar Cambios' }}
            </button>
          </div>
        </form>
        <div v-else class="flex justify-center items-center py-12">
          <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
export default {
  name: 'EditPost',
  data() {
    return {
      post: null,
      form: {
        title: '',
        content: '',
        category_id: ''
      },
      categories: [],
      loading: false,
      error: null
    }
  },
  async created() {
    try {
      const postId = this.$route.params.id;
      await this.$store.dispatch('posts/fetchPost', postId);
      this.post = this.$store.state.posts.currentPost;
      this.form = {
        title: this.post.title,
        content: this.post.content,
        category_id: this.post.category_id
      };
      
      const response = await this.$store.dispatch('categories/fetchCategories');
      this.categories = response.data;
    } catch (error) {
      this.error = 'Error al cargar el post o las categorías';
    }
  },
  methods: {
    async handleSubmit() {
      this.loading = true;
      this.error = null;
      
      try {
        await this.$store.dispatch('posts/updatePost', {
          id: this.post.id,
          data: this.form
        });
        this.$router.push('/posts');
      } catch (error) {
        this.error = error.response?.data?.message || 'Error al actualizar el post';
      } finally {
        this.loading = false;
      }
    }
  }
}
</script> 