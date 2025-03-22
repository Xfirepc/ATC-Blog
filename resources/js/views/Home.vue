<template>
  <div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-1">
      <div class="bg-white rounded-lg shadow-lg p-6 mb-4">
        <h5 class="text-xl font-semibold text-gray-800 mb-3">Últimos Posts</h5>
        <p class="text-gray-600 mb-4">Explora nuestros últimos artículos y noticias.</p>
        <router-link to="/posts" class="inline-block bg-blue-600 px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
          Ver Posts
        </router-link>
      </div>
      <div class="bg-white rounded-lg shadow-lg p-6">
        <h5 class="text-xl font-semibold text-gray-800 mb-3">Categorías</h5>
        <p class="text-gray-600 mb-4">Descubre contenido por categorías.</p>
        <router-link to="/categories" class="inline-block bg-blue-600 px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
          Ver Categorías
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Home'
}
</script>

<style scoped>
.home {
  text-align: center;
}
.card {
  margin-bottom: 20px;
}
</style> 