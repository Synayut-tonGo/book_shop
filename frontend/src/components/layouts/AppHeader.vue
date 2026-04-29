<script setup lang="ts">
import { useAuthStore } from '@/stores/auth';
import { RouterLink } from 'vue-router';

const authStore = useAuthStore()
</script>
<template>
        <header class="w-screen h-auto fixed z-0 p-3 backdrop-blur-3xl bg-sage">
            <div class="w-[1500px] m-auto text-2xl font-semibold flex items-center justify-between ">
                <!-- Logo text -->
                <div class="text-3xl w-[300px] flex justify-between items-center text-deep-forest">
                        <RouterLink to="/" class="font-black hover:scale-150  hover:text-2xl hover:text-warm-sand transition duration-300 ">
                            EBOOK STORE
                        </RouterLink>
                </div>
                <!-- content -->
                <div class="w-[600px] flex justify-between items-center">
                        <RouterLink to="/" class=" hover:scale-125  hover:text-2xl hover:text-warm-sand transition duration-300 ">
                            Home
                        </RouterLink>
                        <RouterLink to="/about" class=" hover:scale-125  hover:text-2xl hover:text-warm-sand transition duration-300 ">
                            About
                        </RouterLink>
                        <RouterLink to="/book" class=" hover:scale-125  hover:text-2xl hover:text-warm-sand transition duration-300 ">
                            Book
                        </RouterLink>
                        <RouterLink to="/contact" class=" hover:scale-125  hover:text-2xl hover:text-warm-sand transition duration-300 ">
                            Contact
                        </RouterLink>
                        <RouterLink to="/cart" class=" hover:scale-125  hover:text-2xl hover:text-warm-sand transition duration-300 ">
                            Carts
                        </RouterLink>                                                                                                 
                </div>
                <!-- Authentication -->
                <div class="flex items-center justify-end w-[500px] gap-5">
                    <div v-if="authStore.isAuthentication"
                    class="flex items-center gap-5"
                    >
                        <p>{{ authStore.user?.full_name }}</p>
                        <button class="p-3 bg-moss-green text-white rounded text-xl font-medium hover:bg-warm-sand hover:scale-125 shadow-md hover:shadow-warm-sand hover:text-moss-green transition duration-300" @click="authStore.logOut">LogOut</button>
                    </div>
                    <div v-else
                    class="flex gap-5"
                    >
                        <button class="p-3 bg-moss-green text-white rounded text-xl hover:scale-110 hover:bg-warm-sand shadow-md hover:shadow-warm-sand hover:text-moss-green transition duration-300"><RouterLink to="/login">Login</RouterLink></button>
                        <button class="p-3  bg-moss-green text-white rounded text-xl hover:scale-110 hover:bg-warm-sand shadow-md hover:shadow-warm-sand hover:text-moss-green transition duration-300"><RouterLink to="/register">Register</RouterLink></button>
                    </div>
                </div>
            </div>
        </header>
</template>