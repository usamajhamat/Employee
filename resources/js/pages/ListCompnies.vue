<template>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div
                class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6"
            >
                <div
                    class="bg-gradient-to-r from-teal-600 to-cyan-600 px-6 py-4 rounded-t-lg"
                >
                    <div
                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between"
                    >
                        <div class="flex items-center">
                            <Building2 class="h-6 w-6 text-white mr-3" />
                            <div>
                                <h1 class="text-xl font-semibold text-white">
                                    Company List
                                </h1>
                                <p class="text-teal-100 mt-1">
                                    Manage and view all companies
                                </p>
                            </div>
                        </div>
                        <div class="mt-4 sm:mt-0">
                            <router-link
                                :to="{ name: 'AddCompany' }"
                                class="inline-flex items-center px-4 py-2 bg-white text-teal-600 font-medium rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition-all duration-200"
                            >
                                <Plus class="h-4 w-4 mr-2" />
                                Add New Company
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div
                class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 p-6"
            >
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <label
                            for="search"
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            Search Companies
                        </label>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                            >
                                <Search class="h-5 w-5 text-gray-400" />
                            </div>
                            <input
                                id="search"
                                v-model="searchQuery"
                                type="text"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                placeholder="Search by company name or description..."
                            />
                        </div>
                    </div>

                    <!-- Items per page -->
                    <div>
                        <label
                            for="items-per-page"
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            Items per page
                        </label>
                        <select
                            id="items-per-page"
                            v-model="itemsPerPage"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                        >
                            <option value="6">6</option>
                            <option value="12">12</option>
                            <option value="24">24</option>
                            <option value="48">48</option>
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div
                    class="flex flex-col sm:flex-row gap-3 mt-4 pt-4 border-t border-gray-200"
                >
                    <button
                        @click="clearSearch"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200"
                    >
                        <RotateCcw class="h-4 w-4 mr-2" />
                        Clear Search
                    </button>

                    <button
                        @click="exportCompanies"
                        class="inline-flex items-center px-4 py-2 bg-teal-100 text-teal-700 font-medium rounded-lg shadow-sm hover:bg-teal-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all duration-200"
                    >
                        <Download class="h-4 w-4 mr-2" />
                        Export CSV
                    </button>
                </div>
            </div>

            <!-- Loading State -->
            <div
                v-if="isLoading"
                class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center"
            >
                <div class="inline-flex items-center">
                    <div
                        class="animate-spin rounded-full h-6 w-6 border-b-2 border-teal-600 mr-3"
                    ></div>
                    <span class="text-gray-600">Loading companies...</span>
                </div>
            </div>

            <!-- Empty State -->
            <div
                v-else-if="filteredCompanies?.length === 0"
                class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center"
            >
                <Building2 class="h-12 w-12 text-gray-400 mx-auto mb-4" />
                <h3 class="text-lg font-medium text-gray-900 mb-2">
                    No companies found
                </h3>
                <p class="text-gray-500 mb-4">
                    {{
                        searchQuery
                            ? "Try adjusting your search terms"
                            : "Get started by adding your first company"
                    }}
                </p>
                <router-link
                    :to="{ name: 'AddCompany' }"
                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-teal-600 to-cyan-600 text-white font-medium rounded-lg shadow-sm hover:from-teal-700 hover:to-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all duration-200"
                >
                    <Plus class="h-4 w-4 mr-2" />
                    Add First Company
                </router-link>
            </div>

            <!-- Companies Grid -->
            <div v-else>
                <!-- Results Info -->
                <div
                    class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 px-6 py-4"
                >
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">
                            Companies ({{ filteredCompanies?.length || 0 }})
                        </h3>
                        <div class="text-sm text-gray-500">
                            Showing {{ startItem }} to {{ endItem }} of
                            {{ filteredCompanies?.length }} results
                        </div>
                    </div>
                </div>

                <!-- Grid Layout -->
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6"
                >
                    <div
                        v-for="company in paginatedCompanies"
                        :key="company.id"
                        class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-all duration-200 overflow-hidden"
                    >
                        <!-- Company Header -->
                        <div
                            class="bg-gradient-to-r from-teal-50 to-cyan-50 px-6 py-4 border-b border-gray-200"
                        >
                            <div class="flex items-start justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="h-12 w-12 rounded-lg bg-gradient-to-r from-teal-500 to-cyan-500 flex items-center justify-center"
                                        >
                                            <Building2
                                                class="h-6 w-6 text-white"
                                            />
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <h3
                                            class="text-lg font-semibold text-gray-900 truncate"
                                        >
                                            {{ company?.company_name }}
                                        </h3>
                                        <div
                                            class="flex items-center text-xs text-gray-500 mt-1"
                                        >
                                            <Calendar class="h-3 w-3 mr-1" />
                                            Created:
                                            {{
                                                formatDate(company?.created_at)
                                            }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions Dropdown -->
                                <div class="relative">
                                    <button
                                        @click="toggleDropdown(company?.id)"
                                        class="p-1 rounded-full hover:bg-white/50 transition-colors duration-200"
                                    >
                                        <MoreVertical
                                            class="h-5 w-5 text-gray-500"
                                        />
                                    </button>

                                    <div
                                        v-if="activeDropdown === company.id"
                                        class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 border border-gray-200"
                                        @click.stop
                                    >
                                        <div class="py-1">
                                            <button
                                            @click="viewCompany(company)"
                                                class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200"
                                            >
                                                <Eye class="h-4 w-4 mr-2" />
                                                View Details
                                            </button>

                                            <router-link
                                                :to="{
                                                    name: 'EditCompany',
                                                    query: {
                                                        companyId: company.id,
                                                    },
                                                }"
                                            >
                                                <button
                                                    @click="
                                                        editCompany(company)
                                                    "
                                                    class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200"
                                                >
                                                    <Edit
                                                        class="h-4 w-4 mr-2"
                                                    />
                                                    Edit Company
                                                </button>
                                            </router-link>

                                            <button
                                                @click="
                                                    deleteCompany(company.id)
                                                "
                                                class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors duration-200"
                                            >
                                                <Trash2 class="h-4 w-4 mr-2" />
                                                Delete Company
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Company Content -->
                        <div class="p-6">
                            <div class="mb-4">
                                <h4
                                    class="text-sm font-medium text-gray-700 mb-2"
                                >
                                    Description
                                </h4>
                                <p
                                    class="text-sm text-gray-600 leading-relaxed line-clamp-3"
                                >
                                    {{
                                        company.description ||
                                        "No description provided"
                                    }}
                                </p>
                            </div>
                        </div>

                        <!-- Company Footer -->
                        <div
                            class="px-6 py-3 bg-gray-50 border-t border-gray-200"
                        >
                            <div class="flex items-center justify-between">
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800"
                                >
                                    <div
                                        class="w-1.5 h-1.5 bg-green-400 rounded-full mr-1"
                                    ></div>
                                    Active
                                </span>
                                <button
                                    @click="viewCompany(company)"
                                    class="text-sm text-teal-600 hover:text-teal-800 font-medium transition-colors duration-200"
                                >
                                    View Details →
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div
                    v-if="filteredCompanies?.length > 0"
                    class="bg-white rounded-lg shadow-sm border border-gray-200 px-4 py-3"
                >
                    <div class="flex items-center justify-between">
                        <div class="flex-1 flex justify-between sm:hidden">
                            <button
                                @click="previousPage"
                                :disabled="currentPage === 1"
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                Previous
                            </button>
                            <button
                                @click="nextPage"
                                :disabled="currentPage === totalPages"
                                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                Next
                            </button>
                        </div>
                        <div
                            class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between"
                        >
                            <div>
                                <p class="text-sm text-gray-700">
                                    Showing
                                    <span class="font-medium">{{
                                        startItem
                                    }}</span>
                                    to
                                    <span class="font-medium">{{
                                        endItem
                                    }}</span>
                                    of
                                    <span class="font-medium">{{
                                        filteredCompanies.length
                                    }}</span>
                                    results
                                </p>
                            </div>
                            <div>
                                <nav
                                    class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                    aria-label="Pagination"
                                >
                                    <button
                                        @click="previousPage"
                                        :disabled="currentPage === 1"
                                        class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        <ChevronLeft class="h-5 w-5" />
                                    </button>

                                    <button
                                        v-for="page in visiblePages"
                                        :key="page"
                                        @click="goToPage(page)"
                                        :class="[
                                            'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                                            page === currentPage
                                                ? 'z-10 bg-teal-50 border-teal-500 text-teal-600'
                                                : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                                        ]"
                                    >
                                        {{ page }}
                                    </button>

                                    <button
                                        @click="nextPage"
                                        :disabled="currentPage === totalPages"
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
        </div>

        <!-- View Company Modal -->
        <div
        v-if="showViewModal"
        class="fixed inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full z-50 transition-opacity duration-300"
        @click="closeViewModal"
    >
        <div
            class="relative top-16 mx-auto p-6 border w-full max-w-lg sm:max-w-xl lg:max-w-2xl shadow-2xl rounded-xl bg-white"
            @click.stop
        >
            <div class="relative">
                <!-- Close Button (Top Right) -->
                <button
                    @click="closeViewModal"
                    class="absolute -top-2 -right-2 p-2 rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all duration-200"
                    aria-label="Close modal"
                >
                    <X class="h-5 w-5" />
                </button>

                <!-- Modal Header -->
                <div
                    class="bg-gradient-to-r from-teal-600 to-cyan-600 p-6 rounded-t-xl text-white shadow-sm"
                >
                    <div class="flex items-center space-x-4">
                        <div
                            class="h-12 w-12 rounded-lg bg-white bg-opacity-20 flex items-center justify-center"
                        >
                            <Building2 class="h-6 w-6 text-white" />
                        </div>
                        <div>
                            <h3 class="text-xl font-bold tracking-tight">
                                {{ selectedCompany?.company_name || "Company Details" }}
                            </h3>
                            <div class="flex items-center mt-2 text-sm text-teal-100">
                                <Calendar class="h-4 w-4 mr-1.5" />
                                Created: {{ formatDate(selectedCompany?.created_at) }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Body -->
                <div v-if="selectedCompany" class="p-6 space-y-6">
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Company Name -->
                        <div>
                            <label
                                class="block text-sm font-semibold text-gray-700 mb-2"
                            >
                                Company Name
                            </label>
                            <div
                                class="bg-gray-50 rounded-lg px-4 py-3 text-gray-900 text-sm border border-gray-200"
                            >
                                {{ selectedCompany.company_name }}
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label
                                class="block text-sm font-semibold text-gray-700 mb-2"
                            >
                                Description
                            </label>
                            <div
                                class="bg-gray-50 rounded-lg px-4 py-3 text-gray-900 text-sm border border-gray-200 leading-relaxed min-h-[100px]"
                            >
                                {{ selectedCompany.description || "No description provided" }}
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label
                                class="block text-sm font-semibold text-gray-700 mb-2"
                            >
                                Status
                            </label>
                            <span
                                class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-800"
                            >
                                <div
                                    class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"
                                ></div>
                                Active
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="p-6 border-t border-gray-200 flex justify-end">
                    <button
                        @click="closeViewModal"
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-teal-600 to-cyan-600 text-white font-medium rounded-lg shadow-sm hover:from-teal-700 hover:to-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all duration-200"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import {
    Building2,
    Calendar,
    ChevronLeft,
    ChevronRight,
    Download,
    Edit,
    Eye,
    MoreVertical,
    Plus,
    RotateCcw,
    Search,
    Trash2,
    X,
} from "lucide-vue-next";
import { useRouter } from "vue-router";
import { DELETE_COMPANY, FETCH_COMPANY } from "@/services/store/actions.type";
import { useStore } from "vuex";

const router = useRouter();

// State
const isLoading = ref(true);
const companies = ref([]);
const searchQuery = ref("");
const itemsPerPage = ref(6);
const currentPage = ref(1);
const showViewModal = ref(false);
const selectedCompany = ref(null);
const activeDropdown = ref(null);
const store = useStore();

// Sample data - replace with API call
const companiesData = computed(() => store.getters["company/companyData"]);




// Computed properties
const filteredCompanies = computed(() => {
    if (!searchQuery.value) return companiesData.value;

    const query = searchQuery.value.toLowerCase();
    return companiesData?.value?.filter(
        (company) =>
            company.company_name.toLowerCase().includes(query) ||
            (company.description &&
                company.description.toLowerCase().includes(query))
    );
});

const totalPages = computed(() =>
    Math.ceil(filteredCompanies?.value?.length / itemsPerPage.value)
);

const paginatedCompanies = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;

    const companies = Array.isArray(filteredCompanies.value)
        ? filteredCompanies.value
        : [];

    return companies.slice(start, end);
});

const startItem = computed(() => {
    return filteredCompanies?.value?.length === 0
        ? 0
        : (currentPage.value - 1) * itemsPerPage.value + 1;
});

const endItem = computed(() => {
    const end = currentPage.value * itemsPerPage.value;
    return end > filteredCompanies?.value?.length
        ? filteredCompanies?.value?.length
        : end;
});

const visiblePages = computed(() => {
    const pages = [];
    const total = totalPages.value;
    const current = currentPage.value;

    if (total <= 7) {
        for (let i = 1; i <= total; i++) {
            pages.push(i);
        }
    } else {
        if (current <= 4) {
            for (let i = 1; i <= 5; i++) {
                pages.push(i);
            }
            pages.push("...");
            pages.push(total);
        } else if (current >= total - 3) {
            pages.push(1);
            pages.push("...");
            for (let i = total - 4; i <= total; i++) {
                pages.push(i);
            }
        } else {
            pages.push(1);
            pages.push("...");
            for (let i = current - 1; i <= current + 1; i++) {
                pages.push(i);
            }
            pages.push("...");
            pages.push(total);
        }
    }

    return pages;
});

// Methods
const loadCompanies = async () => {
    isLoading.value = true;
    try {
        // Simulate API call
        store.dispatch("company/" + FETCH_COMPANY);
    } catch (error) {
        console.error("Error loading companies:", error);
    } finally {
        isLoading.value = false;
    }
};

function deleteCompany(companyId) {
    store
        .dispatch("company/" + DELETE_COMPANY, { companyId })
        .then(() => {
            loadCompanies(); // Refresh the list after deletion
        })
        .catch((error) => {
            console.error("Error deleting Company:", error);
            toast("Failed to delete Company. Please try again.", {
                type: "error",
            });
        });
}

const clearSearch = () => {
    searchQuery.value = "";
    currentPage.value = 1;
};

const exportCompanies = () => {
    // Create CSV content
    const headers = ["Company Name", "Description", "Created Date"];
    const csvContent = [
        headers.join(","),
        ...filteredCompanies?.value?.map((company) =>
            [
                `"${company.company_name}"`,
                `"${company.description || ""}"`,
                company.created_at || "",
            ].join(",")
        ),
    ].join("\n");

    // Download CSV
    const blob = new Blob([csvContent], { type: "text/csv" });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement("a");
    a.href = url;
    a.download = "companies.csv";
    a.click();
    window.URL.revokeObjectURL(url);
};

const formatDate = (dateString) => {
    if (!dateString) return "N/A";
    const date = new Date(dateString);
    return date.toLocaleDateString("en-GB");
};

const toggleDropdown = (companyId) => {
    activeDropdown.value =
        activeDropdown.value === companyId ? null : companyId;
};

const viewCompany = (company) => {
    selectedCompany.value = company;
    showViewModal.value = true;
    activeDropdown.value = null;
};

const editCompany = (company) => {
    selectedCompany.value = company;
    showViewModal.value = true;
    activeDropdown.value = null;
};

const closeViewModal = () => {
    showViewModal.value = false;
    selectedCompany.value = null;
};

// const editCompany = (company) => {
//     router.push({ name: "EditCompany", params: { id: company.id } });
//     activeDropdown.value = null;
// };

// Pagination methods
const previousPage = () => {
    if (currentPage.value > 1) {
        currentPage.value--;
    }
};

const nextPage = () => {
    if (currentPage.value < totalPages.value) {
        currentPage.value++;
    }
};

const goToPage = (page) => {
    if (typeof page === "number") {
        currentPage.value = page;
    }
};

// Close dropdown when clicking outside
const handleClickOutside = (event) => {
    if (!event.target.closest(".relative")) {
        activeDropdown.value = null;
    }
};

// Lifecycle
onMounted(() => {
    loadCompanies();
    document.addEventListener("click", handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener("click", handleClickOutside);
});
</script>

<style scoped>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
