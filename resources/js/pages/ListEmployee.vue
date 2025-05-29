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
                            <Users class="h-6 w-6 text-white mr-3" />
                            <div>
                                <h1 class="text-xl font-semibold text-white">
                                    Employee List
                                </h1>
                                <p class="text-teal-100 mt-1">
                                    Manage and view all employees
                                </p>
                            </div>
                        </div>
                        <div class="mt-4 sm:mt-0">
                            <router-link
                                :to="{ name: 'AddEmployee' }"
                                class="inline-flex items-center px-4 py-2 bg-white text-teal-600 font-medium rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition-all duration-200"
                            >
                                <UserPlus class="h-4 w-4 mr-2" />
                                Add New Employee
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters and Search -->
            <div
                class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 p-6"
            >
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4"
                >
                    <!-- Search -->
                    <div class="lg:col-span-2">
                        <label
                            for="search"
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            Search Employees
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
                                placeholder="Search by name, candidate ID, or company..."
                            />
                        </div>
                    </div>

                    <!-- Company Filter -->
                    <div>
                        <label
                            for="company-filter"
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            Filter by Company
                        </label>
                        <select
                            id="company-filter"
                            v-model="selectedCompany"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                        >
                            <option value="">All Companies</option>
                            <option
                                v-for="company in companiesData"
                                :key="company.id"
                                :value="company.company_name"
                            >
                                {{ company.company_name }}
                            </option>
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label
                            for="status-filter"
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            Filter by Status
                        </label>
                        <select
                            id="status-filter"
                            v-model="selectedStatus"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                        >
                            <option value="">All Status</option>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                            <option value="Pending">Pending</option>
                            <option value="On Leave">On Leave</option>
                            <option value="Terminated">Terminated</option>
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div
                    class="flex flex-col sm:flex-row gap-3 mt-4 pt-4 border-t border-gray-200"
                >
                    <button
                        @click="clearFilters"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200"
                    >
                        <RotateCcw class="h-4 w-4 mr-2" />
                        Clear Filters
                    </button>

                    <button
                        @click="exportEmployees"
                        class="inline-flex items-center px-4 py-2 bg-teal-100 text-teal-700 font-medium rounded-lg shadow-sm hover:bg-teal-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all duration-200"
                    >
                        <Download class="h-4 w-4 mr-2" />
                        Export CSV
                    </button>
                </div>
            </div>

            <!-- Employee Table -->
            <div
                class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden"
            >
                <!-- Table Header -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">
                            Employees ({{ filteredEmployees?.length }})
                        </h3>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-500">Show:</span>
                            <select
                                v-model="itemsPerPage"
                                class="px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                            >
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Loading State -->
                <div v-if="isLoading" class="p-8 text-center">
                    <div class="inline-flex items-center">
                        <div
                            class="animate-spin rounded-full h-6 w-6 border-b-2 border-teal-600 mr-3"
                        ></div>
                        <span class="text-gray-600">Loading employees...</span>
                    </div>
                </div>

                <!-- Empty State -->
                <div
                    v-else-if="filteredEmployees?.length === 0"
                    class="p-8 text-center"
                >
                    <Users class="h-12 w-12 text-gray-400 mx-auto mb-4" />
                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                        No employees found
                    </h3>
                    <p class="text-gray-500 mb-4">
                        {{
                            searchQuery || selectedCompany || selectedStatus
                                ? "Try adjusting your filters"
                                : "Get started by adding your first employee"
                        }}
                    </p>
                    <router-link
                        :to="{ name: 'AddEmployee' }"
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-teal-600 to-cyan-600 text-white font-medium rounded-lg shadow-sm hover:from-teal-700 hover:to-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all duration-200"
                    >
                        <UserPlus class="h-4 w-4 mr-2" />
                        Add First Employee
                    </router-link>
                </div>

                <!-- Table -->
                <div v-else class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Employee
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Company
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Contact
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Join Date
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Status
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr
                                v-for="employee in paginatedEmployees"
                                :key="employee.id"
                                class="hover:bg-gray-50 transition-colors duration-200"
                            >
                                <!-- Employee Info -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div
                                                class="h-10 w-10 rounded-full bg-gradient-to-r from-teal-400 to-cyan-400 flex items-center justify-center"
                                            >
                                                <span
                                                    class="text-white font-medium text-sm"
                                                >
                                                    {{
                                                        getInitials(
                                                            employee.name
                                                        )
                                                    }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div
                                                class="text-sm font-medium text-gray-900"
                                            >
                                                {{ employee.name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                ID: {{ employee.candidate_id }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Company -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ employee.company || "Not Assigned" }}
                                    </div>
                                </td>

                                <!-- Contact -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ employee.contact_number || "N/A" }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ employee.state || "N/A" }}
                                    </div>
                                </td>

                                <!-- Join Date -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{
                                            employee.join_company
                                                ? formatDate(
                                                      employee.join_company
                                                  )
                                                : "N/A"
                                        }}
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                                        :class="getStatusClass(employee.status)"
                                    >
                                        {{ employee.status || "Pending" }}
                                    </span>
                                </td>

                                <!-- Actions -->
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                                >
                                    <div
                                        class="flex items-center justify-end space-x-2"
                                    >
                                        <button
                                            @click="viewEmployee(employee)"
                                            class="text-teal-600 hover:text-teal-900 p-1 rounded-full hover:bg-teal-50 transition-all duration-200"
                                            title="View Employee"
                                        >
                                            <Eye class="h-4 w-4" />
                                        </button>
                                        <router-link 
                                            :to="{ name: 'EditEmployee', query: { id: employee.id } }">
                                            <button
                                            @click="editEmployee(employee.id)"
                                            class="text-blue-600 hover:text-blue-900 p-1 rounded-full hover:bg-blue-50 transition-all duration-200"
                                            title="Edit Employee"
                                        >
                                            <Edit class="h-4 w-4" />
                                        </button>
                                        </router-link>
                                        <button
                                            @click="deleteEmployee(employee.id)"
                                            class="text-red-600 hover:text-red-900 p-1 rounded-full hover:bg-red-50 transition-all duration-200"
                                            title="Delete Employee"
                                        >
                                            <Trash2 class="h-4 w-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div
                    v-if="filteredEmployees?.length > 0"
                    class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6"
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
                                        filteredEmployees.length
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

        <!-- View Employee Modal -->
        <div
            v-if="showViewModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
            @click="closeViewModal"
        >
            <div
                class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white"
                @click.stop
            >
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            Employee Details
                        </h3>
                        <button
                            @click="closeViewModal"
                            class="text-gray-400 hover:text-gray-600"
                        >
                            <X class="h-6 w-6" />
                        </button>
                    </div>

                    <div v-if="selectedEmployee" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Name</label
                                >
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ selectedEmployee?.name }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Candidate ID</label
                                >
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ selectedEmployee?.candidate_id }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Company</label
                                >
                                <p class="mt-1 text-sm text-gray-900">
                                    {{
                                        selectedEmployee?.company ||
                                        "Not Assigned"
                                    }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Status</label
                                >
                                <span
                                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                                    :class="
                                        getStatusClass(selectedEmployee?.status)
                                    "
                                >
                                    {{ selectedEmployee?.status || "Pending" }}
                                </span>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Contact Number</label
                                >
                                <p class="mt-1 text-sm text-gray-900">
                                    {{
                                        selectedEmployee?.contact_number ||
                                        "N/A"
                                    }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Emergency Contact</label
                                >
                                <p class="mt-1 text-sm text-gray-900">
                                    {{
                                        selectedEmployee?.emergency_contact ||
                                        "N/A"
                                    }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Date of Birth</label
                                >
                                <p class="mt-1 text-sm text-gray-900">
                                    {{
                                        selectedEmployee?.dob
                                            ? formatDate(selectedEmployee?.dob)
                                            : "N/A"
                                    }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Gender</label
                                >
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ selectedEmployee?.gender || "N/A" }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >State</label
                                >
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ selectedEmployee?.state || "N/A" }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Join Date</label
                                >
                                <p class="mt-1 text-sm text-gray-900">
                                    {{
                                        selectedEmployee?.join_company
                                            ? formatDate(
                                                  selectedEmployee?.join_company
                                              )
                                            : "N/A"
                                    }}
                                </p>
                            </div>
                        </div>

                        <div v-if="selectedEmployee?.address">
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >Address</label
                            >
                            <p class="mt-1 text-sm text-gray-900">
                                {{ selectedEmployee?.address }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import {
    ChevronLeft,
    ChevronRight,
    Download,
    Edit,
    Eye,
    RotateCcw,
    Search,
    Trash2,
    UserPlus,
    Users,
    X,
} from "lucide-vue-next";
import { useRouter } from "vue-router";
import {
    DELETE_EMPLOYEE,
    FETCH_COMPANY,
    FETCH_EMPLOYEES,
} from "@/services/store/actions.type";
import { useStore } from "vuex";
import { toast } from "vue3-toastify";

const router = useRouter();
const store = useStore();

// State
const isLoading = ref(true);
const employees = ref([]);
const searchQuery = ref("");
const selectedCompany = ref("");
const selectedStatus = ref("");
const itemsPerPage = ref(10);
const currentPage = ref(1);
const showViewModal = ref(false);
const selectedEmployee = ref(null);

const employeesData = computed(() => store.getters["employee/employeeData"]);
const companiesData = computed(() => store.getters["company/companyData"]);

// Sample data - replace with API call

// Computed properties
const filteredEmployees = computed(() => {
    let filtered = employeesData.value;

    // Search filter
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(
            (employee) =>
                employee.name.toLowerCase().includes(query) ||
                employee.candidate_id.toLowerCase().includes(query) ||
                (employee.company &&
                    employee.company.toLowerCase().includes(query))
        );
    }

    // Company filter
    if (selectedCompany.value) {
        filtered = filtered.filter(
            (employee) => employee.company === selectedCompany.value
        );
    }

    // Status filter
    if (selectedStatus.value) {
        filtered = filtered.filter(
            (employee) => employee.status === selectedStatus.value
        );
    }

    return filtered;
});

const totalPages = computed(() =>
    Math.ceil(filteredEmployees.value.length / itemsPerPage.value)
);

const paginatedEmployees = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;

    const employees = Array.isArray(filteredEmployees.value)
        ? filteredEmployees.value
        : [];

    return employees.slice(start, end);
});

const startItem = computed(() => {
    return filteredEmployees?.value?.length === 0
        ? 0
        : (currentPage.value - 1) * itemsPerPage.value + 1;
});

const endItem = computed(() => {
    const end = currentPage.value * itemsPerPage.value;
    return end > filteredEmployees?.value?.length
        ? filteredEmployees?.value?.length
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
const loadEmployees = async () => {
    isLoading.value = true;
    try {
        store.dispatch("employee/" + FETCH_EMPLOYEES);
    } catch (error) {
        console.error("Error loading employees:", error);
    } finally {
        isLoading.value = false;
    }
};

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

function deleteEmployee(employeeId) {
    store
        .dispatch("employee/" + DELETE_EMPLOYEE, { employeeId })
        .then(() => {
            loadEmployees(); // Refresh the list after deletion
        })
        .catch((error) => {
            console.error("Error deleting Employee:", error);
            toast("Failed to delete Employee. Please try again.", {
                type: "error",
            });
        });
}

const clearFilters = () => {
    searchQuery.value = "";
    selectedCompany.value = "";
    selectedStatus.value = "";
    currentPage.value = 1;
};

const exportEmployees = () => {
    // Create CSV content
    const headers = [
        "Name",
        "Candidate ID",
        "Company",
        "Contact",
        "Status",
        "Join Date",
    ];
    const csvContent = [
        headers.join(","),
        ...filteredEmployees.value.map((emp) =>
            [
                emp.name,
                emp.candidate_id,
                emp.company || "",
                emp.contact_number || "",
                emp.status || "",
                emp.join_company || "",
            ].join(",")
        ),
    ].join("\n");

    // Download CSV
    const blob = new Blob([csvContent], { type: "text/csv" });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement("a");
    a.href = url;
    a.download = "employees.csv";
    a.click();
    window.URL.revokeObjectURL(url);
};

const getInitials = (name) => {
    return name
        .split(" ")
        .map((n) => n[0])
        .join("")
        .toUpperCase();
};

const getStatusClass = (status) => {
    const classes = {
        Active: "bg-green-100 text-green-800",
        Inactive: "bg-gray-100 text-gray-800",
        Pending: "bg-yellow-100 text-yellow-800",
        "On Leave": "bg-blue-100 text-blue-800",
        Terminated: "bg-red-100 text-red-800",
    };
    return classes[status] || "bg-gray-100 text-gray-800";
};

const formatDate = (dateString) => {
    if (!dateString) return "N/A";
    const date = new Date(dateString);
    return date.toLocaleDateString("en-GB");
};

const viewEmployee = (employee) => {
    selectedEmployee.value = employee;
    showViewModal.value = true;
};

const closeViewModal = () => {
    showViewModal.value = false;
    selectedEmployee.value = null;
};

const editEmployee = (employee) => {
    // router.push({ name: "EditEmployee", params: { id: employee.id } });
};

// const deleteEmployee = (employee) => {
//     if (confirm(`Are you sure you want to delete ${employee.name}?`)) {
//         employees.value = employees.value.filter(
//             (emp) => emp.id !== employee.id
//         );
//         // Here you would typically make an API call to delete the employee
//         console.log("Deleting employee:", employee.id);
//     }
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

// Lifecycle
onMounted(() => {
    loadEmployees();
    loadCompanies();
});
</script>
