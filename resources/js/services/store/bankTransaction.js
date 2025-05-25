import {
    DELETE_BANK_TRANSACTIONS,
    FETCH_BANK_TRANSACTIONS,
    SAVE_BANK_TRANSACTION,
} from "./actions.type";
import apiService from "./apiService";
import { toast } from "vue3-toastify";
import {
    IS_LOADING,
    SET_VALIDATION_ERRORS,
    SET_BANK_TRANSACTIONS,
} from "./mutations.type";

const state = {
    transactions: [],
    isLoading: false,
    validationErrors: null,
};

const getters = {
    transactions(state) {
        return state.transactions;
    },
    isLoading(state) {
        return state.isLoading;
    },
    validationErrors(state) {
        return state.validationErrors;
    },
};

const actions = {
    async [FETCH_BANK_TRANSACTIONS](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.getBankTransactions(params);
            console.log(JSON.stringify(response.data));
            context.commit(SET_BANK_TRANSACTIONS, response.data);
        } catch (error) {
            console.log(error);
            toast(error, {
                theme: "dark",
                type: "error",
                dangerouslyHTMLString: true,
            });
        }
    },

    async [SAVE_BANK_TRANSACTION](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.saveBankTransaction(params);
            console.log(JSON.stringify(response.data));
            context.dispatch(FETCH_BANK_TRANSACTIONS);
            toast("Transaction saved successfully.", {
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

    async [DELETE_BANK_TRANSACTIONS](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.deleteBankTransactions(params);
            console.log(JSON.stringify(response.data));
            context.dispatch(FETCH_BANK_TRANSACTIONS);
            toast("Transactions deleted successfully.", {
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
    [SET_BANK_TRANSACTIONS](state, data) {
        state.transactions = data;
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
