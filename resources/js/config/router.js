import { createRouter, createWebHistory } from "vue-router";
import routes from "../services/routes";
import store from "./store";
import { FETCH_USER } from "@/services/store/actions.type";

const router = createRouter({
    history: createWebHistory(),
    routes: routes
});



router.beforeEach((to, from) => {
    if (to.meta.requiresAuth && localStorage.getItem("access_token") == null) {
        return {
            path: "/",
        };
    }
});

export default router;

