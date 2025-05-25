import { DELETE_CUSTOMERS, FETCH_CUSTOMERS, SAVE_CUSTOMER, UPDATE_CUSTOMER } from "./actions.type";
import apiService from "./apiService";
import { toast } from "vue3-toastify";
import {
    IS_LOADING,
    SET_VALIDATION_ERRORS,
    SET_CUSTOMERS,
} from "./mutations.type";

const state = {
    customers: [],
    customersData: {},
    isLoading: false,
    validationErrors: null,
}

const getters = {
    customers(state) {
        return state.customers;
    },
    customersData(state) {
        return state.customersData;
    },
    customer: (state) => (id) => {
        if (state.customers != null) {
            var customer = state.customers.find((customer) => customer.id == id);
            if (customer) {
                return customer;
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
    async [FETCH_CUSTOMERS](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.getCustomers(params);
            console.log(JSON.stringify(response.data));
            context.commit(SET_CUSTOMERS, response.data);
        } catch (error) {
            console.log(error);
            toast(error, {
                "theme": "dark",
                "type": "error",
                "dangerouslyHTMLString": true
            })
        }
    },

    async [SAVE_CUSTOMER](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.saveCustomer(params);
            console.log(JSON.stringify(response.data));
            context.dispatch(FETCH_CUSTOMERS);
            toast('Customer saved successfully.', {
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

    async [UPDATE_CUSTOMER](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.updateCustomer(params);
            console.log(JSON.stringify(response.data));
            context.dispatch(FETCH_CUSTOMERS)
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

    async [DELETE_CUSTOMERS](context, params) {
        console.log(params);
        context.commit(IS_LOADING);
        try {
            const response = await apiService.deleteCustomers(params);
            console.log(JSON.stringify(response.data));
            context.dispatch(FETCH_CUSTOMERS);
            toast('Customer deleted successfully.', {
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
    [SET_CUSTOMERS](state, data) {
        state.customers = data.data;
        state.customersData = data
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