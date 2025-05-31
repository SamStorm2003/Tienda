<script>
export default {
    name: 'VentasIndex'
}
</script>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, computed } from 'vue';
import { defineProps } from 'vue';
import Swal from 'sweetalert2';
import axios from 'axios';
import { usePage, Head, Link, router } from '@inertiajs/vue3';
import jsPDF from 'jspdf';

const page = usePage();
const userId = page.props.auth.user.id;

const props = defineProps({
    Producto: Array,
    Proveedores: Array,
    Subcategorias: Array,
    Descuentos: Array,
    ProductoDescuentos: Array,
    Productos_detalles: Array,
    Ventas: Array,
    Colores: Array,
    Tallas: Array,
    Sucursales: Array,
});

const selectedProducts = ref([]);
const searchSku = ref('');
const foundProduct = ref(null);
const showModal = ref(false);
const activeDiscount = ref(null);
const discountValue = ref(0);
const selectedSucursal = ref('');
const showSaleModal = ref(false);
const searchTerm = ref('');
const foundUser = ref(null);
const showInputModal = ref(false);
const name = ref('');
const phone = ref('');

const applyDiscount = (descuento) => {
    if (selectedProducts.value.length === 0) {
        Swal.fire('Error', 'No hay productos seleccionados. Selecciona al menos un producto antes de aplicar un descuento.', 'error');
        return;
    }
    if (activeDiscount.value) {
        Swal.fire('Advertencia', 'Ya hay un descuento de temporada aplicado. Elimina productos antes de aplicar otro.', 'warning');
        return;
    }
    const today = new Date();
    const fechaInicio = new Date(descuento.fecha_inicio);
    const fechaFin = new Date(descuento.fecha_fin);
    if (today >= fechaInicio && today <= fechaFin) {
        activeDiscount.value = descuento;
        discountValue.value = descuento.valor;
        Swal.fire('Éxito', 'Descuento de temporada aplicado correctamente.', 'success');
    } else {
        Swal.fire('Error', 'El descuento no es válido en esta fecha.', 'error');
    }
};

const removeProduct = (producto) => {
    const index = selectedProducts.value.findIndex(p =>
        p.producto_id === producto.producto_id &&
        p.color_id === producto.color_id &&
        p.talla_id === producto.talla_id
    );
    if (index !== -1) {
        selectedProducts.value.splice(index, 1);
    }
    if (selectedProducts.value.length === 0) {
        resetDiscount();
    }
};

const addProductFromModal = () => {
    if (!foundProduct.value) {
        Swal.fire('Error', 'Debe seleccionar un producto.', 'error');
        return;
    }
    const productoDetalle = getProductoDetalle(foundProduct.value.producto_id);
    if (!validateStock(productoDetalle)) return;

    const existingProduct = selectedProducts.value.find(p =>
        p.producto_id === foundProduct.value.producto_id &&
        p.color_id === productoDetalle.color_id &&
        p.talla_id === productoDetalle.talla_id
    );

    if (existingProduct) {
        if (existingProduct.cantidad + 1 > productoDetalle.stock) {
            Swal.fire('Advertencia', 'No hay suficiente stock disponible para este producto.', 'warning');
            return;
        }
        existingProduct.cantidad++;
    } else {
        const precioConDescuento = foundProduct.value.precio - (foundProduct.value.precio * (foundProduct.value.descuento_porcentaje / 100));
        selectedProducts.value.push({
            ...foundProduct.value,
            color_id: productoDetalle.color_id,
            talla_id: productoDetalle.talla_id,
            cantidad: 1,
            descuento: foundProduct.value.descuento_porcentaje,
            color_nombre: getColorName(productoDetalle.color_id),
            talla_nombre: getTallaName(productoDetalle.talla_id),
            stock: productoDetalle.stock,
            precioConDescuento
        });
    }

    searchSku.value = '';
    closeModal();
};

const resetDiscount = () => {
    activeDiscount.value = null;
    discountValue.value = 0;
};

const validateStock = (productoDetalle) => {
    if (!productoDetalle) {
        Swal.fire('Error', 'No se encontró el detalle del producto', 'error');
        return false;
    }
    if (productoDetalle.stock <= 0) {
        Swal.fire('Error', 'El producto no está disponible en stock.', 'error');
        return false;
    }
    return true;
};

const addProduct = (producto) => {
    const productoDetalle = getProductoDetalle(producto.producto_id);
    if (!validateStock(productoDetalle)) return;
    const existingProduct = selectedProducts.value.find(p =>
        p.producto_id === producto.producto_id &&
        p.color_id === productoDetalle.color_id &&
        p.talla_id === productoDetalle.talla_id
    );
    if (existingProduct) {
        if (existingProduct.cantidad + 1 > productoDetalle.stock) {
            Swal.fire('Advertencia', 'No hay suficiente stock disponible para este producto.', 'warning');
            return;
        }
        existingProduct.cantidad++;
    } else {
        const precioConDescuento = producto.precio - (producto.precio * (producto.descuento_porcentaje / 100));
        selectedProducts.value.push({
            ...producto,
            color_id: productoDetalle.color_id,
            talla_id: productoDetalle.talla_id,
            cantidad: 1,
            descuento: producto.descuento_porcentaje,
            color_nombre: getColorName(productoDetalle.color_id),
            talla_nombre: getTallaName(productoDetalle.talla_id),
            stock: productoDetalle.stock,
            precioConDescuento
        });
    }
};

const total = computed(() => {
    const subtotal = selectedProducts.value.reduce((sum, product) => {
        return sum + (product.precioConDescuento * product.cantidad);
    }, 0);
    return discountValue.value > 0 ? subtotal - (subtotal * (discountValue.value / 100)) : subtotal;
});

const resetSale = () => {
    selectedProducts.value = [];
    resetDiscount();
};

const getProductoDetalle = (productoId) => {
    return props.Productos_detalles.find(detalle => detalle.producto_id === productoId);
};

const getColorName = (colorId) => {
    const color = props.Colores.find(c => c.color_id === colorId);
    return color ? color.nombre : 'No definido';
};

const getTallaName = (tallaId) => {
    const talla = props.Tallas.find(t => t.talla_id === tallaId);
    return talla ? talla.nombre : 'No definido';
};

const searchProductBySku = () => {
    const skuLower = searchSku.value.toLowerCase();
    foundProduct.value = props.Producto.find(p => p.sku.toLowerCase() === skuLower);
    if (foundProduct.value) {
        showModal.value = true;
    } else {
        Swal.fire('Error', 'Producto no encontrado', 'error');
    }
};

const closeModal = () => {
    showModal.value = false;
    foundProduct.value = null;
    showSaleModal.value = false;
    searchTerm.value = '';
    foundUser.value = null;
    showInputModal.value = false;
};

const datosUsuario = ref(null);

const buscarVenta = async () => {
    try {
        const response = await axios.get(`/usuarios/buscar/${searchTerm.value}`);
        foundUser.value = response.data;
        datosUsuario.value = response.data;
        Swal.fire('Usuario encontrado', `Nombre: ${foundUser.value.nombre}`, 'success');
    } catch (error) {
        if (error.response && error.response.status === 404) {
            foundUser.value = null;
            datosUsuario.value = null;
            Swal.fire('Error', 'Usuario no encontrado', 'error');
        } else {
            console.error(error);
            Swal.fire('Error', 'Ocurrió un error inesperado', 'error');
        }
    }
};

const guardarSinDatos = () => {
    showInputModal.value = true;
};

const confirmSaveSale = async () => {
    if (!name.value || !phone.value) {
        Swal.fire('Error', 'Por favor, llena todos los campos requeridos (Nombre y Teléfono).', 'error');
        return;
    }
    if (selectedProducts.value.length === 0) {
        Swal.fire('Error', 'No hay productos seleccionados. Por favor selecciona al menos un producto.', 'error');
        return;
    }
    if (!selectedSucursal.value) {
        Swal.fire('Error', 'No se ha seleccionado ninguna sucursal. Por favor selecciona una sucursal.', 'error');
        return;
    }
    let processingSwal;
    try {
        processingSwal = Swal.fire({
            title: 'Procesando venta',
            text: 'Por favor, espera mientras se completa la venta.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        try {
            await axios.post('/actualizar-stock', {
                productos: selectedProducts.value.map(producto => ({
                    producto_id: producto.producto_id,
                    cantidad: producto.cantidad
                }))
            });
        } catch (error) {
            Swal.close();
            Swal.fire('Error', error.response.data.error || 'Error al actualizar el stock.', 'error');
            return;
        }
        const response = await axios.post('/venta-anonima', {
            sucursal_id: selectedSucursal.value,
            total: total.value,
        });
        const ingresoId = response.data.ingreso_id;
        await axios.post('/ventas-anonimo-detalle', {
            ingreso_id: ingresoId,
            productos: selectedProducts.value.map(producto => ({
                producto_id: producto.producto_id,
                color_id: producto.color_id,
                talla_id: producto.talla_id,
                vendedor_id: userId,
                cantidad: producto.cantidad,
                precio_unitario: producto.precio,
                precio_con_descuento: producto.precioConDescuento,
                subtotal: (producto.precioConDescuento * producto.cantidad).toFixed(2)
            }))
        });
        await axios.post('/usuariosexternos', {
            nombre: name.value,
            telefono: phone.value,
            ingreso_id: ingresoId
        });
        generarFactura();
        resetSale();
        Swal.close();
        Swal.fire('Éxito', 'La venta se realizó correctamente.', 'success');
        closeModal();
    } catch (error) {
        Swal.close();
        Swal.fire('Error', 'No se pudo guardar la venta o los detalles. Inténtalo de nuevo.', 'error');
        console.error(error);
    }
};

const generarFactura = () => {
    console.log('Generando factura...');
    console.log('Nombre:', name.value, 'Teléfono:', phone.value);
    console.log('FoundUser:', foundUser);
    const doc = new jsPDF();
    const empresaNombre = "Shatoria Desing Em5";
    const fechaActual = new Date().toLocaleDateString();
    doc.setFillColor(0, 102, 204);
    doc.rect(0, 0, 210, 40, 'F');
    doc.setFontSize(20);
    doc.setTextColor(255, 255, 255);
    doc.text(empresaNombre, 10, 20);
    doc.setFontSize(12);
    doc.text(`Fecha: ${fechaActual}`, 150, 20);
    doc.setFontSize(20);
    doc.text("Factura de Venta", 10, 35);
    doc.setTextColor(0, 0, 0);
    doc.setFontSize(12);
    let yPosition = 50;
    const nombreCompleto = name.value || `${datosUsuario.value?.nombre || ''} ${datosUsuario.value?.apellido || ''}`;
    doc.text(`Nombre: ${nombreCompleto}`, 10, yPosition);
    yPosition += 10;
    const telefonoCliente = phone.value || datosUsuario.value?.telefono || 'No disponible';
    doc.text(`Teléfono: ${telefonoCliente}`, 10, yPosition);
    yPosition += 10;
    if (datosUsuario.value?.email) {
        doc.text(`Email: ${datosUsuario.value.email}`, 10, yPosition);
        yPosition += 10;
    }
    if (datosUsuario.value?.documento_identidad) {
        doc.text(`Documento de Identidad: ${datosUsuario.value.documento_identidad}`, 10, yPosition);
        yPosition += 10;
    }
    const selectedSucursalName = props.Sucursales.find(sucursal => sucursal.sucursal_id === selectedSucursal.value)?.nombre || 'No seleccionada';
    doc.text(`Sucursal: ${selectedSucursalName}`, 10, yPosition);
    yPosition += 10;
    doc.line(10, yPosition, 200, yPosition);
    yPosition += 10;
    doc.setFontSize(14);
    doc.text("Productos:", 10, yPosition);
    yPosition += 10;
    selectedProducts.value.forEach(producto => {
        const precio = Number(producto.precio) || 0;
        const precioConDescuento = Number(producto.precioConDescuento) || 0;
        const cantidad = Number(producto.cantidad) || 0;
        doc.setFontSize(12);
        doc.text(`- Producto: ${producto.nombre}`, 10, yPosition);
        yPosition += 10;
        doc.text(`   SKU: ${producto.sku}`, 10, yPosition);
        yPosition += 10;
        doc.text(`   Color: ${producto.color_nombre}`, 10, yPosition);
        yPosition += 10;
        doc.text(`   Talla: ${producto.talla_nombre}`, 10, yPosition);
        yPosition += 10;
        doc.text(`   Cantidad: ${cantidad}`, 10, yPosition);
        yPosition += 10;
        doc.text(`   Precio original: Bs.${precio.toFixed(2)}`, 10, yPosition);
        yPosition += 10;
        doc.text(`   Precio con descuento: Bs.${precioConDescuento.toFixed(2)}`, 10, yPosition);
        yPosition += 10;
        const subtotal = precioConDescuento * cantidad;
        doc.text(`   Subtotal: Bs.${subtotal.toFixed(2)}`, 10, yPosition);
        yPosition += 20;
    });
    doc.setFontSize(14);
    doc.text(`Total: Bs.${total.value.toFixed(2)}`, 10, yPosition);
    yPosition += 10;
    doc.setFontSize(10);
    yPosition += 10;
    doc.text("Todos los derechos reservados. © 2025  Shatoria Desing Em5", 10, yPosition);
    doc.save("factura.pdf");
};

const guardarConFactura = async () => {
    if (!foundUser.value) {
        Swal.fire('Error', 'No se ha encontrado un usuario. Por favor, busca un usuario antes de proceder.', 'error');
        return;
    }
    if (selectedProducts.value.length === 0) {
        Swal.fire('Error', 'No hay productos seleccionados. Por favor selecciona al menos un producto.', 'error');
        return;
    }
    if (!selectedSucursal.value) {
        Swal.fire('Error', 'No se ha seleccionado ninguna sucursal. Por favor selecciona una sucursal.', 'error');
        return;
    }
    let processingSwal;
    try {
        processingSwal = Swal.fire({
            title: 'Procesando venta',
            text: 'Por favor, espera mientras se completa la venta.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        try {
            await axios.post('/guardar-venta-stock', {
                productos: selectedProducts.value.map(producto => ({
                    producto_id: producto.producto_id,
                    cantidad: producto.cantidad,
                }))
            });
        } catch (error) {
            Swal.close();
            Swal.fire('Error', error.response.data.error || 'Error al actualizar el stock.', 'error');
            return;
        }
        const ventaResponse = await axios.post('/guardar-venta', {
            usuario_id: foundUser.value.id_usuario,
            sucursal_id: selectedSucursal.value,
            total: total.value,
        });
        const ventaId = ventaResponse.data.venta_id;
        const selectedSucursalName = props.Sucursales.find(sucursal => sucursal.sucursal_id === selectedSucursal.value).nombre;
        const detallesVenta = selectedProducts.value.map(producto => ({
            producto_id: producto.producto_id,
            cantidad: producto.cantidad,
            precio_unitario: producto.precio,
            precio_con_descuento: total.value,
            subtotal: (producto.precioConDescuento * producto.cantidad).toFixed(2),
            costo_envio: producto.costo_envio || null,
            direccion_envio: producto.direccion_envio || null,
            ciudad_envio: selectedSucursalName + ' (Local)',
        }));
        await axios.post('/guardar-detalle-venta', {
            venta_id: ventaId,
            productos: detallesVenta,
        });
        generarFactura();
        Swal.close();
        Swal.fire('Éxito', `Venta registrada exitosamente.`, 'success');
        resetSale();
        closeModal();
    } catch (error) {
        Swal.close();
        if (error.response && error.response.status === 400 && error.response.data.error.includes('Stock insuficiente')) {
            Swal.fire('Error', error.response.data.error, 'error');
        } else {
            Swal.fire('Error', 'No se pudo guardar la venta. Inténtalo de nuevo.', 'error');
        }
        console.error(error);
    }
};

</script>
<!-- De aca para abajo mdificar -->
<template>
    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800">Ventana de control de ventas</h2>
        </template>
        <div class="mb-6">
            <label for="sku" class="block text-sm font-medium text-gray-700">Buscar producto por SKU:</label>
            <div class="mt-1 flex rounded-md shadow-sm">
                <input v-model="searchSku" type="text" name="sku" id="sku"
                    class="flex-1 block w-full rounded-none rounded-l-md sm:text-sm border-gray-300"
                    @keyup.enter="searchProductBySku">
                <button @click="searchProductBySku"
                    class="bg-blue-500 text-white px-4 py-2 rounded-r-md hover:bg-blue-600">Buscar</button>
            </div>
        </div>
        <div>
            <label for="sucursal">Selecciona una sucursal:</label>
            <select id="sucursal" v-model="selectedSucursal">
                <option v-for="sucursal in Sucursales" :key="sucursal.sucursal_id" :value="sucursal.sucursal_id">
                    {{ sucursal.nombre }}
                </option>
            </select>
        </div>
        <h1 class="text-2xl font-bold mt-6 mb-4">Productos Seleccionados</h1>
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <ul class="space-y-4">
                <li v-for="producto in selectedProducts" :key="producto.producto_id"
                    class="flex items-center space-x-4">
                    <div class="flex-none w-auto h-24 bg-gray-100 rounded-lg overflow-hidden">
                        <img v-if="producto.imagen_url" :src="`/storage/${producto.imagen_url}`"
                            alt="Imagen del producto" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-auto">
                        <h2 class="text-lg font-semibold text-gray-900">Producto Id: {{ producto.producto_id }}</h2>
                        <h2 class="text-lg font-semibold text-gray-900">{{ producto.nombre }}</h2>
                        <p class="text-sm text-gray-500">SKU: {{ producto.sku }}</p>
                        <p class="text-sm text-gray-500">Color: {{ producto.color_nombre }}</p>
                        <p class="text-sm text-gray-500">Talla: {{ producto.talla_nombre }}</p>
                        <p class="text-sm text-gray-500">Cantidad: {{ producto.cantidad }} / {{ producto.stock }}</p>
                        <div class="flex justify-between items-center mt-2">
                            <p class="text-sm text-gray-500">Precio original: Bs. {{ producto.precio }}</p>
                            <p class="text-sm text-gray-500">Descuento: Bs. {{ producto.precioConDescuento.toFixed(2) }}
                            </p>
                            <p class="text-lg font-semibold text-gray-900">Subtotal: Bs. {{ (producto.precioConDescuento
                                *
                                producto.cantidad).toFixed(2) }}</p>
                        </div>
                    </div>
                    <button @click="removeProduct(producto)"
                        class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 transition">Eliminar</button>
                </li>
            </ul>
        </div>
        <div v-if="showModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-xl font-semibold mb-4">Detalles del Producto</h3>
                <p><strong>Nombre:</strong> {{ foundProduct.nombre }}</p>
                <p><strong>SKU:</strong> {{ foundProduct.sku }}</p>
                <p><strong>Precio:</strong> Bs. {{ foundProduct.precio }}</p>
                <p><strong>Descripción:</strong> {{ foundProduct.descripcion }}</p>
                <p><strong>Stock:</strong> {{ getProductoDetalle(foundProduct.producto_id)?.stock || 'No disponible' }}
                </p>
                <div class="mt-4 flex justify-between">
                    <button @click="addProductFromModal"
                        class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Agregar</button>
                    <button @click="closeModal"
                        class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">Cerrar</button>
                </div>
            </div>
        </div>
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold">Total</h2>
            <h2 class="text-2xl font-bold text-gray-900">Bs. {{ total.toFixed(2) }}</h2>
        </div>
        <button @click="showSaleModal = true"
            class="w-full bg-blue-600 text-white text-lg font-semibold py-2 rounded-lg hover:bg-blue-700 transition">
            Guardar Venta
        </button>
        <div v-if="showSaleModal" @click.self="closeModal"
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full" @click.stop>
                <h3 class="text-xl font-semibold mb-4">Guardar Venta</h3>
                <div class="mb-4">
                    <label for="buscador" class="block text-sm font-medium text-gray-700">Buscar usuario por Documento
                        de
                        Identidad:</label>
                    <input v-model="searchTerm" type="text" id="buscador"
                        class="block w-full p-2 border border-gray-300 rounded-lg">
                </div>
                <button @click="buscarVenta"
                    class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 mb-4">
                    Buscar
                </button>
                <div v-if="foundUser">
                    <p><strong>Nombre:</strong> {{ foundUser.nombre }} {{ foundUser.apellido }}</p>
                    <p><strong>Email:</strong> {{ foundUser.email }}</p>
                    <p><strong>Teléfono:</strong> {{ foundUser.telefono }}</p>
                    <p><strong>Documento de Identidad:</strong> {{ foundUser.documento_identidad }}</p>
                    <p><strong>ID Usuario:</strong> {{ foundUser.id_usuario }}</p>
                </div>
                <div class="mb-4">
                    <p>Opciones de Guardado:</p>
                    <button @click="guardarSinDatos"
                        class="w-full bg-gray-600 text-white py-2 rounded-lg hover:bg-gray-700 mb-2">
                        Guardar venta sin datos
                    </button>
                    <button @click="guardarConFactura"
                        class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">
                        Guardar venta con factura
                    </button>
                </div>
                <button @click="closeModal" class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700">
                    Cancelar
                </button>
            </div>
        </div>

        <div v-if="showInputModal" @click.self="closeModal"
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full" @click.stop>
                <h3 class="text-xl font-semibold mb-4">Ingrese sus datos</h3>
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nombre:</label>
                    <input v-model="name" type="text" id="name"
                        class="block w-full p-2 border border-gray-300 rounded-lg">
                </div>
                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700">Teléfono:</label>
                    <input v-model="phone" type="text" id="phone"
                        class="block w-full p-2 border border-gray-300 rounded-lg">
                </div>
                <button @click="confirmSaveSale"
                    class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 mb-2">
                    Guardar Venta
                </button>
                <button @click="closeModal" class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700">
                    Cancelar
                </button>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
            <div class="md:col-span-2">
                <h1 class="text-2xl font-bold mb-4">Productos</h1>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <ul class="space-y-4">
                        <li v-for="producto in Producto" :key="producto.producto_id"
                            class="flex items-center space-x-4">
                            <div class="flex-none w-24 h-24 bg-gray-100 rounded-lg overflow-hidden">
                                <img v-if="producto.imagen_url" :src="`/storage/${producto.imagen_url}`"
                                    alt="Imagen del producto" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-auto">
                                <h2 class="text-lg font-semibold text-gray-900">Producto Id: {{ producto.producto_id }}
                                </h2>
                                <h2 class="text-lg font-semibold text-gray-900">{{ producto.nombre }}</h2>
                                <p class="text-sm text-gray-500">Descripción: {{ producto.descripcion }}</p>
                                <p class="text-sm text-gray-500">Precio: Bs. {{ producto.precio }}</p>
                                <p class="text-sm text-gray-500">SKU: {{ producto.sku }}</p>
                                <p class="text-sm text-gray-500">Descuento: {{ producto.descuento_porcentaje }}%</p>
                                <p class="text-sm text-gray-500">Stock: {{
                                    getProductoDetalle(producto.producto_id)?.stock ||
                                    'No disponible' }}</p>
                            </div>
                            <button @click="addProduct(producto)"
                                class="bg-blue-500 text-white px-3 py-1 rounded-lg hover:bg-blue-600 transition">Agregar</button>
                        </li>
                    </ul>
                </div>
            </div>
            <div>
                <h1 class="text-2xl font-bold mb-4">Descuentos por Temporada</h1>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <ul class="space-y-4">
                        <li v-for="descuento in Descuentos" :key="descuento.descuento_id"
                            class="flex justify-between items-center">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">{{ descuento.nombre }}</h2>
                                <p class="text-sm text-gray-500">{{ descuento.descripcion }}</p>
                                <p class="text-sm text-gray-500"> Valor: Bs. {{ descuento.valor }}</p>
                                <p class="text-sm text-gray-500"> Fecha de inicio: {{ descuento.fecha_inicio }}</p>
                                <p class="text-sm text-gray-500"> Fecha de caducidad: {{ descuento.fecha_fin }}</p>
                            </div>
                            <button @click="applyDiscount(descuento)"
                                class="bg-green-500 text-white px-3 py-1 rounded-lg hover:bg-green-600 transition">Aplicar</button>
                        </li>
                    </ul>
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
