import { toast } from "vue3-toastify";
import {
    DELETE_TODO,
    FETCH_TODOS,
    FETCH_TODO_DETAILS,
    SAVE_TODO,
    UPDATE_TODO,
} from "./actions.type";
import apiService from "./apiService";
import {
    IS_LOADING,
    NOT_IS_LOADING,
    SET_API_ERROR,
    SET_TODOS,
    SET_TODO_DETAILS,
} from "./mutations.type";

const state = {
    todos: [],
    todoDetails: null,
    isLoading: false,
    apiErrors: [],
};

const getters = {
    todos: (state) => state.todos,
    todoDetails: (state) => state.todoDetails,
    isLoading: (state) => state.isLoading,
    apiErrors: (state) => state.apiErrors,
};

const actions = {
    async [SAVE_TODO](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.saveTodo(params);
            context.dispatch(FETCH_TODOS); // Refresh list
            //   toast("ToDo saved successfully", { type: "success" });
        } catch (error) {
            console.error("SAVE_TODO error:", error);
            const errorMessage =
                error.response?.data?.message || "An error occurred.";
            toast(errorMessage, { type: "error" });
            context.commit(SET_API_ERROR, error);
        } finally {
            context.commit(NOT_IS_LOADING);
        }
    },

    async [FETCH_TODOS](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.getTodos(params);
            context.commit(SET_TODOS, response.data);
        } catch (error) {
            toast(error, {
                theme: "dark",
                type: "error",
                dangerouslyHTMLString: true,
            });
        } finally {
            context.commit(NOT_IS_LOADING);
        }
    },

    async [FETCH_TODO_DETAILS](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.getTodoDetails(params);
            context.commit(SET_TODO_DETAILS, response.data);
        }  catch (error) {
            toast(error, {
                theme: "dark",
                type: "error",
                dangerouslyHTMLString: true,
            });
        } finally {
            context.commit(NOT_IS_LOADING);
        }
    },

    async [UPDATE_TODO](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.updateTodo(params);
            context.commit(SET_TODO_DETAILS, response.data);
            toast("ToDo updated successfully", { type: "success" });
        } catch (error) {
            toast(error, {
                theme: "dark",
                type: "error",
                dangerouslyHTMLString: true,
            });
        } finally {
            context.commit(NOT_IS_LOADING);
        }
    },

    async [DELETE_TODO](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.deleteTodo(params);
            context.dispatch(FETCH_TODOS); // Refresh list after deletion
            toast("ToDo deleted successfully", { type: "success" });
        } catch (error) {
            toast(error, {
                theme: "dark",
                type: "error",
                dangerouslyHTMLString: true,
            });
        } finally {
            context.commit(NOT_IS_LOADING);
        }
    },
};

const mutations = {
    [IS_LOADING](state) {
        state.isLoading = true;
    },
    [NOT_IS_LOADING](state) {
        state.isLoading = false;
    },
    [SET_API_ERROR](state, error) {
        if (error.response?.data?.errors) {
            state.apiErrors = error.response.data.errors;
        } else {
            state.apiErrors = [error.message || "Unknown error"];
        }
    },
    [SET_TODOS](state, data) {
        state.todos = data;
        state.isLoading = false;
    },
    [SET_TODO_DETAILS](state, data) {
        state.todoDetails = data;
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
