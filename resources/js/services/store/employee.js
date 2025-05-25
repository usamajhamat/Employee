import { toast } from "vue3-toastify";
import { FETCH_EMPLOYEES, SAVE_EMPLOYEE_DATA } from "./actions.type";
import apiService from "./apiService";
import {
    IS_LOADING,
    NOT_IS_LOADING,
    SET_API_ERROR,
    SET_EMPLOYEE_DATA,
} from "./mutations.type";

const state = {
    employeeData: null,
    employeeDetails: null,
    isLoading: false,
    apiErrors: [],
};

const getters = {
    employeeData: (state) => state.employeeData,
    employeeDetails: (state) => state.employeeDetails,
    isLoading: (state) => state.isLoading,
    apiErrors: (state) => state.apiErrors,
};

const actions = {
    async [SAVE_EMPLOYEE_DATA](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.saveEmployee(params);
            context.commit(SET_EMPLOYEE_DATA, response.data);

            toast("Employee saved successfully", {
                type: "success",
            });
        } catch (error) {
            console.error("SAVE_EMPLOYEE_DATA error:", error);

            const errorMessage =
                error.response?.data?.message || "An error occurred.";
            toast(errorMessage, {
                type: "error",
            });

            context.commit(SET_API_ERROR, error);
        } finally {
            context.commit(NOT_IS_LOADING);
        }
    },


    async [FETCH_EMPLOYEES](context, params) {
            context.commit(IS_LOADING);
            try {
                const response = await apiService.getEmployees(params);
                console.log(JSON.stringify(response.data));
                context.commit(SET_EMPLOYEE_DATA, response.data);
            } catch (error) {
                console.log(error);
                toast(error, {
                    theme: "dark",
                    type: "error",
                    dangerouslyHTMLString: true,
                });
            }
        },
};

const mutations = {
    [IS_LOADING](state) {
        state.isLoading = true;
    },
    [NOT_IS_LOADING](state) {
        state.isLoading = false;
    },
    [SET_API_ERROR](state, error) {
        if (error.response?.data?.errors) {
            state.apiErrors = error.response.data.errors;
        } else {
            state.apiErrors = [error.message || "Unknown error"];
        }
    },
    [SET_EMPLOYEE_DATA](state, data) {
        state.employeeData = data;
        state.isLoading = false;
    },
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
};
