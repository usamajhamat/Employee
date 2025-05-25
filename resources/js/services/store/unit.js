import { DELETE_UNITS, FETCH_UNITS, SAVE_UNIT, UPDATE_UNIT } from "./actions.type";
import apiService from "./apiService";
import { toast } from "vue3-toastify";
import {
    IS_LOADING,
    SET_VALIDATION_ERRORS,
    SET_UNITS,
} from "./mutations.type";

const state = {
    units: [],
    unitsData: {},
    isLoading: false,
    validationErrors: null,
}

const getters = {
    units(state) {
        return state.units;
    },
    unitsData(state) {
        return state.unitsData;
    },
    unit: (state) => (id) => {
        if (state.units != null) {
            var unit = state.units.find((unit) => unit.id == id);
            if (unit) {
                return unit;
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
    async [FETCH_UNITS](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.getUnits(params);
            console.log(JSON.stringify(response.data));
            context.commit(SET_UNITS, response.data);
        } catch (error) {
            console.log(error);
            toast(error, {
                "theme": "dark",
                "type": "error",
                "dangerouslyHTMLString": true
            })
        }
    },

    async [SAVE_UNIT](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.saveUnit(params);
            console.log(JSON.stringify(response.data));
            context.dispatch(FETCH_UNITS);
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

    async [UPDATE_UNIT](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.updateUnit(params);
            console.log(JSON.stringify(response.data));
            context.dispatch(FETCH_UNITS)
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

    async [DELETE_UNITS](context, params) {
        console.log(params);
        context.commit(IS_LOADING);
        try {
            const response = await apiService.deleteUnits(params);
            console.log(JSON.stringify(response.data));
            context.dispatch(FETCH_UNITS);
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
    [SET_UNITS](state, data) {
        state.units = data.data;
        state.unitsData = data
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