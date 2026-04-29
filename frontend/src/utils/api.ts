import axios from "axios";
import { API_BASE_URL } from "./constants";
import { getToken } from "./cookies";
import { useAuthStore } from "@/stores/auth";


const api = axios.create({
    baseURL: API_BASE_URL,
    timeout: 15000
})

api.interceptors.request.use(config => {
    const token = getToken();

    if(token) {
        config.headers.Authorization = `Bearer ${token}`
    }

    if(config.data instanceof FormData){
        config.headers ["Content-Type"] = "Content-Type";
    }

    return config

})

api.interceptors.response.use(response => response,
    error => {
        if(error.response?.status === 401){
            import('../stores/auth').then(({useAuthStore})=> {
                const authStore = useAuthStore();
                authStore.logOut();
            })
        }

        return Promise.reject(error);
    }
)

export default api