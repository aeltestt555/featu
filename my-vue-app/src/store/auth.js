// store/modules/auth.js
import axios from 'axios';

const state = {
  user: JSON.parse(localStorage.getItem('user')) || null,
  token: localStorage.getItem('access_token') || null,
  role: JSON.parse(localStorage.getItem('user'))?.roles?.[0]?.slug || null,
  permissions: JSON.parse(localStorage.getItem('user'))?.permissions || [],
};

const getters = {
  isAuthenticated: (state) => !!state.token,
  user: (state) => state.user,
  role: (state) => state.role,
  permissions: (state) => state.permissions,
  isAdmin: (state) => state.role === 'admin',
};

const mutations = {
  SET_TOKEN(state, token) {
    state.token = token;
    localStorage.setItem('access_token', token);
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
  },
  SET_USER(state, user) {
    state.user = { ...user };
    state.role = user.roles?.[0]?.slug; // Assuming the first role is the primary role
    state.permissions = user.permissions || [];
    localStorage.setItem('user', JSON.stringify(user));
  },
  CLEAR_AUTH(state) {
    state.token = null;
    state.user = null;
    state.role = null;
    state.permissions = [];
    localStorage.removeItem('access_token');
    localStorage.removeItem('user');
    delete axios.defaults.headers.common['Authorization'];
  },
};

const actions = {
  async login({ commit }, credentials) {
    try {
      const response = await axios.post('/login', credentials);
      commit('SET_TOKEN', response.data.access_token);
      commit('SET_USER', response.data.user);
      return response;
    } catch (error) {
      console.error('Login error:', error);
      throw error;
    }
  },
  async logout({ commit }) {
    try {
      await axios.post('/logout', {}, {
        headers: { Authorization: `Bearer ${localStorage.getItem('access_token')}` }
      });
    } catch (error) {
      console.error('Logout error:', error);
    } finally {
      commit('CLEAR_AUTH');
    }
  },
  async checkAuth({ commit, state }) {
    const token = localStorage.getItem('access_token');
    if (token && !state.token) {
      commit('SET_TOKEN', token);
      const user = JSON.parse(localStorage.getItem('user'));
      if (user) commit('SET_USER', user);
    }
  },
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};
