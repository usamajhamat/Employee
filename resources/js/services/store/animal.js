import {
    DELETE_ANIMAL,
    FETCH_ANIMALS,
    SAVE_ANIMAL,
    UPDATE_ANIMAL,
    FETCH_ANIMAL_STATS
} from "./actions.type";
import apiService from "./apiService";
import { toast } from "vue3-toastify";
import {
    IS_LOADING,
    SET_VALIDATION_ERRORS,
    SET_ANIMALS,
    SET_ANIMAL_STATS
} from "./mutations.type";

const state = {
    animals: [],
    animalStats: [],
    animalsData: {},
    isLoading: false,
    validationErrors: null,
};

const getters = {
    animals(state) {
        return state.animals;
    },
    animalsData(state) {
        return state.animalsData;
    },
    animalStats(state) {
        return state.animalStats;
    },
    animal: (state) => (id) => {
        if (state.animals != null) {
            var animal = state.animals.find((animal) => animal.id == id);
            if (animal) {
                return animal;
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
    async [FETCH_ANIMALS](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.getAnimals(params);
            console.log("Animals Data : " + JSON.stringify(response.data));
            context.commit(SET_ANIMALS, response.data);
           
        } catch (error) {
            console.log(error);
            toast(error, {
                theme: "dark",
                type: "error",
                dangerouslyHTMLString: true,
            });
        }
    },

    async [FETCH_ANIMAL_STATS](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.getAnimalStats(params);
            console.log("Animals Data : " + JSON.stringify(response.data));
            context.commit(SET_ANIMAL_STATS, response.data);
           
        } catch (error) {
            console.log(error);
            toast(error, {
                theme: "dark",
                type: "error",
                dangerouslyHTMLString: true,
            });
        }
    },

    async [SAVE_ANIMAL](context, params) {
        context.commit(IS_LOADING);
        try {
            console.log(params);
            const response = await apiService.saveAnimal(params);
            console.log(JSON.stringify(response.data));
            context.dispatch(FETCH_ANIMALS);
            toast("Animal saved successfully.", {
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

    async [UPDATE_ANIMAL](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.updateAnimal(params);
            console.log(JSON.stringify(response.data));
            context.dispatch(FETCH_ANIMALS);
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

    async [DELETE_ANIMAL](context, params) {
        console.log(params);
        context.commit(IS_LOADING);
        try {
            const response = await apiService.deleteAnimals(params);
            console.log(JSON.stringify(response.data));
            context.dispatch(FETCH_ANIMALS);
            toast("ANIMAL deleted successfully.", {
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
    [SET_ANIMALS](state, data) {
        state.animals = data.data;
        state.animalsData = data;
        state.isLoading = false;
    },
    [SET_ANIMAL_STATS](state, data) {
        state.animalStats = data;
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
