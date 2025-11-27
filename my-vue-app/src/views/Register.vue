<!-- Register.vue -->
<template>
  <form @submit.prevent="register" class="register-form">
    <h2>Register</h2>
    <input v-model="name" type="text" placeholder="Name" required>
    <input v-model="email" type="email" placeholder="Email" required>
    <input v-model="password" type="password" placeholder="Password" required>
    <button type="submit">Register</button>
  </form>
</template>

<script>
import { mapActions } from 'vuex';

export default {
  data() {
    return {
      name: '',
      email: '',
      password: '',
      errorMessage: '',
      isLoading: false,
    };
  },
  methods: {
    ...mapActions('auth', ['register']),
    async handleRegister() {
      this.errorMessage = '';
      this.isLoading = true;

      if (!this.name || !this.email || !this.password) {
        this.errorMessage = 'Tous les champs sont requis';
        this.isLoading = false;
        return;
      }

      try {
        await this.register({
          name: this.name,
          email: this.email,
          password: this.password,
        });
        this.$router.push('/dashboard');
      } catch (err) {
        console.error('Registration error:', err);
        this.errorMessage = err.response?.data?.message || 'Erreur lors de l\'inscription';
      } finally {
        this.isLoading = false;
      }
    },
  },
};
</script>
<style scoped>
.register-form {
  max-width: 400px;
  margin: 0 auto;
  padding: 20px;
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
.register-form h2 {
  margin-bottom: 20px;
  text-align: center;
}
.register-form input {
  display: block;
  width: 100%;
  padding: 10px;
  margin-bottom: 10px;
  border: 1px solid #ddd;
  border-radius: 4px;
}
.register-form button {
  display: block;
  width: 100%;
  padding: 10px;
  background: #0a64de;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}
</style>
