<script setup>
import { ref, defineProps, computed } from 'vue';
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
  proveedores: Array,
  subcategorias: Array,
  productosdetalles: Array,
  colores: Array,
  tallas: Array,
});

const imagePreviewUrl = ref(null);
const originalImageUrl = computed(() => props.form.imagen_url || null);

const handleFile = (file) => {
  if (file) {
    props.form.imagen = file;
    const reader = new FileReader();
    reader.onload = (e) => {
      imagePreviewUrl.value = e.target.result;
    };
    reader.readAsDataURL(file);
  } else {
    props.form.imagen = null;
    imagePreviewUrl.value = null;
  }
};

const handleFileChange = (event) => {
  const file = event.target.files[0];
  handleFile(file);
};

const handleDrop = (event) => {
  event.preventDefault();
  const file = event.dataTransfer.files[0];
  handleFile(file);
};

const handleDragOver = (event) => {
  event.preventDefault();
};

const handleError = async (error, defaultMessage = 'Hubo un error en el proceso.') => {
  if (error.response && error.response.data && error.response.data.errors) {
    props.form.errors = error.response.data.errors;
  }
  await Swal.fire({
    title: 'Error',
    text: defaultMessage,
    icon: 'error',
    confirmButtonText: 'OK',
  });
};

const submitForm = async () => {
  const formData = new FormData();
  for (const key in props.form) {
    if (props.form[key] !== undefined && props.form[key] !== null && props.form[key] !== originalImageUrl.value) {
      formData.append(key, props.form[key]);
    }
  }
  if (props.form.imagen) {
    formData.append('imagen', props.form.imagen);
  }
  try {
    for (const detalle of props.productosdetalles) {
      await axios.post(route('productosdetallesstock', { id: props.form.id }), {
        stock: detalle.stock,
        color_id: detalle.color_id,
        talla_id: detalle.talla_id
      });
    }
    await axios.post(route('Productos.update', { id: props.form.id }), formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
        'X-HTTP-Method-Override': 'PUT'
      },
    });
    await Swal.fire({
      title: 'Producto actualizado',
      text: 'El producto se ha actualizado exitosamente.',
      icon: 'success',
      confirmButtonText: 'OK',
    });
  } catch (error) {
    handleError(error, 'Hubo un error al actualizar el producto.');
  }
};

const getError = (field) => {
  if (props.form.errors && Array.isArray(props.form.errors[field])) {
    return props.form.errors[field].join(', ');
  }
  return props.form.errors[field];
};

const cancel = () => {
  window.location.href = route('Productos.index');
};

</script>

<!-- De aca para abajo modificar diseño-->
<template>
  <form @submit.prevent="submitForm" class="max-w-lg mx-auto p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-xl font-semibold mb-4">Actualizar Producto</h2>

    <div class="mb-4">
      <InputLabel for="nombre" value="Nombre" />
      <TextInput id="nombre" v-model="form.nombre" class="mt-1 block w-full border border-gray-300 rounded-md" />
      <InputError :message="getError('nombre')" class="mt-2" />
    </div>
    <div class="mb-4">
      <InputLabel for="descripcion" value="Descripción" />
      <TextInput id="descripcion" v-model="form.descripcion"
        class="mt-1 block w-full border border-gray-300 rounded-md" />
      <InputError :message="getError('descripcion')" class="mt-2" />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <InputLabel for="sku" value="Descripcion:" />
        <TextInput id="sku" v-model="form.sku" class="mt-1 block w-full border border-gray-300 rounded-md" />
        <InputError :message="getError('sku')" class="mt-2" />
      </div>

      <div>
        <InputLabel for="precio" value="Precio" />
        <TextInput id="precio" type="number" v-model="form.precio"
          class="mt-1 block w-full border border-gray-300 rounded-md" />
        <InputError :message="getError('precio')" class="mt-2" />
      </div>

      <div>
        <InputLabel for="descuento_porcentaje" value="Descuento (%)" />
        <TextInput id="descuento_porcentaje" type="number" v-model="form.descuento_porcentaje"
          class="mt-1 block w-full border border-gray-300 rounded-md" />
        <InputError :message="getError('descuento_porcentaje')" class="mt-2" />
      </div>

      <div>
        <InputLabel for="subcategoria_id" value="Subcategoría" />
        <select id="subcategoria_id" v-model="form.subcategoria_id"
          class="mt-1 block w-full border border-gray-300 rounded-md">
          <option value="" disabled>Seleccione una subcategoría</option>
          <option v-for="subcategoria in subcategorias" :key="subcategoria.subcategoria_id"
            :value="subcategoria.subcategoria_id">
            {{ subcategoria.nombre }}
          </option>
        </select>
        <InputError :message="getError('subcategoria_id')" class="mt-2" />
      </div>

      <div>
        <InputLabel for="proveedor_id" value="Proveedor" />
        <select id="proveedor_id" v-model="form.proveedor_id"
          class="mt-1 block w-full border border-gray-300 rounded-md">
          <option value="" disabled>Seleccione un proveedor</option>
          <option v-for="proveedor in proveedores" :key="proveedor.proveedor_id" :value="proveedor.proveedor_id">
            {{ proveedor.nombre }}
          </option>
        </select>
        <InputError :message="getError('proveedor_id')" class="mt-2" />
      </div>

      <div>
        <InputLabel for="temporada" value="Temporada" />
        <select id="temporada" v-model="form.temporada" class="mt-1 block w-full border border-gray-300 rounded-md">
          <option value="" disabled>Seleccione una temporada</option>
          <option value="Primavera">Primavera</option>
          <option value="Verano">Verano</option>
          <option value="Otoño">Otoño</option>
          <option value="Invierno">Invierno</option>
        </select>
        <InputError :message="getError('temporada')" class="mt-2" />
      </div>

      <div>
        <InputLabel for="genero" value="Género" />
        <select id="genero" v-model="form.genero" class="mt-1 block w-full border border-gray-300 rounded-md">
          <option value="" disabled>Seleccione un género</option>
          <option value="Hombre">Hombre</option>
          <option value="Mujer">Mujer</option>
          <option value="Niño">Niño</option>
          <option value="Niña">Niña</option>
          <option value="Unisex">Unisex</option>
        </select>
        <InputError :message="getError('genero')" class="mt-2" />
      </div>
    </div>
    <h3 class="text-lg font-semibold mt-6">Detalles del Producto</h3>

    <div v-for="(detalle, index) in props.productosdetalles" :key="index" class="mb-4">
  <div class="mb-2">
    <InputLabel :for="'stock_' + index" value="Stock" />
    <TextInput :id="'stock_' + index" type="number" v-model="detalle.stock" class="mt-1 block w-full border border-gray-300 rounded-md" />
    <InputError :message="getError('productosdetalles.' + index + '.stock')" class="mt-2" />
  </div>

  <div class="mb-2">
    <InputLabel :for="'talla_' + index" value="Talla" />
    <select :id="'talla_' + index" v-model="detalle.talla_id" class="mt-1 block w-full border border-gray-300 rounded-md">
      <option value="" disabled>Seleccione una talla</option>
      <option v-for="talla in props.tallas" :key="talla.talla_id" :value="talla.talla_id">{{ talla.nombre }}</option>
    </select>
    <InputError :message="getError('productosdetalles.' + index + '.talla_id')" class="mt-2" />
  </div>

  <div class="mb-2">
    <InputLabel :for="'color_' + index" value="Color" />
    <select :id="'color_' + index" v-model="detalle.color_id" class="mt-1 block w-full border border-gray-300 rounded-md">
      <option value="" disabled>Seleccione un color</option>
      <option v-for="color in props.colores" :key="color.color_id" :value="color.color_id">{{ color.nombre }}</option>
    </select>
    <InputError :message="getError('productosdetalles.' + index + '.color_id')" class="mt-2" />
  </div>
</div>
    <div class="mb-4">
      <InputLabel for="imagen_actual" value="Imagen Actual" />
      <div class="mt-2">
        <img v-if="form.imagen_url" :src="'/storage/' + form.imagen_url" alt="Imagen del producto"
          class="w-32 h-32 object-cover" />
      </div>
    </div>

    <div class="mb-4" @drop="handleDrop" @dragover="handleDragOver">
      <InputLabel for="imagen">Imagen Nueva</InputLabel>
      <input @change="handleFileChange" id="imagen" type="file" class="hidden" />
      <p class="text-center border-dashed border-2 border-gray-300 p-4 rounded-md">Arrastra y suelta una imagen aquí o
        haz clic para seleccionar</p>
      <div v-if="imagePreviewUrl">
        <img :src="imagePreviewUrl" alt="Vista previa de la imagen" class="mt-2 w-32 h-32 object-cover" />
      </div>
    </div>

    <div class="flex justify-between">
      <PrimaryButton type="submit" class="bg-blue-500 hover:bg-blue-600">Actualizar Producto</PrimaryButton>
      <PrimaryButton type="button" @click="cancel" class="bg-red-500 hover:bg-red-600">Cancelar</PrimaryButton>
    </div>
  </form>
</template>
