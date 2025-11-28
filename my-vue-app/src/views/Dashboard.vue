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

    <!-- Charts Section -->
    <div class="charts-container">
      <!-- First Row: Bar Chart and Line Chart -->
      <div class="charts-row">
        <div class="chart-container">
          <div class="chart-header">
            <h2>Monthly Revenue</h2>
            <div class="chart-actions">
              <button 
                v-for="period in periods" 
                :key="period" 
                @click="selectedPeriod = period"
                :class="{ active: selectedPeriod === period }"
                class="period-btn"
              >
                {{ period }}
              </button>
            </div>
          </div>
          <div class="chart-wrapper">
            <Bar 
              v-if="barChartData && barChartData.labels"
              :data="barChartData" 
              :options="barChartOptions" 
            />
          </div>
        </div>

        <div class="chart-container">
          <div class="chart-header">
            <h2>User Activity</h2>
            <div class="chart-actions">
              <button 
                @click="toggleLineDataset" 
                class="toggle-btn"
              >
                {{ showActiveUsers ? 'Show All Users' : 'Show Active Users' }}
              </button>
            </div>
          </div>
          <div class="chart-wrapper">
            <Line 
              v-if="lineChartData && lineChartData.labels"
              :data="lineChartData" 
              :options="lineChartOptions" 
            />
          </div>
        </div>
      </div>

      <!-- Second Row: Doughnut Chart and Radar Chart -->
      <div class="charts-row">
        <div class="chart-container">
          <div class="chart-header">
            <h2>Project Distribution</h2>
            <div class="chart-actions">
              <button @click="refreshDoughnutData" class="refresh-btn">
                <i class="fas fa-sync-alt"></i> Refresh
              </button>
            </div>
          </div>
          <div class="chart-wrapper">
            <Doughnut 
              v-if="doughnutChartData && doughnutChartData.labels"
              :data="doughnutChartData" 
              :options="doughnutChartOptions" 
            />
          </div>
        </div>

        <div class="chart-container">
          <div class="chart-header">
            <h2>Skills Assessment</h2>
            <div class="chart-actions">
              <select v-model="selectedTeam" @change="updateRadarData" class="team-select">
                <option value="team1">Development Team</option>
                <option value="team2">Design Team</option>
                <option value="team3">Marketing Team</option>
              </select>
            </div>
          </div>
          <div class="chart-wrapper">
            <Radar 
              v-if="radarChartData && radarChartData.labels"
              :data="radarChartData" 
              :options="radarChartOptions" 
            />
          </div>
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
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  BarElement,
  Title,
  Tooltip,
  Legend,
  ArcElement,
  RadialLinearScale,
  Filler
} from 'chart.js';
import { Bar, Line, Doughnut, Radar } from 'vue-chartjs';

// Register Chart.js components
ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  BarElement,
  Title,
  Tooltip,
  Legend,
  ArcElement,
  RadialLinearScale,
  Filler
);

export default {
  name: 'Dashboard',
  components: {
    Bar,
    Line,
    Doughnut,
    Radar
  },
  data() {
    return {
      selectedPeriod: 'Monthly',
      periods: ['Daily', 'Weekly', 'Monthly', 'Yearly'],
      showActiveUsers: true,
      selectedTeam: 'team1',
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
      // Initialize chart data as null to prevent undefined errors
      barChartData: null,
      lineChartData: null,
      doughnutChartData: null,
      radarChartData: null
    };
  },
  computed: {
    ...mapGetters('auth', ['user']),
  },
  created() {
    // Initialize chart data in the created lifecycle hook
    this.initializeChartData();
  },
  methods: {
    initializeChartData() {
      // Initialize Bar Chart Data
      this.barChartData = {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [
          {
            label: 'Revenue',
            backgroundColor: '#4e73df',
            borderColor: '#4e73df',
            borderWidth: 1,
            data: [12000, 19000, 15000, 25000, 22000, 30000],
            borderRadius: 5,
            barPercentage: 0.6
          },
          {
            label: 'Expenses',
            backgroundColor: '#e74a3b',
            borderColor: '#e74a3b',
            borderWidth: 1,
            data: [8000, 12000, 10000, 14000, 13000, 17000],
            borderRadius: 5,
            barPercentage: 0.6
          }
        ]
      };

      // Initialize Line Chart Data
      this.lineChartData = {
        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        datasets: [
          {
            label: 'Active Users',
            backgroundColor: 'rgba(28, 200, 138, 0.2)',
            borderColor: '#1cc88a',
            borderWidth: 2,
            pointBackgroundColor: '#1cc88a',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: '#1cc88a',
            pointRadius: 4,
            pointHoverRadius: 6,
            fill: true,
            tension: 0.4,
            data: [65, 78, 90, 81, 86, 95, 88]
          },
          {
            label: 'New Users',
            backgroundColor: 'rgba(246, 194, 62, 0.2)',
            borderColor: '#f6c23e',
            borderWidth: 2,
            pointBackgroundColor: '#f6c23e',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: '#f6c23e',
            pointRadius: 4,
            pointHoverRadius: 6,
            fill: true,
            tension: 0.4,
            data: [28, 48, 40, 59, 66, 57, 70]
          }
        ]
      };

      // Initialize Doughnut Chart Data
      this.doughnutChartData = {
        labels: ['Web Development', 'Mobile Apps', 'UI/UX Design', 'Marketing', 'Other'],
        datasets: [
          {
            backgroundColor: [
              '#4e73df',
              '#1cc88a',
              '#36b9cc',
              '#f6c23e',
              '#e74a3b'
            ],
            hoverBackgroundColor: [
              '#2e59d9',
              '#17a673',
              '#2c9faf',
              '#f4b619',
              '#c0392b'
            ],
            borderWidth: 1,
            borderColor: '#fff',
            hoverOffset: 4,
            data: [30, 25, 20, 15, 10]
          }
        ]
      };

      // Initialize Radar Chart Data
      this.radarChartData = {
        labels: ['Technical Skills', 'Communication', 'Problem Solving', 'Teamwork', 'Creativity', 'Time Management'],
        datasets: [
          {
            label: 'Current',
            backgroundColor: 'rgba(78, 115, 223, 0.2)',
            borderColor: '#4e73df',
            borderWidth: 2,
            pointBackgroundColor: '#4e73df',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: '#4e73df',
            pointRadius: 4,
            pointHoverRadius: 6,
            data: [85, 75, 90, 80, 70, 88]
          },
          {
            label: 'Target',
            backgroundColor: 'rgba(28, 200, 138, 0.2)',
            borderColor: '#1cc88a',
            borderWidth: 2,
            pointBackgroundColor: '#1cc88a',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: '#1cc88a',
            pointRadius: 4,
            pointHoverRadius: 6,
            data: [95, 85, 95, 90, 85, 92]
          }
        ]
      };
    },
    toggleLineDataset() {
      this.showActiveUsers = !this.showActiveUsers;
      if (this.showActiveUsers) {
        this.lineChartData.datasets = [
          {
            label: 'Active Users',
            backgroundColor: 'rgba(28, 200, 138, 0.2)',
            borderColor: '#1cc88a',
            borderWidth: 2,
            pointBackgroundColor: '#1cc88a',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: '#1cc88a',
            pointRadius: 4,
            pointHoverRadius: 6,
            fill: true,
            tension: 0.4,
            data: [65, 78, 90, 81, 86, 95, 88]
          },
          {
            label: 'New Users',
            backgroundColor: 'rgba(246, 194, 62, 0.2)',
            borderColor: '#f6c23e',
            borderWidth: 2,
            pointBackgroundColor: '#f6c23e',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: '#f6c23e',
            pointRadius: 4,
            pointHoverRadius: 6,
            fill: true,
            tension: 0.4,
            data: [28, 48, 40, 59, 66, 57, 70]
          }
        ];
      } else {
        this.lineChartData.datasets = [
          {
            label: 'Total Users',
            backgroundColor: 'rgba(54, 185, 204, 0.2)',
            borderColor: '#36b9cc',
            borderWidth: 2,
            pointBackgroundColor: '#36b9cc',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: '#36b9cc',
            pointRadius: 4,
            pointHoverRadius: 6,
            fill: true,
            tension: 0.4,
            data: [120, 135, 150, 142, 158, 165, 170]
          }
        ];
      }
    },
    refreshDoughnutData() {
      // Generate new random data for the doughnut chart
      const newData = [
        Math.floor(Math.random() * 30) + 20,
        Math.floor(Math.random() * 30) + 15,
        Math.floor(Math.random() * 30) + 10,
        Math.floor(Math.random() * 30) + 5,
        Math.floor(Math.random() * 30) + 5
      ];
      
      // Normalize to percentages
      const sum = newData.reduce((a, b) => a + b, 0);
      const percentages = newData.map(val => Math.round((val / sum) * 100));
      
      this.doughnutChartData.datasets[0].data = percentages;
    },
    updateRadarData() {
      // Generate different data based on selected team
      let newData;
      
      switch(this.selectedTeam) {
        case 'team1': // Development Team
          newData = [
            [85, 75, 90, 80, 70, 88],
            [95, 85, 95, 90, 85, 92]
          ];
          break;
        case 'team2': // Design Team
          newData = [
            [65, 85, 75, 90, 95, 78],
            [80, 95, 85, 95, 98, 85]
          ];
          break;
        case 'team3': // Marketing Team
          newData = [
            [70, 90, 80, 85, 88, 92],
            [85, 95, 90, 92, 95, 95]
          ];
          break;
      }
      
      this.radarChartData.datasets[0].data = newData[0];
      this.radarChartData.datasets[1].data = newData[1];
    }
  },
  watch: {
    selectedPeriod() {
      // Update bar chart data based on selected period
      let labels, data1, data2;
      
      switch(this.selectedPeriod) {
        case 'Daily':
          labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
          data1 = [1200, 1900, 1500, 2500, 2200, 3000, 2800];
          data2 = [800, 1200, 1000, 1400, 1300, 1700, 1500];
          break;
        case 'Weekly':
          labels = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
          data1 = [12000, 19000, 15000, 25000];
          data2 = [8000, 12000, 10000, 14000];
          break;
        case 'Monthly':
          labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
          data1 = [12000, 19000, 15000, 25000, 22000, 30000];
          data2 = [8000, 12000, 10000, 14000, 13000, 17000];
          break;
        case 'Yearly':
          labels = ['2019', '2020', '2021', '2022', '2023'];
          data1 = [120000, 190000, 150000, 250000, 300000];
          data2 = [80000, 120000, 100000, 140000, 170000];
          break;
      }
      
      this.barChartData.labels = labels;
      this.barChartData.datasets[0].data = data1;
      this.barChartData.datasets[1].data = data2;
    }
  }
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

/* Charts Section */
.charts-container {
  margin-bottom: 30px;
}

.charts-row {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
  gap: 24px;
  margin-bottom: 24px;
}

.chart-container {
  background: #fff;
  border-radius: 12px;
  padding: 24px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
  border: 1px solid #e0e6ed;
  transition: all 0.3s ease;
}

.chart-container:hover {
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
}

.chart-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.chart-header h2 {
  margin: 0;
  color: #2c3e50;
  font-size: 20px;
  font-weight: 700;
}

.chart-actions {
  display: flex;
  gap: 10px;
}

.period-btn, .toggle-btn, .refresh-btn {
  padding: 6px 12px;
  border-radius: 6px;
  border: 1px solid #e0e6ed;
  background: #fff;
  color: #6c757d;
  font-size: 12px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s ease;
}

.period-btn:hover, .toggle-btn:hover, .refresh-btn:hover {
  background: #f8f9fa;
  border-color: #4e73df;
  color: #4e73df;
}

.period-btn.active {
  background: #4e73df;
  border-color: #4e73df;
  color: #fff;
}

.team-select {
  padding: 6px 12px;
  border-radius: 6px;
  border: 1px solid #e0e6ed;
  background: #fff;
  color: #6c757d;
  font-size: 12px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s ease;
}

.team-select:focus {
  outline: none;
  border-color: #4e73df;
  box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.1);
}

.chart-wrapper {
  position: relative;
  height: 300px;
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

:deep(.dark-mode) .chart-container {
  background: #1e293b;
  border-color: #334155;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

:deep(.dark-mode) .chart-header h2 {
  color: #e1e8f0;
}

:deep(.dark-mode) .period-btn, 
:deep(.dark-mode) .toggle-btn, 
:deep(.dark-mode) .refresh-btn {
  background: #0f172a;
  border-color: #334155;
  color: #94a3b8;
}

:deep(.dark-mode) .period-btn:hover, 
:deep(.dark-mode) .toggle-btn:hover, 
:deep(.dark-mode) .refresh-btn:hover {
  background: #1e293b;
  border-color: #4e73df;
  color: #4e73df;
}

:deep(.dark-mode) .period-btn.active {
  background: #4e73df;
  border-color: #4e73df;
  color: #fff;
}

:deep(.dark-mode) .team-select {
  background: #0f172a;
  border-color: #334155;
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
  
  .charts-row {
    grid-template-columns: 1fr;
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
  
  .chart-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
  }
  
  .chart-actions {
    width: 100%;
    justify-content: flex-end;
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

  .chart-container {
    padding: 20px 16px;
  }

  .chart-header h2 {
    font-size: 18px;
  }
  
  .period-btn, .toggle-btn, .refresh-btn {
    font-size: 11px;
    padding: 5px 10px;
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