<template>
    <aside
        id="logo-sidebar"
        class="fixed top-0 left-0 z-40 h-screen transition-transform bg-gradient-to-b from-slate-900 via-teal-900 to-slate-900 text-white"
        :class="[
            isExpanded ? 'w-72' : 'w-20',
            !isExpanded && '-translate-x-full sm:translate-x-0',
        ]"
        aria-label="Sidebar"
    >
        <!-- Custom EmployeeHub Logo -->
        <div class="flex justify-center py-6 px-3">
            <div class="relative cursor-pointer" @click="toggleSidebar">
                <div
                    class="absolute -inset-1 rounded-full opacity-75 bg-white blur-sm"
                ></div>
                <div
                    class="relative flex items-center justify-center h-14 w-14 rounded-full bg-white"
                >
                    <Users class="h-8 w-8 text-teal-600" />
                </div>
            </div>
            <span
                v-if="isExpanded"
                class="ml-3 text-xl font-bold text-white transition-opacity duration-300 opacity-100 self-center"
            >
                EmployeeHub
            </span>
        </div>

        <!-- Navigation Menu -->
        <div class="h-full px-3 pb-20 overflow-y-auto">
            <ul class="space-y-2 font-medium">
                <!-- Dashboard -->
                <li>
                    <router-link
                        :to="{ name: 'Dashboard' }"
                        class="flex items-center p-2 rounded-lg group transition-all duration-200 text-teal-300 hover:text-white hover:bg-teal-800/50"
                        :class="{
                            'bg-teal-800/50 text-white':
                                $route.name === 'Dashboard',
                            'justify-center': !isExpanded
                        }"
                    >
                        <div
                            class="transition-all duration-200 rounded-full"
                            :class="{
                                'w-1 h-6 bg-white mr-3':
                                    $route.name === 'Dashboard' && isExpanded,
                            }"
                        ></div>
                        <LayoutDashboard
                            class="flex-shrink-0 w-5 h-5 transition-colors duration-200 group-hover:text-white"
                            :class="{
                                'text-white': $route.name === 'Dashboard',
                            }"
                        />
                        <span
                            class="ms-3 whitespace-nowrap"
                            :class="[
                                !isExpanded ? 'hidden' : '',
                                { 'text-white': $route.name === 'Dashboard' },
                            ]"
                        >
                            Dashboard
                        </span>
                    </router-link>
                </li>
            </ul>

            <!-- EMPLOYEE MANAGEMENT Section -->
            <div class="mt-6 mb-4">
                <h2 
                    class="px-2 mb-2 text-xs font-semibold text-teal-300 uppercase tracking-wider"
                    :class="{ 'text-center': !isExpanded }"
                >
                    {{ isExpanded ? 'Employee Management' : 'Employee' }}
                </h2>
                <ul class="space-y-2">
                    <!-- Employee Dropdown -->
                    <li>
                        <Accordion type="single" collapsible class="w-full">
                            <AccordionItem value="employees" class="border-none">
                                <AccordionTrigger 
                                    class="flex items-center p-2 rounded-lg group transition-all duration-200 text-teal-300 hover:text-white hover:bg-teal-800/50 hover:no-underline"
                                    :class="{
                                        'bg-teal-800/50 text-white': 
                                            $route.name === 'AddEmployee' || $route.name === 'ListEmployees',
                                        'justify-center': !isExpanded
                                    }"
                                >
                                    <div class="flex items-center w-full">
                                        <div
                                            class="transition-all duration-200 rounded-full"
                                            :class="{
                                                'w-1 h-6 bg-white mr-3': 
                                                    ($route.name === 'AddEmployee' || $route.name === 'ListEmployees') && isExpanded,
                                            }"
                                        ></div>
                                        <UserPlus
                                            class="flex-shrink-0 w-5 h-5 transition-colors duration-200 group-hover:text-white"
                                            :class="{
                                                'text-white': $route.name === 'AddEmployee' || $route.name === 'ListEmployees',
                                            }"
                                        />
                                        <span
                                            class="ms-3 whitespace-nowrap flex-1 text-left"
                                            :class="[
                                                !isExpanded ? 'hidden' : '',
                                                { 'text-white': $route.name === 'AddEmployee' || $route.name === 'ListEmployees' }
                                            ]"
                                        >
                                            Employees
                                           
                                        </span>
                                    </div>
                                </AccordionTrigger>
                                <AccordionContent v-if="isExpanded" class="pb-0">
                                    <ul class="ml-6 mt-2 space-y-1">
                                        <li>
                                            <router-link
                                                :to="{ name: 'AddEmployee' }"
                                                class="flex items-center p-2 rounded-lg group transition-all duration-200 text-teal-300 hover:text-white hover:bg-teal-800/30"
                                                :class="{
                                                    'bg-teal-800/30 text-white': $route.name === 'AddEmployee'
                                                }"
                                            >
                                                <Plus class="flex-shrink-0 w-4 h-4 mr-2" />
                                                <span class="text-sm">Add Employee</span>
                                            </router-link>
                                        </li>
                                        <li>
                                            <router-link
                                                :to="{ name: 'ListEmployees' }"
                                                class="flex items-center p-2 rounded-lg group transition-all duration-200 text-teal-300 hover:text-white hover:bg-teal-800/30"
                                                :class="{
                                                    'bg-teal-800/30 text-white': $route.name === 'ListEmployees'
                                                }"
                                            >
                                                <List class="flex-shrink-0 w-4 h-4 mr-2" />
                                                <span class="text-sm">List Employees</span>
                                            </router-link>
                                        </li>
                                    </ul>
                                </AccordionContent>
                            </AccordionItem>
                        </Accordion>
                    </li>
                </ul>
            </div>

            <!-- COMPANY MANAGEMENT Section -->
            <div class="mt-6 mb-4">
                <h2 
                    class="px-2 mb-2 text-xs font-semibold text-teal-300 uppercase tracking-wider"
                    :class="{ 'text-center': !isExpanded }"
                >
                    {{ isExpanded ? 'Company Management' : 'Company' }}
                </h2>
                <ul class="space-y-2">
                    <!-- Company Dropdown -->
                    <li>
                        <Accordion type="single" collapsible class="w-full">
                            <AccordionItem value="companies" class="border-none">
                                <AccordionTrigger 
                                    class="flex items-center p-2 rounded-lg group transition-all duration-200 text-teal-300 hover:text-white hover:bg-teal-800/50 hover:no-underline"
                                    :class="{
                                        'bg-teal-800/50 text-white': 
                                            $route.name === 'AddCompany' || $route.name === 'ListCompanies',
                                        'justify-center': !isExpanded
                                    }"
                                >
                                    <div class="flex items-center w-full">
                                        <div
                                            class="transition-all duration-200 rounded-full"
                                            :class="{
                                                'w-1 h-6 bg-white mr-3': 
                                                    ($route.name === 'AddCompany' || $route.name === 'ListCompanies') && isExpanded,
                                            }"
                                        ></div>
                                        <Building2
                                            class="flex-shrink-0 w-5 h-5 transition-colors duration-200 group-hover:text-white"
                                            :class="{
                                                'text-white': $route.name === 'AddCompany' || $route.name === 'ListCompanies',
                                            }"
                                        />
                                        <span
                                            class="ms-3 whitespace-nowrap flex-1 text-left"
                                            :class="[
                                                !isExpanded ? 'hidden' : '',
                                                { 'text-white': $route.name === 'AddCompany' || $route.name === 'ListCompanies' }
                                            ]"
                                        >
                                            Companies
                                        </span>
                                    </div>
                                </AccordionTrigger>
                                <AccordionContent v-if="isExpanded" class="pb-0">
                                    <ul class="ml-6 mt-2 space-y-1">
                                        <li>
                                            <router-link
                                                :to="{ name: 'AddCompany' }"
                                                class="flex items-center p-2 rounded-lg group transition-all duration-200 text-teal-300 hover:text-white hover:bg-teal-800/30"
                                                :class="{
                                                    'bg-teal-800/30 text-white': $route.name === 'AddCompany'
                                                }"
                                            >
                                                <Plus class="flex-shrink-0 w-4 h-4 mr-2" />
                                                <span class="text-sm">Add Company</span>
                                            </router-link>
                                        </li>
                                        <li>
                                            <router-link
                                                :to="{ name: 'ListCompanies' }"
                                                class="flex items-center p-2 rounded-lg group transition-all duration-200 text-teal-300 hover:text-white hover:bg-teal-800/30"
                                                :class="{
                                                    'bg-teal-800/30 text-white': $route.name === 'ListCompanies'
                                                }"
                                            >
                                                <List class="flex-shrink-0 w-4 h-4 mr-2" />
                                                <span class="text-sm">List Companies</span>
                                            </router-link>
                                        </li>
                                    </ul>
                                </AccordionContent>
                            </AccordionItem>
                        </Accordion>
                    </li>
                </ul>
            </div>

            <!-- REPORTS Section -->
           
        </div>

        <!-- User Profile Section -->
        <div
            v-if="isExpanded"
            class="absolute bottom-0 left-0 right-0 p-4 border-t border-teal-700/50 flex items-center bg-slate-900/50 backdrop-blur-sm"
        >
            <div class="relative">
                <div
                    class="relative flex items-center justify-center h-10 w-10 rounded-full bg-teal-700 text-white"
                >
                    <User class="h-5 w-5" />
                </div>
                <div
                    class="absolute bottom-0 right-0 h-3 w-3 rounded-full bg-green-500 border-2 border-slate-900"
                ></div>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-white capitalize">
                    {{ user?.name || 'Admin' }}
                </p>
                <p class="text-xs text-teal-300">
                    {{ user?.email || 'admin@gmail.com' }}
                </p>
            </div>
            <button
                @click="handleLogout"
                class="ml-auto p-1 rounded-full hover:bg-teal-800/50 transition-colors duration-200"
            >
                <LogOut class="h-5 w-5 text-teal-300 hover:text-white" />
            </button>
        </div>

        <!-- Toggle Button -->
        <button
            class="absolute -right-3 top-20 bg-teal-600 p-1 rounded-full shadow-lg hover:bg-teal-700 transition-colors duration-200"
            @click="toggleSidebar"
        >
            <ChevronLeft v-if="isExpanded" class="h-4 w-4 text-white" />
            <ChevronRight v-else class="h-4 w-4 text-white" />
        </button>
    </aside>
</template>

<script setup>
import { computed, onMounted, ref } from "vue";
import {
    BarChart3,
    Building2,
    ChevronLeft,
    ChevronRight,
    LayoutDashboard,
    List,
    LogOut,
    PieChart,
    Plus,
    User,
    UserPlus,
    Users
} from "lucide-vue-next";
import {
    Accordion,
    AccordionContent,
    AccordionItem,
    AccordionTrigger,
} from "@/components/ui/accordion";
import { FETCH_USER, LOGOUT } from "@/services/store/actions.type";
import { useStore } from "vuex";
import { useRouter } from "vue-router";

// State
const isExpanded = ref(true);
const store = useStore();
const router = useRouter();

const user = computed(() => store.getters["auth/user"]);

function fetchUser() {
    store.dispatch("auth/" + FETCH_USER);
}

function handleLogout() {
    store.dispatch("auth/" + LOGOUT).then(() => {
        router.push({ name: "Login" });
    });
}

// Methods
const toggleSidebar = () => {
    isExpanded.value = !isExpanded.value;
};

onMounted(() => {
    fetchUser();
});
</script>