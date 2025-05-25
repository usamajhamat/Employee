import { FETCH_DEVICE_STATUS, SAVE_TANAZA_ACCOUNT, SAVE_TANAZA_NETWORK } from "./actions.type";
import apiService from "./apiService";
import { toast } from "vue3-toastify";
import {
    IS_LOADING,
    SET_VALIDATION_ERRORS,
    SET_DEVICE_STATUS,
} from "./mutations.type";

const state = {
    deviceStatus: null,
    isLoading: false,
    validationErrors: null,
}

const getters = {
    deviceStatus(state) {
        return state.deviceStatus;
    },
    isLoading(state) {
        return state.isLoading;
    },
    validationErrors(state) {
        return state.validationErrors;
    },
}

const actions = {
    async [FETCH_DEVICE_STATUS](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.getDeviceStatus(params);
            console.log(JSON.stringify(response.data));
            context.commit(SET_DEVICE_STATUS, response.data);
        } catch (error) {
            console.log(error);
            toast(error, {
                "theme": "dark",
                "type": "error",
                "dangerouslyHTMLString": true
            })
        }
    },

    

     async [SAVE_TANAZA_ACCOUNT](context, params) {
            context.commit(IS_LOADING);
            try {
                const response = await apiService.saveTanazaAccount(params);
                console.log(JSON.stringify(response.data));
                // context.dispatch(FETCH_SUPPLIERS);
                // toast('Supplier saved successfully.', {
                //     "theme": "dark",
                //     "type": "success",
                //     "dangerouslyHTMLString": true
                // })
            } catch (error) {
                console.log(error);
                // toast(error, {
                //     "theme": "dark",
                //     "type": "error",
                //     "dangerouslyHTMLString": true
                // })
            } 
        },

         async [SAVE_TANAZA_NETWORK](context, params) {
            context.commit(IS_LOADING);
            try {
                const response = await apiService.saveTanazaNetwork(params);
                console.log(JSON.stringify(response.data));
                // context.dispatch(FETCH_SUPPLIERS);
                // toast('Supplier saved successfully.', {
                //     "theme": "dark",
                //     "type": "success",
                //     "dangerouslyHTMLString": true
                // })
            } catch (error) {
                console.log(error);
                // toast(error, {
                //     "theme": "dark",
                //     "type": "error",
                //     "dangerouslyHTMLString": true
                // })
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
    [SET_DEVICE_STATUS](state, data) {
        state.deviceStatus = data;
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



// import { createStore } from 'vuex';
// import axios from 'axios';

// export default createStore({
//   state: {
//     deviceStatus: null,
//     loading: false,
//     error: null,
//   },
//   mutations: {
//     setDeviceStatus(state, status) {
//       state.deviceStatus = status;
//     },
//     setLoading(state, loading) {
//       state.loading = loading;
//     },
//     setError(state, error) {
//       state.error = error;
//     },
//   },
//   actions: {
//     async fetchDeviceStatus({ commit }) {
//       commit('setLoading', true);
//       commit('setError', null);

//       try {
//         const response = await axios.get('http://localhost:8000/api/tanaza/status'); // Update with your Laravel API URL
//         if (response.data.success) {
//           commit('setDeviceStatus', response.data.data);
//         } else {
//           commit('setError', response.data.message);
//         }
//       } catch (error) {
//         commit('setError', 'Failed to fetch device status');
//       } finally {
//         commit('setLoading', false);
//       }
//     },
//   },
//   getters: {
//     deviceStatus: (state) => state.deviceStatus,
//     isLoading: (state) => state.loading,
//     error: (state) => state.error,
//   },
// });