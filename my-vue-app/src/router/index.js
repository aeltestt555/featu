import { createRouter, createWebHistory } from 'vue-router';
import Login from '../views/Login.vue';
import Dashboard from '../views/Dashboard.vue'; // Ensure this path is correct
import store from '../store';
import NotFound from '../views/NotFound.vue';

const routes = [
  { path: '/login', component: Login, meta: { requiresGuest: true } },
  { path: '/dashboard', component: Dashboard, meta: { requiresAuth: true } },
  { path: '/', redirect: '/dashboard' },
  { path: '/:pathMatch(.*)*', component: NotFound } 
  // Add other routes as needed
];

// const router = createWebHistory();

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach(async (to, from, next) => {
  const isAuthenticated = !!localStorage.getItem('access_token');

  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!isAuthenticated) {
      next('/login');
    } else {
      // Ensure Vuex state is updated before proceeding
      await store.dispatch('auth/checkAuth');
      next();
    }
  } else if (to.matched.some(record => record.meta.requiresGuest)) {
    if (isAuthenticated) {
      next('/dashboard');
    } else {
      next();
    }
  } else {
    next();
  }
});

export default router;
