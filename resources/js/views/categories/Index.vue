<template>
  <div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
      <h2 class="text-3xl font-bold text-gray-900">Categorías</h2>
    </div>

    <!-- Estado de carga -->
    <div v-if="isLoading" class="text-center py-8">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto"></div>
    </div>

    <!-- Mensaje de error -->
    <div v-else-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
      <span class="block sm:inline">{{ error }}</span>
    </div>

    <!-- Lista de categorías -->
    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="category in categories.data" :key="category?.id" class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="p-6">
          <h5 class="text-xl font-semibold text-gray-900 mb-2">{{ category?.name }}</h5>
          <p class="text-gray-600 mb-4">{{ category?.description }}</p>
          <div class="flex justify-between items-center">
            <small class="text-gray-500">{{ category?.posts_count || 0 }} posts</small>
            <router-link 
              :to="'/categories/' + category?.id" 
              class="inline-flex items-center px-3 py-1 border border-blue-500 rounded-md text-sm font-medium text-blue-600 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              Ver Posts
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'CategoriesIndex',
  computed: {
    categories() {
      return this.$store.state.categories.items;
    },
    isLoading() {
      return this.$store.state.categories.loading;
    },
    error() {
      return this.$store.state.categories.error;
    }
  },
  created() {
    this.$store.dispatch('categories/fetchCategories');
  }
}
</script> 