<script setup>

import { Button } from "./ui/button";
import { MoveDown, MoveUp } from "lucide-vue-next";
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from "@/components/ui/popover";
import { User } from "lucide-vue-next";
import { LogOut } from "lucide-vue-next";
import { useStore } from "vuex";
import {
    FETCH_USER,
    LOGOUT,
} from "@/services/store/actions.type";
import { useRouter } from "vue-router";
import { computed, onMounted, watch } from "vue";
import { Bell } from "lucide-vue-next";
import { Eye } from "lucide-vue-next";
import moment from "moment";

const store = useStore();
const router = useRouter();

const user = computed(() => store.getters["auth/user"]);
function handleLogout() {
    store.dispatch("auth/" + LOGOUT).then(() => {
        router.push({ name: "Login" });
    });
}

function fetchUser() {
    store.dispatch("auth/" + FETCH_USER);
}

onMounted(() => {
    fetchUser();
});
</script>

<template>
    <nav class="bg-white shadow-sm border-b border-slate-200">
        <div class="px-4 py-4 lg:px-6">
            <div class="flex items-center justify-between">
                <!-- Left side - Mobile menu toggle -->
                <div class="flex items-center justify-start">
                    <button
                        data-drawer-target="logo-sidebar"
                        data-drawer-toggle="logo-sidebar"
                        aria-controls="logo-sidebar"
                        type="button"
                        class="inline-flex items-center p-2 text-slate-600 rounded-lg sm:hidden hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-colors"
                    >
                        <span class="sr-only">Open sidebar</span>
                        <svg
                            class="w-6 h-6"
                            aria-hidden="true"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                clip-rule="evenodd"
                                fill-rule="evenodd"
                                d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"
                            ></path>
                        </svg>
                    </button>
                </div>

                <!-- Right side - Action buttons and user menu -->
                <div class="flex items-center">
                    <div class="flex items-center space-x-3 md:space-x-4">
                        <div class="flex items-center gap-x-3">
                            <!-- User Profile Popover -->
                            <Popover>
                                <PopoverTrigger>
                                    <button
                                        class="flex items-center space-x-2 p-2 rounded-full bg-gradient-to-r from-purple-600 to-violet-600 text-white shadow-sm hover:shadow-md transition-all duration-200"
                                    >
                                        <User class="w-5 h-5" />
                                    </button>
                                </PopoverTrigger>
                                <PopoverContent
                                    class="w-64 p-0 rounded-xl shadow-lg border border-slate-200"
                                >
                                    <div
                                        class="p-4 border-b border-slate-100 bg-gradient-to-r from-purple-50 to-violet-50"
                                    >
                                        <p class="font-medium text-slate-800 capitalize">
                                            {{ user.name }}
                                        </p>
                                        <p class="text-sm text-slate-500">
                                            {{ user.email }}
                                        </p>
                                    </div>
                                    <ul>
                                        <li
                                            @click="handleLogout()"
                                            class="flex items-center gap-3 hover:bg-red-50 cursor-pointer p-4 text-red-600 transition-colors duration-200 rounded-b-xl"
                                        >
                                            <LogOut class="w-5 h-5" />
                                            <span class="font-medium"
                                                >Sign out</span
                                            >
                                        </li>
                                    </ul>
                                </PopoverContent>
                            </Popover>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</template>
