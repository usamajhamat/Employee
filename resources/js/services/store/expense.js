import {
    DELETE_EXPENSES,
    FETCH_EXPENSES,
    SAVE_EXPENSE,
    UPDATE_EXPENSE,
} from "./actions.type";
import apiService from "./apiService";
import { toast } from "vue3-toastify";
import {
    IS_LOADING,
    SET_VALIDATION_ERRORS,
    SET_EXPENSES,
} from "./mutations.type";

const state = {
    expenses: [],
    totalExpenses:null,
    expensesData: {},
    isLoading: false,
    validationErrors: null,
};

const getters = {
    expenses(state) {
        return state.expenses;
    },
    expensesData(state) {
        return state.expensesData;
    },
    totalExpenses(state) {
        return state.totalExpenses;
    },
    expense: (state) => (id) => {
        if (state.expenses != null) {
            var expense = state.expenses.find((expense) => expense.id == id);
            if (expense) {
                return expense;
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
    async [FETCH_EXPENSES](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.getExpenses(params);
            console.log(JSON.stringify(response.data));
            context.commit(SET_EXPENSES, response.data);
        } catch (error) {
            console.log(error);
            toast(error, {
                theme: "dark",
                type: "error",
                dangerouslyHTMLString: true,
            });
        }
    },

    async [SAVE_EXPENSE](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.saveExpense(params);
            console.log(JSON.stringify(response.data));
            context.dispatch(FETCH_EXPENSES);
            toast("Expense saved successfully.", {
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

    async [UPDATE_EXPENSE](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.updateExpense(params);
            console.log(JSON.stringify(response.data));
            context.dispatch(FETCH_EXPENSES);
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

    async [DELETE_EXPENSES](context, params) {
        console.log(params);
        context.commit(IS_LOADING);
        try {
            const response = await apiService.deleteExpenses(params);
            console.log(JSON.stringify(response.data));
            context.dispatch(FETCH_EXPENSES);
            toast("Expense deleted successfully.", {
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
    [SET_EXPENSES](state, data) {
        state.expenses = data.expenses.data;
        state.expensesData = data.expenses;
        state.totalExpenses = data.totalExpenses;
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
