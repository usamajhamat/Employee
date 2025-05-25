import {
    DELETE_CUSTOMER_PAYMENT,
    FETCH_CUSTOMER_PAYMENTS,
    SAVE_CUSTOMER_PAYMENT,
} from "./actions.type";
import apiService from "./apiService";
import { toast } from "vue3-toastify";
import {
    IS_LOADING,
    SET_VALIDATION_ERRORS,
    SET_CUSTOMER_PAYMENTS,
} from "./mutations.type";
import { errorMessages } from "vue/compiler-sfc";

const state = {
    payments: [],
    isLoading: false,
    validationErrors: null,
};

const getters = {
    payments(state) {
        return state.payments;
    },
    isLoading(state) {
        return state.isLoading;
    },
    validationErrors(state) {
        return state.validationErrors;
    },
};

const actions = {
    async [FETCH_CUSTOMER_PAYMENTS](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.getCustomerPayments(params);
            console.log(JSON.stringify(response.data));
            context.commit(SET_CUSTOMER_PAYMENTS, response.data);
        } catch (error) {
            console.log(error);
            toast(error, {
                theme: "dark",
                type: "error",
                dangerouslyHTMLString: true,
            });
        }
    },

    async [SAVE_CUSTOMER_PAYMENT](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.saveCustomerPayment(params);
            console.log(JSON.stringify(response.data));
            toast("Payment saved successfully.", {
                theme: "dark",
                type: "success",
                dangerouslyHTMLString: true,
            });
            context.dispatch(FETCH_CUSTOMER_PAYMENTS);
        } catch (error) {
            console.log(error);
            toast(error, {
                theme: "dark",
                type: "error",
                dangerouslyHTMLString: true,
            });
        }
    },

    async [DELETE_CUSTOMER_PAYMENT](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.deleteCustomerPayment(params);
            console.log(JSON.stringify(response.data));
            toast("Payment deleted successfully.", {
                theme: "dark",
                type: "success",
                dangerouslyHTMLString: true,
            });
            context.dispatch(FETCH_CUSTOMER_PAYMENTS);
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
    [SET_VALIDATION_ERRORS](state, error) {
        if (
            error.response &&
            error.response.data &&
            error.response.data.message
        ) {
            console.log(error.response.data);
            state.error = error.response.data.message;
        }
    },
    [SET_CUSTOMER_PAYMENTS](state, data) {
        state.payments = data;
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
