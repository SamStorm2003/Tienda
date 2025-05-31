<script>
import { useForm } from '@inertiajs/vue3';
import { loadStripe } from '@stripe/stripe-js';
import Swal from 'sweetalert2';
import { Inertia } from '@inertiajs/inertia';
import AppLayout from '@/Layouts/AppLayout.vue';
import { usePage, Head, Link, router } from '@inertiajs/vue3';
import { ref, defineProps, computed, onBeforeUnmount, watch } from 'vue';
import jsPDF from 'jspdf';
import Footer from '@/Layouts/Footer.vue';
export default {
    components: {
        AppLayout
    },
    props: ['producto', 'subtotal', 'total', 'ciudad', 'envio', 'descuento', 'cantidad', 'detalle_carrito_id', 'carrito_id'],
    data() {
        return {
            form: useForm({
                nombre: ''
            }),
            stripe: null,
            cardElement: null,
            clientSecret: null,
        };
    },
    setup() {
        const { props } = usePage();
        const userId = computed(() => props.auth.user.id);
        const userName = computed(() => props.auth.user.name);
        const userApellido = computed(() => props.auth.user.apellido);
        const userEmail = computed(() => props.auth.user.email);
        const userTelefono = computed(() => props.auth.user.telefono);
        const userDocumentoIdentidad = computed(() => props.auth.user.documento_identidad);
        const userGenero = computed(() => props.auth.user.genero);
        const userCiudad = computed(() => props.auth.user.ciudad);
        const userDireccion = computed(() => props.auth.user.direccion);
        const fullName = computed(() => `${userName.value} ${userApellido.value}`);
        return {
            userId,
            userName,
            userApellido,
            userEmail,
            userTelefono,
            userDocumentoIdentidad,
            userGenero,
            userCiudad,
            userDireccion,
            fullName
        };
    },
    async mounted() {
        this.stripe = await loadStripe('pk_test_51RUdraHD9snYuO3zAkjPJhFREOePEetiOz7Rx3gcgC7PWlAqgTLHBPnUexWV10io5hPoU8tEpJFa0ZMB2Wyahs2M00bRUAfSwV');
        const elements = this.stripe.elements();
        this.cardElement = elements.create('card');
        this.cardElement.mount('#card-element');
    },
    methods: {
        quitarDelCarrito(detalleCarritoId, carritoId) {
            if (detalleCarritoId && carritoId) {
                Inertia.post(route('carritos.comprar_carrito', { detalleCarritoId, carritoId }));
            }
        },
        async procesarPago() {
            try {
                const stockResponse = await axios.post(`/actualizar-stockuser`, {
                    producto_id: this.producto.producto_id,
                    cantidad: this.cantidad,
                });
                console.log('Respuesta del stock:', stockResponse.data);
            } catch (stockError) {
                console.error('Error al actualizar el stock:', stockError.response.data);
                if (stockError.response && stockError.response.status === 400) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Stock insuficiente',
                        text: stockError.response.data.message || 'No hay suficiente stock disponible.',
                        confirmButtonText: 'Aceptar',
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error inesperado',
                        text: 'Hubo un problema al actualizar el stock. Intenta nuevamente.',
                        confirmButtonText: 'Aceptar',
                    });
                }
                return;
            }
            if (!this.userCiudad || !this.userDireccion || !this.userApellido) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Datos Incompletos',
                    text: 'No puede comprar sin tener una dirección para el envío. Complete los datos.',
                    showCancelButton: true,
                    confirmButtonText: 'Completar Perfil',
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.isConfirmed) {
                        router.replace(route('profile.show'));
                    }
                });
                return;
            }
            Swal.fire({
                title: 'Procesando pago...',
                text: 'Por favor, espera mientras procesamos tu pago.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            try {
                this.form.nombre = this.fullName;
                const { data } = await axios.post('/create-payment-intent', {
                    amount: this.total
                });
                this.clientSecret = data.clientSecret;
                const { error, paymentIntent } = await this.stripe.confirmCardPayment(this.clientSecret, {
                    payment_method: {
                        card: this.cardElement,
                        billing_details: {
                            name: this.form.nombre,
                            email: this.userEmail.value,
                            address: {
                                city: this.userCiudad.value,
                                line1: this.userDireccion.value,
                            },
                        },
                    },
                });
                Swal.close();
                if (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al procesar el pago',
                        text: error.message,
                        confirmButtonText: 'Aceptar',
                    });
                } else {
                    const response = await axios.post('/ventas', {
                        usuario_id: this.userId,
                        total: this.total,
                    });
                    await axios.post('/detalle-ventas', {
                        venta_id: response.data.venta_id,
                        producto_id: this.producto.producto_id,
                        cantidad: this.cantidad,
                        precio_unitario: this.producto.precio,
                        precio_con_descuento: this.total,
                        subtotal: this.subtotal,
                        costo_envio: this.envio,
                        direccion_envio: this.userDireccion,
                        ciudad_envio: this.userCiudad
                    });
                    this.generarFactura(paymentIntent.id);
                }
            } catch (error) {
                console.error('Error al procesar el pago:', error);
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Error inesperado',
                    text: 'Hubo un problema al procesar tu pago. Intenta nuevamente.',
                    confirmButtonText: 'Aceptar',
                });
            }
        },
        generarFactura(paymentId) {
            Swal.fire({
                title: 'Generando factura...',
                text: 'Por favor, espera mientras generamos tu factura.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            const doc = new jsPDF();
            doc.setFontSize(22);
            doc.setTextColor(0, 102, 204);
            doc.text('Factura', 105, 20, null, null, 'center');
            doc.setFontSize(14);
            doc.text('Shatoria Design', 105, 30, null, null, 'center');
            doc.setFontSize(10);
            doc.setTextColor(150);
            doc.text('© 2025 Todos los derechos reservados.', 105, 35, null, null, 'center');
            doc.setDrawColor(0);
            doc.setLineWidth(0.5);
            doc.line(10, 40, 200, 40);
            doc.setTextColor(0);
            doc.setFontSize(12);
            const details = [
                `Nombre: ${this.fullName}`,
                `Email: ${this.userEmail}`,
                `Teléfono: ${this.userTelefono}`,
                `Dirección: ${this.userDireccion}`,
                `Ciudad: ${this.userCiudad}`,
                `Documento de Identidad: ${this.userDocumentoIdentidad}`,
                `Género: ${this.userGenero}`
            ];
            let yPosition = 50;
            details.forEach(line => {
                doc.text(line, 14, yPosition);
                yPosition += 10;
            });
            doc.line(10, yPosition, 200, yPosition);
            yPosition += 10;
            doc.setFontSize(16);
            doc.setTextColor(0, 102, 204);
            doc.text('Resumen del Pedido', 14, yPosition);
            yPosition += 10;
            doc.setFontSize(12);
            doc.setTextColor(0);
            const orderSummary = [
                `Producto: ${this.producto.nombre}`,
                `Descripción: ${this.producto.descripcion}`,
                `Precio: Bs. ${this.producto.precio}`,
                `Cantidad: ${this.cantidad}`,
                `Subtotal: Bs. ${this.subtotal}`,
                `Costo de Envío: Bs. ${this.envio}`,
                `Descuento: Bs. ${this.descuento}`,
                `Total: Bs. ${this.total}`,
                `ID del Pago: ${paymentId}`
            ];
            orderSummary.forEach(line => {
                doc.text(line, 14, yPosition);
                yPosition += 10;
            });
            doc.line(10, yPosition, 200, yPosition);
            yPosition += 10;
            doc.setFontSize(10);
            doc.setTextColor(150);
            doc.text('Condiciones y Devoluciones:', 14, yPosition);
            yPosition += 5;
            doc.setTextColor(0);
            const conditionsText = 'Cualquier reclamación o devolución debe ser reportada a la oficina dentro de las 24 horas posteriores a la compra. Para solicitar un reembolso, es necesario acudir a la agencia correspondiente o contactar con el soporte. Agradecemos tu comprensión.';
            doc.text(conditionsText, 14, yPosition, { maxWidth: 190 });

            doc.save(`factura_${paymentId}.pdf`);
            Swal.close();
            this.quitarDelCarrito(this.detalle_carrito_id, this.carrito_id);
            Swal.fire({
                icon: 'success',
                title: 'Factura generada con éxito',
                text: 'Tu factura se ha descargado correctamente.',
                confirmButtonText: 'Aceptar',
            }).then(() => {
                setTimeout(() => {
                    router.replace(route('Carrito.index'));
                }, 300);
            });
        }
    }
};
</script>


<template>
    <AppLayout title="Formulario de Compra">
      <template #header>
        <h2 class="font-semibold text-3xl text-white">Formulario de Compra</h2>
      </template>
      <!-- Imagen de fondo responsiva -->
      <div class="py-12 min-h-screen bg-cover bg-center" style="background-image: url('/Images/propio.webp');">
        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row space-y-6 lg:space-x-6 px-4 sm:px-6 lg:px-8">
          <!-- Formulario principal -->
          <div class="flex-1 bg-white bg-opacity-10 backdrop-filter backdrop-blur-lg rounded-2xl shadow-xl p-8 border border-white border-opacity-30">
            <form @submit.prevent="submit">
              <h3 class="text-2xl font-semibold mb-6 text-white">Detalles de la Compra</h3>
              <div class="bg-white bg-opacity-10 rounded-xl shadow-lg mb-8 p-6">
                <h3 class="text-xl font-semibold mb-4 text-white">Resumen del Pedido</h3>
                <div class="space-y-3 text-white">
                  <p><strong class="font-medium">Nombre:</strong> {{ producto.nombre }}</p>
                  <p><strong class="font-medium">Descripción:</strong> {{ producto.descripcion }}</p>
                  <p><strong class="font-medium">Precio:</strong> Bs. {{ producto.precio }}</p>
                  <p><strong class="font-medium">Subtotal:</strong> Bs. {{ subtotal }}</p>
                  <p><strong class="font-medium">Ciudad de envío:</strong> {{ ciudad }}</p>
                  <p><strong class="font-medium">Envío:</strong> Bs. {{ envio }}</p>
                  <p><strong class="font-medium">Descuento:</strong> Bs. {{ descuento }}</p>
                  <p><strong class="font-medium">Cantidad:</strong> {{ cantidad }}</p>
                </div>
              </div>
  
              <div class="bg-white bg-opacity-10 rounded-xl shadow-lg mb-8 p-6">
                <h3 class="text-xl font-semibold mb-4 text-white">Datos de Usuario</h3>
                <div class="space-y-3 text-white">
                  <p><strong class="font-medium">Nombre:</strong> {{ userName }}</p>
                  <p><strong class="font-medium">Apellido:</strong> {{ userApellido }}</p>
                  <p><strong class="font-medium">Email:</strong> {{ userEmail }}</p>
                  <p><strong class="font-medium">Teléfono:</strong> {{ userTelefono }}</p>
                  <p><strong class="font-medium">Documento de Identidad:</strong> {{ userDocumentoIdentidad }}</p>
                  <p><strong class="font-medium">Género:</strong> {{ userGenero }}</p>
                  <p><strong class="font-medium">Ciudad:</strong> {{ userCiudad }}</p>
                  <p><strong class="font-medium">Dirección:</strong> {{ userDireccion }}</p>
                </div>
              </div>
  
              <p class="text-white text-sm">Por favor, verifica que todos los datos sean correctos antes de continuar con el pago. Si algún dato no es correcto actualizalo en tu perfil.</p>
            </form>
          </div>
  
          <!-- Barra lateral de estado de compra -->
          <aside class="bg-white bg-opacity-20 backdrop-filter backdrop-blur-lg rounded-2xl shadow-xl p-8 border border-white border-opacity-30 lg:w-1/3">
            <h3 class="text-2xl font-semibold mb-6 text-white">Estado de Compra</h3>
            <p class="font-bold mb-4 text-xl text-white"><strong>Total a pagar:</strong> Bs. {{ total }}</p>
            <p class="text-white mb-4"><strong>Nombre:</strong> {{ fullName }}</p>
            <p class="text-white mb-2">Introduzca los datos de su tarjeta:</p>
            <!-- Cuadro de la tarjeta ahora es blanco -->
            <div id="card-element" class="mt-4 bg-white rounded-xl p-4 focus:outline-none"></div>
            <button @click="procesarPago" type="button"
              class="mt-6 w-full px-6 py-3 font-semibold rounded-xl bg-gradient-to-r from-purple-600 to-pink-600 text-white hover:from-purple-700 hover:to-pink-700 transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
              Finalizar Compra
            </button>
  
            <p class="text-red-300 text-sm mt-4 text-center">Verifique bien los datos de su facturación.</p>
          </aside>
        </div>
      </div>
     <Footer/>
    </AppLayout>
</template>


