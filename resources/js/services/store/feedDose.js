import {
    DELETE_FEED_DOSE,
    FETCH_FEED_DOSES,
    SAVE_FEED_DOSE,
    UPDATE_FEED_DOSE,
} from "./actions.type";
import apiService from "./apiService";
import { toast } from "vue3-toastify";
import {
    IS_LOADING,
    SET_VALIDATION_ERRORS,
    SET_FEED_DOSE,
    SET_FEEDS
   
} from "./mutations.type";




const state = {
    feedDoses: [],
    FeedDoseData: {},
    isLoading: false,
    validationErrors: null,
};

const getters = {
    feedDoses(state) {
        return state.feedDoses;
    },
    FeedDoseData(state) {
        return state.FeedDoseData;
    },
    
    isLoading(state) {
        return state.isLoading;
    },
    validationErrors(state) {
        return state.validationErrors;
    },
};

const actions = {
    async [FETCH_FEED_DOSES](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.getFeedDoses(params);
            console.log(JSON.stringify(response.data));
            context.commit(SET_FEED_DOSE, response.data);
        } catch (error) {
            console.log(error);
            toast(error, {
                "theme": "dark",
                "type": "error",
                "dangerouslyHTMLString": true
            })
        }
    },
    async [SAVE_FEED_DOSE](context, params) {
        context.commit(IS_LOADING);
        try {
            console.log(params);
            const response = await apiService.saveFeedDose(params);
            console.log(JSON.stringify(response.data));
           
            toast("Feed dose set Successfully.", {
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
    [SET_FEED_DOSE](state, data) {
        state.feedDoses = data.data;
        state.FeedDoseData = data;
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