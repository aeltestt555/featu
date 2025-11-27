import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import store from './store';
import axios from 'axios';
import './style.css';


// Configure Axios globally
axios.defaults.baseURL = 'http://127.0.0.1:8000/api';
axios.defaults.headers.common['Content-Type'] = 'application/json';
axios.defaults.headers.common['Accept'] = 'application/json';

// Intercept requests to add the token
axios.interceptors.request.use(config => {
  const token = localStorage.getItem('access_token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
}, error => {
  return Promise.reject(error);
});

// Intercept responses to handle token expiration
axios.interceptors.response.use(response => {
  return response;
}, error => {
  if (error.response && error.response.status === 401) {
    store.dispatch('auth/CLEAR_AUTH');
    router.push('/login');
  }
  return Promise.reject(error);
});

const app = createApp(App);
app.use(router);
app.use(store);

store.dispatch('auth/checkAuth');

app.mount('#app');
