<template>
  <div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
      <h2 class="text-3xl font-bold text-gray-900">Posts</h2>
      <router-link 
        v-if="isAuthenticated" 
        to="/posts/create" 
        class="inline-flex items-center px-4 py-2 text-sm btn btn-success"
      >
        Crear Post
      </router-link>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="post in posts.data" :key="post.id" class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="p-6">
          <h5 class="text-xl font-semibold text-gray-900 mb-2">{{ post.title }}</h5>
          <p class="text-gray-600 mb-4">{{ post.excerpt }}</p>
          <div class="flex justify-between items-center">
            <small class="text-gray-500">Por {{ post.user.name }}</small>
            <router-link 
              :to="'/posts/' + post.id" 
              class="inline-flex items-center px-3 py-1 border border-blue-500 rounded-md text-sm font-medium text-blue-600 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              Leer más
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'PostsIndex',
  computed: {
    isAuthenticated() {
      return this.$store.state.auth.isAuthenticated;
    },
    posts() {
      return this.$store.state.posts.items;
    }
  },
  created() {
    this.$store.dispatch('posts/fetchPosts');
  }
}
</script> 