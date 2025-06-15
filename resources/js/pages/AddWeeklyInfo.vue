<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
        <div class="bg-gradient-to-r from-teal-600 to-cyan-600 px-8 py-6 rounded-t-xl">
          <div class="flex items-center">
            <div class="h-12 w-12 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center mr-4">
              <Calendar class="h-6 w-6 text-white" />
            </div>
            <div>
              <h1 class="text-2xl font-bold text-white">Add Weekly Information</h1>
              <p class="text-teal-100 mt-1">Record weekly recruitment data for companies</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Form -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <form @submit.prevent="submitWeeklyInfo" class="p-8 space-y-6">
          <!-- Company and Date Row -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Company Name -->
            <div>
              <label for="company_name" class="block text-sm font-semibold text-gray-700 mb-2">
                Company Name <span class="text-red-500">*</span>
              </label>
              <select
                id="company_name"
                v-model="form.company_name"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
              >
                <option value="">Select Company</option>
                <option v-for="company in companiesData?.data" :key="company.id" :value="company.company_name">
                  {{ company.company_name }}
                </option>
              </select>
            </div>

            <!-- Date -->
            <div>
              <label for="date" class="block text-sm font-semibold text-gray-700 mb-2">
                Date <span class="text-red-500">*</span>
              </label>
              <input
                id="date"
                v-model="form.date"
                type="date"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
              />
            </div>
          </div>

          <!-- Recruitment Metrics -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Total Candidates -->
            <div>
              <label for="total_candidates" class="block text-sm font-semibold text-gray-700 mb-2">
                Total Candidates <span class="text-red-500">*</span>
              </label>
              <input
                id="total_candidates"
                v-model.number="form.total_candidates"
                type="number"
                min="0"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                placeholder="Enter total candidates"
              />
            </div>

            <!-- Interviewed -->
            <div>
              <label for="interviewed" class="block text-sm font-semibold text-gray-700 mb-2">
                Interviewed <span class="text-red-500">*</span>
              </label>
              <input
                id="interviewed"
                v-model.number="form.interviewed"
                type="number"
                min="0"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                placeholder="Enter interviewed count"
              />
            </div>

            <!-- Passed -->
            <div>
              <label for="passed" class="block text-sm font-semibold text-gray-700 mb-2">
                Passed <span class="text-red-500">*</span>
              </label>
              <input
                id="passed"
                v-model.number="form.passed"
                type="number"
                min="0"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                placeholder="Enter passed count"
              />
            </div>
          </div>

          <!-- Employee Type Distribution -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Hostel -->
            <div>
              <label for="hostel" class="block text-sm font-semibold text-gray-700 mb-2">
                Hostel Employees <span class="text-red-500">*</span>
              </label>
              <input
                id="hostel"
                v-model.number="form.hostel"
                type="number"
                min="0"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                placeholder="Enter hostel employees"
              />
            </div>

            <!-- Walk-in -->
            <div>
              <label for="walkin" class="block text-sm font-semibold text-gray-700 mb-2">
                Walk-in Employees <span class="text-red-500">*</span>
              </label>
              <input
                id="walkin"
                v-model.number="form.walkin"
                type="number"
                min="0"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                placeholder="Enter walk-in employees"
              />
            </div>
          </div>

          <!-- Summary Card -->
          <div class="bg-gradient-to-r from-teal-50 to-cyan-50 rounded-lg p-6 border border-teal-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Summary</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
              <div class="text-center">
                <div class="text-2xl font-bold text-teal-600">{{ form.total_candidates || 0 }}</div>
                <div class="text-sm text-gray-600">Total Candidates</div>
              </div>
              <div class="text-center">
                <div class="text-2xl font-bold text-blue-600">{{ form.interviewed || 0 }}</div>
                <div class="text-sm text-gray-600">Interviewed</div>
              </div>
              <div class="text-center">
                <div class="text-2xl font-bold text-green-600">{{ form.passed || 0 }}</div>
                <div class="text-sm text-gray-600">Passed</div>
              </div>
              <div class="text-center">
                <div class="text-2xl font-bold text-purple-600">{{ (form.hostel || 0) + (form.walkin || 0) }}</div>
                <div class="text-sm text-gray-600">Total Employees</div>
              </div>
            </div>
          </div>

          <!-- Form Actions -->
          <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
            <button
              type="submit"
              :disabled="isSubmitting"
              class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-teal-600 to-cyan-600 text-white font-semibold rounded-lg shadow-sm hover:from-teal-700 hover:to-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <Save class="h-5 w-5 mr-2" />
              {{ isSubmitting ? 'Saving...' : 'Save Weekly Info' }}
            </button>
            <button
              type="button"
              @click="resetForm"
              class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-lg shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200"
            >
              <RotateCcw class="h-5 w-5 mr-2" />
              Reset Form
            </button>
            <router-link
              :to="{ name: 'WeeklyReports' }"
              class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-white border border-gray-300 text-gray-700 font-semibold rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200"
            >
              <BarChart3 class="h-5 w-5 mr-2" />
              View Reports
            </router-link>
          </div>
        </form>
      </div>

      <!-- Success Message -->
      <div
        v-if="showSuccess"
        class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg shadow-lg z-50 transition-all duration-300"
      >
        <div class="flex items-center">
          <CheckCircle class="h-5 w-5 mr-2" />
          <span class="font-medium">Weekly information saved successfully!</span>
        </div>
      </div>

      <!-- Error Message -->
      <div
        v-if="showError"
        class="fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg shadow-lg z-50 transition-all duration-300"
      >
        <div class="flex items-center">
          <XCircle class="h-5 w-5 mr-2" />
          <span class="font-medium">{{ errorMessage }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { BarChart3, Calendar, CheckCircle, RotateCcw, Save, XCircle } from 'lucide-vue-next';
import { useStore } from 'vuex';
import { toast } from 'vue3-toastify';
import { FETCH_COMPANY, CREATE_WEEKLY_INFO } from '@/services/store/actions.type';

const store = useStore();

// State
const isSubmitting = ref(false);
const showSuccess = ref(false);
const showError = ref(false);
const errorMessage = ref('');

// Computed
const companiesData = computed(() => store.getters['company/companyData']);

// Form data
const form = reactive({
  company_name: '',
  date: '',
  total_candidates: 0,
  interviewed: 0,
  passed: 0,
  hostel: 0,
  walkin: 0
});

// Methods
const loadCompanies = async () => {
  try {
    await store.dispatch(`company/${FETCH_COMPANY}`);
  } catch (error) {
    console.error('Error loading companies:', error);
    toast.error('Failed to load companies. Please try again.');
  }
};

const submitWeeklyInfo = async () => {
  isSubmitting.value = true;
  showError.value = false;
  errorMessage.value = '';
 

  try {
    const weeklyInfoData = {
      company_name: form.company_name,
      date: form.date,
      total_candidates: form.total_candidates,
      interviewed: form.interviewed,
      passed: form.passed,
      hostel: form.hostel,
      walkin: form.walkin,
    };

    await store.dispatch("weeklyInfo/" + CREATE_WEEKLY_INFO, weeklyInfoData);

    // toast.success("Weekly information saved successfully!");
    showSuccess.value = true;
    setTimeout(() => {
      showSuccess.value = false;
    }, 3000);

    resetForm();
  } catch (err) {
    console.error("Error saving weekly info:", err);
    errorMessage.value =
      err.response?.data?.message || "Failed to save weekly information. Please try again.";
    showError.value = true;
    toast.error(errorMessage.value);

    setTimeout(() => {
      showError.value = false;
    }, 3000);
  } finally {
    isSubmitting.value = false;
  }
};


const resetForm = () => {
  Object.keys(form).forEach(key => {
    if (typeof form[key] === 'number') {
      form[key] = 0;
    } else {
      form[key] = '';
    }
  });
};

// Lifecycle
onMounted(() => {
  loadCompanies();
});
</script>