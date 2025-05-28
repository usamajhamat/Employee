<template>
    <pre>{{ companyId }}</pre>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div
                class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6"
            >
                <div
                    class="bg-gradient-to-r from-teal-600 to-cyan-600 px-6 py-4 rounded-t-lg"
                >
                    <div class="flex items-center">
                        <Building2 class="h-6 w-6 text-white mr-3" />
                        <div>
                            <h1 class="text-xl font-semibold text-white">
                                Add New Company
                            </h1>
                            <p class="text-teal-100 mt-1">
                                Create a new company profile
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <form @submit.prevent="handleSubmit" class="p-6 space-y-6">
                    <!-- Error Display -->
                    <div
                        v-if="error"
                        class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6"
                    >
                        {{ error }}
                    </div>
                    <div
                        v-if="formErrors.length"
                        class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg mb-6"
                    >
                        <ul class="list-disc pl-5">
                            <li v-for="error in formErrors" :key="error">
                                {{ error }}
                            </li>
                        </ul>
                    </div>

                    <!-- Company Information Section -->
                    <div class="pb-6">
                        <h2
                            class="text-lg font-medium text-gray-900 mb-4 flex items-center"
                        >
                            <Building class="h-5 w-5 text-teal-600 mr-2" />
                            Company Information
                        </h2>

                        <div class="space-y-4">
                            <!-- Company Name -->
                            <div>
                                <label
                                    for="company_name"
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >
                                    Company Name
                                    <span class="text-red-500">*</span>
                                </label>
                                <input
                                    id="company_name"
                                    v-model="form.company_name"
                                    type="text"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                    placeholder="Enter company name"
                                    @input="sanitizeInput('company_name')"
                                />
                                <p class="mt-1 text-sm text-gray-500">
                                    Enter the full legal name of the company
                                </p>
                            </div>

                            <!-- Company Description -->
                            <div>
                                <label
                                    for="description"
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >
                                    Company Description
                                </label>
                                <textarea
                                    id="description"
                                    v-model="form.description"
                                    rows="4"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                    placeholder="Enter company description, business activities, or any relevant information..."
                                    @input="sanitizeInput('description')"
                                ></textarea>
                                <p class="mt-1 text-sm text-gray-500">
                                    Provide a brief description of the company's
                                    business activities and services
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div
                        class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200"
                    >
                        <button
                            type="submit"
                            :disabled="
                                isSubmitting || !form.company_name.trim()
                            "
                            class="flex-1 sm:flex-none px-6 py-3 bg-gradient-to-r from-teal-600 to-cyan-600 text-white font-medium rounded-lg shadow-sm hover:from-teal-700 hover:to-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span
                                v-if="!isSubmitting"
                                class="flex items-center justify-center"
                            >
                                <Save class="h-4 w-4 mr-2" />
                                Add Company
                            </span>
                            <span
                                v-else
                                class="flex items-center justify-center"
                            >
                                <div
                                    class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"
                                ></div>
                                Adding Company...
                            </span>
                        </button>

                        <button
                            type="button"
                            @click="resetForm"
                            class="flex-1 sm:flex-none px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-lg shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200"
                        >
                            <span class="flex items-center justify-center">
                                <RotateCcw class="h-4 w-4 mr-2" />
                                Reset Form
                            </span>
                        </button>

                        <router-link
                            :to="{ name: 'ListCompanies' }"
                            class="flex-1 sm:flex-none px-6 py-3 bg-white text-gray-700 font-medium rounded-lg shadow-sm border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all duration-200 text-center"
                        >
                            <span class="flex items-center justify-center">
                                <ArrowLeft class="h-4 w-4 mr-2" />
                                Back to List
                            </span>
                        </router-link>
                    </div>
                </form>
            </div>

            <!-- Preview Card -->
            <div
                v-if="form.company_name.trim()"
                class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200"
            >
                <div class="p-6">
                    <h3
                        class="text-lg font-medium text-gray-900 mb-4 flex items-center"
                    >
                        <Eye class="h-5 w-5 text-teal-600 mr-2" />
                        Preview
                    </h3>

                    <div
                        class="bg-gradient-to-r from-teal-50 to-cyan-50 rounded-lg p-4 border border-teal-200"
                    >
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div
                                    class="h-12 w-12 rounded-lg bg-gradient-to-r from-teal-500 to-cyan-500 flex items-center justify-center"
                                >
                                    <Building2 class="h-6 w-6 text-white" />
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <h4 class="text-lg font-semibold text-gray-900">
                                    {{ form.company_name }}
                                </h4>
                                <p
                                    v-if="form.description.trim()"
                                    class="mt-2 text-sm text-gray-600 leading-relaxed"
                                >
                                    {{ form.description }}
                                </p>
                                <p
                                    v-else
                                    class="mt-2 text-sm text-gray-400 italic"
                                >
                                    No description provided
                                </p>
                                <div
                                    class="mt-3 flex items-center text-xs text-gray-500"
                                >
                                    <Calendar class="h-3 w-3 mr-1" />
                                    Created:
                                    {{ new Date().toLocaleDateString() }}
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
import { computed, ref } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useStore } from "vuex";
import {
    ArrowLeft,
    Building,
    Building2,
    Calendar,
    Eye,
    RotateCcw,
    Save,
} from "lucide-vue-next";
import {
    FETCH_COMPANY_DETAILS,
    SAVE_COMPANY,
} from "@/services/store/actions.type";
import { toast } from "vue3-toastify";
import { onMounted } from "vue";

const router = useRouter();
const store = useStore();
const isSubmitting = ref(false);
const error = ref(null);
const formErrors = ref([]);
const route = useRoute();
const form = ref({
    company_name: "",
    description: "",
});

const companyId = route.query.companyId;

const companyDetails = computed(() => store.getters["company/companyDetails"]);

function getCompanyDetails() {
    store
        .dispatch("company/" + FETCH_COMPANY_DETAILS, {
            companyId: companyId,
        })
        .then(() => {
           form.value.company_name = companyDetails.value.company_name || "";
            form.value.description = companyDetails.value.description || "";
        })
        .catch((err) => {
            error.value =
                err.response?.data?.message ||
                "Error fetching company details.";
        });
}

function updateCompanyDetails() {
     isSubmitting.value = true;
    error.value = null;
    formErrors.value = [];

    if (!validateForm()) {
        isSubmitting.value = false;
        return;
    }

    try {
        const companyData = {
            company_name: form.value.company_name.trim(),
            description: form.value.description.trim() || null,
        };

         store.dispatch("company/" + UPDATE_COMPANY_DETAILS, companyData);
        
        router.push({ name: "ListCompanies" });
    } catch (err) {
        error.value =
            err.response?.data?.message ||
            "Error adding company. Please try again.";
    } finally {
        isSubmitting.value = false;
    }
}

// Form data


// Input sanitization
const sanitizeInput = (field) => {
    const value = form.value[field];
    if (typeof value === "string") {
        form.value[field] = value.replace(/[<>]/g, "");
    }
};

// Form validation
const validateForm = () => {
    formErrors.value = [];

    if (!form.value.company_name.trim()) {
        formErrors.value.push("Company name is required");
    }
    if (form.value.company_name.length > 255) {
        formErrors.value.push("Company name must not exceed 255 characters");
    }
    if (form.value.description.length > 1000) {
        formErrors.value.push("Description must not exceed 1000 characters");
    }

    return formErrors.value.length === 0;
};

// Form submission
const handleSubmit = async () => {
    isSubmitting.value = true;
    error.value = null;
    formErrors.value = [];

    if (!validateForm()) {
        isSubmitting.value = false;
        return;
    }

    try {
        const companyData = {
            company_name: form.value.company_name.trim(),
            description: form.value.description.trim() || null,
        };

        await store.dispatch("company/" + SAVE_COMPANY, companyData);
        // toast.success('Company added successfully!', {
        //   position: 'top-right',
        //   autoClose: 3000,
        //   hideProgressBar: false,
        //   closeOnClick: true,
        //   pauseOnHover: true,
        //   draggable: true,
        //   progress: undefined
        // });
        router.push({ name: "ListCompanies" });
    } catch (err) {
        error.value =
            err.response?.data?.message ||
            "Error adding company. Please try again.";
    } finally {
        isSubmitting.value = false;
    }
};

// Reset form
const resetForm = () => {
    form.value = {
        company_name: "",
        description: "",
    };
    error.value = null;
    formErrors.value = [];
};
onMounted(() => {
    getCompanyDetails();
});
</script>
