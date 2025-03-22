<template>
  <div class="container mx-auto px-4 py-8">
    <!-- Encabezado con información de la categoría -->
    <div class="mb-8">
      <div v-if="category" class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ category.name }}</h1>
        <p class="text-gray-600">{{ category.description }}</p>
      </div>

      <!-- Botón para crear nuevo post -->
      <div class="flex justify-end">
        <router-link 
          v-if="isAuthenticated"
          to="/posts/create" 
          class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-white bg-green-500 hover:bg-green-600"
        >
          Crear Post
        </router-link>
      </div>
    </div>

    <!-- Estado de carga -->
    <div v-if="isLoading" class="flex justify-center items-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-500"></div>
    </div>

    <!-- Mensaje de error -->
    <div v-else-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
      <span class="block sm:inline">{{ error }}</span>
    </div>

    <!-- Lista de posts -->
    <div v-else-if="posts.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="post in posts" :key="post.id" class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
        <div class="p-6 flex flex-col h-full">
          <!-- Título del post -->
          <h2 class="text-xl font-semibold text-gray-900 mb-3">
            <router-link :to="'/posts/' + post.id" class="hover:text-green-600 transition-colors duration-200">
              {{ post.title }}
            </router-link>
          </h2>

          <!-- Contenido del post -->
          <p class="text-gray-600 mb-6 flex-grow line-clamp-3">{{ post.content }}</p>
          
          <!-- Metadatos del post -->
          <div class="mt-auto space-y-3">
            <!-- Autor y fecha -->
            <div class="flex items-center text-sm text-gray-500">
              <span class="flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                {{ post.user?.name }}
              </span>
              <span class="mx-3">•</span>
              <span class="flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                {{ formatDate(post.created_at) }}
              </span>
            </div>

            <!-- Comentarios y botón de leer más -->
            <div class="flex items-center justify-between border-t pt-3">
              <span class="flex items-center text-sm text-gray-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                {{ post.comments?.length || 0 }} comentarios
              </span>
              <router-link 
                :to="'/posts/' + post.id"
                class="inline-flex items-center text-sm font-medium text-green-600 hover:text-green-700 transition-colors duration-200"
              >
                Leer más
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Mensaje cuando no hay posts -->
    <div v-else class="text-center py-12">
      <h3 class="text-lg font-medium text-gray-900 mb-2">No hay posts en esta categoría</h3>
      <p class="text-gray-600">Sé el primero en crear un post en esta categoría.</p>
      <router-link 
        v-if="isAuthenticated"
        to="/posts/create"
        class="inline-block mt-4 px-4 py-2 rounded-md text-sm font-medium text-white bg-green-500 hover:bg-green-600"
      >
        Crear Post
      </router-link>
    </div>
  </div>
</template>

<script>
export default {
  name: 'CategoryPosts',
  data() {
    return {
      error: null
    }
  },
  computed: {
    category() {
      return this.$store.state.categories.currentCategory;
    },
    posts() {
      const posts = this.$store.state.posts.items;
      console.log('Posts en el componente:', posts);
      return posts || [];
    },
    isLoading() {
      return this.$store.state.categories.loading || this.$store.state.posts.loading;
    },
    isAuthenticated() {
      return this.$store.state.auth.isAuthenticated;
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
    async loadData() {
      try {
        const categoryId = this.$route.params.id;
        console.log('Cargando datos para la categoría:', categoryId);
        
        await Promise.all([
          this.$store.dispatch('categories/fetchCategory', categoryId),
          this.$store.dispatch('posts/fetchPostsByCategory', categoryId)
        ]);

        console.log('Categoría cargada:', this.category);
        console.log('Posts cargados:', this.posts);
      } catch (error) {
        this.error = 'Error al cargar los datos. Por favor, intenta de nuevo.';
        console.error('Error:', error);
      }
    }
  },
  created() {
    console.log('Componente CategoryPosts creado');
    this.loadData();
  },
  // Recargar datos cuando cambie el ID de la categoría
  watch: {
    '$route.params.id': {
      handler(newId) {
        console.log('ID de categoría cambiado a:', newId);
        this.loadData();
      }
    }
  }
}
</script> 