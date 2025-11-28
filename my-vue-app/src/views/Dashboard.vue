<template>
  <div class="dashboard-page">
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
  </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
  name: 'Dashboard',
  data() {
    return {
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
};
</script>

<style scoped>
/* Content Header */
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
:deep(.dark-mode) .content-header h1 {
  color: #e1e8f0;
}

:deep(.dark-mode) .content-header p {
  color: #94a3b8;
}

:deep(.dark-mode) .stats-card {
  background: #1e293b;
  border-color: #334155;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

:deep(.dark-mode) .card-info h3 {
  color: #e1e8f0;
}

:deep(.dark-mode) .card-info p {
  color: #94a3b8;
}

:deep(.dark-mode) .recent-activity {
  background: #1e293b;
  border-color: #334155;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

:deep(.dark-mode) .activity-header h2 {
  color: #e1e8f0;
}

:deep(.dark-mode) table th {
  background: #0f172a;
  color: #e1e8f0;
  border-color: #334155;
}

:deep(.dark-mode) table td {
  color: #94a3b8;
  border-color: #334155;
}

:deep(.dark-mode) tbody tr:hover {
  background-color: #0f172a;
}

/* Responsive Design */
@media (max-width: 1024px) {
  .stats-cards {
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
  }
}

@media (max-width: 768px) {
  .stats-cards {
    grid-template-columns: 1fr;
    gap: 16px;
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