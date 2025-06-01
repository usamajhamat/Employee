<template>
    <pre>{{ dashboardAnalyticsData }}</pre>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
        <div class="bg-gradient-to-r from-teal-600 to-cyan-600 px-8 py-6 rounded-t-xl">
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center">
              <div class="h-12 w-12 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center mr-4">
                <LayoutDashboard class="h-6 w-6 text-white" />
              </div>
              <div>
                <h1 class="text-2xl font-bold text-white">Employee Dashboard</h1>
                <p class="text-teal-100 mt-1">Overview of employee statistics and analytics</p>
              </div>
            </div>
            <div class="mt-4 sm:mt-0 flex items-center space-x-2">
              <button
                @click="refreshData"
                :disabled="isLoading"
                class="inline-flex items-center px-4 py-2 bg-white/20 text-white font-semibold rounded-lg hover:bg-white/30 focus:outline-none focus:ring-2 focus:ring-white/50 transition-all duration-200"
              >
                <RotateCcw class="h-4 w-4 mr-2" :class="{ 'animate-spin': isLoading }" />
                Refresh
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Employees -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
          <div class="flex items-center">
            <div class="h-12 w-12 rounded-lg bg-blue-100 flex items-center justify-center">
              <Users class="h-6 w-6 text-blue-600" />
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Total Employees</p>
              <p class="text-2xl font-bold text-gray-900">{{ totalEmployees }}</p>
            </div>
          </div>
        </div>

        <!-- Active Employees -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
          <div class="flex items-center">
            <div class="h-12 w-12 rounded-lg bg-green-100 flex items-center justify-center">
              <UserCheck class="h-6 w-6 text-green-600" />
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Active Employees</p>
              <p class="text-2xl font-bold text-gray-900">{{ activeEmployees }}</p>
            </div>
          </div>
        </div>

        <!-- Total Companies -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
          <div class="flex items-center">
            <div class="h-12 w-12 rounded-lg bg-purple-100 flex items-center justify-center">
              <Building2 class="h-6 w-6 text-purple-600" />
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Total Companies</p>
              <p class="text-2xl font-bold text-gray-900">{{ totalCompanies }}</p>
            </div>
          </div>
        </div>

        <!-- Walk-in Employees -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
          <div class="flex items-center">
            <div class="h-12 w-12 rounded-lg bg-orange-100 flex items-center justify-center">
              <UserPlus class="h-6 w-6 text-orange-600" />
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Walk-in Employees</p>
              <p class="text-2xl font-bold text-gray-900">{{ walkinEmployees }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Charts Section -->
      <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8">
        <!-- Employees by Company Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Employees by Company</h3>
            <Building2 class="h-5 w-5 text-gray-400" />
          </div>
          <div class="relative h-80">
            <canvas ref="companyChart" class="w-full h-full"></canvas>
          </div>
          <div class="mt-4 space-y-2">
            <div v-for="(item, index) in companyData" :key="index" class="flex items-center justify-between text-sm">
              <div class="flex items-center">
                <div 
                  class="w-3 h-3 rounded-full mr-2" 
                  :style="{ backgroundColor: companyColors[index] }"
                ></div>
                <span class="text-gray-600">{{ item.label }}</span>
              </div>
              <span class="font-semibold text-gray-900">{{ item.value }}</span>
            </div>
          </div>
        </div>

        <!-- Employees by Type Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Employees by Type</h3>
            <Home class="h-5 w-5 text-gray-400" />
          </div>
          <div class="relative h-80">
            <canvas ref="typeChart" class="w-full h-full"></canvas>
          </div>
          <div class="mt-4 space-y-2">
            <div v-for="(item, index) in typeData" :key="index" class="flex items-center justify-between text-sm">
              <div class="flex items-center">
                <div 
                  class="w-3 h-3 rounded-full mr-2" 
                  :style="{ backgroundColor: typeColors[index] }"
                ></div>
                <span class="text-gray-600">{{ item.label }}</span>
              </div>
              <span class="font-semibold text-gray-900">{{ item.value }}</span>
            </div>
          </div>
        </div>

        <!-- Employees by Status Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Employees by Status</h3>
            <Activity class="h-5 w-5 text-gray-400" />
          </div>
          <div class="relative h-80">
            <canvas ref="statusChart" class="w-full h-full"></canvas>
          </div>
          <div class="mt-4 space-y-2">
            <div v-for="(item, index) in statusData" :key="index" class="flex items-center justify-between text-sm">
              <div class="flex items-center">
                <div 
                  class="w-3 h-3 rounded-full mr-2" 
                  :style="{ backgroundColor: statusColors[index] }"
                ></div>
                <span class="text-gray-600">{{ item.label }}</span>
              </div>
              <span class="font-semibold text-gray-900">{{ item.value }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Activity -->
      <!-- <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-8 py-6 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
        </div>
        <div class="p-6">
          <div class="space-y-4">
            <div v-for="activity in recentActivities" :key="activity.id" class="flex items-center p-4 bg-gray-50 rounded-lg">
              <div class="flex-shrink-0">
                <div class="h-10 w-10 rounded-full bg-gradient-to-r from-teal-400 to-cyan-400 flex items-center justify-center">
                  <component :is="activity.icon" class="h-5 w-5 text-white" />
                </div>
              </div>
              <div class="ml-4 flex-1">
                <p class="text-sm font-medium text-gray-900">{{ activity.title }}</p>
                <p class="text-sm text-gray-500">{{ activity.description }}</p>
              </div>
              <div class="text-sm text-gray-400">{{ activity.time }}</div>
            </div>
          </div>
        </div>
      </div> -->
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, nextTick, computed } from 'vue';
import {
  Activity,
  Building2,
  Home,
  LayoutDashboard,
  RotateCcw,
  UserCheck,
  UserPlus,
  Users
} from 'lucide-vue-next';
import Chart from 'chart.js/auto';
import { useStore } from 'vuex';
import { FETCH_DASHBOARD_ANALYTICS } from '@/services/store/actions.type';

// Refs for charts
const companyChart = ref(null);
const typeChart = ref(null);
const statusChart = ref(null);

const store = useStore();

// State
const isLoading = ref(false);

// Chart instances
let companyChartInstance = null;
let typeChartInstance = null;
let statusChartInstance = null;

// Sample data - replace with API calls
const totalEmployees = ref(156);
const activeEmployees = ref(142);
const totalCompanies = ref(8);
const walkinEmployees = ref(89);


const dashboardAnalyticsData = computed(()=> store.getters["company/dashboardAnalyticsData"]);




// Chart data
const companyData = ref([
  { label: 'TechCorp Solutions', value: 45 },
  { label: 'Digital Innovations Ltd', value: 32 },
  { label: 'Global Systems Inc', value: 28 },
  { label: 'Future Technologies', value: 23 },
  { label: 'Smart Solutions Group', value: 18 },
  { label: 'CloudTech Dynamics', value: 10 }
]);

const typeData = ref([
  { label: 'Walk-in', value: 89 },
  { label: 'Hostel', value: 67 }
]);

const statusData = ref([
  { label: 'Active', value: 142 },
  { label: 'Absconded', value: 8 },
  { label: 'Resigned', value: 4 },
  { label: 'Rejected', value: 1 },
  { label: 'KIV', value: 1 }
]);

// Chart colors
const companyColors = [
  '#0891b2', // teal-600
  '#06b6d4', // cyan-500
  '#3b82f6', // blue-500
  '#8b5cf6', // violet-500
  '#ec4899', // pink-500
  '#f59e0b'  // amber-500
];

const typeColors = [
  '#059669', // emerald-600
  '#dc2626'  // red-600
];

const statusColors = [
  '#059669', // emerald-600 - Active
  '#dc2626', // red-600 - Absconded
  '#d97706', // amber-600 - Resigned
  '#7c2d12', // red-900 - Rejected
  '#374151'  // gray-700 - KIV
];

// Recent activities
const recentActivities = ref([
  {
    id: 1,
    icon: UserPlus,
    title: 'New Employee Added',
    description: 'John Doe joined TechCorp Solutions',
    time: '2 hours ago'
  },
  {
    id: 2,
    icon: Building2,
    title: 'Company Updated',
    description: 'Digital Innovations Ltd information updated',
    time: '4 hours ago'
  },
  {
    id: 3,
    icon: UserCheck,
    title: 'Employee Status Changed',
    description: 'Jane Smith status changed to Active',
    time: '6 hours ago'
  },
  {
    id: 4,
    icon: Users,
    title: 'Bulk Import Completed',
    description: '15 employees imported successfully',
    time: '1 day ago'
  }
]);

// Chart creation functions
const createPieChart = (canvas, data, colors, title) => {
  const ctx = canvas.getContext('2d');
  
  return new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: data.map(item => item.label),
      datasets: [{
        data: data.map(item => item.value),
        backgroundColor: colors,
        borderWidth: 2,
        borderColor: '#ffffff',
        hoverBorderWidth: 3,
        hoverBorderColor: '#ffffff'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false
        },
        tooltip: {
          backgroundColor: 'rgba(0, 0, 0, 0.8)',
          titleColor: '#ffffff',
          bodyColor: '#ffffff',
          borderColor: '#374151',
          borderWidth: 1,
          cornerRadius: 8,
          displayColors: true,
          callbacks: {
            label: function(context) {
              const total = context.dataset.data.reduce((a, b) => a + b, 0);
              const percentage = ((context.parsed / total) * 100).toFixed(1);
              return `${context.label}: ${context.parsed} (${percentage}%)`;
            }
          }
        }
      },
      cutout: '60%',
      animation: {
        animateRotate: true,
        duration: 1000
      }
    }
  });
};

const initializeCharts = async () => {
  await nextTick();
  
  // Company Chart
  if (companyChart.value) {
    companyChartInstance = createPieChart(
      companyChart.value,
      companyData.value,
      companyColors,
      'Employees by Company'
    );
  }
  
  // Type Chart
  if (typeChart.value) {
    typeChartInstance = createPieChart(
      typeChart.value,
      typeData.value,
      typeColors,
      'Employees by Type'
    );
  }
  
  // Status Chart
  if (statusChart.value) {
    statusChartInstance = createPieChart(
      statusChart.value,
      statusData.value,
      statusColors,
      'Employees by Status'
    );
  }
};

const destroyCharts = () => {
  if (companyChartInstance) {
    companyChartInstance.destroy();
    companyChartInstance = null;
  }
  if (typeChartInstance) {
    typeChartInstance.destroy();
    typeChartInstance = null;
  }
  if (statusChartInstance) {
    statusChartInstance.destroy();
    statusChartInstance = null;
  }
};

const refreshData = async () => {
  isLoading.value = true;
  
  try {
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 1000));
    
    // Destroy existing charts
    destroyCharts();
    
    // Reinitialize charts with new data
    await initializeCharts();
    
  } catch (error) {
    console.error('Error refreshing data:', error);
  } finally {
    isLoading.value = false;
  }
};



async function fetchDashboardAnalyticsData() {
  try {
    const response = await store.dispatch(`company/${FETCH_DASHBOARD_ANALYTICS}`);
    const data = dashboardAnalyticsData?.value || {};

    // Update scalar values
    totalEmployees.value = data.total_employees || 0;
    activeEmployees.value = data.total_active_employees || 0;
    totalCompanies.value = data.total_companies || 0;
    walkinEmployees.value = data.candidates_by_residence.walk_in || 0;
    

    // Transform candidates_by_residence to match typeData format
    typeData.value = [
      { label: 'Hostel', value: data.candidates_by_residence?.hostel || 0 },
      { label: 'Walk-in', value: data.candidates_by_residence?.walk_in || 0 }
    ];

    // Transform candidates_by_status to match statusData format
    statusData.value = [
      { label: 'Active', value: data.candidates_by_status?.active || 0 },
      { label: 'Absconded', value: data.candidates_by_status?.absconded || 0 },
      { label: 'Resigned', value: data.candidates_by_status?.resigned || 0 },
      { label: 'Rejected', value: data.candidates_by_status?.rejected || 0 },
      { label: 'KIV', value: data.candidates_by_status?.kiv || 0 }
    ];
  } catch (error) {
    console.error('Error fetching dashboard analytics data:', error);
    // Optionally reset values on error
    totalEmployees.value = 0;
    activeEmployees.value = 0;
    totalCompanies.value = 0;
    typeData.value = typeData.value.map(item => ({ ...item, value: 0 }));
    statusData.value = statusData.value.map(item => ({ ...item, value: 0 }));
  }
}





// Lifecycle
onMounted(() => {
  initializeCharts();
  fetchDashboardAnalyticsData();
});
</script>