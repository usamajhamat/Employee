import DefaultLayout from "@/layouts/DefaultLayout.vue";
import Dashboard from "../../pages/Dashboard.vue";

export default [
    {
        path: "/dashboard",
        component: DefaultLayout,
        children: [
            {
                path: "/dashboard",
                name: "Dashboard",
                component: () => import("../../pages/Dashboard.vue"),
                meta: { requiresAuth: true },
            },
            {
                path: "/add-employee",
                name: "AddEmployee",
                component: () => import("../../pages/AddEmployee.vue"),
                meta: { requiresAuth: true },
            },
            {
                path: "/list-employees",
                name: "ListEmployees",
                component: () => import("../../pages/ListEmployee.vue"),
                meta: { requiresAuth: true },
            },
            {
                path: "/add-company",
                name: "AddCompany",
                component: () => import("../../pages/AddCompany.vue"),
                meta: { requiresAuth: true },
            },
            {
                path: "/list-companies",
                name: "ListCompanies",
                component: () => import("../../pages/ListCompnies.vue"),
                meta: { requiresAuth: true },
            },
            {
                path: "/edit-company",
                name: "EditCompany",
                component: () => import("../../pages/EditCompany.vue"),
                meta: { requiresAuth: true },
            },
            {
                path: "/edit-employee",
                name: "EditEmployee",
                component: () => import("../../pages/EditEmployee.vue"),
                meta: { requiresAuth: true },
            },
            {
                path: "/add-todo",
                name: "AddTodo",
                component: () => import("../../pages/AddTodo.vue"),
                meta: { requiresAuth: true },
            },
            {
                path: "/list-todos",
                name: "ListTodos",
                component: () => import("../../pages/ListTodos.vue"),
                meta: { requiresAuth: true },
            },
            {
                path: "/edit-todo",
                name: "EditTodo",
                component: () => import("../../pages/EditTodo.vue"),
                meta: { requiresAuth: true },
            },
            {
                path: "/add-weekly-info",
                name: "AddWeeklyInfo",
                component: () => import("../../pages/AddWeeklyInfo.vue"),
                meta: { requiresAuth: true },
            },
            {
                path: "/weekly-reports",
                name: "WeeklyReports",
                component: () => import("../../pages/WeeklyReports.vue"),
                meta: { requiresAuth: true },
            },
        ],
    },
    // pages with no layout
];
