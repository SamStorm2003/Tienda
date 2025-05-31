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
        const datosGenerales = ref(null);
        const proveedores = ref([]);
        const error = ref(null);
        const nuevoCupon = ref({
            codigo: '',
            descripcion: '',
            descuento: null,
            id_objeto: null,
        });
        const nuevoProveedor = ref({
            nombre: '',
            telefono: '',
            correo: '',
            direccion: '',
        });
        const editandoCupon = ref(false);
        const cuponEditando = ref(null);
        const editandoProveedor = ref(false);
        const proveedorEditando = ref(null);

        const obtenerDatos = async () => {
            try {
                const response = await axios.get('/datos-generales');
                datosGenerales.value = response.data;
                proveedores.value = response.data.proveedores;
                descuentos.value = response.data.descuentos;
            } catch (err) {
                error.value = 'Error al obtener los datos';
                console.error(err);
            }
        };

        const agregarCupon = async () => {
            try {
                await axios.post('/cupones', nuevoCupon.value);
                Swal.fire('Éxito', 'Cupón agregado correctamente', 'success');
                obtenerDatos();
                resetFormularioCupon();
            } catch (err) {
                Swal.fire('Error', 'No se pudo agregar el cupón', 'error');
                console.error(err);
            }
        };

        const editarCupon = async () => {
            try {
                await axios.put(`/cupones/${cuponEditando.value.cupon_id}`, nuevoCupon.value);
                Swal.fire('Éxito', 'Cupón editado correctamente', 'success');
                obtenerDatos();
                resetFormularioCupon();
            } catch (err) {
                Swal.fire('Error', 'No se pudo editar el cupón', 'error');
                console.error(err);
            }
        };

        const eliminarCupon = async (cupon) => {
            try {
                await axios.delete(`/cupones/${cupon.cupon_id}`);
                Swal.fire('Éxito', 'Cupón eliminado correctamente', 'success');
                obtenerDatos();
            } catch (err) {
                Swal.fire('Error', 'No se pudo eliminar el cupón', 'error');
                console.error(err);
            }
        };

        const seleccionarCupon = (cupon) => {
            nuevoCupon.value = { ...cupon };
            editandoCupon.value = true;
            cuponEditando.value = cupon;
        };

        const resetFormularioCupon = () => {
            nuevoCupon.value = { codigo: '', descripcion: '', descuento: null, id_objeto: null };
            editandoCupon.value = false;
            cuponEditando.value = null;
        };

        // Proveedores
        const agregarProveedor = async () => {
            try {
                await axios.post('/proveedores', nuevoProveedor.value);
                Swal.fire('Éxito', 'Proveedor agregado correctamente', 'success');
                obtenerDatos();
                resetFormularioProveedor();
            } catch (err) {
                Swal.fire('Error', 'No se pudo agregar el proveedor', 'error');
                console.error(err);
            }
        };

        const editarProveedor = async () => {
            try {
                await axios.put(`/proveedores/${proveedorEditando.value.proveedor_id}`, nuevoProveedor.value);
                Swal.fire('Éxito', 'Proveedor editado correctamente', 'success');
                obtenerDatos();
                resetFormularioProveedor();
            } catch (err) {
                Swal.fire('Error', 'No se pudo editar el proveedor', 'error');
                console.error(err);
            }
        };

        const eliminarProveedor = async (proveedor) => {
            try {
                await axios.delete(`/proveedores/${proveedor.proveedor_id}`);
                Swal.fire('Éxito', 'Proveedor eliminado correctamente', 'success');
                obtenerDatos();
            } catch (err) {
                Swal.fire('Error', 'No se pudo eliminar el proveedor', 'error');
                console.error(err);
            }
        };

        const seleccionarProveedor = (proveedor) => {
            nuevoProveedor.value = { ...proveedor };
            editandoProveedor.value = true;
            proveedorEditando.value = proveedor;
        };

        const resetFormularioProveedor = () => {
            nuevoProveedor.value = { nombre: '', telefono: '', correo: '', direccion: '' };
            editandoProveedor.value = false;
            proveedorEditando.value = null;
        };
        //descuento
        const nuevosDescuento = ref({
            nombre: '',
            descripcion: '',
            tipo: 'Porcentaje',
            valor: null,
            fecha_inicio: null,
            fecha_fin: null,
            activo: true,
        });
        const editandoDescuento = ref(false);
        const descuentoEditando = ref(null);
        const descuentos = ref([]);

        const agregarDescuento = async () => {
            try {
                await axios.post('/descuentos', nuevosDescuento.value);
                Swal.fire('Éxito', 'Descuento agregado correctamente', 'success');
                obtenerDatos();
                resetFormularioDescuento();
            } catch (err) {
                Swal.fire('Error', 'No se pudo agregar el descuento', 'error');
                console.error(err);
            }
        };

        const editarDescuento = async () => {
            try {
                await axios.put(`/descuentos/${descuentoEditando.value.descuento_id}`, nuevosDescuento.value);
                Swal.fire('Éxito', 'Descuento editado correctamente', 'success');
                obtenerDatos();
                resetFormularioDescuento();
            } catch (err) {
                Swal.fire('Error', 'No se pudo editar el descuento', 'error');
                console.error(err);
            }
        };

        const eliminarDescuento = async (descuento) => {
            try {
                await axios.delete(`/descuentos/${descuento.descuento_id}`);
                Swal.fire('Éxito', 'Descuento eliminado correctamente', 'success');
                obtenerDatos();
            } catch (err) {
                Swal.fire('Error', 'No se pudo eliminar el descuento', 'error');
                console.error(err);
            }
        };

        const seleccionarDescuento = (descuento) => {
            nuevosDescuento.value = { ...descuento };
            editandoDescuento.value = true;
            descuentoEditando.value = descuento;
        };

        const resetFormularioDescuento = () => {
            nuevosDescuento.value = { nombre: '', descripcion: '', tipo: 'Porcentaje', valor: null, fecha_inicio: null, fecha_fin: null, activo: true };
            editandoDescuento.value = false;
            descuentoEditando.value = null;
        };

        onMounted(() => {
            obtenerDatos();
        });

        return {
            datosGenerales,
            proveedores,
            error,
            nuevoCupon,
            agregarCupon,
            editarCupon,
            eliminarCupon,
            seleccionarCupon,
            editandoCupon,
            resetFormularioCupon,
            nuevoProveedor,
            agregarProveedor,
            editarProveedor,
            eliminarProveedor,
            seleccionarProveedor,
            editandoProveedor,
            resetFormularioProveedor,
            nuevosDescuento,
            agregarDescuento,
            editarDescuento,
            eliminarDescuento,
            seleccionarDescuento,
            editandoDescuento,
            resetFormularioDescuento,
            descuentos
        };
    }
}
</script>

<template>
    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800">Administración (Cupones - Proveedores - Descuentos)</h2>
        </template>

        <div class="container mx-auto mt-6 p-4 bg-white rounded-lg shadow-md">
            <div v-if="error" class="text-red-500 mb-4">{{ error }}</div>
            <div v-else>
                <h3 class="font-semibold text-xl mb-4">Cupones:</h3>
                <ul class="space-y-4">
                    <li v-for="cupon in datosGenerales?.cupones" :key="cupon.cupon_id"
                        class="p-4 border rounded-lg shadow hover:shadow-lg transition">
                        <p class="font-medium">Código: <span class="text-gray-600">{{ cupon.codigo }}</span></p>
                        <p class="font-medium">Descripción: <span class="text-gray-600">{{ cupon.descripcion }}</span>
                        </p>
                        <p class="font-medium">Descuento: <span class="text-green-500">{{ cupon.descuento }}%</span></p>
                        <p class="font-medium">Producto: <span class="text-gray-600">{{ cupon.producto ?
                            cupon.producto.nombre :
                                'No asociado' }}</span></p>
                        <div class="mt-2 space-x-2">
                            <button @click="seleccionarCupon(cupon)"
                                class="bg-blue-500 text-white py-1 px-3 rounded hover:bg-blue-600 transition">
                                Editar
                            </button>
                            <button @click="eliminarCupon(cupon)"
                                class="bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600 transition">
                                Eliminar
                            </button>
                        </div>
                    </li>
                </ul>

                <h3 class="font-semibold text-xl mt-6">{{ editandoCupon ? 'Editar Cupón' : 'Agregar Cupón' }}</h3>
                <form @submit.prevent="editandoCupon ? editarCupon() : agregarCupon()" class="mt-4 space-y-4">
                    <input v-model="nuevoCupon.codigo" placeholder="Código" required
                        class="border border-gray-300 p-2 rounded w-full" />
                    <input v-model="nuevoCupon.descripcion" placeholder="Descripción"
                        class="border border-gray-300 p-2 rounded w-full" />
                    <input v-model="nuevoCupon.descuento" type="number" placeholder="Descuento" required
                        class="border border-gray-300 p-2 rounded w-full" />
                    <select v-model="nuevoCupon.id_objeto" required class="border border-gray-300 p-2 rounded w-full">
                        <option v-for="producto in datosGenerales?.productos" :key="producto.producto_id"
                            :value="producto.producto_id">
                            {{ producto.nombre }}
                        </option>
                    </select>
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition">
                        {{ editandoCupon ? 'Actualizar' : 'Agregar' }}
                    </button>
                    <button type="button" @click="resetFormularioCupon"
                        class="ml-2 bg-gray-300 text-black py-2 px-4 rounded hover:bg-gray-400 transition">Cancelar</button>
                </form>
            </div>
        </div>
        <!-- Sección de Proveedores -->
        <div class="mt-6 p-4 bg-white rounded-lg shadow-md">
            <h3 class="font-semibold text-xl mb-4">Proveedores:</h3>
            <table class="min-w-full border-collapse bg-white rounded-lg shadow overflow-hidden">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-4 py-2 text-left text-gray-700">ID</th>
                        <th class="border px-4 py-2 text-left text-gray-700">Nombre</th>
                        <th class="border px-4 py-2 text-left text-gray-700">Teléfono</th>
                        <th class="border px-4 py-2 text-left text-gray-700">Correo</th>
                        <th class="border px-4 py-2 text-left text-gray-700">Dirección</th>
                        <th class="border px-4 py-2 text-left text-gray-700">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="proveedor in proveedores" :key="proveedor.proveedor_id"
                        class="hover:bg-gray-50 transition">
                        <td class="border px-4 py-2">{{ proveedor.proveedor_id }}</td>
                        <td class="border px-4 py-2">{{ proveedor.nombre }}</td>
                        <td class="border px-4 py-2">{{ proveedor.telefono }}</td>
                        <td class="border px-4 py-2">{{ proveedor.correo }}</td>
                        <td class="border px-4 py-2">{{ proveedor.direccion }}</td>
                        <td class="border px-4 py-2 flex space-x-2">
                            <button @click="seleccionarProveedor(proveedor)"
                                class="bg-blue-500 text-white py-1 px-3 rounded hover:bg-blue-600 transition">
                                Editar
                            </button>
                            <button @click="eliminarProveedor(proveedor)"
                                class="bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600 transition">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <h3 class="font-semibold text-xl mt-6">{{ editandoProveedor ? 'Editar Proveedor' : 'Agregar Proveedor' }}
            </h3>
            <form @submit.prevent="editandoProveedor ? editarProveedor() : agregarProveedor()" class="mt-4 space-y-4">
                <input v-model="nuevoProveedor.nombre" placeholder="Nombre" required
                    class="border border-gray-300 p-2 rounded w-full" />
                <input v-model="nuevoProveedor.telefono" placeholder="Teléfono" required
                    class="border border-gray-300 p-2 rounded w-full" />
                <input v-model="nuevoProveedor.correo" placeholder="Correo" type="email" required
                    class="border border-gray-300 p-2 rounded w-full" />
                <input v-model="nuevoProveedor.direccion" placeholder="Dirección" required
                    class="border border-gray-300 p-2 rounded w-full" />
                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition">
                    {{ editandoProveedor ? 'Actualizar' : 'Agregar' }}
                </button>
                <button type="button" @click="resetFormularioProveedor"
                    class="ml-2 bg-gray-300 text-black py-2 px-4 rounded hover:bg-gray-400 transition">Cancelar</button>
            </form>
        </div>
        <!--descuentos-->
        <div class="mt-6 p-4 bg-white rounded-lg shadow-md">
            <h3 class="font-semibold text-xl mb-4">Descuentos:</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse bg-white rounded-lg shadow">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-4 py-2 text-left text-gray-700">ID</th>
                            <th class="border px-4 py-2 text-left text-gray-700">Nombre</th>
                            <th class="border px-4 py-2 text-left text-gray-700">Descripción</th>
                            <th class="border px-4 py-2 text-left text-gray-700">Tipo</th>
                            <th class="border px-4 py-2 text-left text-gray-700">Valor</th>
                            <th class="border px-4 py-2 text-left text-gray-700">Fecha Inicio</th>
                            <th class="border px-4 py-2 text-left text-gray-700">Fecha Fin</th>
                            <th class="border px-4 py-2 text-left text-gray-700">Activo</th>
                            <th class="border px-4 py-2 text-left text-gray-700">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="descuento in descuentos" :key="descuento.descuento_id"
                            class="hover:bg-gray-50 transition">
                            <td class="border px-4 py-2">{{ descuento.descuento_id }}</td>
                            <td class="border px-4 py-2">{{ descuento.nombre }}</td>
                            <td class="border px-4 py-2">{{ descuento.descripcion }}</td>
                            <td class="border px-4 py-2">{{ descuento.tipo }}</td>
                            <td class="border px-4 py-2">{{ descuento.valor }}</td>
                            <td class="border px-4 py-2">{{ descuento.fecha_inicio }}</td>
                            <td class="border px-4 py-2">{{ descuento.fecha_fin }}</td>
                            <td class="border px-4 py-2">{{ descuento.activo ? 'Sí' : 'No' }}</td>
                            <td class="border px-4 py-2 flex space-x-2">
                                <button @click="seleccionarDescuento(descuento)"
                                    class="bg-blue-500 text-white py-1 px-3 rounded hover:bg-blue-600 transition">
                                    Editar
                                </button>
                                <button @click="eliminarDescuento(descuento)"
                                    class="bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600 transition">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h3 class="font-semibold text-xl mt-6">{{ editandoDescuento ? 'Editar Descuento' : 'Agregar Descuento' }}
            </h3>
            <form @submit.prevent="editandoDescuento ? editarDescuento() : agregarDescuento()" class="mt-4 space-y-4">
                <input v-model="nuevosDescuento.nombre" placeholder="Nombre" required
                    class="border border-gray-300 p-2 rounded w-full" />
                <textarea v-model="nuevosDescuento.descripcion" placeholder="Descripción"
                    class="border border-gray-300 p-2 rounded w-full" rows="3"></textarea>
                <select v-model="nuevosDescuento.tipo" required class="border border-gray-300 p-2 rounded w-full">
                    <option value="Porcentaje">Porcentaje</option>
                    <option value="Valor Fijo">Valor Fijo</option>
                </select>
                <input v-model="nuevosDescuento.valor" type="number" placeholder="Valor" required
                    class="border border-gray-300 p-2 rounded w-full" />
                <p class="font-medium">Fecha fin:</p>
                <input v-model="nuevosDescuento.fecha_inicio" type="date"
                    class="border border-gray-300 p-2 rounded w-full" />
                <p class="font-medium">Fecha fin:</p>
                <input v-model="nuevosDescuento.fecha_fin" type="date"
                    class="border border-gray-300 p-2 rounded w-full" />
                <div class="flex items-center">
                    <input type="checkbox" v-model="nuevosDescuento.activo" class="mr-2" />
                    <label>Activo</label>
                </div>
                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition">
                    {{ editandoDescuento ? 'Actualizar' : 'Agregar' }}
                </button>
                <button type="button" @click="resetFormularioDescuento"
                    class="ml-2 bg-gray-300 text-black py-2 px-4 rounded hover:bg-gray-400 transition">Cancelar</button>
            </form>
        </div>
    </AppLayout>
</template>