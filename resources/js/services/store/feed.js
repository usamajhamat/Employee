import { DELETE_FEED, FETCH_FEEDS, SAVE_FEED, UPDATE_FEED } from "./actions.type";
import apiService from "./apiService";
import { toast } from "vue3-toastify";
import {
    IS_LOADING,
    SET_VALIDATION_ERRORS,
    SET_FEEDS,
} from "./mutations.type";

const state = {
    feeds: [],
    feedsData: {},
    isLoading: false,
    validationErrors: null,
}

const getters = {
    feeds(state) {
        return state.feeds;
    },
    feedsData(state) {
        return state.feedsData;
    },
    feed: (state) => (id) => {
        if (state.feeds != null) {
            var feed = state.feeds.find((feed) => feed.id == id);
            if (feed) {
                return feed;
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
    async [FETCH_FEEDS](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.getFeeds(params);
            console.log(JSON.stringify(response.data));
            context.commit(SET_FEEDS, response.data);
        } catch (error) {
            console.log(error);
            toast(error, {
                "theme": "dark",
                "type": "error",
                "dangerouslyHTMLString": true
            })
        }
    },

    async [SAVE_FEED](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.saveFeed(params);
            console.log(JSON.stringify(response.data));
            context.dispatch(FETCH_FEEDS);
            toast('Unit saved successfully.', {
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

    async [UPDATE_FEED](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.updateFeed(params);
            console.log(JSON.stringify(response.data));
            context.dispatch(FETCH_FEEDS)
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

    async [DELETE_FEED](context, params) {
        console.log(params);
        context.commit(IS_LOADING);
        try {
            const response = await apiService.deleteFeed(params);
            console.log(JSON.stringify(response.data));
            context.dispatch(FETCH_FEEDS);
            toast('Unit deleted successfully.', {
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
    [SET_FEEDS](state, data) {
        state.feeds = data.data;
        state.feedsData = data
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