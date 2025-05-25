import { FETCH_USER, LOGIN, LOGOUT } from "./actions.type";
import apiService from "./apiService";
import { toast } from "vue3-toastify";
import {
    IS_LOADING,
    SET_VALIDATION_ERRORS,
    SET_USER,
    SET_TOKEN,
    RESET_TOKEN,
} from "./mutations.type";

const state = {
    user: null,
    token: localStorage.getItem("access_token") || null,
    isLoading: false,
    validationErrors: null,
}

const getters = {
    token(state) {
        return state.token;
    },
    user(state) {
        return state.user;
    },
    isAuthenticated(state) {
        return state.token !== null;
    },
    isLoading(state) {
        return state.isLoading;
    },
    validationErrors(state) {
        return state.validationErrors;
    },
}

const actions = {
    async [FETCH_USER](context) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.getUser();
            console.log(JSON.stringify(response.data));
            context.commit(SET_USER, response.data);
        } catch (error) {
            console.log(error);
            toast(error, {
                "theme": "dark",
                "type": "error",
                "dangerouslyHTMLString": true
            })
        }
    },

    async [LOGIN](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.login(params);
            console.log(JSON.stringify(response.data));
            context.commit(SET_TOKEN, response.data.authorisation);
            toast('Login successfully.', {
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

    async [LOGOUT](context) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.logout();
            console.log(JSON.stringify(response.data));
            context.commit(RESET_TOKEN);
            toast('Logout successfully.', {
                "theme": "dark",
                "type": "success",
                "dangerouslyHTMLString": true
            })
        } catch (error) {
            console.log(error);
            context.commit(RESET_TOKEN);
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
    [SET_TOKEN](state, data) {
        var token = data.token;
        state.token = token;
        if (token) {
            localStorage.setItem("access_token", token);
        } else {
            toast('Failed to authenticate, please try again later.', {
                "theme": "dark",
                "type": "error",
                "dangerouslyHTMLString": true
            })
        }
    },
    [RESET_TOKEN](state) {
        state.token = null;
        localStorage.removeItem("access_token");
    },
    [SET_USER](state, data) {
        state.user = data;
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