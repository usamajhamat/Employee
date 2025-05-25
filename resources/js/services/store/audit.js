import { DELETE_AUDITS, FETCH_AUDITS, SAVE_AUDIT } from "./actions.type";
import apiService from "./apiService";
import { toast } from "vue3-toastify";
import {
    IS_LOADING,
    SET_VALIDATION_ERRORS,
    SET_AUDITS,
} from "./mutations.type";

const state = {
    audits: null,
    isLoading: false,
    validationErrors: null,
};

const getters = {
    audits(state) {
        return state.audits;
    },
    isLoading(state) {
        return state.isLoading;
    },
    validationErrors(state) {
        return state.validationErrors;
    },
};

// const actions = {
//     async [FETCH_AUDITS](context, params) {
//         context.commit(IS_LOADING);
//         try {
//             const response = await apiService.getAudits(params);
//             console.log(JSON.stringify(response.data));
//             context.commit(SET_AUDITS, response.data);
//         } catch (error) {
//             console.log(error);
//             toast(error, {
//                 "theme": "dark",
//                 "type": "error",
//                 "dangerouslyHTMLString": true
//             })
//         }
//     },
const actions = {
    async [FETCH_AUDITS](context, params = {}) {
        // Provide a default empty object
        context.commit(IS_LOADING);
        try {
            // Ensure params.auditTypes exists, default to an empty array if not
            const auditTypes = params.auditTypes || [];

            const response = await apiService.getAudits(params);
            console.log(JSON.stringify(response.data));

            let filteredAudits = response.data;

            // Filter the audits if audit types are specified
            if (auditTypes.length > 0) {
                filteredAudits = response.data.filter((audit) =>
                    auditTypes.includes(audit.audit_type)
                );
            }

            context.commit(SET_AUDITS, filteredAudits);
        } catch (error) {
            console.log(error);
            toast(error, {
                theme: "dark",
                type: "error",
                dangerouslyHTMLString: true,
            });
        }
    },
    async [SAVE_AUDIT](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.saveAudit(params);
            console.log(JSON.stringify(response.data));
            context.dispatch(FETCH_AUDITS);
            toast("Audit saved successfully.", {
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

    async [DELETE_AUDITS](context, params) {
        console.log(params);
        context.commit(IS_LOADING);
        try {
            const response = await apiService.deleteAudits(params);
            console.log(JSON.stringify(response.data));
            context.dispatch(FETCH_AUDITS);
            toast("Audit deleted successfully.", {
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
    [SET_AUDITS](state, data) {
        state.audits = data;
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
