<template>
  <!-- <pre>{{ todosData.data }}</pre> -->
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
        <div class="bg-gradient-to-r from-teal-600 to-cyan-600 px-8 py-6 rounded-t-xl">
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center">
              <div class="h-12 w-12 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center mr-4">
                <List class="h-6 w-6 text-white" />
              </div>
              <div>
                <h1 class="text-2xl font-bold text-white">ToDo List</h1>
                <p class="text-teal-100 mt-1">Manage and track all your tasks</p>
              </div>
            </div>
            <div class="mt-4 sm:mt-0">
              <router-link
                :to="{ name: 'AddTodo' }"
                class="inline-flex items-center px-6 py-3 bg-white text-teal-600 font-semibold rounded-lg shadow-sm hover:bg-gray-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition-all duration-200 transform hover:scale-105"
              >
                <Plus class="h-5 w-5 mr-2" />
                Add New ToDo
              </router-link>
            </div>
          </div>
        </div>
      </div>

      <!-- Filters -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8 p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <!-- Search -->
          <div class="md:col-span-2">
            <label for="search" class="block text-sm font-semibold text-gray-700 mb-2">
              Search ToDos
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <Search class="h-5 w-5 text-gray-400" />
              </div>
              <input
                id="search"
                v-model="searchQuery"
                type="text"
                class="block w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                placeholder="Search by heading or description..."
                @input="fetchTodos"
              />
            </div>
          </div>

          <!-- Status Filter -->
          <div>
            <label for="status-filter" class="block text-sm font-semibold text-gray-700 mb-2">
              Filter by Status
            </label>
            <select
              id="status-filter"
              v-model="statusFilter"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
              @change="fetchTodos"
            >
              <option value="all">All Status</option>
              <option value="pending">Pending</option>
              <option value="in-progress">In Progress</option>
              <option value="completed">Completed</option>
              <option value="cancelled">Cancelled</option>
            </select>
          </div>

          <!-- Priority Filter -->
          <div>
            <label for="priority-filter" class="block text-sm font-semibold text-gray-700 mb-2">
              Filter by Priority
            </label>
            <select
              id="priority-filter"
              v-model="priorityFilter"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
              @change="fetchTodos"
            >
              <option value="all">All Priority</option>
              <option value="low">Low</option>
              <option value="medium">Medium</option>
              <option value="high">High</option>
              <option value="urgent">Urgent</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Loading State -->
      <div
        v-if="isLoading"
        class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center"
      >
        <div class="inline-flex items-center">
          <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-teal-600 mr-4"></div>
          <span class="text-lg text-gray-600">Loading ToDos...</span>
        </div>
      </div>

      <!-- ToDos Grid -->
      <div v-else-if="todosData?.data?.length > 0">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="todo in todosData?.data"
            :key="todo.id"
            class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-lg transition-all duration-300 overflow-hidden"
          >
            <!-- ToDo Header -->
            <div class="p-6 border-b border-gray-100">
              <div class="flex items-start justify-between mb-3">
                <h3 class="text-lg font-semibold text-gray-900 line-clamp-2">
                  {{ todo.heading }}
                </h3>
                <div class="relative ml-2">
                  <button
                    @click="toggleDropdown(todo.id)"
                    class="p-1 rounded-full hover:bg-gray-100 transition-colors duration-200"
                  >
                    <MoreVertical class="h-5 w-5 text-gray-500" />
                  </button>
                  <div
                    v-if="activeDropdown === todo.id"
                    class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-10 border border-gray-200"
                    @click.stop
                  >
                    <div class="py-2">
                      <button
                        @click="viewTodo(todo)"
                        class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200"
                      >
                        <Eye class="h-4 w-4 mr-2" />
                        View Details
                      </button>
                      <router-link :to="{ name: 'EditTodo', query: { todoId: todo.id } }">
                        <button
                          class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200"
                        >
                          <Edit class="h-4 w-4 mr-2" />
                          Edit ToDo
                        </button>
                      </router-link>
                      <button
                        @click="deleteTodo(todo.id)"
                        class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors duration-200"
                      >
                        <Trash2 class="h-4 w-4 mr-2" />
                        Delete ToDo
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              <p class="text-sm text-gray-600 line-clamp-3 mb-4">
                {{ todo.description }}
              </p>
              <!-- Status and Priority Badges -->
              <div class="flex items-center space-x-2 mb-3">
                <span :class="['inline-flex items-center px-2 py-1 rounded-full text-xs font-medium', getStatusColor(todo.status)]">
                  {{ getStatusLabel(todo.status) }}
                </span>
                <span :class="['inline-flex items-center px-2 py-1 rounded-full text-xs font-medium', getPriorityColor(todo.priority)]">
                  {{ getPriorityLabel(todo.priority) }}
                </span>
              </div>
            </div>
            <!-- ToDo Footer -->
            <div class="px-6 py-4 bg-gray-50">
              <div class="flex items-center justify-between text-sm">
                <div class="flex items-center text-gray-500">
                  <Calendar class="h-4 w-4 mr-1" />
                  Due: {{ formatDate(todo.due_date) }}
                </div>
                <div class="flex items-center space-x-2">
                  <button
                    @click="viewTodo(todo)"
                    class="text-teal-600 hover:text-teal-800 font-medium transition-colors duration-200"
                  >
                    View Details
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Backend Pagination -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mt-8 px-6 py-4">
          <div class="flex items-center justify-between">
            <div class="flex-1 flex justify-between sm:hidden">
              <button
                @click="navigate(todosData?.prev_page_url)"
                :disabled="!todosData?.prev_page_url"
                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                Previous
              </button>
              <button
                @click="navigate(todosData?.next_page_url)"
                :disabled="!todosData?.next_page_url"
                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                Next
              </button>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
              <div>
                <p class="text-sm text-gray-700">
                  Showing
                  <span class="font-medium">{{ todosData?.from || 0 }}</span>
                  to
                  <span class="font-medium">{{ todosData?.to || 0 }}</span>
                  of
                  <span class="font-medium">{{ todosData?.total || 0 }}</span>
                  results
                </p>
              </div>
              <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                  <button
                    @click="navigate(todosData?.prev_page_url)"
                    :disabled="!todosData?.prev_page_url"
                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    <ChevronLeft class="h-5 w-5" />
                  </button>
                  <button
                    v-for="link in todosData?.links"
                    :key="link.label"
                    @click="navigate(link.url)"
                    :disabled="!link.url"
                    :class="[
                      'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                      link.active ? 'z-10 bg-teal-50 border-teal-500 text-teal-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                      !link.url ? 'opacity-50 cursor-not-allowed' : '',
                    ]"
                    v-html="link.label"
                  ></button>
                  <button
                    @click="navigate(todosData?.next_page_url)"
                    :disabled="!todosData?.next_page_url"
                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    <ChevronRight class="h-5 w-5" />
                  </button>
                </nav>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div
        v-else
        class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center"
      >
        <div class="h-20 w-20 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-6">
          <List class="h-10 w-10 text-gray-400" />
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-3">No ToDos found</h3>
        <p class="text-gray-500 mb-6 max-w-md mx-auto">
          {{ searchQuery || statusFilter !== "all" || priorityFilter !== "all" ? "Try adjusting your search terms or filters" : "Get started by creating your first ToDo item" }}
        </p>
        <router-link
          :to="{ name: 'AddTodo' }"
          class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-teal-600 to-cyan-600 text-white font-semibold rounded-lg shadow-sm hover:from-teal-700 hover:to-cyan-700 transition-all duration-200"
        >
          <Plus class="h-5 w-5 mr-2" />
          Add First ToDo
        </router-link>
      </div>

      <!-- View ToDo Modal -->
      <div
        v-if="showViewModal"
        class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50"
        @click="closeViewModal"
      >
        <div
          class="relative mx-auto p-6 border w-11/12 sm:w-3/4 lg:w-1/2 max-w-2xl bg-white rounded-xl shadow-2xl"
          @click.stop
        >
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold text-gray-800">ToDo Details</h3>
            <button
              @click="closeViewModal"
              class="text-gray-500 hover:text-gray-700 transition-colors duration-200"
            >
              <X class="h-6 w-6" />
            </button>
          </div>
          <div v-if="selectedTodo" class="space-y-6">
            <!-- ToDo Header -->
            <div class="bg-gradient-to-r from-teal-50 to-cyan-50 rounded-lg p-6 border border-teal-200">
              <h4 class="text-xl font-bold text-gray-900 mb-2">
                {{ selectedTodo.heading }}
              </h4>
              <div class="flex items-center space-x-3">
                <span :class="['inline-flex items-center px-3 py-1 rounded-full text-sm font-medium', getStatusColor(selectedTodo.status)]">
                  {{ getStatusLabel(selectedTodo.status) }}
                </span>
                <span :class="['inline-flex items-center px-3 py-1 rounded-full text-sm font-medium', getPriorityColor(selectedTodo.priority)]">
                  {{ getPriorityLabel(selectedTodo.priority) }}
                </span>
              </div>
            </div>
            <!-- ToDo Information -->
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                <div class="bg-gray-50 rounded-lg p-4">
                  <p class="text-gray-900 leading-relaxed">
                    {{ selectedTodo.description || "No description provided" }}
                  </p>
                </div>
              </div>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Due Date</label>
                  <div class="flex items-center text-gray-900">
                    <Calendar class="h-4 w-4 mr-2 text-gray-500" />
                    {{ formatDate(selectedTodo.due_date) }}
                  </div>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Created Date</label>
                  <div class="flex items-center text-gray-900">
                    <Clock class="h-4 w-4 mr-2 text-gray-500" />
                    {{ formatDate(selectedTodo.created_at) }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import { Calendar, ChevronLeft, ChevronRight, Clock, Edit, Eye, List, MoreVertical, Plus, Search, Trash2, X } from "lucide-vue-next";
import { useRouter } from "vue-router";
import { DELETE_TODO, FETCH_TODOS } from "@/services/store/actions.type";
import { useStore } from "vuex";
import { toast } from "vue3-toastify";

const router = useRouter();
const store = useStore();

// State
const isLoading = ref(true);
const searchQuery = ref("");
const statusFilter = ref("all");
const priorityFilter = ref("all");
const itemsPerPage = ref(6); // Default items per page
const currentPage = ref(1);
const showViewModal = ref(false);
const selectedTodo = ref(null);
const activeDropdown = ref(null);

const todosData = computed(() => store.getters["todo/todos"]);

// Methods
const fetchTodos = async () => {
  isLoading.value = true;
  try {
    await store.dispatch("todo/" + FETCH_TODOS, {
      page: currentPage.value,
      per_page: itemsPerPage.value,
      search: searchQuery.value,
      status: statusFilter.value === "all" ? "" : statusFilter.value,
      priority: priorityFilter.value === "all" ? "" : priorityFilter.value,
    });
  } catch (error) {
    console.error("Error fetching todos:", error);
    toast("Failed to load ToDos. Please try again.", { type: "error" });
  } finally {
    isLoading.value = false;
  }
};

const deleteTodo = async (todoId) => {
  try {
    await store.dispatch("todo/" + DELETE_TODO, { todoId });
    fetchTodos(); // Refresh the list after deletion
  } catch (error) {
    console.error("Error deleting todo:", error);
    toast("Failed to delete ToDo. Please try again.", { type: "error" });
  }
};

const getStatusColor = (status) => {
  const colors = {
    pending: "bg-yellow-100 text-yellow-800",
    "in-progress": "bg-blue-100 text-blue-800",
    completed: "bg-green-100 text-green-800",
    cancelled: "bg-red-100 text-red-800",
  };
  return colors[status] || "bg-gray-100 text-gray-800";
};

const getStatusLabel = (status) => {
  const labels = {
    pending: "Pending",
    "in-progress": "In Progress",
    completed: "Completed",
    cancelled: "Cancelled",
  };
  return labels[status] || status;
};

const getPriorityColor = (priority) => {
  const colors = {
    low: "bg-gray-100 text-gray-800",
    medium: "bg-orange-100 text-orange-800",
    high: "bg-red-100 text-red-800",
    urgent: "bg-purple-100 text-purple-800",
  };
  return colors[priority] || "bg-gray-100 text-gray-800";
};

const getPriorityLabel = (priority) => {
  const labels = {
    low: "Low",
    medium: "Medium",
    high: "High",
    urgent: "Urgent",
  };
  return labels[priority] || priority;
};

const formatDate = (dateString) => {
  if (!dateString) return "N/A";
  const date = new Date(dateString);
  return date.toLocaleDateString("en-GB");
};

const toggleDropdown = (todoId) => {
  activeDropdown.value = activeDropdown.value === todoId ? null : todoId;
};

const viewTodo = (todo) => {
  selectedTodo.value = todo;
  showViewModal.value = true;
  activeDropdown.value = null;
};

const closeViewModal = () => {
  showViewModal.value = false;
  selectedTodo.value = null;
};

const navigate = (url) => {
  if (!url) return;
  const params = new URLSearchParams(new URL(url).search);
  currentPage.value = parseInt(params.get("page")) || 1;
  fetchTodos();
};

// Close dropdown when clicking outside
const handleClickOutside = (event) => {
  if (!event.target.closest(".relative")) {
    activeDropdown.value = null;
  }
};

// Lifecycle
onMounted(() => {
  fetchTodos();
  document.addEventListener("click", handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener("click", handleClickOutside);
});
</script>

<style scoped>
.line-clamp-2,
.line-clamp-3 {
  display: -webkit-box;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
  word-break: break-word;
}

.line-clamp-2 {
  -webkit-line-clamp: 2;
}

.line-clamp-3 {
  -webkit-line-clamp: 3;
}
</style>