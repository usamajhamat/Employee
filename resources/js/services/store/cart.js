import { DELETE_CART_ITEMS, FETCH_CART_ITEMS, SAVE_CART_ITEM, UPDATE_CART_ITEM } from "./actions.type";
import apiService from "./apiService";
import { toast } from "vue3-toastify";
import {
    IS_LOADING,
    SET_VALIDATION_ERRORS,

    SET_CART_ITEMS,
} from "./mutations.type";

const state = {
    cartItems: [],
    isLoading: false,
    validationErrors: null,
}

const getters = {
    cartItems(state) {
        return state.cartItems;
    },
    cartItem: (state) => (id) => {
        if (state.cartItems != null) {
            var cartItem = state.cartItems.find((cartItem) => cartItem.id == id);
            if (cartItem) {
                return cartItem;
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
    async [FETCH_CART_ITEMS](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.getCartItems(params);
            console.log(JSON.stringify(response.data));
            context.commit(SET_CART_ITEMS, response.data);
        } catch (error) {
            console.log(error);
            toast(error, {
                "theme": "dark",
                "type": "error",
                "dangerouslyHTMLString": true
            })
        }
    },

    async [SAVE_CART_ITEM](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.saveCartItem(params);
            console.log(JSON.stringify(response.data));
            context.dispatch(FETCH_CART_ITEMS);
            toast('Product added to cart successfully.', {
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

    async [UPDATE_CART_ITEM](context, params) {
        context.commit(IS_LOADING);
        try {
            const response = await apiService.updateCartItem(params);
            console.log(JSON.stringify(response.data));
            context.dispatch(FETCH_CART_ITEMS, {
                cart_type: response.data.cart_type
            });
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

    async [DELETE_CART_ITEMS](context, params) {
        console.log(params);
        context.commit(IS_LOADING);
        try {
            const response = await apiService.deleteCartItems(params);
            console.log(JSON.stringify(response.data));
            context.dispatch(FETCH_CART_ITEMS);
            toast('Product deleted from cart successfully.', {
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
    [SET_CART_ITEMS](state, data) {
        state.cartItems = data;
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