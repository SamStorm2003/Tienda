<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import ProductosFormEdit from '@/Components/Productos/FormEdit.vue';

const props = defineProps({
    producto: {
        type: Object,
        required: true
    },
    proveedores: {
        type: Array,
        required: true
    },
    subcategorias: {
        type: Array,
        required: true
    },
    productosdetalles: {
        type: Array,
        required: true
    },
    colores: {
        type: Array,
        required: true
    },
    tallas: {
        type: Array,
        required: true
    }
});

const detalles = props.productosdetalles.filter(detalle => detalle.producto_id === props.producto.producto_id);

const detallesForm = detalles.map(detalle => ({
    stock: detalle.stock,
    talla_id: detalle.talla_id,
    color_id: detalle.color_id
}));

const form = useForm({
    id: props.producto.producto_id,
    nombre: props.producto.nombre,
    descripcion: props.producto.descripcion,
    sku: props.producto.sku,
    precio: props.producto.precio,
    descuento_porcentaje: props.producto.descuento_porcentaje,
    imagen_url: props.producto.imagen_url,
    subcategoria_id: props.producto.subcategoria_id,
    proveedor_id: props.producto.proveedor_id,
    temporada: props.producto.temporada,
    genero: props.producto.genero,
    detalles: detallesForm,
});
</script>

<template>
    <AppLayout title="Edit Productos">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800">Editar Producto</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto">
                <div class="bg-white overflow-hidden">
                     <ProductosFormEdit 
                        :updating="true" 
                        :form="form" 
                        :proveedores="props.proveedores"
                        :subcategorias="props.subcategorias" 
                        :productosdetalles="form.detalles" 
                        :colores="props.colores" 
                        :tallas="props.tallas" 
                        @submit="form.put(route('Productos.update', form.id))" />
                </div>
            </div>
        </div>
    </AppLayout>
    <footer class="bg-gray-800 text-white py-8">
        <div class="container mx-auto text-center">
            <p>&copy; 2024 Lucibel Escura -Shop. Todos los derechos reservados.</p>
            <p>Desarrollado por Ark Developer for Collaboration</p>
        </div>
    </footer>
</template>
