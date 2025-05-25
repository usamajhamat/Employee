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
                <form @submit.prevent="handleSubmit" class="p-6 space-y-6">
                    <!-- Personal Information Section -->
                    <div class="border-b border-gray-200 pb-6">
                        <h2
                            class="text-lg font-medium text-gray-900 mb-4 flex items-center"
                        >
                            <User class="h-5 w-5 text-teal-600 mr-2" />
                            Personal Information
                        </h2>

                        <div
                            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"
                        >
                            <!-- Name -->
                            <div>
                                <label
                                    for="name"
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >
                                    Full Name
                                    <span class="text-red-500">*</span>
                                </label>
                                <input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                    placeholder="Enter full name"
                                />
                            </div>

                            <!-- Candidate ID -->
                            <div>
                                <label
                                    for="candidate_id"
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >
                                    Candidate ID
                                    <span class="text-red-500">*</span>
                                </label>
                                <input
                                    id="candidate_id"
                                    v-model="form.candidate_id"
                                    type="text"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                    placeholder="Enter candidate ID"
                                />
                            </div>

                            <!-- IC Number -->
                            <div>
                                <label
                                    for="ic_number"
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >
                                    IC Number
                                </label>
                                <input
                                    id="ic_number"
                                    v-model="form.ic_number"
                                    type="text"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                    placeholder="Enter IC number"
                                />
                            </div>

                            <!-- Date of Birth -->
                            <div>
                                <label
                                    for="dob"
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >
                                    Date of Birth
                                </label>
                                <input
                                    id="dob"
                                    v-model="form.dob"
                                    type="date"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                />
                            </div>

                            <!-- Gender -->
                            <div>
                                <label
                                    for="gender"
                                    class="block text-sm font-medium text-gray-700 mb-1"
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

                            <!-- Religion -->
                            <div>
                                <label
                                    for="religion"
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >
                                    Religion
                                </label>
                                <input
                                    id="religion"
                                    v-model="form.religion"
                                    type="text"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                    placeholder="Enter religion"
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
                            <!-- Contact Number -->
                            <div>
                                <label
                                    for="contact_number"
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >
                                    Contact Number
                                </label>
                                <input
                                    id="contact_number"
                                    v-model="form.contact_number"
                                    type="tel"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                    placeholder="Enter contact number"
                                />
                            </div>

                            <!-- Emergency Contact -->
                            <div>
                                <label
                                    for="emergency_contact"
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >
                                    Emergency Contact
                                </label>
                                <input
                                    id="emergency_contact"
                                    v-model="form.emergency_contact"
                                    type="tel"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                    placeholder="Enter emergency contact"
                                />
                            </div>

                            <!-- State -->
                            <div>
                                <label
                                    for="state"
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >
                                    State
                                </label>
                                <select
                                    id="state"
                                    v-model="form.state"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                >
                                    <option value="">Select State</option>
                                    <option value="Johor">Johor</option>
                                    <option value="Kedah">Kedah</option>
                                    <option value="Kelantan">Kelantan</option>
                                    <option value="Kuala Lumpur">
                                        Kuala Lumpur
                                    </option>
                                    <option value="Labuan">Labuan</option>
                                    <option value="Melaka">Melaka</option>
                                    <option value="Negeri Sembilan">
                                        Negeri Sembilan
                                    </option>
                                    <option value="Pahang">Pahang</option>
                                    <option value="Penang">Penang</option>
                                    <option value="Perak">Perak</option>
                                    <option value="Perlis">Perlis</option>
                                    <option value="Putrajaya">Putrajaya</option>
                                    <option value="Sabah">Sabah</option>
                                    <option value="Sarawak">Sarawak</option>
                                    <option value="Selangor">Selangor</option>
                                    <option value="Terengganu">
                                        Terengganu
                                    </option>
                                </select>
                            </div>

                            <!-- Address -->
                            <div class="md:col-span-2">
                                <label
                                    for="address"
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >
                                    Address
                                </label>
                                <textarea
                                    id="address"
                                    v-model="form.address"
                                    rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                    placeholder="Enter full address"
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
                            <!-- Company -->
                            <div>
                                <label
                                    for="company"
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >
                                    Company
                                </label>
                                <select
                                    id="company"
                                    v-model="form.company"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                >
                                    <option value="">Select Company</option>
                                    <option value="TechCorp Solutions">
                                        TechCorp Solutions
                                    </option>
                                    <option value="Digital Innovations Ltd">
                                        Digital Innovations Ltd
                                    </option>
                                    <option value="Global Systems Inc">
                                        Global Systems Inc
                                    </option>
                                    <option value="Future Technologies">
                                        Future Technologies
                                    </option>
                                    <option value="Smart Solutions Group">
                                        Smart Solutions Group
                                    </option>
                                </select>
                            </div>

                            <!-- Status -->
                            <div>
                                <label
                                    for="status"
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >
                                    Status
                                </label>
                                <select
                                    id="status"
                                    v-model="form.status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                >
                                    <option value="">Select Status</option>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                    <option value="Pending">Pending</option>
                                    <option value="On Leave">On Leave</option>
                                    <option value="Terminated">
                                        Terminated
                                    </option>
                                </select>
                            </div>

                            <!-- Interview Date -->
                            <div>
                                <label
                                    for="interview_date"
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >
                                    Interview Date
                                </label>
                                <input
                                    id="interview_date"
                                    v-model="form.interview_date"
                                    type="date"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                />
                            </div>

                            <!-- Join Company Date -->
                            <div>
                                <label
                                    for="join_company"
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >
                                    Join Company Date
                                </label>
                                <input
                                    id="join_company"
                                    v-model="form.join_company"
                                    type="date"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
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

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Bank Account -->
                            <div>
                                <label
                                    for="bank_account"
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >
                                    Bank Account Number
                                </label>
                                <input
                                    id="bank_account"
                                    v-model="form.bank_account"
                                    type="text"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                    placeholder="Enter bank account number"
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

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Join Accommodation -->

                            <div>
                                <label
                                    for="join_company"
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >
                                    Join Accommodation Date
                                </label>
                                <input
                                    id="join_accommodation"
                                    v-model="form.join_accommodation"
                                    type="date"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                />
                            </div>

                            <!-- Exit Accommodation -->
                            <div>
                                <label
                                    for="join_company"
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >
                                    Exit Accommodation Date
                                </label>
                                <input
                                    id="exit_accommodation"
                                    v-model="form.exit_accommodation"
                                    type="date"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div
                        class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200"
                    >
                        <button
                            type="submit"
                            :disabled="isSubmitting"
                            class="flex-1 sm:flex-none px-6 py-3 bg-gradient-to-r from-teal-600 to-cyan-600 text-white font-medium rounded-lg shadow-sm hover:from-teal-700 hover:to-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
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
                                <div
                                    class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"
                                ></div>
                                Adding Employee...
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
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
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
import { useRouter } from "vue-router";

const router = useRouter();
const isSubmitting = ref(false);

// Form data
const form = ref({
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
});

const error = ref(null);
const isLoading = ref(false);
const formErrors = ref([]);

// Form submission
const handleSubmit = async () => {
    isSubmitting.value = true;

    // Validate form fields
    // if (!name.value) formErrors.value.push("Name field is required.");
    // if (!city.value) formErrors.value.push("City field is required.");
    // if (!address.value) formErrors.value.push("Address field is required.");

    if (formErrors.value.length > 0) {
        return;
    }

    isLoading.value = true;
    error.value = null;

    try {
        const EmployeeData = new FormData();
        otherInstitutionData.append("name", name.value);
        otherInstitutionData.append("company", form.value.company);
        otherInstitutionData.append("candidate_id", form.value.candidate_id);
        otherInstitutionData.append(
            "contact_number",
            form.value.contact_number
        );
        otherInstitutionData.append("ic_number", form.value.ic_number);
        otherInstitutionData.append("dob", form.value.dob);
        otherInstitutionData.append("gender", form.value.gender);
        otherInstitutionData.append("interview_date", form.value.interview_date);
        otherInstitutionData.append("religion", form.value.religion);
        otherInstitutionData.append("state", form.value.state);
        otherInstitutionData.append("address", form.value.address);
        otherInstitutionData.append("bank_account", form.value.bank_account);
        otherInstitutionData.append("emergency_contact", form.value.emergency_contact);
        otherInstitutionData.append("join_accommodation", form.value.join_accommodation);
        otherInstitutionData.append("exit_accommodation", form.value.exit_accommodation);
        otherInstitutionData.append("join_company", form.value.join_company);
        otherInstitutionData.append("status", form.value.status);

        store
            .dispatch(
                `otherInstitution/${SAVE_OTHER_INSTITUTION}`,
                otherInstitutionData
            )
            .then(() => {
                router.push({ name: "ListEmployees" });
            });
    } catch (error) {
        console.error("Error adding employee:", error);
        alert("Error adding employee. Please try again.");
    } finally {
        isSubmitting.value = false;
    }
};

// Reset form
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
        join_accommodation: false,
        exit_accommodation: false,
        join_company: "",
        status: "",
    };
};
</script>
