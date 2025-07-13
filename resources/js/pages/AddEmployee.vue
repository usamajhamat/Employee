<template>
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
                        <UserPlus class="h-6 w-6 text-white mr-3" />
                        <h1 class="text-xl font-semibold text-white">
                            Add New Employee
                        </h1>
                    </div>
                    <p class="text-teal-100 mt-1">
                        Fill in the employee information below
                    </p>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <form class="p-6 space-y-6" @submit.prevent="handleSubmit">
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

                    <!-- Personal Information Section -->
                    <div class="border-b border-gray-200 pb-6">
                        <h2
                            class="text-lg font-medium text-gray-900 mb-4 flex items-center"
                        >
                            <User class="h-5 w-5 text-teal-600 mr-2" /> Personal
                            Information
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
                                </label>
                                <input
                                    id="candidate_id"
                                    v-model="form.candidate_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                    placeholder="Enter candidate ID"
                                    type="text"
                                    @input="sanitizeInput('candidate_id')"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    for="ic_number"
                                    >IC Number</label
                                >
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
                                    >Date of Birth</label
                                >
                                <input
                                    id="dob"
                                    v-model="form.dob"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                    type="date"
                                    @input="formatDate('dob')"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    for="gender"
                                    >Gender</label
                                >
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
                                    >Religion</label
                                >
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
                            <Phone class="h-5 w-5 text-teal-600 mr-2" />
                            Contact Information
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    for="contact_name"
                                >
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
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    for="contact_number"
                                >
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
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >
                                    Emergency Contact Name
                                </label>
                                <input
                                    id="emergency_contact_name"
                                    v-model="form.emergency_contact_name"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                    placeholder="Enter emergency contact name"
                                    type="text"
                                    @input="
                                        sanitizeInput('emergency_contact_name')
                                    "
                                />
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >
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
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >
                                    Emergency Contact Name
                                </label>
                                <input
                                    id="emergency_contact_two_name"
                                    v-model="form.emergency_contact_two_name"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                    placeholder="Enter emergency contact name"
                                    type="text"
                                    @input="
                                        sanitizeInput(
                                            'emergency_contact_two_name'
                                        )
                                    "
                                />
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >
                                    Emergency Contact Number
                                </label>
                                <input
                                    id="emergency_contact_two"
                                    v-model="form.emergency_contact_two"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 Lilliputian"
                                    pattern="[0-9]{10,11}"
                                    placeholder="Enter emergency contact"
                                    type="tel"
                                    @input="
                                        sanitizeInput('emergency_contact_two')
                                    "
                                />
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >
                                    State
                                </label>
                                <input
                                    id="state"
                                    v-model="form.state"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                    placeholder="Enter State"
                                    type="text"
                                    @input="sanitizeInput('state')"
                                />
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
                            <Building2 class="h-5 w-5 text-teal-600 mr-2" />
                            Company Information
                        </h2>
                        <div
                            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"
                        >
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    for="company"
                                    >Company</label
                                >
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
                                    >Status</label
                                >
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
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    for="interview_date"
                                    >Interview Date</label
                                >
                                <input
                                    id="interview_date"
                                    v-model="form.interview_date"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                    type="date"
                                    @input="formatDate('interview_date')"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    for="join_company"
                                    >Join Company Date</label
                                >
                                <input
                                    id="join_company"
                                    v-model="form.join_company"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                    type="date"
                                    @input="formatDate('join_company')"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    >Exit Company Date</label
                                >
                                <input
                                    id="exit_company"
                                    v-model="form.exit_company"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                    type="date"
                                    @input="formatDate('exit_company')"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Financial Information Section -->
                    <div class="border-b border-gray-200 pb-6">
                        <h2
                            class="text-lg font-medium text-gray-900 mb-4 flex items-center"
                        >
                            <CreditCard class="h-5 w-5 text-teal-600 mr-2" />
                            Financial Information
                        </h2>
                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="w-full md:w-1/3">
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    for="bank_name"
                                    >Bank</label
                                >
                                <select
                                    id="bank_name"
                                    v-model="form.bank_name"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                >
                                    <option value="">Select Bank</option>
                                    <option
                                        v-for="state in bankList"
                                        :key="state.id"
                                        :value="state.name"
                                    >
                                        {{ state.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="w-full md:w-1/2">
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    for="bank_account"
                                    >Bank Account Number</label
                                >
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
                            <Home class="h-5 w-5 text-teal-600 mr-2" />
                            Accommodation
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    for="residance"
                                    >Residence</label
                                >
                                <select
                                    id="residance"
                                    v-model="form.residance"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                >
                                    <option value="">Select Residence</option>
                                    <option
                                        v-for="state in residanceList"
                                        :key="state.id"
                                        :value="state.name"
                                    >
                                        {{ state.name }}
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    for="join_accommodation"
                                    >Join Accommodation Date</label
                                >
                                <input
                                    id="join_accommodation"
                                    v-model="form.join_accommodation"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                    type="date"
                                    @input="formatDate('join_accommodation')"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    for="exit_accommodation"
                                    >Exit Accommodation Date</label
                                >
                                <input
                                    id="exit_accommodation"
                                    v-model="form.exit_accommodation"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                    type="date"
                                    @input="formatDate('exit_accommodation')"
                                />
                            </div>
                            <div v-if="form.residance === 'Hostel'">
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    for="room_number"
                                    >Room Number</label
                                >
                                <input
                                    id="room_number"
                                    v-model="form.room_number"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                    placeholder="Enter room number"
                                    type="text"
                                    @input="sanitizeInput('room_number')"
                                />
                            </div>
                            <div v-if="form.residance === 'Hostel'">
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    for="bed_number"
                                    >Bed Number</label
                                >
                                <input
                                    id="bed_number"
                                    v-model="form.bed_number"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                    placeholder="Enter bed number"
                                    type="text"
                                    @input="sanitizeInput('bed_number')"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div
                        class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200"
                    >
                        <button
                            :disabled="isSubmitting"
                            class="flex-1 sm:flex-none px-6 py-3 bg-gradient-to-r from-teal-600 to-cyan-600 text-white font-medium rounded-lg shadow-sm hover:from-teal-700 hover:to-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                            type="submit"
                        >
                            <span
                                v-if="!isSubmitting"
                                class="flex items-center justify-center"
                            >
                                <Save class="h-4 w-4 mr-2" />
                                Save Employee
                            </span>
                            <span
                                v-else
                                class="flex items-center justify-center"
                            >
                                <!--                                <div
                                    class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"
                                ></div>-->
                                Adding Employee...
                            </span>
                        </button>

                        <button
                            class="flex-1 sm:flex-none px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-lg shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200"
                            type="button"
                            @click="resetForm"
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
        </div>
    </div>
</template>

<script setup>
import { computed, onMounted, ref, watch } from "vue";
import { useRouter } from "vue-router";
import { useStore } from "vuex";
import {
    ArrowLeft,
    Building2,
    CreditCard,
    Home,
    Phone,
    RotateCcw,
    Save,
    User,
    UserPlus,
} from "lucide-vue-next";
import {
    FETCH_COMPANY,
    SAVE_EMPLOYEE_DATA,
} from "@/services/store/actions.type";

const router = useRouter();
const store = useStore();
const isSubmitting = ref(false);
const error = ref(null);
const formErrors = ref([]);
const isLoading = ref(false);

const companiesData = computed(() => store.getters["company/companyData"]);
const statusList = [
    { id: "active", name: "Active" },
    { id: "absconded", name: "Absconded" },
    { id: "resigned", name: "Resigned" },
    { id: "rejected", name: "Rejected" },
    { id: "kiv ", name: "KIV " },
    { id: "terminated ", name: "Terminated" },
];
const residanceList = [
    { id: "hostel", name: "Hostel" },
    { id: "walk_in", name: "Walk In" },
];
const bankList = [
    { id: "Maybank", name: "Maybank" },
    { id: "CIMB", name: "CIMB" },
    { id: "Public_Bank", name: "Public Bank" },
    { id: "RHB", name: "RHB" },
    { id: "Hong_Leong_Bank", name: "Hong Leong Bank" },
    { id: "AmBank", name: "AmBank" },
    { id: "UOB Malaysia", name: "UOB Malaysia" },
    { id: "Bank_Rakyat", name: "Bank Rakyat" },
    { id: "OCBC_Malaysia", name: "OCBC Malaysia" },
    { id: "HSBC_Malaysia", name: "HSBC Malaysia" },
    { id: "Bank_Islam", name: "Bank Islam" },
    { id: "Affin_Bank", name: "Affin Bank" },
    { id: "Alliance_Bank", name: "Alliance Bank" },
    { id: "Standard-Chartered_Malaysia", name: "Standard Chartered Malaysia" },
    { id: "MBSB_Bank", name: "MBSB Bank" },
    { id: "Citibank_Malaysia", name: "Citibank Malaysia" },
    { id: "BSN", name: "BSN" },
    { id: "Bank_Muamalat", name: "Bank Muamalat" },
    { id: "Agrobank", name: "Agrobank" },
    { id: "Al_Rajhi_Bank_Malaysia", name: "Al Rajhi Bank Malaysia" },
    { id: "Bank_Pertama", name: "Co-op Bank Pertama" },
];

const form = ref({
    name: "",
    company: "",
    candidate_id: "",
    contact_name: "",
    contact_number: "",
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
    room_number: "",
    bed_number: "",
});

const sanitizeInput = (field) => {
    const value = form.value[field];
    if (typeof value === "string") {
        form.value[field] = value.replace(/[<>]/g, "");
    }
};

const formatDate = (field) => {
    const value = form.value[field];
    // Convert date to yyyy-mm-dd format if necessary
    if (value) {
        const date = new Date(value);
        if (!isNaN(date.getTime())) {
            form.value[field] = date.toISOString().split("T")[0];
        }
    }
};

const validateForm = () => {
    formErrors.value = [];

    if (!form.value.name.trim()) {
        formErrors.value.push("Full Name is required");
    }
    if (
        form.value.contact_number &&
        !/^[0-9]{10,11}$/.test(form.value.contact_number)
    ) {
        formErrors.value.push("Contact number must be 10-11 digits");
    }

    if (form.value.join_accommodation && form.value.exit_accommodation) {
        const joinDate = new Date(form.value.join_accommodation);
        const exitDate = new Date(form.value.exit_accommodation);
        if (joinDate > exitDate) {
            formErrors.value.push(
                "Join accommodation date must be before exit date"
            );
        }
    }
    // if (form.value.residance === "Hostel") {
    //     if (!form.value.room_number.trim()) {
    //         formErrors.value.push(
    //             "Room number is required for hostel accommodation"
    //         );
    //     }
    //     if (!form.value.bed_number.trim()) {
    //         formErrors.value.push(
    //             "Bed number is required for hostel accommodation"
    //         );
    //     }
    // }

    return formErrors.value.length === 0;
};

const handleSubmit = async () => {
    isSubmitting.value = true;
    error.value = null;

    if (!validateForm()) {
        isSubmitting.value = false;
        return;
    }

    try {
        const employeeData = {
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
            contact_name: form.value.contact_name,
            room_number: form.value.room_number,
            bed_number: form.value.bed_number,
        };

        await store.dispatch(`employee/${SAVE_EMPLOYEE_DATA}`, employeeData);
        router.push({ name: "ListEmployees" });
    } catch (err) {
        error.value = err.message || "Error adding employee. Please try again.";
    } finally {
        isSubmitting.value = false;
    }
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

const resetForm = () => {
    form.value = {
        company: "",
        candidate_id: "",
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
        emergency_contact: "",
        join_accommodation: "",
        exit_accommodation: "",
        join_company: "",
        status: "",
        exit_company: "",
        residance: "",
        contact_name: "",
        emergency_contact_name: "",
        emergency_contact_two: "",
        emergency_contact_two_name: "",
        bank_name: "",
        room_number: "",
        bed_number: "",
    };
    formErrors.value = [];
    error.value = null;
};

watch(
  () => form.value.name,
  (newName) => {
    form.value.contact_name = newName;
  }
);

onMounted(() => {
    loadCompanies();
});
</script>
