<template>
    <pre>{{ employeeId }}</pre>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="bg-gradient-to-r from-teal-600 to-cyan-600 px-6 py-4 rounded-t-lg">
                    <div class="flex items-center">
                        <UserPlus class="h-6 w-6 text-white mr-3" />
                        <div>
                            <h1 class="text-xl font-semibold text-white">
                                Edit Employee
                            </h1>
                            <p class="text-teal-100 mt-1">
                                Update employee information
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <form @submit.prevent="handleSubmit" class="p-6 space-y-6">
                    <!-- Error Display -->
                    <div v-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                        {{ error }}
                    </div>
                    <div v-if="formErrors.length" class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg mb-6">
                        <ul class="list-disc pl-5">
                            <li v-for="error in formErrors" :key="error">
                                {{ error }}
                            </li>
                        </ul>
                    </div>

                    <!-- Personal Information Section -->
                    <div class="border-b border-gray-200 pb-6">
                        <h2
                            class="text-lg font-medium text-gray-900 mb-4 flex items-center"
                        >
                            <User class="h-5 w-5 text-teal-600 mr-2"/>
                            Personal Information
                        </h2>

                        <div
                            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"
                        >
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    for="name"
                                >
                                    Full Name
                                    <span class="text-red-500">*</span>
                                </label>
                                <input
                                    id="name"
                                    v-model="form.name"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                    placeholder="Enter full name"
                                    required
                                    type="text"
                                    @input="sanitizeInput('name')"
                                />
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    for="candidate_id"
                                >
                                    Candidate ID
                                    <span class="text-red-500">*</span>
                                </label>
                                <input
                                    id="candidate_id"
                                    v-model="form.candidate_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                    placeholder="Enter candidate ID"
                                    required
                                    type="text"
                                    @input="sanitizeInput('candidate_id')"
                                />
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    for="ic_number"
                                >
                                    IC Number
                                </label>
                                <input
                                    id="ic_number"
                                    v-model="form.ic_number"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                    placeholder="Enter IC number"
                                    type="text"
                                    @input="sanitizeInput('ic_number')"
                                />
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    for="dob"
                                >
                                    Date of Birth
                                </label>
                                <input
                                    id="dob"
                                    v-model="form.dob"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                    max="2007-05-25"
                                    type="date"
                                />
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    for="gender"
                                >
                                    Gender
                                </label>
                                <select
                                    id="gender"
                                    v-model="form.gender"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                >
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    for="religion"
                                >
                                    Religion
                                </label>
                                <input
                                    id="religion"
                                    v-model="form.religion"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                    placeholder="Enter religion"
                                    type="text"
                                    @input="sanitizeInput('religion')"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information Section -->
                    <div class="border-b border-gray-200 pb-6">
                        <h2
                            class="text-lg font-medium text-gray-900 mb-4 flex items-center"
                        >
                            <Phone class="h-5 w-5 text-teal-600 mr-2"/>
                            Contact Information
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">


                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1" for="contact_name">
                                    Contact Name
                                </label>
                                <input
                                    id="contact_name"
                                    v-model="form.contact_name"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                    placeholder="Enter contact name"
                                    type="text"
                                    @input="sanitizeInput('contact_name')"
                                />
                            </div>

                            <!-- Contact Number: Keep pattern -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1" for="contact_number">
                                    Contact Number
                                </label>
                                <input
                                    id="contact_number"
                                    v-model="form.contact_number"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                    pattern="[0-9]{10,11}"
                                    placeholder="Enter contact number"
                                    type="tel"
                                    @input="sanitizeInput('contact_number')"
                                />
                            </div>

                            <!--Emergency Contact-->

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Emergency Contact Name
                                </label>
                                <input
                                    id="emergency_contact_name"
                                    v-model="form.emergency_contact_name"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                    placeholder="Enter emergency contact name"
                                    type="tel"
                                    @input="sanitizeInput('emergency_contact_name')"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Emergency Contact Number
                                </label>
                                <input
                                    id="emergency_contact"
                                    v-model="form.emergency_contact"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                    pattern="[0-9]{10,11}"
                                    placeholder="Enter emergency contact"
                                    type="tel"
                                    @input="sanitizeInput('emergency_contact')"
                                />
                            </div>

                            <!--Emergency Contact 2-->

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Emergency Contact Name
                                </label>
                                <input
                                    id="emergency_contact_two_name"
                                    v-model="form.emergency_contact_two_name"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                    placeholder="Enter emergency contact name"
                                    type="tel"
                                    @input="sanitizeInput('emergency_contact_two_name')"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Emergency Contact Number
                                </label>
                                <input
                                    id="emergency_contact_two"
                                    v-model="form.emergency_contact_two"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                    pattern="[0-9]{10,11}"
                                    placeholder="Enter emergency contact"
                                    type="tel"
                                    @input="sanitizeInput('emergency_contact_two')"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    State
                                </label>
                                <select
                                    id="state"
                                    v-model="form.state"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                >
                                    <option value="">Select State</option>
                                    <option
                                        v-for="state in stateList"
                                        :key="state.id"
                                        :value="state.name"
                                    >
                                        {{ state.name }}
                                    </option>
                                </select>
                            </div>

                            <div class="md:col-span-2">
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    for="address"
                                >
                                    Address
                                </label>
                                <textarea
                                    id="address"
                                    v-model="form.address"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                    placeholder="Enter full address"
                                    rows="3"
                                    @input="sanitizeInput('address')"
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Company Information Section -->
                    <div class="border-b border-gray-200 pb-6">
                        <h2
                            class="text-lg font-medium text-gray-900 mb-4 flex items-center"
                        >
                            <Building2 class="h-5 w-5 text-teal-600 mr-2"/>
                            Company Information
                        </h2>

                        <div
                            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"
                        >
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    for="company"
                                >
                                    Company
                                </label>
                                <select
                                    id="company"
                                    v-model="form.company"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                >
                                    <option value="">Select Company</option>
                                    <option
                                        v-for="company in companiesData?.data"
                                        :key="company.id"
                                        :value="company.company_name"
                                    >
                                        {{ company.company_name }}
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    for="status"
                                >
                                    Status
                                </label>
                                <select
                                    id="status"
                                    v-model="form.status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                >
                                    <option value="">Select Status</option>
                                    <option
                                        v-for="status in statusList"
                                        :key="status.id"
                                        :value="status.name"
                                    >
                                        {{ status.name }}
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1">
                                    Interview Date
                                </label>
                                <input
                                    id="interview_date"
                                    v-model="form.interview_date"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                    max="2025-05-25"
                                    type="date"
                                />
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    for="join_company"
                                >
                                    Join Company Date
                                </label>
                                <input
                                    id="join_company"
                                    v-model="form.join_company"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                    max="2025-05-25"
                                    type="date"
                                />
                            </div>

                            <!--Exit Company-->

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Exit Company Date
                                </label>
                                <input
                                    id="exit_company"
                                    v-model="form.exit_company"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                    max="2025-05-25"
                                    type="date"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Financial Information Section -->
                    <div class="border-b border-gray-200 pb-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <CreditCard class="h-5 w-5 text-teal-600 mr-2"/>
                            Financial Information
                        </h2>

                        <!-- Flex container for State and Bank Account Number -->
                        <div class="flex flex-col md:flex-row gap-4">
                            <!-- State Select -->
                            <div class="w-full md:w-1/3">
                                <label class="block text-sm font-medium text-gray-700 mb-1" for="state">
                                    Bank
                                </label>
                                <select
                                    id="bank_name"
                                    v-model="form.bank_name"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                >
                                    <option value="">Select Bank</option>
                                    <option v-for="state in bankList" :key="state.id" :value="state.name">
                                        {{ state.name }}
                                    </option>
                                </select>
                            </div>

                            <!-- Bank Account Input -->
                            <div class="w-full md:w-1/2">
                                <label class="block text-sm font-medium text-gray-700 mb-1" for="bank_account">
                                    Bank Account Number
                                </label>
                                <input
                                    id="bank_account"
                                    v-model="form.bank_account"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                    placeholder="Enter bank account number"
                                    type="text"
                                    @input="sanitizeInput('bank_account')"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Accommodation Section -->
                    <div class="pb-6">
                        <h2
                            class="text-lg font-medium text-gray-900 mb-4 flex items-center"
                        >
                            <Home class="h-5 w-5 text-teal-600 mr-2"/>
                            Accommodation
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1" for="state">
                                    State
                                </label>
                                <select
                                    id="state"
                                    v-model="form.residance"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                >
                                    <option value="">Select Residance</option>
                                    <option v-for="state in residanceList" :key="state.id" :value="state.name">
                                        {{ state.name }}
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    for="join_accommodation"
                                >
                                    Join Accommodation Date
                                </label>
                                <input
                                    id="join_accommodation"
                                    v-model="form.join_accommodation"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                    max="2025-05-25"
                                    type="date"
                                />
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    for="exit_accommodation"
                                >
                                    Exit Accommodation Date
                                </label>
                                <input
                                    id="exit_accommodation"
                                    v-model="form.exit_accommodation"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                    max="2025-05-25"
                                    type="date"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                        <button
                            type="submit"
                            :disabled="isSubmitting || !form.name.trim() || !form.candidate_id.trim()"
                            class="flex-1 sm:flex-none px-6 py-3 bg-gradient-to-r from-teal-600 to-cyan-600 text-white font-medium rounded-lg shadow-sm hover:from-teal-700 hover:to-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span v-if="!isSubmitting" class="flex items-center justify-center">
                                <Save class="h-4 w-4 mr-2" />
                                Update Employee
                            </span>
                            <span v-else class="flex items-center justify-center">
                                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                                Updating Employee...
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
                            :to="{ name: 'ListEmployees' }"
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
            <div v-if="form.name.trim()" class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                        <Eye class="h-5 w-5 text-teal-600 mr-2" />
                        Preview
                    </h3>

                    <div class="bg-gradient-to-r from-teal-50 to-cyan-50 rounded-lg p-4 border border-teal-200">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="h-12 w-12 rounded-lg bg-gradient-to-r from-teal-500 to-cyan-500 flex items-center justify-center">
                                    <User class="h-6 w-6 text-white" />
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <h4 class="text-lg font-semibold text-gray-900">
                                    {{ form.name }}
                                </h4>
                                <p v-if="form.company" class="mt-1 text-sm text-gray-600">
                                    Company: {{ form.company }}
                                </p>
                                <p v-if="form.candidate_id" class="mt-1 text-sm text-gray-600">
                                    Candidate ID: {{ form.candidate_id }}
                                </p>
                                <p v-if="form.status" class="mt-1 text-sm text-gray-600">
                                    Status: {{ form.status }}
                                </p>
                                <div class="mt-3 flex items-center text-xs text-gray-500">
                                    <Calendar class="h-3 w-3 mr-1" />
                                    Updated: {{ new Date().toLocaleDateString() }}
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
    Building2,
    Calendar,
    CreditCard,
    Eye,
    Home,
    Phone,
    RotateCcw,
    Save,
    User,
    UserPlus,
} from "lucide-vue-next";
import {
    FETCH_COMPANY,
    FETCH_EMPLOYEE_DETAILS,
    UPDATE_EMPLOYEE_DATA,
} from "@/services/store/actions.type";
import { onMounted } from "vue";

const router = useRouter();
const store = useStore();
const route = useRoute();
const isSubmitting = ref(false);
const error = ref(null);
const formErrors = ref([]);
const isLoading = ref(false);

const employeeId = route.query.id;
const companiesData = computed(() => store.getters["company/companyData"]);
const employeeDetails = computed(() => store.getters["employee/employeeDetails"]);
const statusList = [
    { id: "active", name: "Active" },
    { id: "absconded", name: "Absconded" },
    { id: "resigned", name: "Resigned" },
    { id: "rejected", name: "Rejected" },
    { id: "kiv ", name: "KIV " },
];

const residanceList = [
    { id: "hostel", name: "Hostel" },
    { id: "walk_in", name: "Walk In" },

];

const stateList = [
    { id: "johor", name: "Johor" },
    { id: "kedah", name: "Kedah" },
    { id: "kelantan", name: "Kelantan" },
    { id: "kuala_lumpur", name: "Kuala Lumpur" },
    { id: "labuan", name: "Labuan" },
    { id: "melaka", name: "Melaka" },
    { id: "negeri_sembilan", name: "Negeri Sembilan" },
    { id: "pahang", name: "Pahang" },
    { id: "penang", name: "Penang" },
    { id: "perak", name: "Perak" },
    { id: "perlis", name: "Perlis" },
    { id: "putrajaya", name: "Putrajaya" },
    { id: "sabah", name: "Sabah" },
    { id: "sarawak", name: "Sarawak" },
    { id: "selangor", name: "Selangor" },
    { id: "terengganu", name: "Terengganu" }
];

// Form data
const form = ref({
    company: "",
    candidate_id: "",
    contact_name: "",
    contact_number: "",
    name: "",
    ic_number: "",
    dob: "",
    gender: "",
    interview_date: "",
    religion: "",
    state: "",
    address: "",
    bank_account: "",
    bank_name: "",
    emergency_contact_name: "",
    emergency_contact: "",
    emergency_contact_two: "",
    emergency_contact_two_name: "",
    join_accommodation: "",
    exit_accommodation: "",
    join_company: "",
    exit_company: "",
    status: "",
    residance: "",
});

const bankList = [
    {id: "Maybank", name: "Maybank"},
    {id: "CIMB", name: "CIMB"},
    {id: "Public_Bank", name: "Public Bank"},
    {id: "RHB", name: "RHB"},
    {id: "Hong_Leong_Bank", name: "Hong Leong Bank"},
    {id: "AmBank", name: "AmBank"},
    {id: "UOB Malaysia", name: "UOB Malaysia"},
    {id: "Bank_Rakyat", name: "Bank Rakyat"},
    {id: "OCBC_Malaysia", name: "OCBC Malaysia"},
    {id: "HSBC_Malaysia", name: "HSBC Malaysia"},
    {id: "Bank_Islam", name: "Bank Islam"},
    {id: "Affin_Bank", name: "Affin Bank"},
    {id: "Alliance_Bank", name: "Alliance Bank"},
    {id: "Standard-Chartered_Malaysia", name: "Standard Chartered Malaysia"},
    {id: "MBSB_Bank", name: "MBSB Bank"},
    {id: "Citibank_Malaysia", name: "Citibank Malaysia"},
    {id: "BSN", name: "BSN"},
    {id: "Bank_Muamalat", name: "Bank Muamalat"},
    {id: "Agrobank", name: "Agrobank"},
    {id: "Al_Rajhi_Bank_Malaysia", name: "Al Rajhi Bank Malaysia"},
    {id: "Bank_Pertama", name: "Co-op Bank Pertama"}
];

function getEmployeeDetails() {
    isLoading.value = true;
    store
        .dispatch("employee/" + FETCH_EMPLOYEE_DETAILS, {
            employeeId: employeeId,
        })
        .then(() => {
            form.value = {
                company: employeeDetails.value.company || "",
                candidate_id: employeeDetails.value.candidate_id || "",
                contact_name: employeeDetails.value.contact_name || "",
                contact_number: employeeDetails.value.contact_number || "",
                name: employeeDetails.value.name || "",
                ic_number: employeeDetails.value.ic_number || "",
                dob: employeeDetails.value.dob || "",
                gender: employeeDetails.value.gender || "",
                interview_date: employeeDetails.value.interview_date || "",
                religion: employeeDetails.value.religion || "",
                state: employeeDetails.value.state || "",
                address: employeeDetails.value.address || "",
                bank_name: employeeDetails.value.bank_name || "",
                bank_account: employeeDetails.value.bank_account || "",
                emergency_contact_name: employeeDetails.value.emergency_contact_name || "",
                emergency_contact_two: employeeDetails.value.emergency_contact_two || "",
                emergency_contact_two_name: employeeDetails.value.emergency_contact_two_name || "",
                emergency_contact: employeeDetails.value.emergency_contact || "",
                join_accommodation: employeeDetails.value.join_accommodation || "",
                exit_accommodation: employeeDetails.value.exit_accommodation || "",
                join_company: employeeDetails.value.join_company || "",
                exit_company: employeeDetails.value.exit_company || "",
                status: employeeDetails.value.status || "",
                residance: employeeDetails.value.residance || "",
            };
        })
        .catch((err) => {
            error.value = err.response?.data?.message || "Error fetching employee details.";
        })
        .finally(() => {
            isLoading.value = false;
        });
}

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

    if (!form.value.name.trim()) {
        formErrors.value.push("Full Name is required");
    }
    if (!form.value.candidate_id.trim()) {
        formErrors.value.push("Candidate ID is required");
    }
    if (form.value.contact_number && !/^[0-9]{10,11}$/.test(form.value.contact_number)) {
        formErrors.value.push("Contact number must be 10-11 digits");
    }
    if (form.value.dob && new Date(form.value.dob) > new Date("2007-05-25")) {
        formErrors.value.push("Employee must be at least 18 years old");
    }
    if (
        form.value.join_accommodation &&
        form.value.exit_accommodation &&
        new Date(form.value.join_accommodation) > new Date(form.value.exit_accommodation)
    ) {
        formErrors.value.push("Join accommodation date must be before exit date");
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
        const employeeData = {
            employeeId: employeeId,
            company: form.value.company,
            candidate_id: form.value.candidate_id,
            contact_number: form.value.contact_number,
            name: form.value.name,
            ic_number: form.value.ic_number,
            dob: form.value.dob,
            gender: form.value.gender,
            interview_date: form.value.interview_date,
            religion: form.value.religion,
            state: form.value.state,
            address: form.value.address,
            bank_account: form.value.bank_account,
            emergency_contact: form.value.emergency_contact,
            join_accommodation: form.value.join_accommodation,
            exit_accommodation: form.value.exit_accommodation,
            join_company: form.value.join_company,
            exit_company: form.value.exit_company,
            status: form.value.status,
            residance: form.value.residance,
            bank_name: form.value.bank_name,
            emergency_contact_name: form.value.emergency_contact_name,
            emergency_contact_two: form.value.emergency_contact_two,
            emergency_contact_two_name: form.value.emergency_contact_two_name,
            contact_name: form.value.contact_name
        };

        await store.dispatch(`employee/${UPDATE_EMPLOYEE_DATA}`, employeeData);
        router.push({ name: "ListEmployees" });
    } catch (err) {
        error.value = err.response?.data?.message || "Error updating employee. Please try again.";
    } finally {
        isSubmitting.value = false;
    }
};

// Reset form
const resetForm = () => {
    getEmployeeDetails();
    error.value = null;
    formErrors.value = [];
};

const loadCompanies = async () => {
    isLoading.value = true;
    try {
        store.dispatch("company/" + FETCH_COMPANY);
    } catch (error) {
        console.error("Error loading companies:", error);
    } finally {
        isLoading.value = false;
    }
};

onMounted(() => {
    loadCompanies();
    getEmployeeDetails();
});
</script>