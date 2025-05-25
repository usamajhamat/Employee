import {
    DELETE_PURCHASES,
    FETCH_PURCHASES,
    SAVE_PURCHASE,
    UPDATE_PURCHASE,
} from "./actions.type";
import apiService from "./apiService";
import { toast } from "vue3-toastify";
import {
    IS_LOADING,
    SET_VALIDATION_ERRORS,
    SET_PURCAHSES,
} from "./mutations.type";
import router from "@/config/router";

const state = {
    purchases: [],
    payments: [],
    purchasesData: {},
    isLoading: false,
    validationErrors: null,
};

const getters = {
    purchases(state) {
        return state.purchases;
    },
    purchasesData(state) {
        return state.purchasesData;
    },
    purchase: (state) => (id) => {
        if (state.purchases != null) {
            var purchase = state.purchases.find(
                (purchase) => purchase.id == id
            );
            if (purchase) {
                return purchase;
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
};

const actions = {
    async [FETCH_PURCHASES](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.getPurchases(params);
            console.log(JSON.stringify(response.data));
            context.commit(SET_PURCAHSES, response.data);
        } catch (error) {
            console.log(error);
            toast(error, {
                theme: "dark",
                type: "error",
                dangerouslyHTMLString: true,
            });
        }
    },

    async [SAVE_PURCHASE](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.savePurchase(params);
            console.log(JSON.stringify(response.data));
            toast("Purchase saved successfully.", {
                theme: "dark",
                type: "success",
                dangerouslyHTMLString: true,
            });
            router.push({
                name: "PurchaseInvoice",
                query: {
                    purchase_id: response.data.purchase_id,
                },
            });
        } catch (error) {
            console.log(error);
            toast(error, {
                theme: "dark",
                type: "error",
                dangerouslyHTMLString: true,
            });
        }
    },

    async [UPDATE_PURCHASE](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.updatePurchase(params);
            console.log(JSON.stringify(response.data));
            context.dispatch(FETCH_PURCHASES);
            toast("Changes saved successfully.", {
                theme: "dark",
                type: "success",
                dangerouslyHTMLString: true,
            });
        } catch (error) {
            console.log(error);
            toast(error, {
                theme: "dark",
                type: "error",
                dangerouslyHTMLString: true,
            });
        }
    },

    async [DELETE_PURCHASES](context, params) {
        console.log(params);
        context.commit(IS_LOADING);
        try {
            const response = await apiService.deletePurchases(params);
            console.log(JSON.stringify(response.data));
            context.dispatch(FETCH_PURCHASES);
            toast("Purchase deleted successfully.", {
                theme: "dark",
                type: "success",
                dangerouslyHTMLString: true,
            });
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
    [SET_PURCAHSES](state, data) {
        state.purchases = data.data;
        state.purchasesData = data;
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
