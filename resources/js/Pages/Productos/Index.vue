<script>
export default {
    name: 'ProductosIndex'
}
</script>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { defineProps, ref, computed } from 'vue';
import Swal from 'sweetalert2';
import { Link, router } from '@inertiajs/vue3';
import Footer from '@/Layouts/Footer.vue';

const props = defineProps({
    Productos: {
        type: Array,
        required: true
    },
    Proveedores: {
        type: Array,
        required: true
    },
    Subcategorias: {
        type: Array,
        required: true
    },
    Descuentos: {
        type: Array,
        required: true
    },
    ProductoDescuentos: {
        type: Array,
        required: true
    },
    Productos_detalles: {
        type: Array,
        required: true
    },
});

const searchTerm = ref('');
const sortCriterion = ref('');

const filteredAndSortedProducts = computed(() => {
    return props.Productos
        .filter(producto => {
            const matchesName = producto.nombre.toLowerCase().includes(searchTerm.value.toLowerCase());
            const matchesPrice = producto.precio.toString().includes(searchTerm.value);
            return matchesName || matchesPrice;
        })
        .sort((a, b) => {
            if (sortCriterion.value === 'name') {
                return a.nombre.localeCompare(b.nombre);
            } else if (sortCriterion.value === 'season') {
                return a.temporada.localeCompare(b.temporada);
            }
            return 0;
        });
});

const deleteProductos = (id) => {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Este producto se eliminará de forma permanente y no podrá ser recuperado. ¿Estás seguro de que deseas continuar?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminarlo!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('Productos.destroy', id), {
                onSuccess: () => {
                    Swal.fire('Eliminado!', 'El producto ha sido eliminado correctamente.', 'success');
                },
                onError: () => {
                    Swal.fire('Error!', 'Ocurrió un problema al eliminar el producto.', 'error');
                }
            });
        }
    });
};

const confirmDelete = (producto_id) => {
    deleteProductos(producto_id);
};

const getProveedorNombre = (id) => {
    const proveedor = props.Proveedores.find(p => p.proveedor_id === id);
    return proveedor ? proveedor.nombre : 'Desconocido';
};

const getSubcategoriaNombre = (id) => {
    const subcategoria = props.Subcategorias.find(s => s.categoria_id === id);
    return subcategoria ? subcategoria.nombre : 'Desconocida';
};

const getStockPorProductoId = (producto_id) => {
    const productoDetalle = props.Productos_detalles.find(pd => pd.producto_id === producto_id);
    return productoDetalle ? productoDetalle.stock : 0;
};

const obtenerEstadoStock = (producto_id) => {
    const stock = getStockPorProductoId(producto_id);
    if (stock <= 9 && stock > 0) {
        return { mensaje: 'Últimas unidades en stock', clase: 'text-red-500' };
    } else if (stock > 9) {
        return { mensaje: 'Disponible', clase: 'text-green-500' };
    } else {
        return { mensaje: 'Sin stock', clase: 'text-gray-500' };
    }
};

</script>

<template>
    <AppLayout>
        <template #header>
            <h1 class="text-3xl font-bold text-gray-800">Productos Shatoria Desing</h1>
        </template>
        
        <div class="bg-gray-100 min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <!-- Hero Section -->
                <div class="bg-white rounded-lg shadow-xl overflow-hidden mb-8">
                    <div class="relative h-96">
                        <img src="/Images/imagenlogin.png" alt="Fashion collection" class="w-full h-full object-cover" />
                        <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center">
                            <div class="text-center">
                                <h2 class="text-4xl font-bold text-white mb-4">Las mejores prendas en Shatoria Desing</h2>
                                <Link :href="route('Productos.create')" v-if="$page.props.user.roles.includes('Administrador')" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-full hover:bg-blue-700 transition duration-300">
                                    Anadir nuevo producto
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Search and Filter -->
                <div class="flex flex-col sm:flex-row justify-between mb-6 p-4 bg-white shadow-md rounded-lg">
                    <input type="text" v-model="searchTerm" placeholder="Buscar por nombre o precio"
                        class="flex-grow px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150 ease-in-out mb-4 sm:mb-0" />
                    <select v-model="sortCriterion"
                        class="sm:ml-4 px-4 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        <option value="">Ordenar por...</option>
                        <option value="name">Nombre</option>
                        <option value="season">Temporada</option>
                    </select>
                </div>
                
                <!-- Product Grid with Images Above Product Name -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <div v-for="producto in filteredAndSortedProducts.filter(p => getStockPorProductoId(p.producto_id) > 0)" :key="producto.producto_id" 
                         class="bg-white rounded-lg shadow-md overflow-hidden transition duration-300 hover:shadow-xl flex flex-col">
                        <div class="relative h-0 pb-[100%]">
                            <img :src="'/storage/' + producto.imagen_url" :alt="producto.nombre" class="absolute inset-0 w-full h-full object-cover" />
                            <div class="absolute top-0 right-0 bg-white px-2 py-1 m-2 rounded-full text-xs font-semibold" 
                                 :class="{'text-green-600': producto.descuento_porcentaje > 0, 'text-gray-600': producto.descuento_porcentaje === 0}">
                                {{ producto.descuento_porcentaje > 0 ? `${producto.descuento_porcentaje}% OFF` : 'New Arrival' }}
                            </div>
                        </div>
                        <div class="p-4 flex-grow flex flex-col justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ producto.nombre }}</h3>
                                <p class="text-gray-600 text-sm mb-2">{{ producto.descripcion }}</p>
                            </div>
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-xl font-bold text-gray-900">Bs.{{ producto.precio }}</span>
                                    <span :class="obtenerEstadoStock(producto.producto_id).clase" class="text-sm">
                                        {{ obtenerEstadoStock(producto.producto_id).mensaje }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <Link :href="route('Productos.show', producto.producto_id)" 
                                          class="text-blue-600 hover:text-blue-800 transition duration-300">
                                        Ver Detalles
                                    </Link>
                                    <div v-if="$page.props.user.roles.includes('Administrador')" class="flex space-x-2">
                                        <Link :href="route('Productos.edit', producto.producto_id)" 
                                              class="text-gray-600 hover:text-gray-800 transition duration-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                        </Link>
                                        <button @click="confirmDelete(producto.producto_id)" 
                                                class="text-red-600 hover:text-red-800 transition duration-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Footer />
    </AppLayout>
</template>