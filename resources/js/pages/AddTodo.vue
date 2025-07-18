<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
        <div class="bg-gradient-to-r from-teal-600 to-cyan-600 px-8 py-6 rounded-t-xl">
          <div class="flex items-center">
            <div class="h-12 w-12 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center mr-4">
              <Plus class="h-6 w-6 text-white" />
            </div>
            <div>
              <h1 class="text-2xl font-bold text-white">Add New ToDo</h1>
              <p class="text-teal-100 mt-1">Create a new task to track your progress</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Form -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <form @submit.prevent="submitTodo" class="p-8 space-y-6">
          <!-- ToDo Heading -->
          <div>
            <label for="heading" class="block text-sm font-semibold text-gray-700 mb-2">
              ToDo Heading <span class="text-red-500">*</span>
            </label>
            <input
              id="heading"
              v-model="form.heading"
              type="text"
              required
              class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
              placeholder="Enter todo heading..."
              @input="sanitizeInput('heading')"
            />
          </div>

          <!-- Description -->
          <div>
            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
              Description
            </label>
            <textarea
              id="description"
              v-model="form.description"
              rows="4"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200 resize-none"
              placeholder="Enter detailed description..."
              @input="sanitizeInput('description')"
            ></textarea>
          </div>

          <!-- Status and Priority Row -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Status -->
            <div>
              <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                Status <span class="text-red-500">*</span>
              </label>
              <select
                id="status"
                v-model="form.status"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
              >
                <option value="">Select Status</option>
                <option value="pending">Pending</option>
                <option value="in-progress">In Progress</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
              </select>
            </div>

            <!-- Priority -->
            <div>
              <label for="priority" class="block text-sm font-semibold text-gray-700 mb-2">
                Priority <span class="text-red-500">*</span>
              </label>
              <select
                id="priority"
                v-model="form.priority"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
              >
                <option value="">Select Priority</option>
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
                <option value="urgent">Urgent</option>
              </select>
            </div>
          </div>

          <!-- Due Date -->
          <div>
            <label for="due_date" class="block text-sm font-semibold text-gray-700 mb-2">
              Due Date <span class="text-red-500">*</span>
            </label>
            <input
              id="due_date"
              v-model="form.due_date"
              type="date"
              required
              class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
              @input="formatDate('due_date')"
            />
          </div>

          <!-- Form Actions -->
          <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
            <button
              type="submit"
              :disabled="isSubmitting"
              class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-teal-600 to-cyan-600 text-white font-semibold rounded-lg shadow-sm hover:from-teal-700 hover:to-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <Save class="h-5 w-5 mr-2" />
              {{ isSubmitting ? 'Creating...' : 'Create ToDo' }}
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
              :to="{ name: 'ListTodos' }"
              class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-white border border-gray-300 text-gray-700 font-semibold rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200"
            >
              <List class="h-5 w-5 mr-2" />
              View All ToDos
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
          <span class="font-medium">ToDo created successfully!</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';
import { CheckCircle, List, Plus, RotateCcw, Save } from 'lucide-vue-next';
import { toast } from 'vue3-toastify';
import { SAVE_TODO } from '@/services/store/actions.type';

// Router and store
const router = useRouter();
const store = useStore();

// State
const isSubmitting = ref(false);
const showSuccess = ref(false);
const error = ref(null);
const formErrors = ref([]);

// Form data
const form = reactive({
  heading: '',
  description: '',
  status: '',
  priority: '',
  due_date: ''
});

// Sanitize input
const sanitizeInput = (field) => {
  const value = form[field];
  if (typeof value === 'string') {
    form[field] = value.replace(/[<>]/g, '');
  }
};

// Format date
const formatDate = (field) => {
  const value = form[field];
  if (value) {
    const date = new Date(value);
    if (!isNaN(date.getTime())) {
      form[field] = date.toISOString().split('T')[0];
    }
  }
};

// Validation
const validateForm = () => {
  formErrors.value = [];

  if (!form.heading.trim()) {
    formErrors.value.push('Heading is required.');
  }
  if (!form.status) {
    formErrors.value.push('Status is required.');
  }
  if (!form.priority) {
    formErrors.value.push('Priority is required.');
  }
  if (!form.due_date) {
    formErrors.value.push('Due date is required.');
  }

  return formErrors.value.length === 0;
};

// Submit handler
const submitTodo = async () => {
  isSubmitting.value = true;
  error.value = null;
  formErrors.value = [];

  if (!validateForm()) {
    isSubmitting.value = false;
    return;
  }

  try {
    const todoData = {
      heading: form.heading,
      description: form.description || null,
      status: form.status,
      priority: form.priority,
      due_date: form.due_date
    };

    await store.dispatch('todo/' + SAVE_TODO, todoData);
    router.push({ name: "ListTodos" });
    showSuccess.value = true;
    toast.success('ToDo created successfully!');
    resetForm();
    setTimeout(() => (showSuccess.value = false), 3000);
  } catch (err) {
    error.value =
      err.response?.data?.message || 'Error creating ToDo. Please try again.';
  } finally {
    isSubmitting.value = false;
  }
};

// Reset handler
const resetForm = () => {
  Object.keys(form).forEach(key => {
    form[key] = '';
  });
  error.value = null;
  formErrors.value = [];
};
</script>