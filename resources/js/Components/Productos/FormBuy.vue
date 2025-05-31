<script setup>
import { ref, defineProps, computed, onBeforeUnmount, watch } from 'vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Swal from 'sweetalert2';
import axios from 'axios';
import { useRouter } from 'vue-router';
import { Inertia } from '@inertiajs/inertia';
import { usePage, Head, Link, router } from '@inertiajs/vue3';

const props = defineProps({
    form: {
        type: Object,
        required: true,
        default: () => ({ errors: {} }),
    },
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

const detailedProduct = computed(() => {
    const productDetail = props.Productos_detalles.find(detail => detail.producto_id === props.Producto.producto_id);
    const color = props.Colores.find(c => c.color_id === productDetail.color_id);
    const talla = props.Tallas.find(t => t.talla_id === productDetail.talla_id);
    const proveedor = props.Proveedores.find(p => p.proveedor_id === props.Producto.proveedor_id);
    const subcategoria = props.Subcategorias.find(s => s.subcategoria_id === props.Producto.subcategoria_id);
    const productoDescuentos = props.ProductoDescuentos
        .filter(pd => pd.producto_id === props.Producto.producto_id)
        .map(pd => {
            const d = props.Descuentos.find(d => d.descuento_id === pd.descuento_id);
            if (d) {
                const hoy = new Date();
                const fechaFin = new Date(d.fecha_fin);

                if (d.activo === 1 && hoy <= fechaFin) {
                    return { ...d, nombre_producto: props.Producto.nombre };
                } else {
                    return {
                        ...d,
                        nombre: d.nombre,
                        descripcion: 'Descuento Vencido',
                        activo: 0
                    };
                }
            }
            return null;
        })
        .filter(Boolean);
    const descuentoPorcentaje = {
        nombre: 'Descuento del Producto',
        valor: props.Producto.descuento_porcentaje,
        tipo: 'Porcentaje',
        descripcion: `Descuento del ${props.Producto.descuento_porcentaje}% en este producto`
    };
    return {
        ...props.Producto,
        color: color ? color.nombre : 'No especificado',
        color_id: color ? color.color_id : null,
        talla: talla ? talla.nombre : 'No especificado',
        talla_id: talla ? talla.talla_id : null,
        proveedor: proveedor ? proveedor.nombre : 'No especificado',
        subcategoria: subcategoria ? subcategoria.nombre : 'No especificado',
        descuentos: [descuentoPorcentaje, ...productoDescuentos],
        stock: productDetail ? productDetail.stock : 0
    };
});

const cancel = () => {
    window.location.href = route('Productos.index');
};
const cantidad = ref(1);

const subtotal = computed(() => {
    const precio = Number(detailedProduct.value.precio);
    return isNaN(precio) ? 0 : (precio * cantidad.value).toFixed(2);
});

const couponDiscount = ref(0);
const couponCode = ref('');
const mensaje = ref('');

const verificarCupon = async (producto_id) => {
    if (!couponCode.value) {
        mensaje.value = 'Por favor, ingresa un código de cupón.';
        return;
    }
    try {
        const response = await axios.get(route('Cupones.show', { Cupone: couponCode.value, producto_id }));
        couponDiscount.value = response.data.valido ? Number(response.data.cupon.descuento) || 0 : 0; // Cambia esto
        mensaje.value = response.data.valido ? `Cupón válido: ${response.data.cupon.descripcion}` : response.data.mensaje;
    } catch (error) {
        mensaje.value = 'Error al verificar el cupón';
    }
};

const descuentoAplicado = computed(() => {
    let descuentoTotal = 0;

    const descuentoProducto = Number(props.Producto.descuento_porcentaje) || 0;
    if (descuentoProducto > 0) {
        descuentoTotal += (Number(subtotal.value) * (descuentoProducto / 100));
    }

    const descuentoPromocional = detailedProduct.value.descuentos.find(desc => desc.tipo === 'Porcentaje' && desc.activo === 1);
    if (descuentoPromocional) {
        const porcentajePromocional = Number(descuentoPromocional.valor) || 0;
        if (porcentajePromocional > 0) {
            descuentoTotal += (Number(subtotal.value) * (porcentajePromocional / 100));
        }
    }

    const couponDiscountValue = Number(couponDiscount.value) || 0;
    if (couponDiscountValue > 0) {
        descuentoTotal += (Number(subtotal.value) * (couponDiscountValue / 100));
    }

    return descuentoTotal.toFixed(2);
});

const total = computed(() => {
    const subtotalValue = Number(subtotal.value) || 0;
    const descuentoTotal = Number(descuentoAplicado.value) || 0;
    const shippingTotal = Number(shippingPrice.value) || 0;
    const totalValue = subtotalValue - descuentoTotal + shippingTotal;
    return totalValue.toFixed(2);
});

const selectedCity = ref(null);
const shippingPrice = ref(0);
const cities = props.Envios.map(envio => ({
    ciudad: envio.ciudad,
    precio: Number(envio.precio)
}));
const handleCityChange = (city) => {
    selectedCity.value = city;
    const selected = cities.find(envio => envio.ciudad === city);
    shippingPrice.value = selected ? selected.precio : 0;
};
if (cities.length > 0) {
    handleCityChange(cities[0].ciudad);
}
watch(selectedCity, handleCityChange);
watch(cantidad, (newValue) => {
    if (newValue > detailedProduct.value.stock) {
        Swal.fire('Error', `No puedes comprar más de ${detailedProduct.value.stock} unidades.`, 'error'); // Mensaje de error
        cantidad.value = detailedProduct.value.stock;
    }
});

const comprarProducto = () => {
    const productoData = {
        nombre: detailedProduct.value.nombre,
        descripcion: detailedProduct.value.descripcion,
        precio: detailedProduct.value.precio,
        proveedor: detailedProduct.value.proveedor,
        subcategoria: detailedProduct.value.subcategoria,
        color: detailedProduct.value.color_id,
        talla: detailedProduct.value.talla_id,
        subtotal: subtotal.value,
        ciudad: selectedCity.value,
        envio: shippingPrice.value,
        descuento: descuentoAplicado.value,
        total: total.value,
        cantidad: cantidad.value,
    };

    Inertia.visit(route('productos.comprar', { producto: props.Producto.producto_id }), {
        data: productoData,
        method: 'get'
    });
};
// {{ Producto }}
// <h1>Proveedores</h1>
// {{ Proveedores}}
// <h1>Subcategorias</h1>
// {{ Subcategorias}}
// <h1>Descuentos</h1>
// {{ Descuentos }}
// <h1>Productos Descuetos</h1>
// {{ ProductoDescuentos }}
// <h1>Productos Detalles</h1>
// {{ Productos_detalles }}
// <h1>Colores</h1>
// {{ Colores }}
// <h1>Tallas</h1>
// {{ Tallas }}-->

const page = usePage();
const userId = page.props.auth.user.id;
const direccion = page.props.auth.user.direccion;

const agregarAlCarrito = async () => {
    try {
        const carritoResponse = await axios.post(route('carrito.agregar'), {
            usuario_id: userId
        });
        
        const detalleData = {
            carrito_id: carritoResponse.data.carrito_id,
            producto_detalle_id: detailedProduct.value.producto_id,
            cantidad: cantidad.value,
            precio_con_descuento: total.value,
            subtotal: subtotal.value,
            costo_envio: shippingPrice.value,
            direccion_envio: direccion,
            ciudad_envio: selectedCity.value
        };

        const detalleResponse = await axios.post(route('detalleCarrito.store'), detalleData);

        Swal.fire({
            title: 'Éxito',
            text: `${detalleResponse.data.message}`,
            icon: 'success'
        }).then(() => {
            router.replace(route('Carrito.index'));
        });
    } catch (error) {
        Swal.fire({
            title: 'Error',
            text: 'Ocurrió un problema al agregar al carrito.',
            icon: 'error'
        });
    }
};


</script>



<!-- diseñooooooo -->
<template>
    <div class="relative min-h-screen bg-cover bg-center bg-no-repeat bg-fixed" style="background-image: url('/Images/friendship.jpg');">
      <div class="absolute inset-0 bg-black bg-opacity-50"></div>
      <div class="relative min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white bg-opacity-20 backdrop-filter backdrop-blur-lg rounded-lg overflow-hidden w-full max-w-5xl">
          <div class="flex flex-col md:flex-row">
            <!-- Imagen del producto -->
            <div class="w-full md:w-1/3 h-64 md:h-auto relative">
              <img v-if="detailedProduct.imagen_url" 
                   :src="`/storage/${detailedProduct.imagen_url}`" 
                   alt="Imagen del producto" 
                   class="absolute inset-0 w-full h-full object-contain rounded-t-lg md:rounded-l-lg md:rounded-r-none" 
                   loading="lazy" />
            </div>
  
            <!-- Detalles del producto -->
            <div class="w-full md:w-2/3 p-6">
              <h1 class="text-3xl font-bold text-white mb-4">{{ detailedProduct.nombre }}</h1>
              <p class="text-white text-opacity-90 mb-4">{{ detailedProduct.descripcion }}</p>
  
              <div class="grid grid-cols-2 gap-4 mb-4">
                <p class="text-sm font-medium text-white"><strong>Precio:</strong> <span class="text-xl font-semibold">Bs.{{ detailedProduct.precio }}</span></p>
                <p class="text-sm font-medium text-white"><strong>Proveedor:</strong> {{ detailedProduct.proveedor }}</p>
                <p class="text-sm font-medium text-white"><strong>Subcategoría:</strong> {{ detailedProduct.subcategoria }}</p>
                <p class="text-sm font-medium text-white"><strong>Color:</strong> {{ detailedProduct.color }}</p>
                <p class="text-sm font-medium text-white"><strong>Talla:</strong> <span class="font-bold">{{ detailedProduct.talla }}</span></p>
              </div>
  
              <h2 class="text-lg font-semibold text-white mb-2">Descuentos Aplicables</h2>
              <ul class="list-disc list-inside mb-4 text-white">
                <li v-if="detailedProduct.descuentos.length === 0">No hay descuentos aplicables.</li>
                <li v-else v-for="descuento in detailedProduct.descuentos" :key="descuento.descuento_id" class="mb-1">
                  <strong>{{ descuento.nombre }}</strong>: <span class="italic">{{ descuento.descripcion }}</span>
                  <span v-if="descuento.activo === 1" class="block mt-1 text-xs">Válido desde {{ descuento.fecha_inicio }} hasta {{ descuento.fecha_fin }}</span>
                </li>
              </ul>
  
              <!-- Cantidad -->
              <div class="mb-4">
                <label for="cantidad" class="block text-sm font-medium text-white">Cantidad</label>
                <input id="cantidad" type="number" v-model="cantidad" min="1" :max="detailedProduct.stock"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                <InputError :message="form.errors.cantidad" class="mt-2" />
              </div>
  
              <!-- Botones de acción -->
              <div class="flex flex-wrap gap-4">
                <button @click.prevent="comprarProducto"
                        class="w-full sm:w-auto px-6 py-2 font-semibold rounded-md bg-blue-600 text-white hover:bg-blue-700 transition">
                  Comprar
                </button>
                <button @click.prevent="agregarAlCarrito"
                        class="w-full sm:w-auto px-6 py-2 font-semibold rounded-md border border-white text-white hover:bg-white hover:text-blue-600 transition"
                        type="button">
                  Añadir al carrito
                </button>
                <button type="button" @click="cancel"
                        class="w-full sm:w-auto px-6 py-2 font-semibold rounded-md bg-red-500 hover:bg-red-600 text-white">
                  Atrás
                </button>
              </div>
            </div>
          </div>
  
          <!-- Resumen del pedido -->
          <div class="bg-white bg-opacity-30 backdrop-filter backdrop-blur-sm p-6 mt-6 rounded-lg">
            <h2 class="text-xl font-bold text-white mb-4">Resumen del pedido</h2>
            <div class="mb-4">
              <label for="coupon" class="block text-sm font-medium text-white">Código de cupón</label>
              <div class="flex mt-1">
                <input type="text" id="coupon" v-model="couponCode"
                       class="w-48 px-2 py-2 border border-gray-300 rounded-l-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                       placeholder="Ingresa tu cupón" />
                <button @click="verificarCupon(props.Producto.producto_id)"
                        class="px-4 py-2 font-semibold rounded-r-md bg-indigo-500 text-white hover:bg-indigo-600 transition">
                  Verificar
                </button>
              </div>
              <p v-if="mensaje" class="mt-2 text-sm text-indigo-600">{{ mensaje }}</p>
            </div>
  
            <div class="border-t border-gray-200 pt-4">
              <div class="grid grid-cols-2 gap-4 text-sm font-medium text-white">
                <div>Subtotal:</div>
                <div class="text-right">Bs. {{ subtotal }}</div>
  
                <div>
                  <label for="city-select" class="block">Selecciona la ciudad de envío:</label>
                  <select id="city-select" v-model="selectedCity"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-gray-700">
                    <option v-for="city in cities" :key="city.ciudad" :value="city.ciudad">{{ city.ciudad }}</option>
                  </select>
                </div>
                <div class="text-right">
                  <div>Envío:</div>
                  <div class="font-semibold">Bs. {{ shippingPrice.toFixed(2) }}</div>
                </div>
  
                <div v-if="couponDiscount > 0">Cupón:</div>
                <div v-if="couponDiscount > 0" class="text-right">{{ couponDiscount }}%</div>
  
                <div>Descuento:</div>
                <div class="text-right text-green-400">-Bs. {{ descuentoAplicado }}</div>
              </div>
  
              <div class="flex justify-between text-lg font-bold text-white mt-4">
                <span>Total a pagar:</span>
                <span>Bs. {{ total }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  