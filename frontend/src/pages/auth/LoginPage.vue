
<script setup lang="ts">
import { ref } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const showPassword = ref(false);
const email = ref('');
const password = ref('');

const authStore = useAuthStore();
const router = useRouter();
const route = useRoute();

const login = async () => {
    try {
        // call login in store
        await authStore.login(email.value, password.value);

        // get redirect query (if user was redirected to login)
        const redirect = route.query.redirect as string;

        // role-based redirect fallback
        const roleBasedRoute = authStore.user?.roles
            ? authStore.user.roles.some(r => ['super_admin', 'admin'].includes(r.slug))
                ? '/admin'
                : '/'
            : '/';

        // navigate
        router.push(redirect || roleBasedRoute);

    } catch (err: any) {
        console.log(err);
        // optional: show error message
    }
};
</script>

<template>
    <div class="w-[400px] h-auto m-auto bg-white shadow-3xl p-5 flex flex-col justify-center items-center">
        <div>
            <h1 class="font-bold py-3 text-4xl text-center text-moss-green">EBOOK</h1>
        </div>
        <form action="" @submit.prevent="login" class="flex flex-col justify-center items-center gap-5">
            <div class="flex flex-col">
                <label for="" class="text-xl py-2">Email <span class="text-red-700">*</span></label>
                <input v-model="email" type="email" class="p-1 w-[300px] outline-1 outline-moss-green">
            </div>
            <div class="flex flex-col w-[300px] relative">
                <label for="" class="text-xl py-2">Password <span class="text-red-700">*</span></label>
                <input v-model="password" :type="showPassword ? 'text' : 'password'" class="p-1 w-[300px] outline-1">
                <button type="button" @click="showPassword = !showPassword" class="absolute right-2 top-12 text-gray-400">{{ showPassword ? 'Hide' : 'Show' }}</button>
            </div>

            <button class="w-[300px] p-2 bg-moss-green text-xl font-bold text-white">Login</button>

            <div>
                <p class="">Don't have account ? <span class="text-moss-green font-bold"><RouterLink to="/register">Register</RouterLink></span> </p>
            </div>

        </form>
    </div>
</template>