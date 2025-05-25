import {
    DELETE_SUPPLIER_PAYMENT,
    FETCH_SUPPLIER_PAYMENTS,
    SAVE_SUPPLIER_PAYMENT,
} from "./actions.type";
import apiService from "./apiService";
import { toast } from "vue3-toastify";
import {
    IS_LOADING,
    SET_VALIDATION_ERRORS,
    SET_SUPPLIER_PAYMENTS,
} from "./mutations.type";

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
    async [FETCH_SUPPLIER_PAYMENTS](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.getSupplierPayments(params);
            console.log(JSON.stringify(response.data));
            context.commit(SET_SUPPLIER_PAYMENTS, response.data);
        } catch (error) {
            console.log(error);
            toast(error, {
                theme: "dark",
                type: "error",
                dangerouslyHTMLString: true,
            });
        }
    },

    async [SAVE_SUPPLIER_PAYMENT](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.saveSupplierPayment(params);
            console.log(JSON.stringify(response.data));
            toast("Payment saved successfully.", {
                theme: "dark",
                type: "success",
                dangerouslyHTMLString: true,
            });
            context.dispatch(FETCH_SUPPLIER_PAYMENTS);
        } catch (error) {
            console.log(error);
            toast(error, {
                theme: "dark",
                type: "error",
                dangerouslyHTMLString: true,
            });
        }
    },

    async [DELETE_SUPPLIER_PAYMENT](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.deleteSupplierPayment(params);
            console.log(JSON.stringify(response.data));
            toast("Payment deleted successfully.", {
                theme: "dark",
                type: "success",
                dangerouslyHTMLString: true,
            });
            context.dispatch(FETCH_SUPPLIER_PAYMENTS);
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
    [SET_SUPPLIER_PAYMENTS](state, data) {
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
