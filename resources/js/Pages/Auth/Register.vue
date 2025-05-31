<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
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
    <Head title="Registro Shatoria Desing" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <div class="min-h-screen flex items-center justify-center relative">
        <div class="absolute inset-0 bg-cover bg-center filter blur-sm" 
             :style="{'background-image': windowWidth <= 640 ? 'url(/Images/Imagenderegistro.webp)' : 'url(/Images/imagenlogin.png)'}">
        </div>

        <!-- Fondo del formulario con glassmorphism -->
        <div class="rounded-xl bg-sky-950/95 shadow-xl p-8 md:max-w-md w-full relative z-10">
            <div class="flex justify-center mb-6"> 
                <img src="/Imagenes/logotiendados.jpg" alt="Logo" class="h-16 md:h-24 rounded-full"/>
            </div>

            <form @submit.prevent="submit">
                <div class="mb-4">
                    <InputLabel for="name" class="block text-sky-100 font-bold mb-2 text-shadow" value="Nombre:" />
                    <TextInput
                        id="name"
                        v-model="form.name"
                        type="text"
                        class="mt-1 block w-full shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required
                        autofocus
                        autocomplete="name"
                    />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div class="mt-4">
                    <InputLabel for="email" class="block text-sky-100 font-bold mb-2 text-shadow" value="Correo electronico:" />
                    <TextInput
                        id="email"
                        v-model="form.email"
                        type="email"
                        class="mt-1 block w-full shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required
                        autocomplete="username"
                    />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-4">
                    <InputLabel for="password" class="block text-sky-100 font-bold mb-2 text-shadow" value="Contrasena:" />
                    <TextInput
                        id="password"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-full shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required
                        autocomplete="new-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="mt-4">
                    <InputLabel for="password_confirmation" class="block text-sky-100 font-bold mb-2 text-shadow" value="Confirma su contrasena:" />
                    <TextInput
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        class="mt-1 block w-full shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required
                        autocomplete="new-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password_confirmation" />
                </div>

                <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="mt-4">
                    <InputLabel for="terms" class="block text-gray-100 font-bold mb-2 text-shadow">
                        <div class="flex items-center">
                            <Checkbox id="terms" v-model:checked="form.terms" name="terms" class="text-sky-600" required />
                            <div class="ms-2 block text-gray-100 font-bold mb-2 text-shadow">
                                Acepto las <a target="_blank" :href="route('terms.show')" class="underline text-sm text-gray-300 hover:text-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Condiciones</a> y <a target="_blank" :href="route('policy.show')" class="underline text-sm text-gray-300 hover:text-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Privacidad</a>
                            </div>
                        </div>
                        <InputError class="mt-2" :message="form.errors.terms" />
                    </InputLabel>
                </div>

                <div class="flex items-center justify-between mt-4">
                    <Link :href="route('login')" class="underline text-m text-blue-300 hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 font-bold text-shadow">
                        ¿Ya estás registrado?
                    </Link>

                    <PrimaryButton class="ms-4 px-4 py-2 bg-black-100 hover:bg-sky-600 text-white rounded" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        <i class="fas fa-user-plus mr-2"></i> Registrarse
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </div>
</template>


<style scoped>
.glassmorphism-card {
    background: rgba(255, 255, 255, 0.1); /* Fondo blanco semi-transparente */
    border-radius: 16px;
    backdrop-filter: blur(10px); /* Desenfoque de fondo */
    box-shadow: 0 20px 10px rgba(0, 0, 0, 0.2), 
                0 -20px 20px rgba(0, 0, 0, 0.2), 
                25px 0 20px rgba(0, 0, 0, 0.2), 
                -20px 0 20px rgba(0, 0, 0, 0.424); /* Sombra alrededor del formulario */
    border: 1px solid rgba(255, 255, 255, 0.2); /* Bordes más suaves */
}

.text-shadow {
    text-shadow: 2px 2px 4px rgb(0, 0, 0); /* Sombra negra */
}

/* Diseño responsivo */
@media (max-width: 640px) {
    .rounded-xl {
        padding: 9px; /* Espacio menor en móviles */
    }
    .md\:max-w-md {
        max-width: 90%;
    }
}
</style>