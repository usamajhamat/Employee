import { toast } from "vue3-toastify";
import { FETCH_COMPANY, FETCH_EMPLOYEES, SAVE_COMPANY, SAVE_EMPLOYEE_DATA } from "./actions.type";
import apiService from "./apiService";
import {
    IS_LOADING,
    NOT_IS_LOADING,
    SET_API_ERROR,
    SET_COMPANY,
    SET_EMPLOYEE_DATA,
} from "./mutations.type";

const state = {
    companyData: null,
    companyDetails: null,
    isLoading: false,
    apiErrors: [],
};

const getters = {
    companyData: (state) => state.companyData,
    companyDetails: (state) => state.companyDetails,
    isLoading: (state) => state.isLoading,
    apiErrors: (state) => state.apiErrors,
};

const actions = {
    async [SAVE_COMPANY](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.saveCompany(params);
            context.commit(SET_COMPANY, response.data);

            toast("Company saved successfully", {
                type: "success",
            });
        } catch (error) {
            console.error("SAVE_COMPANY error:", error);
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


    async [FETCH_COMPANY](context, params) {
            context.commit(IS_LOADING);
            try {
                const response = await apiService.getCompnies(params);
                console.log(JSON.stringify(response.data));
                context.commit(SET_COMPANY, response.data);
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
    [SET_COMPANY](state, data) {
        state.companyData = data;
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
