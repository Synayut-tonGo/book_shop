
<script setup lang="ts">
import { useAuthStore } from '@/stores/auth';
import { computed, reactive, ref, watch, watchEffect } from 'vue';
import { useRoute, useRouter } from 'vue-router';

    const showPassword = ref<boolean>(false)
    const nextStep = ref<number> (1)
    const error = ref<any> ({});
    const imageFile = ref<File | null> (null)
    const imagePreview = ref<string | undefined> (undefined)
    const form = reactive({
        first_name: '',
        last_name: '',
        email: '',
        password: '',
        password_confirmation: '',
        phone: '',
        gender: '' ,
        dob: '' ,
        image: null ,
    })
    
    const passNotMatchError = ref<string>('') 

    watchEffect ( () => {
        error.value = {}
        if(form.first_name.trim() === ''){
            error.value.first_name = 'Required First Name'
        }
        if(form.last_name.trim() === ''){
            error.value.last_name = 'Required Last Name'
        }
        if(form.email.trim() === ''){
            error.value.email = 'Required Email'
        }
        if(form.password.trim() === ''){
            error.value.password = 'Required Password'
        }
        if(form.password_confirmation.trim() === ''){
            error.value.password_confirmation = 'Required Password Confirmation'
        }
        if(form.phone.trim() === ''){
            error.value.phone = 'Required Phone'
        }
        if(form.gender.trim() === ''){
            error.value.gender = 'Required gender'
        }
        if(form.dob.trim() === ''){
            error.value.dob = 'Required Date of Birth'
        }

        return Object.keys(error.value).length === 0                                        
    })


    const onChangeImage = (e: Event) => {
        const target = e.target as HTMLInputElement

        if(target.files && target.files[0]){
            if(imagePreview.value) {
                URL.revokeObjectURL(imagePreview.value)
            }

            imageFile.value = target.files[0];
            imagePreview.value = URL.createObjectURL(target.files[0])
            target.value = ''
        }
    }

    const disableNextStep = computed(() => {
        if(nextStep.value === 1) {
            return form.first_name.trim() !== '' && form.last_name.trim() !== '' && form.email.trim() !== '' && form.phone.trim() !== '' && form.gender.trim() !== '' && form.dob.trim() !== '' ;
        }

        if(nextStep.value == 2) {
            return form.password.trim() !== '' && form.password_confirmation.trim() !== '' && (form.password.trim() === form.password_confirmation.trim())
        }
    })

    watch(()=>[form.password , form.password_confirmation],() => {
        if(form.password !== form.password_confirmation){
        passNotMatchError.value = 'Password not match'
        } else {
        passNotMatchError.value = ''
        }
    })

    const toNextStep = () => {
       nextStep.value ++
    }
    const backNextStep = () => {
       nextStep.value --
    }

    // const formData = new FormData()

    // Object.entries(form).forEach(([key, value]) => {
    //     if (value !== null) {
    //         formData.append(key, value)
    //     }
    // })

    // if (form.image) {
    //     formData.append('image', form.image)
    // }

    const authStore = useAuthStore();
    const router = useRouter();
    const route = useRoute();
    // ✅ Create submit handler function
    const handleSubmit = async () => {
        const formData = new FormData()
        
        Object.entries(form).forEach(([key, value]) => {
            if (value !== null && value !== '') {
                formData.append(key, value)
            }
        })

        if (imageFile.value) {
            formData.append('image', imageFile.value)
        }
        console.log(formData)
        await authStore.register(formData)
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
    }

</script>

<template>
    <div class="w-[400px] h-auto m-auto bg-white shadow-3xl p-5 flex flex-col justify-center items-center">
        <div>
            <h1 class="font-bold py-3 text-4xl text-center text-moss-green">EBOOK</h1>
        </div>
        <form action="" @submit.prevent="handleSubmit" class="flex flex-col justify-center items-center gap-5">
            <div v-if="nextStep === 1" class="">
                <div class="flex flex-col">
                    <label for="first_name" class="text-xl py-2">First Name <span class="text-red-700">*</span></label>
                    <input id="first_name" type="text" class="p-1 w-[300px] outline-1" v-model="form.first_name">
                    <p v-if="error.first_name" class="text-sm text-red-700">{{ error.first_name }}</p>
                </div>
                <div class="flex flex-col w-[300px] relative">
                    <label for="last_name" class="text-xl py-2">Last Name <span class="text-red-700">*</span></label>
                    <input id="last_name" type="text" class="p-1 w-[300px] outline-1" v-model="form.last_name">
                    <p v-if="error.last_name" class="text-sm text-red-700">{{ error.last_name }}</p>
                </div>
                <div class="flex flex-col w-[300px] relative">
                    <label for="email" class="text-xl py-2">email <span class="text-red-700">*</span></label>
                    <input id="email" type="email" class="p-1 w-[300px] outline-1" v-model="form.email">
                    <p v-if="error.email" class="text-sm text-red-700">{{ error.email }}</p>
                </div>
                <div class="flex flex-col w-[300px] relative">
                    <label for="phone" class="text-xl py-2">Phone <span class="text-red-700">*</span></label>
                    <input id="phone" type="text" class="p-1 w-[300px] outline-1" v-model="form.phone">
                    <p v-if="error.phone" class="text-sm text-red-700">{{ error.phone }}</p>
                </div>
                <div class="flex flex-col w-[300px] relative">
                    <label for="dob" class="text-xl py-2">Date of Birth <span class="text-red-700">*</span></label>
                    <input id="dob" type="date" class="p-1 w-[300px] outline-1" v-model="form.dob">
                    <p v-if="error.dob" class="text-sm text-red-700">{{ error.dob }}</p>
                </div>
                <div class="flex flex-col w-[300px] relative">
                    <label for="gender" class="text-xl py-2">Gender <span class="text-red-700">*</span></label>
                    <div class="flex gap-5">
                        <input id="gender_male" type="radio" name="gender" class="p-1" value="male" v-model="form.gender"> <span>Male</span>
                        <input id="gender_female" type="radio" name="gender" class="p-1" value="female" v-model="form.gender"> <span>Female</span>
                    </div>
                    <p v-if="error.gender" class="text-sm text-red-700">{{ error.gender }}</p>
                </div>                
                <button type="button" @click="toNextStep" :disabled="!disableNextStep" :class="!disableNextStep ? 'w-[300px] p-2 mt-3 bg-sage text-xl font-bold text-white' : 'w-[300px] p-2 mt-3 bg-moss-green text-xl font-bold text-white'"> Next Step ({{nextStep}}/3) </button>                
            </div>
            <div v-if="nextStep === 2" class="">

                    <div class="flex flex-col w-[300px] relative">
                        <label for="password" class="text-xl py-2">Password <span class="text-red-700">*</span></label>
                        <input id="password" :type="showPassword ? 'text' : 'password'" class="p-1 w-[280px] outline-1" v-model="form.password">
                        <input v-model="showPassword" type="checkbox" class="absolute  top-13 right-0">
                        <p v-if="error.password" class="text-sm text-red-700">{{ error.password }}</p>
                    </div>
                    <div class="flex flex-col w-[300px] relative">
                        <label for="password_confirmation" class="text-xl py-2">Password Confirmation <span class="text-red-700">*</span></label>
                        <input id="password_confirmation" :type="showPassword ? 'text' : 'password'" class="p-1 w-[300px] outline-1" v-model="form.password_confirmation">
                        <p v-if="error.password_confirmation" class="text-sm text-red-700">{{ error.password_confirmation }}</p>
                    </div>
                    <p v-if="passNotMatchError" class="text-sm text-red-700">{{ passNotMatchError }}</p>                      
                    <div class="flex gap-5 justify-between">
                        <button @click="backNextStep" type="button" class="w-[140px] p-2 mt-3 bg-deep-forest text-white"> Back </button>
                        <button type="button" @click="toNextStep" :disabled="!disableNextStep" :class="!disableNextStep ? 'w-[140px] p-2 mt-3 bg-sage text-white' : 'w-[140px] p-2 mt-3 bg-moss-green text-white'"> Next Step <span>({{nextStep}}/3)</span> </button>
                    </div>

            </div>
            
            <div v-if="nextStep === 3" class="">

                    <div class="flex flex-col w-[300px] justify-center items-center">
                        <label for="image" class="text-xl py-2">Profile Photo <span class="text-blue-700 text-sm">(Optional)</span></label>
                        
                        <div class="w-50 h-50 flex flex-col items-center justify-center bg-gray-300 rounded-md relative">
                            <input id="image" @change="onChangeImage" type="file" class="w-50 h-50 bg-red-600 flex items-center justify-center absolute opacity-0">
                            <img  :src="imagePreview" alt="" class="w-38 h-38 rounded-full object-cover border-2 border-white shadow">
                        </div>
                    </div>
                    <div class="flex gap-5 justify-between">
                            <button @click="backNextStep" type="button" class="w-[140px] p-2 mt-3 bg-deep-forest text-white"> Back </button>
                            <button type="submit" class="w-[140px] p-2 mt-3 bg-moss-green text-xl font-bold text-white">Register</button>
                    </div> 

            </div>

            <div>
                <p class="">Already have account ? <span class="text-moss-green font-bold"><RouterLink to="/login">Login</RouterLink></span> </p>
            </div>

        </form>
    </div>
</template>