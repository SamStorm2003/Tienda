<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, computed } from 'vue';
import { defineProps } from 'vue';
import Swal from 'sweetalert2';
import axios from 'axios';
import { usePage, Head, Link, router } from '@inertiajs/vue3';

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

const searchQuery = ref('');
const sortOrder = ref('precio_asc'); 

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

const filteredAndSortedProducts = computed(() => {
    let productosFiltrados = props.Productos.filter(producto =>
        producto.nombre.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        producto.descripcion.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
    if (sortOrder.value === 'precio_asc') {
        productosFiltrados.sort((a, b) => a.precio - b.precio);
    } else if (sortOrder.value === 'precio_desc') {
        productosFiltrados.sort((a, b) => b.precio - a.precio);
    } else if (sortOrder.value === 'descuento_asc') {
        productosFiltrados.sort((a, b) => a.descuento_porcentaje - b.descuento_porcentaje);
    } else if (sortOrder.value === 'descuento_desc') {
        productosFiltrados.sort((a, b) => b.descuento_porcentaje - a.descuento_porcentaje);
    }
    return productosFiltrados;
});
</script>
<!-- De aca para abajo modificar diseño-->



<template>
    <AppLayout>
        <template #header>
            <h2 class="text-3xl font-bold text-indigo-800 text-center py-6">
                Ofertas y descuentos grandes
            </h2>
        </template>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mt-8 flex flex-col sm:flex-row sm:space-x-4 space-y-4 sm:space-y-0">
                <input
                    type="text"
                    v-model="searchQuery"
                    placeholder="Buscar productos..."
                    class="flex-grow px-4 py-3 border-2 border-indigo-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                />
                <select
                    v-model="sortOrder"
                    class="px-4 py-3 border-2 border-indigo-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white"
                >
                    <option value="precio_asc">Precio: Bajo a Alto</option>
                    <option value="precio_desc">Precio: Alto a Bajo</option>
                    <option value="descuento_asc">Descuento: Menor a Mayor</option>
                    <option value="descuento_desc">Descuento: Mayor a Menor</option>
                </select>
            </div>

            <div class="mt-10 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <div
                    v-for="producto in filteredAndSortedProducts"
                    :key="producto.producto_id"
                    class="bg-white rounded-xl shadow-lg overflow-hidden transform transition duration-300 hover:scale-105"
                >
                    <div class="relative pb-2/3">
                        <img
                            :src="'/storage/' + producto.imagen_url"
                            :alt="producto.nombre"
                            class="absolute h-full w-full object-cover"
                            loading="lazy"
                        />
                        <div class="absolute top-0 right-0 bg-indigo-600 text-white px-3 py-1 rounded-bl-lg font-semibold">
                            {{ producto.descuento_porcentaje }}% OFF
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ producto.nombre }}</h3>
                        <p class="text-gray-600 mb-4 line-clamp-2">{{ producto.descripcion }}</p>
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-2xl font-bold text-indigo-600">Bs.{{ producto.precio }}</span>
                            <span :class="obtenerEstadoStock(producto.producto_id).clase" class="px-2 py-1 rounded-full text-sm font-medium">
                                {{ obtenerEstadoStock(producto.producto_id).mensaje }}
                            </span>
                        </div>
                        <div class="flex flex-wrap gap-2 text-sm text-gray-600 mb-4">
                            <span>{{ getSubcategoriaNombre(producto.subcategoria_id) }}</span>
                            <span>{{ producto.temporada }}</span>
                            <span>{{ producto.genero }}</span>
                        </div>
                        <Link
                            :href="route('Productos.show', producto.producto_id)"
                            class="block w-full text-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors duration-300"
                        >
                            Ver Detalles
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>

    <footer class="bg-indigo-900 text-white py-8 mt-16">
        <div class="container mx-auto text-center">
            <p class="text-lg font-semibold">&copy; 2024 Lucibel Escura -Shop. Todos los derechos reservados.</p>
            <p class="mt-2 text-indigo-300">Desarrollado con ❤️ por Ark Developer for Collaboration</p>
        </div>
    </footer>
</template>