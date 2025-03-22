<template>
  <div class="container mx-auto px-4 py-8">
    <!-- Estado de carga -->
    <div v-if="isLoading" class="flex justify-center items-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-500"></div>
    </div>

    <!-- Mensaje de error -->
    <div v-else-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
      <span class="block sm:inline">{{ error }}</span>
    </div>

    <!-- Contenido del post -->
    <div v-else-if="post" class="max-w-4xl mx-auto">
      <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="p-6">
          <!-- Encabezado del post -->
          <div class="flex justify-between items-start mb-6">
            <div>
              <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ post.title }}</h1>
              <div class="flex items-center text-gray-600 space-x-4">
                <span>Por {{ post.user?.name }}</span>
                <span>{{ formatDate(post.created_at) }}</span>
                <router-link 
                  :to="'/categories/' + post.category?.id" 
                  class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm hover:bg-green-200"
                >
                  {{ post.category?.name }}
                </router-link>
              </div>
            </div>
            
            <!-- Botones de acción -->
            <div v-if="canEdit" class="flex space-x-2">
              <router-link 
                :to="'/posts/' + post.id + '/edit'"
                class="inline-flex items-center px-3 py-1 border border-green-500 rounded-md text-sm font-medium text-green-600 hover:bg-green-50"
              >
                Editar
              </router-link>
              <button 
                @click="handleDelete"
                class="inline-flex items-center px-3 py-1 border border-red-500 rounded-md text-sm font-medium text-red-600 hover:bg-red-50"
              >
                Eliminar
              </button>
            </div>
          </div>

          <!-- Contenido del post -->
          <div class="prose max-w-none mb-8">
            <p class="text-gray-700 leading-relaxed">{{ post.content }}</p>
          </div>

          <!-- Sección de comentarios -->
          <div class="border-t pt-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Comentarios</h2>
            
            <!-- Formulario de comentario -->
            <div v-if="isAuthenticated" class="mb-6">
              <textarea
                v-model="newComment"
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                placeholder="Escribe un comentario..."
              ></textarea>
              <button
                @click="submitComment"
                :disabled="!newComment.trim()"
                class="mt-2 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50"
              >
                Comentar
              </button>
            </div>

            <!-- Lista de comentarios -->
            <div class="space-y-4">
              <div v-for="comment in post.comments" :key="comment.id" class="bg-gray-50 rounded-lg p-4">
                <div class="flex justify-between items-start">
                  <div>
                    <p class="font-medium text-gray-900">{{ comment.user?.name }}</p>
                    <p class="text-sm text-gray-500">{{ formatDate(comment.created_at) }}</p>
                  </div>
                  <div v-if="canEditComment(comment)" class="flex space-x-2">
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

            <!-- Mensaje cuando no hay comentarios -->
            <div v-if="post.comments?.length === 0" class="text-center py-6 text-gray-500">
              No hay comentarios aún. ¡Sé el primero en comentar!
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Mensaje cuando no se encuentra el post -->
    <div v-else class="text-center py-12">
      <h3 class="text-lg font-medium text-gray-900 mb-2">Post no encontrado</h3>
      <p class="text-gray-600">El post que buscas no existe o ha sido eliminado.</p>
      <router-link 
        to="/posts"
        class="inline-block mt-4 px-4 py-2 rounded-md text-sm font-medium text-white bg-green-500 hover:bg-green-600"
      >
        Volver a Posts
      </router-link>
    </div>
  </div>
</template>

<script>
export default {
  name: 'PostShow',
  data() {
    return {
      newComment: '',
      error: null
    }
  },
  computed: {
    post() {
      return this.$store.state.posts.currentPost;
    },
    isLoading() {
      return this.$store.state.posts.loading;
    },
    isAuthenticated() {
      return this.$store.state.auth.isAuthenticated;
    },
    currentUser() {
      return this.$store.state.auth.user;
    },
    canEdit() {
      return this.isAuthenticated && this.post?.user?.id === this.currentUser?.id;
    }
  },
  methods: {
    formatDate(date) {
      if (!date) return '';
      return new Date(date).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      });
    },
    async loadPost() {
      try {
        await this.$store.dispatch('posts/fetchPost', this.$route.params.id);
      } catch (error) {
        this.error = 'Error al cargar el post. Por favor, intenta de nuevo.';
        console.error('Error:', error);
      }
    },
    canEditComment(comment) {
      return this.isAuthenticated && comment.user?.id === this.currentUser?.id;
    },
    async submitComment() {
      if (!this.newComment.trim()) return;
      
      try {
        await this.$store.dispatch('comments/createComment', {
          post_id: this.post.id,
          content: this.newComment.trim()
        });
        this.newComment = '';
        await this.loadPost(); // Recargar el post para obtener los comentarios actualizados
      } catch (error) {
        console.error('Error al crear el comentario:', error);
      }
    },
    async handleDelete() {
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
        await this.loadPost(); // Recargar el post para actualizar los comentarios
      } catch (error) {
        console.error('Error al eliminar el comentario:', error);
      }
    },
  },
  created() {
    this.loadPost();
  },
  // Recargar el post cuando cambie el ID en la URL
  watch: {
    '$route.params.id': {
      handler() {
        this.loadPost();
      }
    }
  }
}
</script> 