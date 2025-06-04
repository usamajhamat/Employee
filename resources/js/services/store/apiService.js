import apiClient from "@/config/axios";
import { Save } from "lucide-vue-next";

export default {
    // auth
    login(params) {
        return apiClient.post("/login", params);
    },

    getUser() {
        return apiClient.get("/user");
    },

    logout() {
        return apiClient.post("/logout");
    },

    // file uploader

    saveEmployee(params) {
        return apiClient.post("/save-employee", params);
    },

    getEmployees(params) {
        return apiClient.get("/get-employees", {
            params: params,
        });
    },

    deleteEmployee(params) {
        return apiClient.delete("/delete-employee", {
            params: params,
        });
    },
    updateEmployee(params) {
        return apiClient.post("/update-employee", params);
    },
    getEmployeeDetails(params) {
        return apiClient.get("/get-employee-details", {
            params: params,
        });
    },

    saveCompany(params) {
        return apiClient.post("/save-company", params);
    },

    updateCompany(params) {
        return apiClient.post("/update-company", params);
    },

    getCompnies(params) {
        return apiClient.get("/get-compnies", {
            params: params,
        });
    },

    getCompnyDetails(params) {
        return apiClient.get("/get-compny-details", {
            params: params,
        });
    },

    deleteCompany(params) {
        return apiClient.delete("/delete-company", {
            params: params,
        });
    },

    getDashboardAnalytics(params) {
        return apiClient.get("/get-dashboard-analytics", {
            params: params,
        });
    },

    saveTodo(params) {
        return apiClient.post("/save-todo", params);
    },

    updateTodo(params) {
        return apiClient.post("/update-todo", params);
    },

    getTodos(params) {
        return apiClient.get("/get-todos", {
            params: params,
        });
    },

    getTodoDetails(params) {
        return apiClient.get("/get-todo-details", {
            params: params,
        });
    },

    deleteTodo(params) {
        return apiClient.delete("/delete-todo", {
            params: params,
        });
    },
};
