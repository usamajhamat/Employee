import {
    DELETE_BANK_TRANSACTIONS,
    DELETE_BANKS,
    FETCH_BANKS,
    SAVE_BANK,
    SAVE_BANK_TRANSACTION,
    UPDATE_BANK,
} from "./actions.type";
import apiService from "./apiService";
import { toast } from "vue3-toastify";
import { IS_LOADING, SET_VALIDATION_ERRORS, SET_BANKS } from "./mutations.type";

const state = {
    banks: [],
    banksData: {},
    isLoading: false,
    validationErrors: null,
};

const getters = {
    banks(state) {
        return state.banks;
    },
    banksData(state) {
        return state.banksData;
    },
    bank: (state) => (id) => {
        if (state.banks != null) {
            var bank = state.banks.find((bank) => bank.id == id);
            if (bank) {
                return bank;
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
};

const actions = {
    async [FETCH_BANKS](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.getBanks(params);
            console.log(JSON.stringify(response.data));
            context.commit(SET_BANKS, response.data);
        } catch (error) {
            console.log(error);
            toast(error, {
                theme: "dark",
                type: "error",
                dangerouslyHTMLString: true,
            });
        }
    },

    async [SAVE_BANK](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.saveBank(params);
            console.log(JSON.stringify(response.data));
            context.dispatch(FETCH_BANKS);
            toast("Bank saved successfully.", {
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

    async [UPDATE_BANK](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.updateBank(params);
            console.log(JSON.stringify(response.data));
            context.dispatch(FETCH_BANKS);
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

    async [DELETE_BANKS](context, params) {
        console.log(params);
        context.commit(IS_LOADING);
        try {
            const response = await apiService.deleteBanks(params);
            console.log(JSON.stringify(response.data));
            context.dispatch(FETCH_BANKS);
            toast("Bank deleted successfully.", {
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

    async [SAVE_BANK_TRANSACTION](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.saveBankTransaction(params);
            console.log(JSON.stringify(response.data));
            context.dispatch(FETCH_BANKS);
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
            context.dispatch(FETCH_BANKS);
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
    [SET_BANKS](state, data) {
        state.banks = data.data;
        state.banksData = data;
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
