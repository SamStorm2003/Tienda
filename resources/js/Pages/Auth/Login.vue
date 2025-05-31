<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

//Modo Movil
import { ref, onMounted } from 'vue';

const windowWidth = ref(window.innerWidth);

onMounted(() => {
    window.addEventListener('resize', () => {
        windowWidth.value = window.innerWidth;
    });
});
</script>


<template>
    <Head title="Login" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <div class="min-h-screen flex items-center justify-center relative">
        <!-- Fondo del formulario con imagen difuminada -->
        <div class="absolute inset-0 bg-cover bg-center filter blur-[2px]" 
             :style="{'background-image': windowWidth <= 640 ? 'url(/Images/imagenlogin.png)' : 'url(/Images/Imagenderegistro.webp)'}">
        </div>
        <!-- Fondo del formulario con glassmorphism y sombra más visible -->
        <div class="rounded-xl bg-sky-950/95 shadow-xl p-8 md:max-w-md w-full relative z-10">
            <div class="flex justify-center mb-6"> 
                <img src="/Imagenes/logotiendados.jpg" alt="Logo" class="h-16 md:h-24 rounded-full" />

            </div>

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>

            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label for="email" class="block text-gray-100 font-bold mb-2 text-shadow">Correo Electronico:</label>
                    <input
                        id="email"
                        v-model="form.email"
                        type="email"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required
                        autofocus
                        autocomplete="username"
                    />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-gray-100 font-bold mb-2 text-shadow">Contrasena:</label>
                    <input
                        id="password"
                        v-model="form.password"
                        type="password"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required
                        autocomplete="current-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center">
                        <!-- Personalizar el checkbox -->
                        <Checkbox v-model:checked="form.remember" name="remember" class="text-sky-600" />
                        <span class="ml-2 text-m font-bold text-gray-100  text-shadow ">Recuérdame la contrasena</span>
                    </label>
                    <Link v-if="canResetPassword" :href="route('password.request')" class="inline-block align-baseline font-bold text-blue-300 hover:text-gray-100  text-shadow">
                        ¿Olvidaste tu contraseña?
                    </Link>
                </div>

                <div class="flex items-center justify-center">
                    <!-- Cambiar el color del botón -->
                    <PrimaryButton class="px-6 py-4 bg-black-100 hover:bg-sky-600 text-white rounded" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        <i class="fas fa-sign-in-alt mr-2"></i> Iniciar sesión
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped>
/* Glassmorphism con una sombra más visible */
.glassmorphism-card {
    background: rgba(255, 255, 255, 0.1); /* Fondo blanco semi-transparente */
    border-radius: 16px;
    backdrop-filter: blur(10px); /* Desenfoque de fondo */
    box-shadow: 0 20px 10px rgba(0, 0, 0, 0.2), 0 -20px 20px rgba(0, 0, 0, 0.2), 
                25px 0 20px rgba(0, 0, 0, 0.2), -20px 0 20px rgba(0, 0, 0, 0.424); /* Sombra alrededor del formulario */
    border: 1px solid rgba(255, 255, 255, 0.2); /* Bordes más suaves */
}


/* Diseño responsivo */
@media (max-width: 640px) {
    .rounded-xl {
        padding: 4px; /* Espacio menor en móviles */
    }
    .md\:max-w-md {
        max-width: 90%;
    }
    .h-16 {
        height: 4rem; /* Ajustar altura en móviles */
    }

}

.text-shadow {
    text-shadow: 2px 2px 4px rgb(0, 0, 0); /* Sombra negra */
}
</style>
