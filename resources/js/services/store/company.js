import { toast } from "vue3-toastify";
import {
    DELETE_COMPANY,
    FETCH_COMPANY,
    FETCH_COMPANY_DETAILS,
    FETCH_DASHBOARD_ANALYTICS,
    SAVE_COMPANY,
    UPDATE_COMPANY_DETAILS,
} from "./actions.type";
import apiService from "./apiService";
import {
    IS_LOADING,
    NOT_IS_LOADING,
    SET_API_ERROR,
    SET_COMPANY,
    SET_COMPANY_DETAILS,
    SET_DASHBOARD_ANALYTICS,
} from "./mutations.type";

const state = {
    companyData: null,
    companyDetails: null,
    dashboardAnalyticsData: null,
    isLoading: false,
    apiErrors: [],
};

const getters = {
    companyData: (state) => state.companyData,
    companyDetails: (state) => state.companyDetails,
    dashboardAnalyticsData: (state) => state.dashboardAnalyticsData,
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

    async [FETCH_COMPANY_DETAILS](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.getCompnyDetails(params);
            console.log(JSON.stringify(response.data));
            context.commit(SET_COMPANY_DETAILS, response.data);
        } catch (error) {
            console.log(error);
            toast(error, {
                theme: "dark",
                type: "error",
                dangerouslyHTMLString: true,
            });
        }
    },

    async [UPDATE_COMPANY_DETAILS](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.updateCompany(params);
            console.log(JSON.stringify(response.data));
            context.commit(SET_COMPANY_DETAILS, response.data);
        } catch (error) {
            console.log(error);
            toast(error, {
                theme: "dark",
                type: "error",
                dangerouslyHTMLString: true,
            });
        }
    },

    async [DELETE_COMPANY](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.deleteCompany(params);
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

    //Dashboard Analytics
    async [FETCH_DASHBOARD_ANALYTICS](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.getDashboardAnalytics(params);
            console.log(JSON.stringify(response.data));
            context.commit(SET_DASHBOARD_ANALYTICS, response.data);
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
    [SET_COMPANY_DETAILS](state, data) {
        state.companyDetails = data;
        state.isLoading = false;
    },
    [SET_DASHBOARD_ANALYTICS](state, data) {
        state.dashboardAnalyticsData = data;
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
