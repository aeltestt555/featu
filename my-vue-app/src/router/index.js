// src/router/index.js
import { createRouter, createWebHistory } from 'vue-router';
import DefaultLayout from '../views/layout/DefaultLayout.vue';
import Login from '../views/Login.vue';
import Dashboard from '../views/Dashboard.vue';
import Projects from '../views/Projects.vue';
import Clients from '../views/Clients.vue';
import Reports from '../views/Reports.vue';
import Settings from '../views/Settings.vue';
import NotFound from '../views/NotFound.vue';
import Unauthorized from '../views/Unauthorized.vue';
import store from '../store';

// ⭐ IMPORT ROLE CHECKER FROM CONFIG
import { hasRouteAccess } from '../config/roles';

const routes = [
  // Routes without layout (guest routes)
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: { requiresGuest: true }
  },
  {
    path: '/register',
    name: 'register',
    component: () => import('../views/Register.vue'),
    meta: { requiresGuest: true }
  },
  
  // Unauthorized access page
  {
    path: '/unauthorized',
    name: 'unauthorized',
    component: Unauthorized,
    meta: { requiresAuth: true }
  },
  
  // Routes with DefaultLayout (authenticated routes)
  {
    path: '/',
    component: DefaultLayout,
    meta: { requiresAuth: true },
    children: [
      {
        path: '',
        redirect: '/dashboard'
      },
      {
        path: 'dashboard',
        name: 'dashboard',
        component: Dashboard,
        meta: { 
          requiresAuth: true,
          routeKey: 'dashboard'  // Key must match in config/roles.js
        }
      },
      {
        path: 'projects',
        name: 'projects',
        component: Projects,
        meta: { 
          requiresAuth: true,
          routeKey: 'projects'
        }
      },
      {
        path: 'clients',
        name: 'clients',
        component: Clients,
        meta: { 
          requiresAuth: true,
          routeKey: 'clients'
        }
      },
      {
        path: 'reports',
        name: 'reports',
        component: Reports,
        meta: { 
          requiresAuth: true,
          routeKey: 'reports'
        }
      },
      {
        path: 'settings',
        name: 'settings',
        component: Settings,
        meta: { 
          requiresAuth: true,
          routeKey: 'settings'
        }
      },
    ]
  },
  
  // 404 route - must be last
  {
    path: '/:pathMatch(.*)*',
    name: 'notfound',
    component: NotFound
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Navigation guard for authentication and role-based access
router.beforeEach(async (to, from, next) => {
  const isAuthenticated = !!localStorage.getItem('access_token');
  
  // Ensure auth state is loaded from localStorage if user is authenticated
  if (isAuthenticated && !store.getters['auth/user']) {
    await store.dispatch('auth/checkAuth');
  }
  
  const userRole = store.getters['auth/role'];

  // Handle guest-only routes (login, register)
  if (to.matched.some(record => record.meta.requiresGuest)) {
    if (isAuthenticated) {
      return next('/dashboard');
    }
    return next();
  }
  
  // Handle protected routes
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!isAuthenticated) {
      return next('/login');
    }
    
    // ⭐ CHECK ROLE-BASED ACCESS USING config/roles.js
    const routeKey = to.meta.routeKey;
    
    if (routeKey) {
      const hasAccess = hasRouteAccess(routeKey, userRole);
      
      if (!hasAccess) {
        console.warn(`Access denied: User role '${userRole}' cannot access '${routeKey}'`);
        return next('/unauthorized');
      }
    }
    
    return next();
  }
  
  // Public routes
  next();
});

export default router;