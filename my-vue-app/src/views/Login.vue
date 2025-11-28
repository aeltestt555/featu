<template>
  <div class="login-container" :class="{ 'dark-mode': isDarkMode }">
    <div class="login-wrapper">
      <!-- Left Section -->
      <div class="login-left">
        <h1 class="title">Dashboard</h1>
        <p class="subtitle">Trouvez la paix avec une plateforme simple et fluide.</p>
        <button class="left-btn">Commencer</button>
      </div>

      <!-- Right Section -->
      <div class="login-right">
        <div class="login-card">
          <h2>Bienvenue 👋</h2>
          <p class="desc">Connectez-vous pour continuer</p>

          <form @submit.prevent="handleLogin" class="login-form">
            <div class="form-group">
              <input
                v-model="email"
                type="email"
                placeholder="Email"
                required
                class="form-control"
              />
            </div>

            <div class="form-group">
              <input
                v-model="password"
                type="password"
                placeholder="Mot de passe"
                required
                class="form-control"
              />
            </div>

            <button
              type="submit"
              class="submit-btn"
              :disabled="isLoading"
            >
              <span v-if="isLoading">
                <i class="fas fa-spinner fa-spin"></i> Connexion en cours...
              </span>
              <span v-else>Se connecter</span>
            </button>

         <!--   <p class="forgot-password">
              <router-link to="/forgot-password">Mot de passe oublié ?</router-link>
            </p>
            -->

            <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>
          </form>

          <!-- Theme Toggle -->
          <div class="theme-toggle-wrapper">
            <button class="theme-toggle" @click="toggleTheme">
              <i class="fas" :class="isDarkMode ? 'fa-moon' : 'fa-sun'"></i>
              <span>{{ isDarkMode ? 'Light Mode' : 'Dark Mode' }}</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions } from 'vuex';

export default {
  data() {
    return {
      email: '',
      password: '',
      errorMessage: '',
      isLoading: false,
      isDarkMode: false,
    };
  },
  mounted() {
    this.isDarkMode = localStorage.getItem('darkMode') === 'true';
    document.body.classList.toggle('dark-mode', this.isDarkMode);
  },
  methods: {
    ...mapActions('auth', ['login']),
    async handleLogin() {
      this.errorMessage = '';
      this.isLoading = true;

      if (!this.email || !this.password) {
        this.errorMessage = 'Email et mot de passe sont requis';
        this.isLoading = false;
        return;
      }

      try {
        await this.login({
          email: this.email,
          password: this.password,
        });
        this.$router.push('/dashboard');
      } catch (err) {
        console.error('Login error:', err);
        this.errorMessage = err.response?.data?.message || 'Erreur du serveur';
      } finally {
        this.isLoading = false;
      }
    },
    toggleTheme() {
      this.isDarkMode = !this.isDarkMode;
      localStorage.setItem('darkMode', this.isDarkMode);
      document.body.classList.toggle('dark-mode', this.isDarkMode);
    },
  },
};
</script>

<style scoped>
/* Reset */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

/* Base Styles */
.login-container {
  min-height: 100vh;
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
  background-color: #f8f9fa;
  transition: background-color 0.3s ease, color 0.3s ease;
}

.login-wrapper {
  display: flex;
  min-height: 100vh;
}

/* Left Section */
.login-left {
  flex: 1;
  background: linear-gradient(135deg, #2c3e50, #3498db);
  color: #fff;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: flex-start;
  padding: 60px;
}

.login-left .title {
  font-size: 48px;
  font-weight: 700;
  margin-bottom: 20px;
  line-height: 1.2;
}

.login-left .subtitle {
  font-size: 18px;
  margin-bottom: 40px;
  line-height: 1.6;
  opacity: 0.95;
  max-width: 500px;
}

.left-btn {
  background-color: #fff;
  color: #2c3e50;
  padding: 16px 40px;
  border-radius: 30px;
  border: none;
  font-weight: 600;
  font-size: 16px;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.left-btn:hover {
  background-color: #f3f5f7;
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
}

.left-btn:active {
  transform: translateY(0);
}

/* Right Section */
.login-right {
  flex: 1;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #f8f9fa;
  padding: 40px 20px;
}

.login-card {
  width: 100%;
  max-width: 450px;
  padding: 50px 40px;
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
  transition: all 0.3s ease;
}

.login-card h2 {
  font-size: 32px;
  font-weight: 700;
  margin-bottom: 10px;
  color: #2c3e50;
  text-align: center;
}

.login-card .desc {
  color: #6c757d;
  font-size: 15px;
  margin-bottom: 35px;
  text-align: center;
  line-height: 1.5;
}

.login-form {
  width: 100%;
}

.form-group {
  margin-bottom: 24px;
}

.form-control {
  width: 100%;
  padding: 14px 18px;
  border-radius: 10px;
  border: 2px solid #e0e6ed;
  font-size: 15px;
  outline: none;
  transition: all 0.3s ease;
  background-color: #f8f9fa;
  color: #2c3e50;
}

.form-control::placeholder {
  color: #adb5bd;
}

.form-control:focus {
  border-color: #3498db;
  background-color: #fff;
  box-shadow: 0 0 0 4px rgba(52, 152, 219, 0.1);
}

.submit-btn {
  width: 100%;
  background: linear-gradient(135deg, #2c3e50, #3498db);
  color: #fff;
  padding: 14px 20px;
  border-radius: 10px;
  font-size: 16px;
  font-weight: 600;
  border: none;
  cursor: pointer;
  transition: all 0.3s ease;
  margin-top: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}

.submit-btn:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(52, 152, 219, 0.3);
}

.submit-btn:active:not(:disabled) {
  transform: translateY(0);
}

.submit-btn:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.forgot-password {
  text-align: center;
  margin-top: 20px;
  margin-bottom: 0;
}

.forgot-password a {
  color: #3498db;
  text-decoration: none;
  font-size: 14px;
  font-weight: 500;
  transition: all 0.3s ease;
}

.forgot-password a:hover {
  color: #2c3e50;
  text-decoration: underline;
}

.error-message {
  color: #e74a3b;
  background-color: #fee;
  border: 1px solid #e74a3b;
  font-size: 14px;
  text-align: center;
  margin-top: 20px;
  padding: 12px 16px;
  border-radius: 8px;
}

.theme-toggle-wrapper {
  margin-top: 30px;
  padding-top: 25px;
  border-top: 1px solid #e0e6ed;
  display: flex;
  justify-content: center;
}

.theme-toggle {
  background: transparent;
  border: 2px solid #e0e6ed;
  padding: 10px 20px;
  border-radius: 25px;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 14px;
  font-weight: 500;
  color: #6c757d;
  transition: all 0.3s ease;
}

.theme-toggle:hover {
  background-color: #f8f9fa;
  border-color: #3498db;
  color: #3498db;
}

.theme-toggle i {
  font-size: 16px;
}

/* Dark Mode */
.dark-mode {
  background-color: #0f172a;
}

.dark-mode .login-right {
  background-color: #0f172a;
}

.dark-mode .login-card {
  background-color: #1e293b;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4);
}

.dark-mode .login-card h2 {
  color: #e1e8f0;
}

.dark-mode .login-card .desc {
  color: #94a3b8;
}

.dark-mode .form-control {
  background-color: #0f172a;
  border-color: #334155;
  color: #e1e8f0;
}

.dark-mode .form-control::placeholder {
  color: #64748b;
}

.dark-mode .form-control:focus {
  background-color: #1e293b;
  border-color: #3498db;
  box-shadow: 0 0 0 4px rgba(52, 152, 219, 0.2);
}

.dark-mode .theme-toggle-wrapper {
  border-top-color: #334155;
}

.dark-mode .theme-toggle {
  border-color: #334155;
  color: #94a3b8;
}

.dark-mode .theme-toggle:hover {
  background-color: #0f172a;
  border-color: #3498db;
  color: #3498db;
}

.dark-mode .error-message {
  background-color: rgba(231, 74, 59, 0.1);
  border-color: #e74a3b;
}

/* Responsive Design */
@media (max-width: 1024px) {
  .login-left {
    padding: 40px;
  }
  
  .login-left .title {
    font-size: 40px;
  }
  
  .login-left .subtitle {
    font-size: 16px;
  }
}

@media (max-width: 768px) {
  .login-wrapper {
    flex-direction: column;
  }

  .login-left {
    min-height: 50vh;
    padding: 40px 30px;
    align-items: center;
    text-align: center;
  }
  
  .login-left .title {
    font-size: 36px;
  }
  
  .login-left .subtitle {
    font-size: 16px;
    max-width: 100%;
  }

  .login-right {
    min-height: 50vh;
    padding: 30px 20px;
  }

  .login-card {
    padding: 40px 30px;
    max-width: 100%;
  }
  
  .login-card h2 {
    font-size: 28px;
  }
}

@media (max-width: 480px) {
  .login-left {
    padding: 30px 20px;
    min-height: 40vh;
  }
  
  .login-left .title {
    font-size: 32px;
  }
  
  .login-left .subtitle {
    font-size: 15px;
    margin-bottom: 30px;
  }
  
  .left-btn {
    padding: 14px 32px;
    font-size: 15px;
  }

  .login-card {
    padding: 30px 20px;
  }
  
  .login-card h2 {
    font-size: 26px;
  }
  
  .form-control {
    padding: 12px 16px;
    font-size: 14px;
  }
  
  .submit-btn {
    padding: 12px 18px;
    font-size: 15px;
  }
}

@media (max-width: 360px) {
  .login-left .title {
    font-size: 28px;
  }
  
  .login-card {
    padding: 25px 15px;
  }
}
</style>