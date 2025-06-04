<template>
    <!-- <pre>{{ form }}</pre> -->
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8"
            >
                <div
                    class="bg-gradient-to-r from-teal-600 to-cyan-600 px-8 py-6 rounded-t-xl"
                >
                    <div class="flex items-center">
                        <div
                            class="h-12 w-12 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center mr-4"
                        >
                            <Edit class="h-6 w-6 text-white" />
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-white">
                                Edit ToDo
                            </h1>
                            <p class="text-teal-100 mt-1">
                                Update your task information
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loading State -->
            <div
                v-if="isLoading"
                class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center"
            >
                <div class="inline-flex items-center">
                    <div
                        class="animate-spin rounded-full h-8 w-8 border-b-2 border-teal-600 mr-4"
                    ></div>
                    <span class="text-gray-600 text-lg font-medium"
                        >Loading todo...</span
                    >
                </div>
            </div>

            <!-- Form -->
            <div
                v-else="todoDetails"
                class="bg-white rounded-xl shadow-sm border border-gray-200"
            >
                <form @submit.prevent="updateTodo" class="p-8 space-y-6">
                    <!-- ToDo Heading -->
                    <div>
                        <label
                            for="heading"
                            class="block text-sm font-semibold text-gray-700 mb-2"
                        >
                            ToDo Heading <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="heading"
                            v-model="form.heading"
                            type="text"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                            placeholder="Enter todo heading..."
                        />
                    </div>

                    <!-- Description -->
                    <div>
                        <label
                            for="description"
                            class="block text-sm font-semibold text-gray-700 mb-2"
                        >
                            Description
                        </label>
                        <textarea
                            id="description"
                            v-model="form.description"
                            rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200 resize-none"
                            placeholder="Enter detailed description..."
                        ></textarea>
                    </div>

                    <!-- Status and Priority Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Status -->
                        <div>
                            <label
                                for="status"
                                class="block text-sm font-semibold text-gray-700 mb-2"
                            >
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
                            <label
                                for="priority"
                                class="block text-sm font-semibold text-gray-700 mb-2"
                            >
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
                        <label
                            for="due_date"
                            class="block text-sm font-semibold text-gray-700 mb-2"
                        >
                            Due Date <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="due_date"
                            v-model="form.due_date"
                            type="date"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                        />
                    </div>

                    <!-- Form Actions -->
                    <div
                        class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200"
                    >
                        <button
                            type="submit"
                            :disabled="isSubmitting"
                            class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-teal-600 to-cyan-600 text-white font-semibold rounded-lg shadow-sm hover:from-teal-700 hover:to-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <Save class="h-5 w-5 mr-2" />
                            {{ isSubmitting ? "Updating..." : "Update ToDo" }}
                        </button>

                        <button
                            type="button"
                            @click="resetForm"
                            class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-lg shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200"
                        >
                            <RotateCcw class="h-5 w-5 mr-2" />
                            Reset Changes
                        </button>

                        <router-link
                            :to="{ name: 'ListTodos' }"
                            class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-white border border-gray-300 text-gray-700 font-semibold rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200"
                        >
                            <ArrowLeft class="h-5 w-5 mr-2" />
                            Back to List
                        </router-link>
                    </div>
                </form>
            </div>

            <!-- Error State -->
            <!-- <div
                v-else
                class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center"
            >
                <div
                    class="h-20 w-20 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-6"
                >
                    <AlertCircle class="h-10 w-10 text-red-500" />
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">
                    ToDo Not Found
                </h3>
                <p class="text-gray-500 mb-6">
                    The requested todo item could not be found.
                </p>
                <router-link
                    :to="{ name: 'ListTodos' }"
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-teal-600 to-cyan-600 text-white font-semibold rounded-lg shadow-sm hover:from-teal-700 hover:to-cyan-700 transition-all duration-200"
                >
                    <ArrowLeft class="h-5 w-5 mr-2" />
                    Back to List
                </router-link>
            </div> -->

            <!-- Success Message -->
            <div
                v-if="showSuccess"
                class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg shadow-lg z-50 transition-all duration-300"
            >
                <div class="flex items-center">
                    <CheckCircle class="h-5 w-5 mr-2" />
                    <span class="font-medium">ToDo updated successfully!</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useStore } from "vuex";

import {
    AlertCircle,
    ArrowLeft,
    CheckCircle,
    Edit,
    RotateCcw,
    Save,
} from "lucide-vue-next";

import { FETCH_TODO_DETAILS, UPDATE_TODO } from "@/services/store/actions.type";

// Core setup
const route = useRoute();
const router = useRouter();
const store = useStore();
const todoId = route.query.todoId;

// State
const isLoading = ref(true);
const isSubmitting = ref(false);
const showSuccess = ref(false);
const error = ref(null);
const todoDetails = computed(() => store.getters["todo/todoDetails"]);

// Form data
const form = ref({
    heading: "",
    description: "",
    status: "",
    priority: "",
    due_date: "",
});

// Fetch ToDo details from store
const getTodoDetails = async () => {
    isLoading.value = true;
    try {
        await store.dispatch("todo/" + FETCH_TODO_DETAILS, { todoId });

        const data = todoDetails.value;
        if (data) {
            form.value = {
                heading: data.heading || "",
                description: data.description || "",
                status: data.status || "",
                priority: data.priority || "",
                due_date: data.due_date || "",
            };
        }
    } catch (err) {
        console.error("Error fetching todo details:", err?.response?.data?.message || err.message);
    } finally {
        isLoading.value = false;
    }
};

// Submit update
const updateTodo = async () => {
    isSubmitting.value = true;
    error.value = null;

    if (
        !form.value.heading ||
        !form.value.status ||
        !form.value.priority ||
        !form.value.due_date
    ) {
        error.value = "Please fill all required fields.";
        isSubmitting.value = false;
        return;
    }

    try {
        const payload = {
            todoId,
            heading: form.value.heading.trim(),
            description: form.value.description?.trim() || null,
            status: form.value.status,
            priority: form.value.priority,
            due_date: form.value.due_date,
        };

        await store.dispatch("todo/" + UPDATE_TODO, payload);

        // Show success and navigate
        showSuccess.value = true;
        setTimeout(() => {
            showSuccess.value = false;
            router.push({ name: "ListTodos" });
        }, 1500);
    } catch (err) {
        console.error("Error updating todo:", err);
        error.value = err?.response?.data?.message || "Error updating ToDo. Please try again.";
    } finally {
        isSubmitting.value = false;
    }
};

// Reset form
const resetForm = () => {
    const data = todoDetails.value;
    if (data) {
        form.value = {
            heading: data.heading || "",
            description: data.description || "",
            status: data.status || "",
            priority: data.priority || "",
            due_date: data.due_date || "",
        };
    }
};

// Lifecycle
onMounted(() => {
    getTodoDetails();
});
</script>
