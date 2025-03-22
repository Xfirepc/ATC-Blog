import './bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import Vue from 'vue';
import App from './App.vue';
import router from './router';
import store from './store';

// Verificar autenticación al iniciar la aplicación
const token = localStorage.getItem('token');
if (token) {
  store.dispatch('auth/fetchUser');
}

const app = new Vue({
    router,
    store,
    render: h => h(App)
}).$mount('#app');
