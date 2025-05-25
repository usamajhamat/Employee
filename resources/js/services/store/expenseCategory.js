import {
    DELETE_EXPENSE_CATEGORIES,
    FETCH_EXPENSE_CATEGORIES,
    SAVE_EXPENSE_CATEGORY,
    UPDATE_EXPENSE_CATEGORY,
} from "./actions.type";
import apiService from "./apiService";
import { toast } from "vue3-toastify";
import {
    IS_LOADING,
    SET_VALIDATION_ERRORS,
    SET_EXPENSE_CATEGORIES,
} from "./mutations.type";

const state = {
    expenseCategories: [],
    expenseCategoriesData: {},
    isLoading: false,
    validationErrors: null,
};

const getters = {
    expenseCategories(state) {
        return state.expenseCategories;
    },
    expenseCategoriesData(state) {
        return state.expenseCategoriesData;
    },
    expenseCategory: (state) => (id) => {
        if (state.expenseCategories != null) {
            var category = state.expenseCategories.find(
                (category) => category.id == id
            );
            if (category) {
                return category;
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
    async [FETCH_EXPENSE_CATEGORIES](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.getExpenseCategories(params);
            console.log(JSON.stringify(response.data));
            context.commit(SET_EXPENSE_CATEGORIES, response.data);
        } catch (error) {
            console.log(error);
            toast(error, {
                theme: "dark",
                type: "error",
                dangerouslyHTMLString: true,
            });
        }
    },

    async [SAVE_EXPENSE_CATEGORY](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.saveExpenseCategory(params);
            console.log(JSON.stringify(response.data));
            context.dispatch(FETCH_EXPENSE_CATEGORIES);
            toast("Expense category saved successfully.", {
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

    async [UPDATE_EXPENSE_CATEGORY](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.updateExpenseCategory(params);
            console.log(JSON.stringify(response.data));
            context.dispatch(FETCH_EXPENSE_CATEGORIES);
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

    async [DELETE_EXPENSE_CATEGORIES](context, params) {
        console.log(params);
        context.commit(IS_LOADING);
        try {
            const response = await apiService.deleteExpenseCategories(params);
            console.log(JSON.stringify(response.data));
            context.dispatch(FETCH_EXPENSE_CATEGORIES);
            toast("Expense category deleted successfully.", {
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
    [SET_EXPENSE_CATEGORIES](state, data) {
        state.expenseCategories = data.data;
        state.expenseCategoriesData = data;
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
