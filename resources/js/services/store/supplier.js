import { DELETE_SUPPLIERS, FETCH_SUPPLIERS, SAVE_SUPPLIER, UPDATE_SUPPLIER } from "./actions.type";
import apiService from "./apiService";
import { toast } from "vue3-toastify";
import {
    IS_LOADING,
    SET_VALIDATION_ERRORS,
    SET_SUPPLIERS,
} from "./mutations.type";

const state = {
    suppliers: [],
    suppliersData: {},
    isLoading: false,
    validationErrors: null,
}

const getters = {
    suppliers(state) {
        return state.suppliers;
    },
    suppliersData(state) {
        return state.suppliersData;
    },
    supplier: (state) => (id) => {
        if (state.suppliers != null) {
            var supplier = state.suppliers.find((supplier) => supplier.id == id);
            if (supplier) {
                return supplier;
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
    async [FETCH_SUPPLIERS](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.getSuppliers(params);
            console.log(JSON.stringify(response.data));
            context.commit(SET_SUPPLIERS, response.data);
        } catch (error) {
            console.log(error);
            toast(error, {
                "theme": "dark",
                "type": "error",
                "dangerouslyHTMLString": true
            })
        }
    },

    async [SAVE_SUPPLIER](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.saveSupplier(params);
            console.log(JSON.stringify(response.data));
            context.dispatch(FETCH_SUPPLIERS);
            toast('Supplier saved successfully.', {
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

    async [UPDATE_SUPPLIER](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.updateSupplier(params);
            console.log(JSON.stringify(response.data));
            context.dispatch(FETCH_SUPPLIERS)
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

    async [DELETE_SUPPLIERS](context, params) {
        console.log(params);
        context.commit(IS_LOADING);
        try {
            const response = await apiService.deleteSuppliers(params);
            console.log(JSON.stringify(response.data));
            context.dispatch(FETCH_SUPPLIERS);
            toast('Supplier deleted successfully.', {
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
    [SET_SUPPLIERS](state, data) {
        state.suppliers = data.data;
        state.suppliersData = data
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