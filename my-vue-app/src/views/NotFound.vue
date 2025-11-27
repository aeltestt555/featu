<template>
  <div class="not-found-container" :class="{ 'dark-mode': isDarkMode }">
    <div class="not-found-wrapper">
      <!-- Left Section -->
      <div class="not-found-left">
        <h1 class="title">404</h1>
        <p class="subtitle">Oops! Page not found.</p>
        <button class="left-btn" @click="goHome">Return to Home</button>
      </div>

      <!-- Right Section -->
      <div class="not-found-right">
        <div class="not-found-card">
          <h2>Page Not Found</h2>
          <p class="desc">The page you're looking for doesn't exist or has been moved.</p>

          <div class="search-section">
            <div class="search-bar">
              <i class="fas fa-search"></i>
              <input type="text" placeholder="Search our site..." v-model="searchQuery" />
            </div>
          </div>

          <div class="suggestions">
            <h3>You might be looking for:</h3>
            <ul>
              <li v-for="(suggestion, index) in suggestions" :key="index">
                <router-link :to="suggestion.path">{{ suggestion.title }}</router-link>
              </li>
            </ul>
          </div>

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
export default {
  data() {
    return {
      isDarkMode: false,
      searchQuery: '',
      suggestions: [
        { title: 'Dashboard', path: '/dashboard' },
        { title: 'Projects', path: '/dashboard/projects' },
        { title: 'Clients', path: '/dashboard/clients' },
        { title: 'Reports', path: '/dashboard/reports' },
        { title: 'Settings', path: '/dashboard/settings' },
      ],
    };
  },
  mounted() {
    this.isDarkMode = localStorage.getItem('darkMode') === 'true';
    document.body.classList.toggle('dark-mode', this.isDarkMode);
  },
  methods: {
    toggleTheme() {
      this.isDarkMode = !this.isDarkMode;
      localStorage.setItem('darkMode', this.isDarkMode);
      document.body.classList.toggle('dark-mode', this.isDarkMode);
    },
    goHome() {
      this.$router.push('/dashboard');
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
.not-found-container {
  min-height: 100vh;
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
  background-color: #f8f9fa;
  transition: background-color 0.3s ease;
}

.not-found-wrapper {
  display: flex;
  min-height: 100vh;
}

/* Left Section */
.not-found-left {
  flex: 1;
  background: linear-gradient(135deg, #2c3e50, #3498db);
  color: #fff;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: flex-start;
  padding: 60px;
}

.not-found-left .title {
  font-size: 120px;
  font-weight: 900;
  margin-bottom: 20px;
  line-height: 1;
  opacity: 0.9;
}

.not-found-left .subtitle {
  font-size: 24px;
  margin-bottom: 40px;
  line-height: 1.4;
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
.not-found-right {
  flex: 1;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #f8f9fa;
  padding: 40px 20px;
}

.not-found-card {
  width: 100%;
  max-width: 450px;
  padding: 50px 40px;
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
  transition: all 0.3s ease;
}

.not-found-card h2 {
  font-size: 32px;
  font-weight: 700;
  margin-bottom: 10px;
  color: #2c3e50;
  text-align: center;
}

.not-found-card .desc {
  color: #6c757d;
  font-size: 15px;
  margin-bottom: 35px;
  text-align: center;
  line-height: 1.5;
}

/* Search Section */
.search-section {
  margin-bottom: 30px;
}

.search-bar {
  position: relative;
  width: 100%;
}

.search-bar input {
  width: 100%;
  padding: 14px 18px 14px 45px;
  border-radius: 10px;
  border: 2px solid #e0e6ed;
  font-size: 15px;
  outline: none;
  transition: all 0.3s ease;
  background-color: #f8f9fa;
  color: #2c3e50;
}

.search-bar input:focus {
  border-color: #3498db;
  background-color: #fff;
  box-shadow: 0 0 0 4px rgba(52, 152, 219, 0.1);
}

.search-bar i {
  position: absolute;
  left: 18px;
  top: 50%;
  transform: translateY(-50%);
  color: #adb5bd;
  font-size: 14px;
}

/* Suggestions */
.suggestions {
  margin-top: 30px;
}

.suggestions h3 {
  font-size: 16px;
  font-weight: 600;
  color: #2c3e50;
  margin-bottom: 15px;
}

.suggestions ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.suggestions li {
  margin-bottom: 10px;
}

.suggestions a {
  color: #3498db;
  text-decoration: none;
  font-size: 14px;
  font-weight: 500;
  transition: all 0.3s ease;
  display: block;
  padding: 8px 0;
  border-bottom: 1px solid #f0f2f5;
}

.suggestions a:hover {
  color: #2c3e50;
  text-decoration: underline;
}

/* Theme Toggle */
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

.dark-mode .not-found-right {
  background-color: #0f172a;
}

.dark-mode .not-found-card {
  background-color: #1e293b;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4);
}

.dark-mode .not-found-card h2 {
  color: #e1e8f0;
}

.dark-mode .not-found-card .desc {
  color: #94a3b8;
}

.dark-mode .search-bar input {
  background-color: #0f172a;
  border-color: #334155;
  color: #e1e8f0;
}

.dark-mode .search-bar input::placeholder {
  color: #64748b;
}

.dark-mode .search-bar input:focus {
  background-color: #1e293b;
  border-color: #3498db;
}

.dark-mode .search-bar i {
  color: #64748b;
}

.dark-mode .suggestions h3 {
  color: #e1e8f0;
}

.dark-mode .suggestions a {
  color: #64748b;
  border-bottom-color: #334155;
}

.dark-mode .suggestions a:hover {
  color: #e1e8f0;
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

/* Responsive Design */
@media (max-width: 1024px) {
  .not-found-left {
    padding: 40px;
  }

  .not-found-left .title {
    font-size: 100px;
  }

  .not-found-left .subtitle {
    font-size: 20px;
  }
}

@media (max-width: 768px) {
  .not-found-wrapper {
    flex-direction: column;
  }

  .not-found-left {
    min-height: 50vh;
    padding: 40px 30px;
    align-items: center;
    text-align: center;
  }

  .not-found-left .title {
    font-size: 80px;
  }

  .not-found-left .subtitle {
    font-size: 18px;
    max-width: 100%;
  }

  .not-found-right {
    min-height: 50vh;
    padding: 30px 20px;
  }

  .not-found-card {
    padding: 40px 30px;
    max-width: 100%;
  }

  .not-found-card h2 {
    font-size: 28px;
  }
}

@media (max-width: 480px) {
  .not-found-left {
    padding: 30px 20px;
    min-height: 40vh;
  }

  .not-found-left .title {
    font-size: 60px;
  }

  .not-found-left .subtitle {
    font-size: 16px;
    margin-bottom: 30px;
  }

  .left-btn {
    padding: 14px 32px;
    font-size: 15px;
  }

  .not-found-card {
    padding: 30px 20px;
  }

  .not-found-card h2 {
    font-size: 24px;
  }

  .search-bar input {
    padding: 12px 16px 12px 40px;
    font-size: 14px;
  }
}
</style>
