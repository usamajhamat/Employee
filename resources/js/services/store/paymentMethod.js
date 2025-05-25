import { DELETE_PAYMENT_METHODS, FETCH_PAYMENT_METHODS, SAVE_PAYMENT_METHOD, UPDATE_PAYMENT_METHOD } from "./actions.type";
import apiService from "./apiService";
import { toast } from "vue3-toastify";
import {
    IS_LOADING,
    SET_VALIDATION_ERRORS,
    SET_PAYMENT_METHODS,
} from "./mutations.type";

const state = {
    paymentMethods: [],
    paymentMethodsData: {},
    isLoading: false,
    validationErrors: null,
}

const getters = {
    paymentMethods(state) {
        return state.paymentMethods;
    },
    paymentMethodsData(state) {
        return state.paymentMethodsData;
    },
    paymentMethod: (state) => (id) => {
        if (state.paymentMethods != null) {
            var paymentMethod = state.paymentMethods.find((paymentMethod) => paymentMethod.id == id);
            if (paymentMethod) {
                return paymentMethod;
            }
        }
        return null;
    },
    isLoading(state) {
        return state.isLoading;
    },
    validationErrors(state) {
        return state.validationErrors;
    },
}

const actions = {
    async [FETCH_PAYMENT_METHODS](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.getPaymentMethods(params);
            console.log(JSON.stringify(response.data));
            context.commit(SET_PAYMENT_METHODS, response.data);
        } catch (error) {
            console.log(error);
            toast(error, {
                "theme": "dark",
                "type": "error",
                "dangerouslyHTMLString": true
            })
        }
    },

    async [SAVE_PAYMENT_METHOD](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.savePaymentMethod(params);
            console.log(JSON.stringify(response.data));
            context.dispatch(FETCH_PAYMENT_METHODS);
            toast('Payment method saved successfully.', {
                "theme": "dark",
                "type": "success",
                "dangerouslyHTMLString": true
            })
        } catch (error) {
            console.log(error);
            toast(error, {
                "theme": "dark",
                "type": "error",
                "dangerouslyHTMLString": true
            })
        }
    },

    async [UPDATE_PAYMENT_METHOD](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.updatePaymentMethod(params);
            console.log(JSON.stringify(response.data));
            context.dispatch(FETCH_PAYMENT_METHODS)
            toast('Changes saved successfully.', {
                "theme": "dark",
                "type": "success",
                "dangerouslyHTMLString": true
            })
        } catch (error) {
            console.log(error);
            toast(error, {
                "theme": "dark",
                "type": "error",
                "dangerouslyHTMLString": true
            })
        }
    },

    async [DELETE_PAYMENT_METHODS](context, params) {
        console.log(params);
        context.commit(IS_LOADING);
        try {
            const response = await apiService.deletePaymentMethods(params);
            console.log(JSON.stringify(response.data));
            context.dispatch(FETCH_PAYMENT_METHODS);
            toast('Payment method deleted successfully.', {
                "theme": "dark",
                "type": "success",
                "dangerouslyHTMLString": true
            })
        } catch (error) {
            console.log(error);
            toast(error, {
                "theme": "dark",
                "type": "error",
                "dangerouslyHTMLString": true
            })
        }
    },
}

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
    [SET_PAYMENT_METHODS](state, data) {
        state.paymentMethods = data.data;
        state.paymentMethodsData = data
        state.isLoading = false;
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
}