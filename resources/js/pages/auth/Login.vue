<template>
  <div class="min-h-screen w-full flex items-center justify-center bg-gradient-to-br from-slate-900 via-teal-900 to-slate-900 p-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden">
      <!-- Logo and Header Section -->
      <div class="bg-gradient-to-r from-teal-600 to-cyan-600 p-6 text-center">
        <div class="flex justify-center mb-3">
          <div class="relative">
            <div class="absolute -inset-1 rounded-full opacity-75 bg-white blur-sm"></div>
            <div class="relative flex items-center justify-center h-16 w-16 rounded-full bg-white">
              <UsersIcon class="h-8 w-8 text-teal-600" />
            </div>
          </div>
        </div>
        <h1 class="text-2xl font-bold text-white">EmployeeHub</h1>
        <p class="text-teal-100 mt-1">Employee Management System</p>
      </div>

      <!-- Form Section -->
      <div class="p-6 pt-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-6 text-center">Access Employee Portal</h2>
        
        <form @submit.prevent="handleLogin" class="space-y-5">
          <!-- Email Input -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Employee Email</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <MailIcon class="h-5 w-5 text-gray-400" />
              </div>
              <input 
                id="email" 
                v-model="email" 
                type="email" 
                required 
                class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                placeholder="employee@company.com"
              />
            </div>
          </div>

          <!-- Password Input -->
          <div>
            <div class="flex items-center justify-between mb-1">
              <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
              <a href="#" class="text-xs text-teal-600 hover:text-teal-800 transition-colors">Forgot password?</a>
            </div>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <LockIcon class="h-5 w-5 text-gray-400" />
              </div>
              <input 
                id="password" 
                v-model="password" 
                :type="showPassword ? 'text' : 'password'" 
                required 
                class="block w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                placeholder="••••••••"
              />
              <button 
                type="button" 
                @click="showPassword = !showPassword" 
                class="absolute inset-y-0 right-0 pr-3 flex items-center"
              >
                <EyeIcon v-if="showPassword" class="h-5 w-5 text-gray-400 hover:text-gray-600" />
                <EyeOffIcon v-else class="h-5 w-5 text-gray-400 hover:text-gray-600" />
              </button>
            </div>
          </div>

          <!-- Remember Me -->
          <div class="flex items-center">
            <input 
              id="remember-me" 
              v-model="rememberMe" 
              type="checkbox" 
              class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded"
            />
            <label for="remember-me" class="ml-2 block text-sm text-gray-700">
              Keep me signed in
            </label>
          </div>

          <!-- Login Button -->
          <button 
            type="submit" 
            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-teal-600 to-cyan-600 hover:from-teal-700 hover:to-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all duration-200"
          >
            <span class="flex items-center">
              <span>Access Portal</span>
              <ArrowRightIcon class="ml-2 h-4 w-4" />
            </span>
          </button>
        </form>

        <!-- Additional Info -->
        <div class="mt-6 text-center">
          <p class="text-xs text-gray-500">
            Need help? Contact IT Support at 
            <a href="mailto:support@company.com" class="text-teal-600 hover:text-teal-800">
              support@company.com
            </a>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { 
  Users as UsersIcon, 
  Mail as MailIcon, 
  Lock as LockIcon, 
  Eye as EyeIcon, 
  EyeOff as EyeOffIcon, 
  ArrowRight as ArrowRightIcon
} from 'lucide-vue-next';
import { LOGIN } from "@/services/store/actions.type";
import { useRouter } from "vue-router";
import { useStore } from "vuex";

// Store and router
const store = useStore();
const router = useRouter();
const isAuthenticated = computed(() => store.getters["auth/isAuthenticated"]);

// Form state
const email = ref('');
const password = ref('');
const showPassword = ref(false);
const rememberMe = ref(false);

// Login handler
function handleLogin() {
  store
    .dispatch("auth/" + LOGIN, {
      email: email.value,
      password: password.value,
    })
    .then(() => {
      router.push({ name: "Dashboard" });
    });
}
</script>

<style scoped>
/* Additional custom styles if needed */
</style>