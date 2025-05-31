<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

export default {
    name: 'Edit2',
    components: {
        AppLayout
    },
    setup() {
        const datosGenerales = ref({ tallas: [], colores: [] });
        const error = ref(null);
        const nuevoColor = ref({ nombre: '' });
        const nuevoTalla = ref({ nombre: '' });
        const nuevoEnvio = ref({ ciudad: '', pais: '', precio: '' });
        const nuevoSucursal = ref({ nombre: '', direccion: '', ciudad: '', telefono: '' });

        const obtenerDatos = async () => {
            try {
                const response = await axios.get('/datos-generales');
                datosGenerales.value = response.data;
            } catch (err) {
                error.value = 'Error al obtener los datos';
                console.error(err);
            }
        };
        // Agregar un nuevo color
        const addColor = async () => {
            try {
                const response = await axios.post('/admin/colores', nuevoColor.value);
                datosGenerales.value.colores.push(response.data);
                nuevoColor.value = { nombre: '' };
                Swal.fire('Éxito', 'Color agregado correctamente', 'success');
            } catch (err) {
                Swal.fire('Error', 'Error al agregar color', 'error');
                console.error(err);
            }
        };
        // Editar un color existente
        const editColor = async (color) => {
            const { value: nuevoNombre } = await Swal.fire({
                title: 'Nuevo nombre:',
                input: 'text',
                inputPlaceholder: color.nombre
            });
            if (nuevoNombre) {
                try {
                    const response = await axios.put(`/admin/colores/${color.color_id}`, { nombre: nuevoNombre });
                    const index = datosGenerales.value.colores.findIndex(c => c.color_id === color.color_id);
                    datosGenerales.value.colores[index] = response.data;
                    Swal.fire('Éxito', 'Color editado correctamente', 'success');
                } catch (err) {
                    Swal.fire('Error', 'Error al editar color', 'error');
                    console.error(err);
                }
            }
        };
        // Eliminar un color
        const deleteColor = async (id) => {
            const { isConfirmed } = await Swal.fire({
                title: '¿Seguro que quieres eliminar este color?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            });

            if (isConfirmed) {
                try {
                    await axios.delete(`/admin/colores/${id}`);
                    datosGenerales.value.colores = datosGenerales.value.colores.filter(c => c.color_id !== id);
                    Swal.fire('Eliminado', 'Color eliminado correctamente', 'success');
                } catch (err) {
                    Swal.fire('Error', 'Error al eliminar color', 'error');
                    console.error(err);
                }
            }
        };
        // Agregar una nueva talla
        const addTalla = async () => {
            try {
                const response = await axios.post('/admin/tallas', nuevoTalla.value);
                datosGenerales.value.tallas.push(response.data);
                nuevoTalla.value = { nombre: '' };
                Swal.fire('Éxito', 'Talla agregada correctamente', 'success');
            } catch (err) {
                Swal.fire('Error', 'Error al agregar talla', 'error');
                console.error(err);
            }
        };
        // Editar una talla existente
        const editTalla = async (talla) => {
            const { value: nuevoNombre } = await Swal.fire({
                title: 'Nuevo nombre:',
                input: 'text',
                inputPlaceholder: talla.nombre
            });
            if (nuevoNombre) {
                try {
                    const response = await axios.put(`/admin/tallas/${talla.talla_id}`, { nombre: nuevoNombre });
                    const index = datosGenerales.value.tallas.findIndex(t => t.talla_id === talla.talla_id);
                    datosGenerales.value.tallas[index] = response.data;
                    Swal.fire('Éxito', 'Talla editada correctamente', 'success');
                } catch (err) {
                    Swal.fire('Error', 'Error al editar talla', 'error');
                    console.error(err);
                }
            }
        };
        // Eliminar una talla
        const deleteTalla = async (id) => {
            const { isConfirmed } = await Swal.fire({
                title: '¿Seguro que quieres eliminar esta talla?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            });
            if (isConfirmed) {
                try {
                    await axios.delete(`/admin/tallas/${id}`);
                    datosGenerales.value.tallas = datosGenerales.value.tallas.filter(t => t.talla_id !== id);
                    Swal.fire('Eliminado', 'Talla eliminada correctamente', 'success');
                } catch (err) {
                    Swal.fire('Error', 'Error al eliminar talla', 'error');
                    console.error(err);
                }
            }
        };
        // Agregar un nuevo envío
        const addEnvio = async () => {
            try {
                const response = await axios.post('/admin/envios', nuevoEnvio.value);
                datosGenerales.value.envios.push(response.data);
                nuevoEnvio.value = { ciudad: '', pais: '', precio: '' };
                Swal.fire('Éxito', 'Envío agregado correctamente', 'success');
            } catch (err) {
                Swal.fire('Error', 'Error al agregar envío', 'error');
                console.error(err);
            }
        };
        // Editar un envío existente
        const editEnvio = async (envio) => {
            const { value: formValues } = await Swal.fire({
                title: 'Editar envío',
                html: `
                    <input id="ciudad" class="swal2-input" placeholder="Ciudad" value="${envio.ciudad}">
                    <input id="pais" class="swal2-input" placeholder="País" value="${envio.pais}">
                    <input id="precio" class="swal2-input" placeholder="Precio" value="${envio.precio}">
                `,
                focusConfirm: false,
                preConfirm: () => {
                    return {
                        ciudad: document.getElementById('ciudad').value,
                        pais: document.getElementById('pais').value,
                        precio: document.getElementById('precio').value
                    };
                }
            });
            if (formValues) {
                try {
                    const response = await axios.put(`/admin/envios/${envio.id_envio}`, formValues);
                    const index = datosGenerales.value.envios.findIndex(e => e.id_envio === envio.id_envio);
                    datosGenerales.value.envios[index] = response.data;
                    Swal.fire('Éxito', 'Envío editado correctamente', 'success');
                } catch (err) {
                    Swal.fire('Error', 'Error al editar envío', 'error');
                    console.error(err);
                }
            }
        };
        // Eliminar un envío
        const deleteEnvio = async (id) => {
            const { isConfirmed } = await Swal.fire({
                title: '¿Seguro que quieres eliminar este envío?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            });
            if (isConfirmed) {
                try {
                    await axios.delete(`/admin/envios/${id}`);
                    datosGenerales.value.envios = datosGenerales.value.envios.filter(e => e.id_envio !== id);
                    Swal.fire('Eliminado', 'Envío eliminado correctamente', 'success');
                } catch (err) {
                    Swal.fire('Error', 'Error al eliminar envío', 'error');
                    console.error(err);
                }
            }
        };
        // Agregar una nueva sucursal
        const addSucursal = async () => {
            try {
                const response = await axios.post('/admin/sucursales', nuevoSucursal.value);
                datosGenerales.value.sucursales.push(response.data);
                nuevoSucursal.value = { nombre: '', direccion: '' };
                Swal.fire('Éxito', 'Sucursal agregada correctamente', 'success');
            } catch (err) {
                Swal.fire('Error', 'Error al agregar sucursal', 'error');
                console.error(err);
            }
        };
        // Editar una sucursal existente
        const editSucursal = async (sucursal) => {
            const { value: formValues } = await Swal.fire({
                title: 'Editar sucursal',
                html: `
                    <input id="nombre" class="swal2-input" placeholder="Nombre" value="${sucursal.nombre}">
                    <input id="direccion" class="swal2-input" placeholder="Dirección" value="${sucursal.direccion}">
                `,
                focusConfirm: false,
                preConfirm: () => {
                    return {
                        nombre: document.getElementById('nombre').value,
                        direccion: document.getElementById('direccion').value
                    };
                }
            });
            if (formValues) {
                try {
                    const response = await axios.put(`/admin/sucursales/${sucursal.sucursal_id}`, formValues);
                    const index = datosGenerales.value.sucursales.findIndex(s => s.sucursal_id === sucursal.sucursal_id);
                    datosGenerales.value.sucursales[index] = response.data;
                    Swal.fire('Éxito', 'Sucursal editada correctamente', 'success');
                } catch (err) {
                    Swal.fire('Error', 'Error al editar sucursal', 'error');
                    console.error(err);
                }
            }
        };
        // Eliminar una sucursal
        const deleteSucursal = async (id) => {
            const { isConfirmed } = await Swal.fire({
                title: '¿Seguro que quieres eliminar esta sucursal?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            });

            if (isConfirmed) {
                try {
                    await axios.delete(`/admin/sucursales/${id}`);
                    datosGenerales.value.sucursales = datosGenerales.value.sucursales.filter(s => s.sucursal_id !== id);
                    Swal.fire('Eliminado', 'Sucursal eliminada correctamente', 'success');
                } catch (err) {
                    Swal.fire('Error', 'Error al eliminar sucursal', 'error');
                    console.error(err);
                }
            }
        };

        onMounted(obtenerDatos);

        return {
            datosGenerales,
            error,
            nuevoColor,
            addColor,
            editColor,
            deleteColor,
            nuevoTalla,
            addTalla,
            editTalla,
            deleteTalla,
            nuevoEnvio,
            addEnvio,
            editEnvio,
            deleteEnvio,
            nuevoSucursal,
            addSucursal,
            editSucursal,
            deleteSucursal
        };
    }
}
</script>
<!-- De aca para abajo modificar diseño-->
<template>
    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800">Administración (Colores - Tallas - Envios - Sucursales )
            </h2>
        </template>
        <div v-if="error">
            <p class="text-red-500">{{ error }}</p>
        </div>
        <div class="p-4">
            <div v-if="!error && datosGenerales">
                <!-- Colores -->
                <h3 class="mt-4 text-xl font-bold text-gray-800">Colores</h3>
                <div class="overflow-hidden rounded-lg shadow-lg">
                    <table class="min-w-full border-collapse bg-white">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="p-4 text-left text-sm font-semibold text-gray-600">ID</th>
                                <th class="p-4 text-left text-sm font-semibold text-gray-600">Nombre</th>
                                <th class="p-4 text-left text-sm font-semibold text-gray-600">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="color in datosGenerales.colores" :key="color.color_id" class="hover:bg-gray-50">
                                <td class="p-4 text-sm text-gray-700">{{ color.color_id }}</td>
                                <td class="p-4 text-sm text-gray-700">{{ color.nombre }}</td>
                                <td class="p-4 text-sm text-gray-700 flex space-x-2">
                                    <button @click="editColor(color)"
                                        class="inline-flex items-center px-3 py-1.5 bg-blue-500 text-white text-xs font-medium rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5h2M11 19h2M11 13h2"></path>
                                        </svg>
                                        Editar
                                    </button>
                                    <button @click="deleteColor(color.color_id)"
                                        class="inline-flex items-center px-3 py-1.5 bg-red-500 text-white text-xs font-medium rounded hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-6 flex items-center space-x-3">
                    <input v-model="nuevoColor.nombre" placeholder="Nuevo Color"
                        class="flex-1 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" />
                    <button @click="addColor"
                        class="px-5 py-2 bg-green-500 text-white text-sm font-medium rounded-lg hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-400">
                        Agregar Color
                    </button>
                </div>

                <!-- Tallas -->
                <h3 class="mt-4 text-2xl font-semibold text-gray-800">Tallas</h3>
                <div class="overflow-x-auto mt-4">
                    <table class="min-w-full border border-gray-200 bg-white shadow-md rounded-lg">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border border-gray-200 p-3 text-left text-sm font-medium text-gray-600">ID
                                </th>
                                <th class="border border-gray-200 p-3 text-left text-sm font-medium text-gray-600">
                                    Nombre</th>
                                <th class="border border-gray-200 p-3 text-left text-sm font-medium text-gray-600">
                                    Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="talla in datosGenerales.tallas" :key="talla.talla_id"
                                class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="border border-gray-200 p-3 text-sm text-gray-700">{{ talla.talla_id }}</td>
                                <td class="border border-gray-200 p-3 text-sm text-gray-700">{{ talla.nombre }}</td>
                                <td class="border border-gray-200 p-3 text-sm">
                                    <button @click="editTalla(talla)"
                                        class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-1 px-3 rounded-md transition duration-200">Editar</button>
                                    <button @click="deleteTalla(talla.talla_id)"
                                        class="bg-red-500 hover:bg-red-600 text-white font-semibold py-1 px-3 rounded-md transition duration-200 ml-2">Eliminar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 flex items-center space-x-2">
                    <input v-model="nuevoTalla.nombre" placeholder="Nueva Talla"
                        class="border border-gray-300 rounded-md p-2 w-full" />
                    <button @click="addTalla"
                        class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-md transition duration-200">Agregar
                        Talla</button>
                </div>

                <!-- Envíos -->
                <h3 class="mt-4 text-2xl font-semibold text-gray-800">Envios</h3>
                <div class="overflow-x-auto mt-4">
                    <table class="min-w-full border border-gray-200 bg-white shadow-md rounded-lg">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border border-gray-200 p-3 text-left text-sm font-medium text-gray-600">ID
                                </th>
                                <th class="border border-gray-200 p-3 text-left text-sm font-medium text-gray-600">
                                    Ciudad</th>
                                <th class="border border-gray-200 p-3 text-left text-sm font-medium text-gray-600">País
                                </th>
                                <th class="border border-gray-200 p-3 text-left text-sm font-medium text-gray-600">
                                    Precio</th>
                                <th class="border border-gray-200 p-3 text-left text-sm font-medium text-gray-600">
                                    Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="envio in datosGenerales.envios" :key="envio.id_envio"
                                class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="border border-gray-200 p-3 text-sm text-gray-700">{{ envio.id_envio }}</td>
                                <td class="border border-gray-200 p-3 text-sm text-gray-700">{{ envio.ciudad }}</td>
                                <td class="border border-gray-200 p-3 text-sm text-gray-700">{{ envio.pais }}</td>
                                <td class="border border-gray-200 p-3 text-sm text-gray-700">{{ envio.precio }}</td>
                                <td class="border border-gray-200 p-3 text-sm">
                                    <button @click="editEnvio(envio)"
                                        class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-1 px-3 rounded-md transition duration-200">Editar</button>
                                    <button @click="deleteEnvio(envio.id_envio)"
                                        class="bg-red-500 hover:bg-red-600 text-white font-semibold py-1 px-3 rounded-md transition duration-200 ml-2">Eliminar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 flex flex-col space-y-2">
                    <div class="flex space-x-2">
                        <input v-model="nuevoEnvio.ciudad" placeholder="Ciudad"
                            class="border border-gray-300 rounded-md p-2 w-full" />
                        <input v-model="nuevoEnvio.pais" placeholder="País"
                            class="border border-gray-300 rounded-md p-2 w-full" />
                        <input v-model="nuevoEnvio.precio" placeholder="Precio"
                            class="border border-gray-300 rounded-md p-2 w-full" />
                    </div>
                    <button @click="addEnvio"
                        class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-md transition duration-200">Agregar
                        Envío</button>
                </div>

                <!-- Sucursales -->
                <h3 class="mt-4 text-2xl font-semibold text-gray-800">Sucursales</h3>
                <div class="overflow-x-auto mt-4">
                    <table class="min-w-full border border-gray-200 bg-white shadow-md rounded-lg">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border border-gray-200 p-3 text-left text-sm font-medium text-gray-600">ID
                                </th>
                                <th class="border border-gray-200 p-3 text-left text-sm font-medium text-gray-600">
                                    Nombre</th>
                                <th class="border border-gray-200 p-3 text-left text-sm font-medium text-gray-600">
                                    Dirección
                                </th>
                                <th class="border border-gray-200 p-3 text-left text-sm font-medium text-gray-600">
                                    Ciudad</th>
                                <th class="border border-gray-200 p-3 text-left text-sm font-medium text-gray-600">
                                    Teléfono</th>
                                <th class="border border-gray-200 p-3 text-left text-sm font-medium text-gray-600">
                                    Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="sucursal in datosGenerales.sucursales" :key="sucursal.sucursal_id"
                                class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="border border-gray-200 p-3 text-sm text-gray-700">{{ sucursal.sucursal_id }}
                                </td>
                                <td class="border border-gray-200 p-3 text-sm text-gray-700">{{ sucursal.nombre }}</td>
                                <td class="border border-gray-200 p-3 text-sm text-gray-700">{{ sucursal.direccion }}
                                </td>
                                <td class="border border-gray-200 p-3 text-sm text-gray-700">{{ sucursal.ciudad }}</td>
                                <td class="border border-gray-200 p-3 text-sm text-gray-700">{{ sucursal.telefono }}
                                </td>
                                <td class="border border-gray-200 p-3 text-sm">
                                    <button @click="editSucursal(sucursal)"
                                        class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-1 px-3 rounded-md transition duration-200">Editar</button>
                                    <button @click="deleteSucursal(sucursal.sucursal_id)"
                                        class="bg-red-500 hover:bg-red-600 text-white font-semibold py-1 px-3 rounded-md transition duration-200 ml-2">Eliminar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 flex flex-col space-y-2">
                    <div class="flex space-x-2">
                        <input v-model="nuevoSucursal.nombre" placeholder="Nombre"
                            class="border border-gray-300 rounded-md p-2 w-full" />
                        <input v-model="nuevoSucursal.direccion" placeholder="Dirección"
                            class="border border-gray-300 rounded-md p-2 w-full" />
                        <input v-model="nuevoSucursal.ciudad" placeholder="Ciudad"
                            class="border border-gray-300 rounded-md p-2 w-full" />
                        <input v-model="nuevoSucursal.telefono" placeholder="Teléfono"
                            class="border border-gray-300 rounded-md p-2 w-full" />
                    </div>
                    <button @click="addSucursal"
                        class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-md transition duration-200">Agregar
                        Sucursal</button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>