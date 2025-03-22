<template>
  <div id="app" class="min-h-screen flex flex-col bg-gray-50">
    <nav class="bg-gray-800 shadow-lg relative">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <!-- Logo -->
          <router-link to="/" class="flex items-center text-white text-xl font-bold">
            Blog ATC
          </router-link>

          <!-- Menú Desktop -->
          <div class="hidden lg:flex items-center space-x-4">
            <router-link 
              to="/posts" 
              class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium"
            >
              Posts
            </router-link>
            <router-link 
              to="/categories" 
              class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium"
            >
              Categorías
            </router-link>
            <template v-if="!isAuthenticated">
              <router-link 
                to="/login" 
                class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium"
              >
                Login
              </router-link>
              <router-link 
                to="/register" 
                class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium"
              >
                Registro
              </router-link>
            </template>
            <button 
              v-else 
              @click="logout"
                class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium"
            >
              Cerrar Sesión
            </button>
          </div>

          <!-- Botón menú mobile -->
          <button 
            @click="toggleMenu"
            class="lg:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none"
          >
            <svg 
              class="h-6 w-6"
              xmlns="http://www.w3.org/2000/svg" 
              fill="none" 
              viewBox="0 0 24 24" 
              stroke="currentColor"
            >
              <path 
                stroke-linecap="round" 
                stroke-linejoin="round" 
                stroke-width="2" 
                :d="isMenuOpen ? 'M6 18L18 6M6 6l12 12' : 'M4 6h16M4 12h16M4 18h16'"
              />
            </svg>
          </button>
        </div>
      </div>

      <!-- Menú Mobile -->
      <transition
        enter-active-class="transition duration-100 ease-out"
        enter-from-class="transform scale-95 opacity-0"
        enter-to-class="transform scale-100 opacity-100"
        leave-active-class="transition duration-75 ease-in"
        leave-from-class="transform scale-100 opacity-100"
        leave-to-class="transform scale-95 opacity-0"
      >
        <div 
          v-show="isMenuOpen"
          class="lg:hidden absolute top-16 inset-x-0 bg-gray-800 border-t border-gray-700 shadow-lg"
        >
          <div class="px-2 pt-2 pb-3 space-y-1">
            <router-link 
              to="/posts" 
              class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700"
              @click="closeMenu"
            >
              Posts
            </router-link>
            <router-link 
              to="/categories" 
              class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700"
              @click="closeMenu"
            >
              Categorías
            </router-link>
            <template v-if="!isAuthenticated">
              <router-link 
                to="/login" 
                class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700"
                @click="closeMenu"
              >
                Login
              </router-link>
              <router-link 
                to="/register" 
                class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700"
                @click="closeMenu"
              >
                Registro
              </router-link>
            </template>
            <button 
              v-else 
              @click="logout"
              class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700"
            >
              Cerrar Sesión
            </button>
          </div>
        </div>
      </transition>
    </nav>

    <main class="flex-grow container mx-auto px-4 py-8">
      <router-view></router-view>
    </main>
  </div>
</template>

<script>
export default {
  name: 'App',
  data() {
    return {
      isMenuOpen: false
    }
  },
  computed: {
    isAuthenticated() {
      return this.$store.state.auth.isAuthenticated;
    }
  },
  methods: {
    closeMenu() {
      this.isMenuOpen = false;
    },
    toggleMenu() {
      this.isMenuOpen = !this.isMenuOpen;
    },
    logout() {
      this.$store.dispatch('auth/logout');
      this.$router.push('/login');
      this.closeMenu();
    }
  }
}
</script> 