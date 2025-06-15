import { toast } from "vue3-toastify";
import { CREATE_WEEKLY_INFO, FETCH_WEEKLY_REPORTS } from "./actions.type";
import apiService from "./apiService";
import {
    IS_LOADING,
    NOT_IS_LOADING,
    SET_API_ERROR,
    SET_WEEKLY_REPORTS,
} from "./mutations.type";

const state = {
    weeklyReports: null,
    isLoading: false,
    apiErrors: [],
};

const getters = {
    weeklyReports: (state) => state.weeklyReports,
    isLoading: (state) => state.isLoading,
    apiErrors: (state) => state.apiErrors,
};

const actions = {
    async [CREATE_WEEKLY_INFO](context, params) {
        context.commit(IS_LOADING);
        try {
           const response = await apiService.saveWeeklyInfo(params);
           context.commit(SET_WEEKLY_REPORTS, response.data);
        } catch (error) {
            context.commit(SET_API_ERROR, error);
            toast(error?.message || "Failed to create weekly info", {
                theme: "dark",
                type: "error",
                dangerouslyHTMLString: true,
            });
        } finally {
            context.commit(NOT_IS_LOADING);
        }
    },

    async [FETCH_WEEKLY_REPORTS](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.getWeeklyReports(params);
            context.commit(SET_WEEKLY_REPORTS, response.data);
        } catch (error) {
            context.commit(SET_API_ERROR, error);
            toast(error?.message || "Failed to fetch weekly reports", {
                theme: "dark",
                type: "error",
                dangerouslyHTMLString: true,
            });
        } finally {
            context.commit(NOT_IS_LOADING);
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
        if (error?.response?.data?.errors) {
            state.apiErrors = error.response.data.errors;
        } else {
            state.apiErrors = [error?.message || "Unknown error"];
        }
    },
    [SET_WEEKLY_REPORTS](state, data) {
        state.weeklyReports = data;
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
