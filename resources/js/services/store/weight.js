import{
    FETCH_WEIGHTS,
    SAVE_WEIGHT,
    UPDATE_WEIGHT,
    DELETE_WEIGHT
} from "./actions.type";
import apiService from "./apiService";
import { toast } from "vue3-toastify";
import { IS_LOADING, SET_VALIDATION_ERRORS, SET_WEIGHT } from "./mutations.type";


const state = {
    weights: [],
    weightsData: {},
    isLoading: false,
    validationErrors: null,
};

const getters = {
    weights(state) {
        return state.weights;
    },
    weightsData(state) {
        return state.weightsData;
    },
    weight: (state) => (id) => {
        if (state.weight != null) {
            var weight = state.weight.find((weight) => weight.id == id);
            if (weight) {
                return weight;
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
    
    async [FETCH_WEIGHTS](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.getWeights(params);
            console.log(JSON.stringify(response.data));
            context.commit(SET_WEIGHT, response.data);
        } catch (error) {
            console.log(error);
            toast(error, {
                theme: "dark",
                type: "error",
                dangerouslyHTMLString: true,
            });
        }
    },
    async [SAVE_WEIGHT](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.saveWeight(params);
            console.log(JSON.stringify(response.data));
            context.dispatch(FETCH_WEIGHTS, {
                animalId: response.data.animalId 
            });
            toast("Weight saved successfully.", {
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
    }

    
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
    [SET_WEIGHT](state, data) {
        state.weights = data.data;
        state.weightsData = data;
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
