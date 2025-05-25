import { DELETE_CATEGORIES, FETCH_CATEGORIES, SAVE_CATEGORY, UPDATE_CATEGORY } from "./actions.type";
import apiService from "./apiService";
import { toast } from "vue3-toastify";
import {
    IS_LOADING,
    SET_VALIDATION_ERRORS,
    SET_CATEGORIES,
} from "./mutations.type";

const state = {
    categories: [],
    categoriesData: {},
    isLoading: false,
    validationErrors: null,
}

const getters = {
    categories(state) {
        return state.categories;
    },
    categoriesData(state) {
        return state.categoriesData;
    },
    category: (state) => (id) => {
        if (state.categories != null) {
            var category = state.categories.find((category) => category.id == id);
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
}

const actions = {
    async [FETCH_CATEGORIES](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.getCategories(params);
            console.log(JSON.stringify(response.data));
            context.commit(SET_CATEGORIES, response.data);
        } catch (error) {
            console.log(error);
            toast(error, {
                "theme": "dark",
                "type": "error",
                "dangerouslyHTMLString": true
            })
        }
    },

    async [SAVE_CATEGORY](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.saveCategory(params);
            console.log(JSON.stringify(response.data));
            context.dispatch(FETCH_CATEGORIES);
            toast('Category saved successfully.', {
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

    async [UPDATE_CATEGORY](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.updateCategory(params);
            console.log(JSON.stringify(response.data));
            context.dispatch(FETCH_CATEGORIES)
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

    async [DELETE_CATEGORIES](context, params) {
        console.log(params);
        context.commit(IS_LOADING);
        try {
            const response = await apiService.deleteCategories(params);
            console.log(JSON.stringify(response.data));
            context.dispatch(FETCH_CATEGORIES);
            toast('Category deleted successfully.', {
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
    [SET_CATEGORIES](state, data) {
        state.categories = data.data;
        state.categoriesData = data
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