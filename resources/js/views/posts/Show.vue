<template>
  <div class="container mx-auto px-4 py-8">
    <!-- Estado de carga -->
    <div v-if="isLoading" class="text-center py-8">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto"></div>
    </div>

    <!-- Mensaje de error -->
    <div v-else-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
      <span class="block sm:inline">{{ error }}</span>
    </div>

    <!-- Contenido del post -->
    <div v-else class="max-w-4xl mx-auto">
      <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Encabezado del post -->
        <div class="p-6">
          <div class="flex justify-between items-start mb-4">
            <div>
              <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ post?.title }}</h1>
              <div class="flex items-center text-gray-600">
                <span class="mr-4">Por {{ post?.user?.name }}</span>
                <span class="mr-4">{{ formatDate(post?.created_at) }}</span>
                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-sm">
                  {{ post?.category?.name }}
                </span>
              </div>
            </div>
            <div v-if="canEdit" class="flex space-x-2">
              <router-link 
                :to="'/posts/' + post?.id + '/edit'"
                class="inline-flex items-center px-3 py-1 border border-blue-500 rounded-md text-sm font-medium text-blue-600 hover:bg-blue-50"
              >
                Editar
              </router-link>
              <button 
                @click="deletePost"
                class="inline-flex items-center px-3 py-1 border border-red-500 rounded-md text-sm font-medium text-red-600 hover:bg-red-50"
              >
                Eliminar
              </button>
            </div>
          </div>

          <!-- Contenido del post -->
          <div class="prose max-w-none mb-8">
            {{ post?.content }}
          </div>

          <!-- Sección de comentarios -->
          <div class="border-t pt-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Comentarios</h2>
            
            <!-- Formulario de comentario (si está autenticado) -->
            <div v-if="isAuthenticated" class="mb-6">
              <textarea
                v-model="newComment"
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                placeholder="Escribe un comentario..."
              ></textarea>
              <button
                @click="submitComment"
                class="mt-2 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
              >
                Comentar
              </button>
            </div>

            <!-- Lista de comentarios -->
            <div class="space-y-4">
              <div v-for="comment in post?.comments" :key="comment.id" class="bg-gray-50 rounded-lg p-4">
                <div class="flex justify-between items-start">
                  <div>
                    <p class="font-medium text-gray-900">{{ comment.user?.name }}</p>
                    <p class="text-sm text-gray-500">{{ formatDate(comment.created_at) }}</p>
                  </div>
                    <button 
                      @click="deleteComment(comment.id)"
                      class="text-sm text-red-600 hover:text-red-800"
                    >
                      Eliminar
                    </button>
                  </div>
                </div>
                <p class="mt-2 text-gray-700">{{ comment.content }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'PostShow',
  data() {
    return {
      newComment: '',
      editingComment: null
    }
  },
  computed: {
    post() {
      return this.$store.state.posts.currentPost;
    },
    isLoading() {
      return this.$store.state.posts.loading;
    },
    error() {
      return this.$store.state.posts.error;
    },
    isAuthenticated() {
      return this.$store.state.auth.isAuthenticated;
    },
    canEdit() {
      return this.isAuthenticated && this.post?.user?.id === this.$store.state.auth.user?.id;
    }
  },
  methods: {
    formatDate(date) {
      return new Date(date).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      });
    },
    async submitComment() {
      if (!this.newComment.trim()) return;
      
      try {
        await this.$store.dispatch('comments/createComment', {
          post_id: this.post.id,
          content: this.newComment
        });
        this.newComment = '';
      } catch (error) {
        console.error('Error al crear el comentario:', error);
      }
    },
    async deletePost() {
      if (!confirm('¿Estás seguro de que quieres eliminar este post?')) return;
      
      try {
        await this.$store.dispatch('posts/deletePost', this.post.id);
        this.$router.push('/posts');
      } catch (error) {
        console.error('Error al eliminar el post:', error);
      }
    },
    async deleteComment(commentId) {
      if (!confirm('¿Estás seguro de que quieres eliminar este comentario?')) return;
      
      try {
        await this.$store.dispatch('comments/deleteComment', commentId);
      } catch (error) {
        console.error('Error al eliminar el comentario:', error);
      }
    },
  },
  created() {
    this.$store.dispatch('posts/fetchPost', this.$route.params.id);
  }
}
</script> 