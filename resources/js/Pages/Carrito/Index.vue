<script>
export default {
    name: 'CarritoIndex'
}
</script>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Swal from 'sweetalert2';
import { Inertia } from '@inertiajs/inertia';
import { usePage, Head, Link, router } from '@inertiajs/vue3';
import { ref, defineProps, computed, onBeforeUnmount, watch } from 'vue';
const props = defineProps({
    Carrito: {
        type: Array,
        required: true
    },
    DetalleCarrito: {
        type: Array,
        required: true
    },
    Ventas: {
        type: Array,
        required: true
    },
    DetalleVentas: {
        type: Array,
        required: true
    },
    Productos: {
        type: Array,
        required: true
    },
    Productos_detalles: {
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
    Colores: {
        type: Array,
        required: true
    },
    Tallas: {
        type: Array,
        required: true
    },
    Envios: {
        type: Array,
        required: true
    },
});

const page = usePage();
const userId = page.props.auth.user.id;

const filteredCarrito = computed(() => {
    return props.Carrito.filter(carrito => carrito.usuario_id === userId);
});


const getProductoInfo = (producto_detalle_id) => {
    const detalleProducto = props.Productos_detalles.find(d => d.producto_detalle_id === producto_detalle_id);
    if (!detalleProducto) return null;
    const producto = props.Productos.find(p => p.producto_id === detalleProducto.producto_id);
    if (!producto) return null;
    const proveedor = props.Proveedores.find(prov => prov.proveedor_id === producto.proveedor_id);
    const subcategoria = props.Subcategorias.find(sub => sub.subcategoria_id === producto.subcategoria_id);
    const color = props.Colores.find(col => col.color_id === detalleProducto.color_id);
    const talla = props.Tallas.find(t => t.talla_id === detalleProducto.talla_id);
    const productoDescuento = props.ProductoDescuentos.find(pd => pd.producto_id === producto.producto_id);
    const descuento = productoDescuento ? props.Descuentos.find(desc => desc.descuento_id === productoDescuento.descuento_id) : null;
    const descuentoActivo = descuento ? (new Date(descuento.fecha_fin) >= new Date() ? 'Activo' : 'Vencido') : 'No Aplica';
    return {
        producto,
        proveedor,
        subcategoria,
        color,
        talla,
        descuento,
        descuentoActivo,
        detalleProducto
    };
};

const carritoConDetalles = computed(() => {
    return props.DetalleCarrito.map(detalle => {
        const infoProducto = getProductoInfo(detalle.producto_detalle_id);
        return {
            ...detalle,
            ...infoProducto
        };
    });
});

const comprarProducto = (detalle) => {
    const productoData = {
        producto_id: detalle.producto.producto_id,
        nombre: detalle.producto.nombre,
        descripcion: detalle.producto.descripcion,
        precio: detalle.producto.precio,
        proveedor: detalle.proveedor.nombre,
        subcategoria: detalle.subcategoria.nombre,
        color: detalle.color.nombre,
        talla: detalle.talla.nombre,
        subtotal: detalle.subtotal,
        envio: detalle.costo_envio,
        ciudad: detalle.ciudad_envio,
        descuento: 'Aplicado',
        total: detalle.precio_con_descuento,
        cantidad: detalle.cantidad,
        detalle_carrito_id: detalle.detalle_carrito_id,
        carrito_id: detalle.carrito_id,
    };

    Inertia.post(route('carritos.comprar', { producto: detalle.producto.producto_id }), productoData);
};

const quitarDelCarrito = (detalleCarritoId) => {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡Este producto será eliminado del carrito!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminarlo!'
    }).then((result) => {
        if (result.isConfirmed) {
            Inertia.delete(route('carritos.destroy', detalleCarritoId), {
                preserveScroll: true,
                onSuccess: () => {
                    Swal.fire('¡Eliminado!', 'Producto eliminado del carrito', 'success');
                }
            });
        }
    });
};
const ventasConDetalles = computed(() => {
    const ventasUsuario = props.Ventas.filter(venta => venta.usuario_id === userId);
    return ventasUsuario.map(venta => ({
        ...venta,
        detalles: props.DetalleVentas.filter(detalle => detalle.venta_id === venta.venta_id)
    }));

}); const getProductDetails = (producto_id) => {
    return props.Productos.find(producto => producto.producto_id === producto_id);
};

const searchTerm = ref('');

const filteredCarritoConDetalles = computed(() => {
    return carritoConDetalles.value.filter(detalle => 
        detalle.producto.nombre.toLowerCase().includes(searchTerm.value.toLowerCase())
    );
});

const filteredVentasConDetalles = computed(() => {
    return ventasConDetalles.value.filter(venta => 
        venta.detalles.some(detalle => 
            getProductDetails(detalle.producto_id)?.nombre.toLowerCase().includes(searchTerm.value.toLowerCase())
        )
    );
});

</script>



<template>
    <AppLayout>
        <div class="bg-gray-100 min-h-screen">
            <!-- Header -->
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <h1 class="text-3xl font-bold text-gray-900">Carrito</h1>
                </div>
            </header>

            <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <!-- Search Bar -->
                <div class="mb-8">
                    <input 
                        type="text" 
                        v-model="searchTerm" 
                        placeholder="Buscar productos..." 
                        class="w-full p-4 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    />
                </div>

                <!-- Cart Products -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
                    <div class="bg-blue-600 text-white p-4">
                        <h2 class="text-xl font-semibold">Productos agregados al carrito</h2>
                    </div>
                    <div v-if="filteredCarrito.length === 0" class="p-4 text-center">
                        <p class="text-gray-500">No hay carritos para este usuario.</p>
                    </div>
                    <div v-else>
                        <div v-for="(detalle, index) in filteredCarritoConDetalles" :key="index"
                            class="border-b border-gray-200 p-4 sm:p-6">
                            <div class="flex flex-col sm:flex-row items-center">
                                <div class="w-full sm:w-1/3 md:w-1/4 mb-4 sm:mb-0">
                                    <div class="aspect-w-1 aspect-h-1 w-full">
                                        <img v-if="detalle.producto.imagen_url" 
                                             :src="`/storage/${detalle.producto.imagen_url}`"
                                             alt="Imagen del producto"
                                             class="object-cover object-center w-full h-full rounded-lg shadow-sm" />
                                    </div>
                                </div>
                                <div class="sm:ml-6 flex-grow">
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ detalle.producto.nombre }}</h3>
                                    <p class="text-gray-600 mb-4">{{ detalle.producto.descripcion }}</p>
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        <span class="px-2 py-1 bg-gray-200 rounded-full text-sm">Talla: {{ detalle.talla.nombre }}</span>
                                        <span class="px-2 py-1 bg-gray-200 rounded-full text-sm">Color: {{ detalle.color.nombre }}</span>
                                        <span class="px-2 py-1 bg-gray-200 rounded-full text-sm">Cantidad: {{ detalle.cantidad }}</span>
                                    </div>
                                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                                        <div class="mb-4 sm:mb-0">
                                            <p class="text-2xl font-bold text-blue-600">Bs.{{ detalle.precio_con_descuento }}</p>
                                            <p class="text-sm text-gray-500">Precio original: Bs.{{ detalle.producto.precio }}</p>
                                        </div>
                                        <div class="space-y-2 sm:space-y-0 sm:space-x-2">
                                            <button @click="comprarProducto(detalle)"
                                                class="w-full sm:w-auto px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300">
                                                Comprar ahora
                                            </button>
                                            <button @click="quitarDelCarrito(detalle.detalle_carrito_id, filteredCarrito[index].carrito_id)"
                                                class="w-full sm:w-auto px-6 py-2 border border-gray-300 rounded-lg hover:bg-red-100 transition duration-300">
                                                Quitar del Carrito
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recently Purchased Products -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="bg-blue-600 text-white p-4">
                        <h2 class="text-xl font-semibold">Productos Adquiridos Recientemente</h2>
                    </div>
                    <div v-if="ventasConDetalles.length === 0" class="p-4 text-center">
                        <p class="text-gray-500">No hay productos comprados.</p>
                    </div>
                    <div v-else>
                        <div v-for="venta in filteredVentasConDetalles" :key="venta.venta_id"
                            class="border-b border-gray-200 p-6">
                            <h3 class="text-lg font-semibold mb-2">Producto Comprado: {{ venta.venta_id }}</h3>
                            <p class="text-sm text-gray-600">Fecha: {{ venta.fecha }} | Total: Bs. {{ venta.total }} | Estado: {{ venta.estado }}</p>
                            <div class="mt-4 space-y-4">
                                <div v-for="detalle in venta.detalles" :key="detalle.detalle_venta_id"
                                    class="bg-gray-50 rounded-lg p-4 flex flex-col sm:flex-row">
                                    <div class="w-full sm:w-1/3 md:w-1/4 mb-4 sm:mb-0">
                                        <div class="aspect-w-1 aspect-h-1 w-full">
                                            <img v-if="getProductDetails(detalle.producto_id)?.imagen_url"
                                                :src="`/storage/${getProductDetails(detalle.producto_id).imagen_url}`"
                                                alt="Imagen del producto"
                                                class="object-cover object-center w-full h-full rounded-lg shadow-sm" />
                                        </div>
                                    </div>
                                    <div class="sm:ml-6 flex-grow">
                                        <h4 class="text-lg font-semibold mb-2">{{ getProductDetails(detalle.producto_id).nombre }}</h4>
                                        <p class="text-sm text-gray-600 mb-2">{{ getProductDetails(detalle.producto_id).descripcion }}</p>
                                        <div class="flex flex-wrap gap-2 mb-2">
                                            <span class="px-2 py-1 bg-gray-200 rounded-full text-xs">Cantidad: {{ detalle.cantidad }}</span>
                                            <span class="px-2 py-1 bg-gray-200 rounded-full text-xs">Subtotal: Bs. {{ detalle.subtotal }}</span>
                                            <span class="px-2 py-1 bg-gray-200 rounded-full text-xs">Envío: Bs. {{ detalle.costo_envio }}</span>
                                        </div>
                                        <p class="text-sm text-gray-600">Dirección: {{ detalle.direccion_envio }}, {{ detalle.ciudad_envio }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-8 mt-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <p>&copy; 2024 Lucibel Escura -Shop. Todos los derechos reservados.</p>
                <p>Desarrollado por Ark Developer for Collaboration</p>
            </div>
        </footer>
    </AppLayout>
</template>