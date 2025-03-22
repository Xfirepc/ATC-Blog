import Vue from 'vue';
import VueRouter from 'vue-router';
import store from '../store';

Vue.use(VueRouter);

const routes = [
  {
    path: '/',
    name: 'home',
    component: () => import('../views/Home.vue')
  },
  {
    path: '/login',
    name: 'login',
    component: () => import('../views/auth/Login.vue'),
    meta: { guest: true }
  },
  {
    path: '/register',
    name: 'register',
    component: () => import('../views/auth/Register.vue'),
    meta: { guest: true }
  },
  {
    path: '/posts',
    name: 'posts',
    component: () => import('../views/posts/Index.vue')
  },
  {
    path: '/posts/create',
    name: 'posts.create',
    component: () => import('../views/posts/Create.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/posts/:id',
    name: 'posts.show',
    component: () => import('../views/posts/Show.vue')
  },
  {
    path: '/posts/:id/edit',
    name: 'posts.edit',
    component: () => import('../views/posts/Edit.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/categories',
    name: 'categories',
    component: () => import('../views/categories/Index.vue')
  },
  {
    path: '/categories/:id',
    name: 'categories.posts',
    component: () => import('../views/categories/Posts.vue')
  }
];

const router = new VueRouter({
  mode: 'history',
  // base: process.env.BASE_URL,
  routes
});

router.beforeEach((to, from, next) => {
  console.log('Navegando a:', to.path);
  const isAuthenticated = store.state.auth.isAuthenticated;

  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!isAuthenticated) {
      next('/login');
    } else {
      next();
    }
  } else if (to.matched.some(record => record.meta.guest)) {
    if (isAuthenticated) {
      next('/');
    } else {
      next();
    }
  } else {
    next();
  }
});

export default router; 