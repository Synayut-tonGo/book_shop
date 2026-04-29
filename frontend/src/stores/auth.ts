import type { User } from "@/types/auth";
import api from "@/utils/api";
import { getToken, removeToken, setToken } from "@/utils/cookies";
import { getDashboardRoute } from "@/utils/roleUtils";
import { defineStore } from "pinia";
import { computed, ref } from "vue";
import { useRouter } from "vue-router";
import router from "../router/index"

export const useAuthStore = defineStore('auth' , () => {


    // state
    const user = ref<User| null> (null)
    const isLoading = ref<boolean> (false)
    const error = ref<string | null> (null)

    // getter 

    const isAuthentication = computed(() => !!user.value);
    const isSuperAdmin = computed(() => user.value?.roles.some (r => r.slug === 'super_admin')?? false)
    const isAdminOrSuper = computed(() => user.value?.roles.some(r => ['super_admin' , 'admin'].includes(r.slug)) ?? false)

    // action

    const register = async (formData : FormData) => {

        isLoading.value = true;
        error.value = null 

        try{
            const res = await api.post('/register', formData)
            const {token , user: newUser} = res.data.data;
            setToken(token);
            user.value = newUser
            const router = useRouter();
            router.push('/')
        }catch(err:any){

            error.value = err.response?.data.message || 'Registration failed'

        }finally{
            isLoading.value = false;
        }
        

    }

    const login = async (email:string , password:string)=>{
        isLoading.value = true;
        error.value = null;

        try{

            const res = await api.post('/login', {email,password});
            const {token, user:loggedUser} = res.data.data;
            setToken(token);
            user.value = loggedUser
            router.push(getDashboardRoute(user.value?.roles));

        }catch(err : any){
            error.value = err.response?.data.message || 'Login failed'
        }finally{
            isLoading.value = false
        }
    }

    
    const fetchUser = async () => {
        if (!getToken()) return;
        try{
            const res = api.get('/me') ;
            user.value = (await res).data.data.user
        }catch(err : any) {
            logOut();
        }
    }
    const logOut = async () => {
        removeToken();
        user.value = null;
        router.push('/login')
    }

    return {
        user,
        isLoading,
        error,
        isAuthentication,
        isSuperAdmin,
        isAdminOrSuper,
        register,
        fetchUser,
        login,
        logOut,
    }

})
