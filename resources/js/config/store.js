import { createStore } from "vuex";


import employee from "@/services/store/employee";
import company from "@/services/store/company";
import auth from "@/services/store/auth";

export default createStore({
    modules: {
        auth: auth,

        employee: employee,
        company: company,
    },
});
