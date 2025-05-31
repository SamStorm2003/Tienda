<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const isMenuOpen = ref(false);

function toggleMenu() {
    isMenuOpen.value = !isMenuOpen.value;
}
</script>

<template>
  <div class="min-h-screen bg-gradient-to-b from-white to-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
      <!-- Encabezado con estilo mejorado -->
      <div class="text-center mb-12">
        <h1 class="text-4xl font-extrabold text-gray-800 mb-4 tracking-tight">
          Bienvenido a <span class="text-purple-600">Shatoria Desing</span>
        </h1>
        <p class="text-gray-600 text-xl max-w-2xl mx-auto">
          Explora nuestra colección de ropa moderna y elegante para todas las ocasiones.
        </p>
      </div>

      <!-- Secciones de productos con altura fija -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
        <div class="bg-white rounded-xl shadow-md overflow-hidden transition-transform duration-300 hover:shadow-lg hover:-translate-y-1 h-full">
          <div class="h-64 overflow-hidden">
            <img src="/Images/Saco.webp" alt="Nueva Llegada" class="w-full h-full object-cover">
          </div>
          <div class="p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-2">Nuevas Llegadas</h3>
            <p class="text-gray-600">Descubre lo último en moda que hemos traído para ti esta temporada.</p>
          </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-md overflow-hidden transition-transform duration-300 hover:shadow-lg hover:-translate-y-1 h-full">
          <div class="h-64 overflow-hidden">
            <img src="/Images/buenacalidad.jpg" alt="Oferta Especial" class="w-full h-full object-cover">
          </div>
          <div class="p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-2">Ofertas Especiales</h3>
            <p class="text-gray-600">Promociones limitadas y descuentos únicos para nuestros clientes.</p>
          </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-md overflow-hidden transition-transform duration-300 hover:shadow-lg hover:-translate-y-1 h-full">
          <div class="h-64 overflow-hidden">
            <img src="/Images/tendencia.jpg" alt="Mejor Valorados" class="w-full h-full object-cover">
          </div>
          <div class="p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-2">Mejor Valorados</h3>
            <p class="text-gray-600">Los productos favoritos y más vendidos escogidos por nuestros clientes.</p>
          </div>
        </div>
      </div>

      <!-- Botones de login y registro con estilo mejorado -->
      <div class="bg-white rounded-xl shadow-md p-8 text-center max-w-2xl mx-auto">
        <p class="text-gray-700 text-lg mb-6 font-medium">
          ¿Quieres acceder a tu carrito, historial de compras y realizar pedidos?
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
          <Link
            href="/login"
            class="bg-purple-600 hover:bg-purple-700 text-white font-medium py-3 px-6 rounded-lg shadow transition-all duration-300 flex items-center justify-center"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.771 7H5V5h10v7H8.771z" clip-rule="evenodd" />
            </svg>
            Iniciar sesión
          </Link>
          <Link
            href="/register"
            class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-6 rounded-lg shadow transition-all duration-300 flex items-center justify-center"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
              <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6z" />
              <path d="M16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
            </svg>
            Registrarse
          </Link>
        </div>
      </div>
    </div>
  </div>
</template>