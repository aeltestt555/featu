<template>
  <div class="dashboard-container" :class="{ 'dark-mode': isDarkMode }">
    <!-- Sidebar -->
    <div class="sidebar" :class="{ 'sidebar-collapsed': isSidebarCollapsed }">
      <div class="sidebar-header">
        <h2 v-if="!isSidebarCollapsed">Admin Panel</h2>
        <button class="sidebar-toggle" @click="toggleSidebar">
          <i class="fas" :class="isSidebarCollapsed ? 'fa-bars' : 'fa-times'"></i>
        </button>
      </div>
      <ul class="sidebar-menu">
        <li v-for="(menuItem, index) in menuItems" :key="index">
          <router-link :to="menuItem.path" class="menu-link" active-class="active">
            <i :class="menuItem.icon"></i>
            <span v-if="!isSidebarCollapsed">{{ menuItem.title }}</span>
          </router-link>
        </li>
      </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content" :class="{ 'main-content-collapsed': isSidebarCollapsed }">
      <!-- Header -->
      <header class="header">
        <div class="header-left">
          <button class="sidebar-toggle-mobile" @click="toggleSidebar">
            <i class="fas fa-bars"></i>
          </button>
          <div class="search-bar">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search..." />
          </div>
        </div>
        <div class="header-right">
          <button class="theme-toggle" @click="toggleTheme">
            <i class="fas" :class="isDarkMode ? 'fa-moon' : 'fa-sun'"></i>
          </button>
          <div class="notifications">
            <i class="fas fa-bell"></i>
            <span class="notification-badge">3</span>
          </div>
          <div class="user-profile">
            <img :src="user.avatar || defaultAvatar" alt="User Avatar" class="user-avatar" />
            <div class="user-info">
              <span class="user-name">{{ user.name }}</span>
              <span class="user-role">{{ user.role || 'User' }}</span>
            </div>
          </div>
          <button class="logout-btn" @click="handleLogout" :disabled="isLoading">
            <i class="fas" :class="isLoading ? 'fa-spinner fa-spin' : 'fa-sign-out-alt'"></i>
            <span class="logout-text">Logout</span>
          </button>
        </div>
      </header>

      <!-- Content Area -->
      <main class="content">
        <div class="content-header">
          <h1>Welcome, {{ user.name }}!</h1>
          <p>Here's an overview of your dashboard.</p>
        </div>

        <!-- Stats Cards -->
        <div class="stats-cards">
          <div class="stats-card" v-for="(stat, index) in stats" :key="index">
            <div class="card-icon" :style="{ backgroundColor: stat.color }">
              <i :class="stat.icon"></i>
            </div>
            <div class="card-info">
              <h3>{{ stat.value }}</h3>
              <p>{{ stat.title }}</p>
            </div>
          </div>
        </div>

        <!-- Recent Activity -->
        <div class="recent-activity">
          <div class="activity-header">
            <h2>Recent Activity</h2>
          </div>
          <div class="table-responsive">
            <table>
              <thead>
                <tr>
                  <th>User</th>
                  <th>Action</th>
                  <th>Time</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(activity, index) in recentActivities" :key="index">
                  <td>{{ activity.user }}</td>
                  <td>{{ activity.action }}</td>
                  <td>{{ activity.time }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
  data() {
    return {
      isSidebarCollapsed: false,
      isDarkMode: false,
      isLoading: false,
      defaultAvatar: 'https://picsum.photos/seed/user123/40/40.jpg',
      menuItems: [
        { title: 'Dashboard', path: '/dashboard', icon: 'fas fa-tachometer-alt' },
        { title: 'Projects', path: '/projects', icon: 'fas fa-project-diagram' },
        { title: 'Clients', path: '/clients', icon: 'fas fa-users' },
        { title: 'Reports', path: '/reports', icon: 'fas fa-chart-bar' },
        { title: 'Settings', path: '/settings', icon: 'fas fa-cog' },
      ],
      stats: [
        { title: 'Total Projects', value: 24, icon: 'fas fa-project-diagram', color: '#4e73df' },
        { title: 'Active Clients', value: 12, icon: 'fas fa-users', color: '#1cc88a' },
        { title: 'Pending Tasks', value: 8, icon: 'fas fa-tasks', color: '#f6c23e' },
        { title: 'Revenue', value: '$12,000', icon: 'fas fa-dollar-sign', color: '#36b9cc' },
      ],
      recentActivities: [
        { user: 'John Doe', action: 'Created a new project', time: '2 mins ago' },
        { user: 'Jane Smith', action: 'Updated client details', time: '10 mins ago' },
        { user: 'Admin', action: 'Generated report', time: '1 hour ago' },
      ],
    };
  },
  computed: {
    ...mapGetters('auth', ['user']),
  },
  created() {
    this.$store.dispatch('auth/checkAuth');
  },
  mounted() {
    this.isDarkMode = localStorage.getItem('darkMode') === 'true';
    document.body.classList.toggle('dark-mode', this.isDarkMode);
  },
  methods: {
    ...mapActions('auth', ['logout']),
    toggleSidebar() {
      this.isSidebarCollapsed = !this.isSidebarCollapsed;
    },
    toggleTheme() {
      this.isDarkMode = !this.isDarkMode;
      localStorage.setItem('darkMode', this.isDarkMode);
      document.body.classList.toggle('dark-mode', this.isDarkMode);
    },
    async handleLogout() {
      this.isLoading = true;
      try {
        await this.logout();
        this.$router.push('/login');
      } catch (error) {
        console.error('Logout failed:', error);
      } finally {
        this.isLoading = false;
      }
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
.dashboard-container {
  display: flex;
  min-height: 100vh;
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
  background-color: #f8f9fa;
  transition: background-color 0.3s ease;
  position: relative;
}

/* Sidebar */
.sidebar {
  width: 260px;
  background: linear-gradient(180deg, #2c3e50 0%, #1a252f 100%);
  color: white;
  transition: width 0.3s ease;
  position: fixed;
  height: 100vh;
  z-index: 1000;
  overflow-y: auto;
  overflow-x: hidden;
  box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
  top: 0;
  left: 0;
}

.sidebar::-webkit-scrollbar {
  width: 6px;
}

.sidebar::-webkit-scrollbar-thumb {
  background-color: rgba(255, 255, 255, 0.2);
  border-radius: 3px;
}

.sidebar-collapsed {
  width: 70px;
}

.sidebar-header {
  padding: 24px 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  min-height: 73px;
}

.sidebar-header h2 {
  font-size: 20px;
  font-weight: 700;
  white-space: nowrap;
}

.sidebar-toggle {
  background: rgba(255, 255, 255, 0.1);
  border: none;
  color: white;
  font-size: 18px;
  cursor: pointer;
  padding: 8px 10px;
  border-radius: 6px;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
}

.sidebar-toggle:hover {
  background: rgba(255, 255, 255, 0.2);
}

.sidebar-menu {
  list-style: none;
  padding: 10px 0;
  margin: 0;
}

.sidebar-menu li {
  padding: 0;
}

.menu-link {
  color: rgba(255, 255, 255, 0.8);
  text-decoration: none;
  display: flex;
  align-items: center;
  transition: all 0.3s ease;
  padding: 14px 20px;
  position: relative;
  gap: 12px;
}

.menu-link i {
  font-size: 18px;
  min-width: 20px;
  text-align: center;
  transition: all 0.3s ease;
}

.menu-link span {
  white-space: nowrap;
  font-size: 15px;
  font-weight: 500;
}

.menu-link:hover {
  background: rgba(255, 255, 255, 0.1);
  color: #fff;
}

.menu-link.active {
  background: rgba(52, 152, 219, 0.2);
  color: #fff;
  border-left: 4px solid #3498db;
}

.menu-link.active i {
  color: #3498db;
}

.sidebar-collapsed .menu-link {
  justify-content: center;
  padding: 14px 10px;
}

/* Main Content */
.main-content {
  flex: 1;
  margin-left: 260px;
  transition: margin-left 0.3s ease;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  width: calc(100% - 260px);
}

.main-content-collapsed {
  margin-left: 70px;
  width: calc(100% - 70px);
}

/* Header */
.header {
  background: #fff;
  padding: 18px 30px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
  position: sticky;
  top: 0;
  z-index: 999;
  min-height: 73px;
}

.header-left {
  display: flex;
  align-items: center;
  gap: 20px;
  flex: 1;
}

.sidebar-toggle-mobile {
  background: none;
  border: none;
  font-size: 20px;
  color: #555;
  cursor: pointer;
  padding: 8px;
  border-radius: 6px;
  transition: all 0.3s ease;
  display: none;
}

.sidebar-toggle-mobile:hover {
  background-color: #f8f9fa;
}

.search-bar {
  position: relative;
  flex: 1;
  max-width: 400px;
}

.search-bar input {
  padding: 10px 16px 10px 40px;
  border-radius: 10px;
  border: 2px solid #e0e6ed;
  outline: none;
  width: 100%;
  transition: all 0.3s ease;
  font-size: 14px;
  background-color: #f8f9fa;
}

.search-bar input:focus {
  border-color: #3498db;
  background-color: #fff;
  box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
}

.search-bar i {
  position: absolute;
  left: 14px;
  top: 50%;
  transform: translateY(-50%);
  color: #adb5bd;
  font-size: 14px;
}

.header-right {
  display: flex;
  align-items: center;
  gap: 20px;
}

.theme-toggle,
.notifications {
  background: transparent;
  border: 2px solid #e0e6ed;
  width: 40px;
  height: 40px;
  border-radius: 10px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 18px;
  color: #6c757d;
  transition: all 0.3s ease;
  position: relative;
}

.theme-toggle:hover,
.notifications:hover {
  background-color: #f8f9fa;
  border-color: #3498db;
  color: #3498db;
}

.notifications {
  border: none;
  background: transparent;
}

.notification-badge {
  background: #e74a3b;
  color: white;
  border-radius: 50%;
  padding: 2px 6px;
  font-size: 11px;
  font-weight: 600;
  position: absolute;
  top: -5px;
  right: -5px;
  min-width: 18px;
  text-align: center;
}

.user-profile {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 6px 12px;
  border-radius: 10px;
  transition: all 0.3s ease;
  cursor: pointer;
}

.user-profile:hover {
  background-color: #f8f9fa;
}

.user-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #e0e6ed;
}

.user-info {
  display: flex;
  flex-direction: column;
}

.user-name {
  font-weight: 600;
  font-size: 14px;
  color: #2c3e50;
  line-height: 1.3;
}

.user-role {
  font-size: 12px;
  color: #6c757d;
  line-height: 1.3;
}

.logout-btn {
  background: transparent;
  border: 2px solid #e0e6ed;
  color: #6c757d;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 14px;
  font-weight: 500;
  padding: 10px 16px;
  border-radius: 10px;
  transition: all 0.3s ease;
}

.logout-btn:hover:not(:disabled) {
  background-color: #fee;
  border-color: #e74a3b;
  color: #e74a3b;
}

.logout-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.logout-btn i {
  font-size: 16px;
}

/* Content Area */
.content {
  padding: 30px;
  flex: 1;
}

.content-header {
  margin-bottom: 30px;
}

.content-header h1 {
  font-size: 32px;
  margin-bottom: 8px;
  color: #2c3e50;
  font-weight: 700;
}

.content-header p {
  color: #6c757d;
  font-size: 15px;
  margin: 0;
}

/* Stats Cards */
.stats-cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 24px;
  margin-bottom: 30px;
}

.stats-card {
  background: #fff;
  border-radius: 12px;
  padding: 24px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
  display: flex;
  align-items: center;
  gap: 20px;
  transition: all 0.3s ease;
  border: 1px solid #e0e6ed;
}

.stats-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
}

.card-icon {
  width: 56px;
  height: 56px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 24px;
  flex-shrink: 0;
}

.card-info {
  flex: 1;
}

.card-info h3 {
  font-size: 28px;
  margin: 0 0 4px 0;
  color: #2c3e50;
  font-weight: 700;
}

.card-info p {
  color: #6c757d;
  margin: 0;
  font-size: 14px;
  font-weight: 500;
}

/* Recent Activity */
.recent-activity {
  background: #fff;
  border-radius: 12px;
  padding: 24px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
  margin-bottom: 30px;
  border: 1px solid #e0e6ed;
}

.activity-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.activity-header h2 {
  margin: 0;
  color: #2c3e50;
  font-size: 22px;
  font-weight: 700;
}

.table-responsive {
  overflow-x: auto;
}

table {
  width: 100%;
  border-collapse: collapse;
}

th,
td {
  padding: 14px 16px;
  text-align: left;
  border-bottom: 1px solid #e0e6ed;
}

th {
  background: #f8f9fa;
  font-weight: 600;
  color: #2c3e50;
  font-size: 14px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

td {
  color: #6c757d;
  font-size: 14px;
}

tbody tr {
  transition: all 0.2s ease;
}

tbody tr:hover {
  background-color: #f8f9fa;
}

tbody tr:last-child td {
  border-bottom: none;
}

/* Dark Mode */
.dark-mode {
  background-color: #0f172a;
  color: #e1e8f0;
}

.dark-mode .sidebar {
  background: linear-gradient(180deg, #1a252f 0%, #0f1419 100%);
}

.dark-mode .main-content {
  background: #0f172a;
}

.dark-mode .header {
  background: #1e293b;
  border-bottom: 1px solid #334155;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.dark-mode .search-bar input {
  background: #0f172a;
  border-color: #334155;
  color: #e1e8f0;
}

.dark-mode .search-bar input::placeholder {
  color: #64748b;
}

.dark-mode .search-bar input:focus {
  background: #1e293b;
  border-color: #3498db;
}

.dark-mode .search-bar i {
  color: #64748b;
}

.dark-mode .theme-toggle,
.dark-mode .logout-btn {
  border-color: #334155;
  color: #94a3b8;
}

.dark-mode .theme-toggle:hover {
  background: #0f172a;
  border-color: #3498db;
  color: #3498db;
}

.dark-mode .logout-btn:hover:not(:disabled) {
  background: rgba(231, 74, 59, 0.1);
  border-color: #e74a3b;
  color: #e74a3b;
}

.dark-mode .user-profile:hover {
  background: #0f172a;
}

.dark-mode .user-name {
  color: #e1e8f0;
}

.dark-mode .user-role {
  color: #94a3b8;
}

.dark-mode .user-avatar {
  border-color: #334155;
}

.dark-mode .content-header h1 {
  color: #e1e8f0;
}

.dark-mode .content-header p {
  color: #94a3b8;
}

.dark-mode .stats-card {
  background: #1e293b;
  border-color: #334155;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.dark-mode .card-info h3 {
  color: #e1e8f0;
}

.dark-mode .card-info p {
  color: #94a3b8;
}

.dark-mode .recent-activity {
  background: #1e293b;
  border-color: #334155;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.dark-mode .activity-header h2 {
  color: #e1e8f0;
}

.dark-mode table th {
  background: #0f172a;
  color: #e1e8f0;
  border-color: #334155;
}

.dark-mode table td {
  color: #94a3b8;
  border-color: #334155;
}

.dark-mode tbody tr:hover {
  background-color: #0f172a;
}

/* Responsive Design */
@media (max-width: 1024px) {
  .stats-cards {
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
  }
  
  .content {
    padding: 24px;
  }
}

@media (max-width: 768px) {
  .sidebar {
    transform: translateX(-100%);
  }

  .sidebar:not(.sidebar-collapsed) {
    transform: translateX(0);
  }

  .main-content {
    margin-left: 0;
    width: 100%;
  }

  .main-content-collapsed {
    margin-left: 0;
    width: 100%;
  }

  .sidebar-toggle-mobile {
    display: flex;
  }

  .header {
    padding: 16px 20px;
    flex-wrap: wrap;
    gap: 12px;
  }

  .header-left {
    order: 1;
    width: 100%;
  }

  .header-right {
    order: 2;
    width: 100%;
    justify-content: space-between;
  }

  .search-bar {
    max-width: 100%;
  }

  .user-info {
    display: none;
  }

  .logout-text {
    display: none;
  }

  .stats-cards {
    grid-template-columns: 1fr;
    gap: 16px;
  }

  .content {
    padding: 20px;
  }

  .content-header h1 {
    font-size: 26px;
  }

  .table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }

  table {
    min-width: 600px;
  }
}

@media (max-width: 480px) {
  .header {
    padding: 12px 16px;
  }

  .header-right {
    gap: 12px;
  }

  .theme-toggle,
  .notifications {
    width: 36px;
    height: 36px;
    font-size: 16px;
  }

  .logout-btn {
    padding: 8px 12px;
  }

  .content {
    padding: 16px;
  }

  .content-header h1 {
    font-size: 24px;
  }

  .stats-card {
    padding: 20px;
  }

  .card-icon {
    width: 48px;
    height: 48px;
    font-size: 20px;
  }

  .card-info h3 {
    font-size: 24px;
  }

  .recent-activity {
    padding: 20px 16px;
  }

  .activity-header h2 {
    font-size: 20px;
  }

  th,
  td {
    padding: 12px;
    font-size: 13px;
  }
}
</style>