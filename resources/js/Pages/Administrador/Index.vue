<script>
export default {
    name: 'AdministradorIndex'
}
</script>

<script setup>
import { ref, onMounted, computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';
import Swal from 'sweetalert2';
import { Link, router } from '@inertiajs/vue3';

const datosGenerales = ref(null);
const error = ref(null);
const obtenerDatos = async () => {
    try {
        const response = await axios.get('/datos-generales');
        datosGenerales.value = response.data;
    } catch (err) {
        error.value = 'Error al obtener los datos';
        console.error(err);
    }
};
onMounted(obtenerDatos);
</script>
<!-- De aca para abajo modificar diseño-->
<template>
    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800">Administrador</h2>
        </template>
        <div v-if="error">
            <p class="text-red-500">{{ error }}</p>
        </div>
        <div class="flex space-x-4 mt-4">
            <Link :href="route('admin.edit2')"
                class="bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-lg hover:bg-blue-700 transition duration-300 transform hover:scale-105">
            Administración Básica
            </Link>
            <Link :href="route('admin.edit3')"
                class="bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-lg hover:bg-blue-700 transition duration-300 transform hover:scale-105">
            Administración Media
            </Link>
        </div>
    </AppLayout>
</template>