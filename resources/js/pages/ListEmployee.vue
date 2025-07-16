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
                            class="block text-sm font-medium text-gray-700 mb-1"
                            for="search"
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
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                placeholder="Search by name, candidate ID, or company..."
                                type="text"
                                @input="loadEmployees"
                            />
                        </div>
                    </div>

                    <!-- Company Filter -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            for="company-filter"
                        >
                            Filter by Company
                        </label>
                        <select
                            id="company-filter"
                            v-model="selectedCompany"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                            @change="loadEmployees"
                        >
                            <option value="">All Companies</option>
                            <option
                                v-for="company in companiesData?.data"
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
                            class="block text-sm font-medium text-gray-700 mb-1"
                            for="status-filter"
                        >
                            Filter by Status
                        </label>
                        <select
                            id="status-filter"
                            v-model="selectedStatus"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                            @change="loadEmployees"
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
                        class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200"
                        @click="clearFilters"
                    >
                        <RotateCcw class="h-4 w-4 mr-2" />
                        Clear Filters
                    </button>
                    <button
                        class="inline-flex items-center px-4 py-2 bg-teal-100 text-teal-700 font-medium rounded-lg shadow-sm hover:bg-teal-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all duration-200"
                        @click="exportEmployees"
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
                            Employees ({{ employeesData?.total || 0 }})
                        </h3>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-500">Show:</span>
                            <select
                                v-model="itemsPerPage"
                                class="px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                                @change="loadEmployees"
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
                    v-else-if="employeesData?.data?.length === 0"
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
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    scope="col"
                                >
                                    Employee
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    scope="col"
                                >
                                    Company
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    scope="col"
                                >
                                    Contact
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    scope="col"
                                >
                                    Join Date
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    scope="col"
                                >
                                    Status
                                </th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    scope="col"
                                >
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr
                                v-for="employee in employeesData?.data"
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
                                        :class="getStatusClass(employee.status)"
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
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
                                            class="text-teal-600 hover:text-teal-900 p-1 rounded-full hover:bg-teal-50 transition-all duration-200"
                                            title="View Employee"
                                            @click="viewEmployee(employee)"
                                        >
                                            <Eye class="h-4 w-4" />
                                        </button>
                                        <router-link
                                            :to="{
                                                name: 'EditEmployee',
                                                query: { id: employee.id },
                                            }"
                                        >
                                            <button
                                                class="text-blue-600 hover:text-blue-900 p-1 rounded-full hover:bg-blue-50 transition-all duration-200"
                                                title="Edit Employee"
                                                @click="
                                                    editEmployee(employee.id)
                                                "
                                            >
                                                <Edit class="h-4 w-4" />
                                            </button>
                                        </router-link>
                                        <button
                                            class="text-red-600 hover:text-red-900 p-1 rounded-full hover:bg-red-50 transition-all duration-200"
                                            title="Delete Employee"
                                            @click="deleteEmployee(employee.id)"
                                        >
                                            <Trash2 class="h-4 w-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Backend Pagination -->
                <div
                    v-if="employeesData?.data?.length > 0"
                    class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6"
                >
                    <div class="flex items-center justify-between">
                        <div class="flex-1 flex justify-between sm:hidden">
                            <button
                                :disabled="!employeesData?.prev_page_url"
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                                @click="navigate(employeesData?.prev_page_url)"
                            >
                                Previous
                            </button>
                            <button
                                :disabled="!employeesData?.next_page_url"
                                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                                @click="navigate(employeesData?.next_page_url)"
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
                                        employeesData?.from || 0
                                    }}</span>
                                    to
                                    <span class="font-medium">{{
                                        employeesData?.to || 0
                                    }}</span>
                                    of
                                    <span class="font-medium">{{
                                        employeesData?.total || 0
                                    }}</span>
                                    results
                                </p>
                            </div>
                            <div>
                                <nav
                                    aria-label="Pagination"
                                    class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                >
                                    <button
                                        :disabled="
                                            !employeesData?.prev_page_url
                                        "
                                        class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                                        @click="
                                            navigate(
                                                employeesData?.prev_page_url
                                            )
                                        "
                                    >
                                        <ChevronLeft class="h-5 w-5" />
                                    </button>
                                    <button
                                        v-for="link in employeesData?.links"
                                        :key="link.label"
                                        :class="[
                                            'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                                            link.active
                                                ? 'z-10 bg-teal-50 border-teal-500 text-teal-600'
                                                : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                                            !link.url
                                                ? 'opacity-50 cursor-not-allowed'
                                                : '',
                                        ]"
                                        :disabled="!link.url"
                                        @click="navigate(link.url)"
                                        v-html="link.label"
                                    ></button>
                                    <button
                                        :disabled="
                                            !employeesData?.next_page_url
                                        "
                                        class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                                        @click="
                                            navigate(
                                                employeesData?.next_page_url
                                            )
                                        "
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
            class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50 transition-opacity duration-300"
            @click="closeViewModal"
        >
            <div
                class="relative mx-auto p-6 border w-11/12 sm:w-3/4 lg:w-2/5 max-w-2xl bg-white rounded-xl shadow-2xl"
                @click.stop
            >
                <div class="relative">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-800">
                            Employee Details
                        </h3>
                        <button
                            aria-label="Close modal"
                            class="text-gray-500 hover:text-gray-700 transition-colors duration-200"
                            @click="closeViewModal"
                        >
                            <X class="h-6 w-6" />
                        </button>
                    </div>

                    <div v-if="selectedEmployee" class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-600"
                                    >Name</label
                                >
                                <p
                                    class="mt-1 text-base text-gray-900 font-medium"
                                >
                                    {{ selectedEmployee?.name || "N/A" }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-600"
                                    >Candidate ID</label
                                >
                                <p
                                    class="mt-1 text-base text-gray-900 font-medium"
                                >
                                    {{
                                        selectedEmployee?.candidate_id || "N/A"
                                    }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-600"
                                    >Company</label
                                >
                                <p
                                    class="mt-1 text-base text-gray-900 font-medium"
                                >
                                    {{
                                        selectedEmployee?.company ||
                                        "Not Assigned"
                                    }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-600"
                                    >Status</label
                                >
                                <span
                                    :class="
                                        getStatusClass(selectedEmployee?.status)
                                    "
                                    class="inline-flex px-3 py-1 text-xs font-semibold rounded-full mt-1"
                                >
                                    {{ selectedEmployee?.status || "Pending" }}
                                </span>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-600"
                                    >Contact Number</label
                                >
                                <p
                                    class="mt-1 text-base text-gray-900 font-medium"
                                >
                                    {{
                                        selectedEmployee?.contact_number ||
                                        "N/A"
                                    }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-600"
                                    >Emergency Contact</label
                                >
                                <p
                                    class="mt-1 text-base text-gray-900 font-medium"
                                >
                                    {{
                                        selectedEmployee?.emergency_contact ||
                                        "N/A"
                                    }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-600"
                                    >Date of Birth</label
                                >
                                <p
                                    class="mt-1 text-base text-gray-900 font-medium"
                                >
                                    {{
                                        selectedEmployee?.dob
                                            ? formatDate(selectedEmployee?.dob)
                                            : "N/A"
                                    }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-600"
                                    >Gender</label
                                >
                                <p
                                    class="mt-1 text-base text-gray-900 font-medium"
                                >
                                    {{ selectedEmployee?.gender || "N/A" }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-600"
                                    >State</label
                                >
                                <p
                                    class="mt-1 text-base text-gray-900 font-medium"
                                >
                                    {{ selectedEmployee?.state || "N/A" }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-600"
                                    >Join Date</label
                                >
                                <p
                                    class="mt-1 text-base text-gray-900 font-medium"
                                >
                                    {{
                                        selectedEmployee?.join_company
                                            ? formatDate(
                                                  selectedEmployee?.join_company
                                              )
                                            : "N/A"
                                    }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-600"
                                    >Bank Account Number</label
                                >
                                <p
                                    class="mt-1 text-base text-gray-900 font-medium"
                                >
                                    {{
                                        selectedEmployee?.bank_account || "N/A"
                                    }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-600"
                                    >Residance</label
                                >
                                <p
                                    class="mt-1 text-base text-gray-900 font-medium"
                                >
                                    {{ selectedEmployee?.residance || "N/A" }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-600"
                                    >Join Accommodation Date</label
                                >
                                <p
                                    class="mt-1 text-base text-gray-900 font-medium"
                                >
                                    {{
                                        selectedEmployee?.join_accommodation
                                            ? formatDate(
                                                  selectedEmployee?.join_accommodation
                                              )
                                            : "N/A"
                                    }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-600"
                                    >Exit Accommodation Date</label
                                >
                                <p
                                    class="mt-1 text-base text-gray-900 font-medium"
                                >
                                    {{
                                        selectedEmployee?.exit_accommodation
                                            ? formatDate(
                                                  selectedEmployee?.exit_accommodation
                                              )
                                            : "N/A"
                                    }}
                                </p>
                            </div>
                        </div>
                        <div v-if="selectedEmployee?.address">
                            <label
                                class="block text-sm font-medium text-gray-600"
                                >Address</label
                            >
                            <p class="mt-1 text-base text-gray-900 font-medium">
                                {{ selectedEmployee?.address }}
                            </p>
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-600"
                                >Exit Company Date</label
                            >
                            <p class="mt-1 text-base text-gray-900 font-medium">
                                {{ selectedEmployee?.exit_company || "N/A" }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, onMounted, ref } from "vue";
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
const searchQuery = ref("");
const selectedCompany = ref("");
const selectedStatus = ref("");
const itemsPerPage = ref(10);
const currentPage = ref(1);
const showViewModal = ref(false);
const selectedEmployee = ref(null);

const employeesData = computed(() => store.getters["employee/employeeData"]);
const companiesData = computed(() => store.getters["company/companyData"]);

// Methods
const loadEmployees = async () => {
    isLoading.value = true;
    try {
        await store.dispatch("employee/" + FETCH_EMPLOYEES, {
            page: currentPage.value,
            per_page: itemsPerPage.value,
            search: searchQuery.value,
            company: selectedCompany.value,
            status: selectedStatus.value,
        });
    } catch (error) {
        console.error("Error loading employees:", error);
        toast("Failed to load employees. Please try again.", { type: "error" });
    } finally {
        isLoading.value = false;
    }
};

const loadCompanies = async () => {
    try {
        await store.dispatch("company/" + FETCH_COMPANY);
    } catch (error) {
        console.error("Error loading companies:", error);
        toast("Failed to load companies. Please try again.", { type: "error" });
    }
};

const deleteEmployee = async (employeeId) => {
    try {
        await store.dispatch("employee/" + DELETE_EMPLOYEE, { employeeId });
        loadEmployees(); // Refresh the list after deletion
    } catch (error) {
        console.error("Error deleting employee:", error);
        toast("Failed to delete employee. Please try again.", {
            type: "error",
        });
    }
};

const clearFilters = () => {
    searchQuery.value = "";
    selectedCompany.value = "";
    selectedStatus.value = "";
    currentPage.value = 1;
    loadEmployees();
};

const exportEmployees = () => {
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
        ...employeesData.value?.data?.map((emp) =>
            [
                `"${emp.name}"`,
                emp.candidate_id,
                `"${emp.company || ""}"`,
                `"${emp.contact_number || ""}"`,
                `"${emp.status || ""}"`,
                `"${emp.join_company || ""}"`,
            ].join(",")
        ),
    ].join("\n");

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

const editEmployee = (employeeId) => {
    // Navigation handled by router-link
};

const navigate = (url) => {
    if (!url) return;
    const params = new URLSearchParams(new URL(url).search);
    currentPage.value = parseInt(params.get("page")) || 1;
    loadEmployees();
};

// Lifecycle
onMounted(() => {
    loadEmployees();
    loadCompanies();
});
</script>
