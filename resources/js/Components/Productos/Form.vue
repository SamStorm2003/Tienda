<script setup>
import { ref, onBeforeUnmount } from 'vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Swal from 'sweetalert2';
import axios from 'axios';

const props = defineProps({
    form: {
        type: Object,
        required: true,
        default: () => ({ errors: {} }),
    },
    updating: {
        type: Boolean,
        default: false,
    },
    proveedores: {
        type: Array,
        required: true,
    },
    subcategorias: {
        type: Array,
        required: true,
    },
    colores: {
        type: Array,
        required: true,
    },
    tallas: {
        type: Array,
        required: true,
    },
});

const imagePreviewUrl = ref('');
const isDragging = ref(false);
const nuevoColor = ref('');
const loading = ref(false);

const updateImage = (file) => {
    if (file) {
        props.form.imagen = file;
        imagePreviewUrl.value = URL.createObjectURL(file);
    } else {
        imagePreviewUrl.value = '';
    }
};

const handleFileChange = (event) => {
    updateImage(event.target.files[0]);
};

const handleDrop = (event) => {
    event.preventDefault();
    isDragging.value = false;
    updateImage(event.dataTransfer.files[0]);
};

const handleDragOver = (event) => {
    event.preventDefault();
    isDragging.value = true;
};

const handleDragLeave = () => {
    isDragging.value = false;
};

onBeforeUnmount(() => {
    if (imagePreviewUrl.value) {
        URL.revokeObjectURL(imagePreviewUrl.value);
    }
});

const showLoading = async () => {
    Swal.fire({
        title: 'Cargando productos',
        text: 'Espere por favor...',
        willOpen: () => {
            Swal.showLoading();
        },
    });
};

const submit = async () => {
    if (!props.form.color_id && !nuevoColor.value) {
        await Swal.fire({
            title: 'Error',
            text: 'Debe seleccionar un color o ingresar un nuevo color.',
            icon: 'error',
            confirmButtonText: 'OK',
        });
        return;
    }
    const formData = new FormData();
    Object.entries(props.form).forEach(([key, value]) => {
        if (value != null) {
            formData.append(key, value);
        }
    });
    loading.value = true;
    try {
        let selectedColorId = props.form.color_id;

        await showLoading();

        if (!selectedColorId && nuevoColor.value) {
            const colorResponse = await axios.post(route('insertar_color'), {
                nombre: nuevoColor.value,
            });
            if (colorResponse.data.success) {
                selectedColorId = colorResponse.data.color_id;
            }
        }
        const response = await axios.post(route('Productos.store'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });
        const productoId = response.data.producto_id;
        const detalleData = {
            producto_id: productoId,
            color_id: selectedColorId || props.form.color_id,
            talla_id: props.form.talla_id,
            stock: props.form.cantidad,
        };
        await axios.post(route('agregar_detalle_producto'), detalleData);
        await Swal.fire({
            title: 'Producto creado',
            text: `El producto se ha creado exitosamente`,
            icon: 'success',
            confirmButtonText: 'OK',
        });
        window.location.href = route('Productos.index');
    } catch (error) {
        if (error.response?.data?.errors) {
            props.form.errors = error.response.data.errors;
        }
        await Swal.fire({
            title: 'Error',
            text: 'Hubo un error al crear el producto o sus detalles.',
            icon: 'error',
            confirmButtonText: 'OK',
        });
        console.error(error);
    } finally {
        loading.value = false;
        Swal.close();
    }
};

const cancel = () => {
    window.location.href = route('Productos.index');
};
</script>
<!-- De aca para abajo modificar diseño-->
<template>
    <form @submit.prevent="submit" class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold mb-6 text-center">Guardar Producto</h2>

        <div class="mb-4">
            <InputLabel for="nombre">Nombre</InputLabel>
            <TextInput v-model="form.nombre" id="nombre" type="text"
                class="border border-gray-300 rounded-md focus:ring focus:ring-blue-300" />
            <InputError :message="form.errors.nombre" />
        </div>

        <div class="mb-4">
            <InputLabel for="descripcion">Descripción</InputLabel>
            <textarea v-model="form.descripcion" id="descripcion"
                class="w-full border border-gray-300 rounded-md focus:ring focus:ring-blue-300 px-4 py-2"
                rows="3"></textarea>
            <InputError :message="form.errors.descripcion" />
        </div>

        <div class="mb-4">
            <InputLabel for="sku">SKU</InputLabel>
            <TextInput v-model="form.sku" id="sku" type="text"
                class="border border-gray-300 rounded-md focus:ring focus:ring-blue-300" />
            <InputError :message="form.errors.sku" />
        </div>

        <div class="mb-4">
            <InputLabel for="precio">Precio</InputLabel>
            <TextInput v-model="form.precio" id="precio" type="number" step="0.01"
                class="border border-gray-300 rounded-md focus:ring focus:ring-blue-300" />
            <InputError :message="form.errors.precio" />
        </div>

        <div class="mb-4">
            <InputLabel for="cantidad">Cantidad</InputLabel>
            <TextInput v-model="form.cantidad" id="cantidad" type="number" min="1"
                class="border border-gray-300 rounded-md focus:ring focus:ring-blue-300"
                placeholder="Ingrese la cantidad" required />
            <InputError :message="form.errors.cantidad" />
        </div>

        <div class="mb-4">
            <InputLabel for="descuento_porcentaje">Descuento (%)</InputLabel>
            <TextInput v-model="form.descuento_porcentaje" id="descuento_porcentaje" type="number" step="0.01"
                class="border border-gray-300 rounded-md focus:ring focus:ring-blue-300" />
            <InputError :message="form.errors.descuento_porcentaje" />
        </div>

        <div class="mb-4">
            <InputLabel for="imagen">Imagen</InputLabel>
            <div class="w-full h-32 border-dashed border-2 border-gray-300 flex items-center justify-center relative rounded-md hover:border-blue-500 transition-colors"
                @dragover="handleDragOver" @dragleave="handleDragLeave" @drop="handleDrop"
                :class="{ 'border-blue-500': isDragging }">
                <input @change="handleFileChange" id="imagen" type="file"
                    class="absolute inset-0 opacity-0 cursor-pointer" />
                <p v-if="!imagePreviewUrl" class="text-gray-500">Arrastra y suelta una imagen aquí, o haz clic para
                    seleccionar</p>
                <img v-if="imagePreviewUrl" :src="imagePreviewUrl" alt="Vista previa de la imagen"
                    class="w-full h-full object-contain rounded-md" />
            </div>
            <InputError :message="form.errors.imagen" />
        </div>

        <div class="mb-4">
            <InputLabel for="subcategoria_id">Subcategoría</InputLabel>
            <select v-model="form.subcategoria_id" id="subcategoria_id"
                class="w-full border border-gray-300 rounded-md focus:ring focus:ring-blue-300 px-4 py-2">
                <option v-for="subcategoria in props.subcategorias" :key="subcategoria.subcategoria_id"
                    :value="subcategoria.subcategoria_id">
                    {{ subcategoria.nombre }}
                </option>
            </select>
            <InputError :message="form.errors.subcategoria_id" />
        </div>

        <div class="mb-4">
            <InputLabel for="proveedor_id">Proveedor</InputLabel>
            <select v-model="form.proveedor_id" id="proveedor_id"
                class="w-full border border-gray-300 rounded-md focus:ring focus:ring-blue-300 px-4 py-2">
                <option v-for="proveedor in props.proveedores" :key="proveedor.proveedor_id"
                    :value="proveedor.proveedor_id">
                    {{ proveedor.nombre }}
                </option>
            </select>
            <InputError :message="form.errors.proveedor_id" />
        </div>

        <div class="mb-4">
            <InputLabel for="color_id">Color</InputLabel>
            <select v-model="form.color_id" id="color_id"
                class="w-full border border-gray-300 rounded-md focus:ring focus:ring-blue-300 px-4 py-2">
                <option value="" disabled>Seleccione un color</option>
                <option v-for="color in props.colores" :key="color.color_id" :value="color.color_id">
                    {{ color.nombre }}
                </option>
            </select>
            <InputError :message="form.errors.color_id" />
        </div>

        <div class="mb-4">
            <InputLabel for="nuevo_color">Agregar nuevo color (Opcional)</InputLabel>
            <input v-model="nuevoColor" id="nuevo_color" type="text"
                class="w-full border border-gray-300 rounded-md focus:ring focus:ring-blue-300 px-4 py-2"
                placeholder="Ingrese nuevo color" />
        </div>

        <div class="mb-4">
            <InputLabel for="talla_id">Talla</InputLabel>
            <select v-model="form.talla_id" id="talla_id"
                class="w-full border border-gray-300 rounded-md focus:ring focus:ring-blue-300 px-4 py-2">
                <option value="" disabled>Seleccione una talla</option>
                <option v-for="talla in props.tallas" :key="talla.talla_id" :value="talla.talla_id">
                    {{ talla.nombre }}
                </option>
            </select>
            <InputError :message="form.errors.talla_id" />
        </div>

        <div class="mb-4">
            <InputLabel for="temporada">Temporada</InputLabel>
            <select v-model="form.temporada" id="temporada"
                class="w-full border border-gray-300 rounded-md focus:ring focus:ring-blue-300 px-4 py-2">
                <option>Primavera</option>
                <option>Verano</option>
                <option>Otoño</option>
                <option>Invierno</option>
            </select>
            <InputError :message="form.errors.temporada" />
        </div>

        <div class="mb-4">
            <InputLabel for="genero">Género</InputLabel>
            <select v-model="form.genero" id="genero"
                class="w-full border border-gray-300 rounded-md focus:ring focus:ring-blue-300 px-4 py-2">
                <option>Hombre</option>
                <option>Mujer</option>
                <option>Niño</option>
                <option>Niña</option>
                <option>Unisex</option>
            </select>
            <InputError :message="form.errors.genero" />
        </div>

        <div class="flex justify-between mt-6">
            <PrimaryButton type="submit" class="w-full mr-2">
                Guardar Producto
            </PrimaryButton>
            <PrimaryButton type="button" @click="cancel" class="w-full bg-red-500 hover:bg-red-600">
                Cancelar
            </PrimaryButton>
        </div>
    </form>
</template>