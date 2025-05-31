<script setup>
import FormBuy from '@/Components/Productos/FormBuy.vue';
import { defineProps } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import Footer from '@/Layouts/Footer.vue';

const props = defineProps({
    Producto: {
        type: Object,
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
</script>

<template>
    <AppLayout title="Edit Productos">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800">Detalles del producto</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto">
                <div class="bg-white overflow-hidden">
                    <FormBuy 
                        :Producto="Producto" 
                        :Proveedores="Proveedores"
                        :Subcategorias="Subcategorias"
                        :Descuentos="Descuentos"
                        :ProductoDescuentos="ProductoDescuentos"
                        :Productos_detalles="Productos_detalles"
                        :Colores="Colores"
                        :Tallas="Tallas"
                        :Envios="Envios"
                    />
                </div>
            </div>
        </div>
        <Footer />
    </AppLayout>
</template>
