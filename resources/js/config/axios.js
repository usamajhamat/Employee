import axios from "axios";

const apiClient = axios.create({
    baseURL:
        import.meta.env.VITE_MODE == "production"
            ? import.meta.env.VITE_API_ROOT + "v1"
            : import.meta.env.VITE_API_ROOT_LOCAL + "v1",
    headers: {
        Accept: "application/json",
       
        Authorization: "Bearer " + localStorage.getItem("access_token"),
    },
});

export default apiClient;