import { DELETE_SALES, FETCH_SALES, SAVE_SALE, UPDATE_SALE } from "./actions.type";
import apiService from "./apiService";
import { toast } from "vue3-toastify";
import {
    IS_LOADING,
    SET_VALIDATION_ERRORS,
    SET_SALES,
} from "./mutations.type";
import router from "@/config/router";

const state = {
    sales: [],
    payments: [],
    salesData: {},
    isLoading: false,
    validationErrors: null,
}

const getters = {
    sales(state) {
        return state.sales;
    },
    salesData(state) {
        return state.salesData;
    },
    sale: (state) => (id) => {
        if (state.sales != null) {
            var sale = state.sales.find((sale) => sale.id == id);
            if (sale) {
                return sale;
            }
        }
        return null;
    },
    payments(state) {
        return state.payments;
    },
    isLoading(state) {
        return state.isLoading;
    },
    validationErrors(state) {
        return state.validationErrors;
    },
}

const actions = {
    async [FETCH_SALES](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.getSales(params);
            console.log(JSON.stringify(response.data));
            context.commit(SET_SALES, response.data);
        } catch (error) {
            console.log(error);
            toast(error, {
                "theme": "dark",
                "type": "error",
                "dangerouslyHTMLString": true
            })
        }
    },

    async [SAVE_SALE](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.saveSale(params);
            console.log(JSON.stringify(response.data));
            toast('Sale saved successfully.', {
                "theme": "dark",
                "type": "success",
                "dangerouslyHTMLString": true
            })
            router.push({
                name: 'SaleInvoice', query: {
                    sale_id: response.data.sale_id
                }
            });
        } catch (error) {
            console.log(error);
            toast(error, {
                "theme": "dark",
                "type": "error",
                "dangerouslyHTMLString": true
            })
        }
    },

    async [UPDATE_SALE](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.updateSale(params);
            console.log(JSON.stringify(response.data));
            context.dispatch(FETCH_SALES)
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

    async [DELETE_SALES](context, params) {
        console.log(params);
        context.commit(IS_LOADING);
        try {
            const response = await apiService.deleteSales(params);
            console.log(JSON.stringify(response.data));
            context.dispatch(FETCH_SALES);
            toast('Sale deleted successfully.', {
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
    [SET_SALES](state, data) {
        state.sales = data.data;
        state.salesData = data
        state.isLoading = false;
    },
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
}