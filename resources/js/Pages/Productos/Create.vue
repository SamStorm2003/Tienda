<script>
export default {
    name: 'ProductosCreate'
}
</script>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import ProductosForm from '@/Components/Productos/Form.vue';
import { usePage } from '@inertiajs/vue3';

const { props } = usePage();

const form = useForm({
    nombre: '',
    descripcion: '',
    sku: '',
    precio: '0.00',
    descuento_porcentaje: '0.00',
    imagen_url: '',
    subcategoria_id: '',
    proveedor_id: '',
    temporada: 'Primavera',
    genero: 'Unisex',
});
const proveedores = props.proveedores;
const subcategorias = props.subcategorias;
const colores = props.colores;
const tallas= props.tallas;

</script>
<!-- De aca para abajo modificar diseño-->
<template>
    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800">Crear Productos</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-6">
                <div class="bg-white p-6 border border-gray-200">
                    <ProductosForm 
                        :form="form" 
                        :proveedores="proveedores" 
                        :subcategorias="subcategorias" 
                        :colores="colores" 
                        :tallas="tallas" 
                    />
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
