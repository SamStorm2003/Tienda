<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { ref, computed, watchEffect, onMounted, onBeforeUnmount } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

const page = usePage();
const user = computed(() => page.props.auth.user);

const missingFields = computed(() => !user.value.apellido || !user.value.genero || !user.value.telefono || !user.value.documento_identidad || !user.value.ciudad || !user.value.direccion);

const showModal = ref(false);
watchEffect(() => {
  if (missingFields.value) {
    showModal.value = true;
  }
});

const goToProfile = () => {
  Inertia.visit(route('profile.show'));
};


// Carrusel
const brands = [
  { name: 'Shein', image: '/Images/shein.webp' },
  { name: 'Bershka', image: '/Images/bershka.webp' },
  { name: 'Adidas', image: '/Images/adidas.webp' }
];

const currentIndex = ref(0);
let intervalId = null;

const startCarousel = () => {
  intervalId = setInterval(() => {
    currentIndex.value = (currentIndex.value + 1) % brands.length;
  }, 5000);
};

const goToBrand = (index) => {
  currentIndex.value = index;
};

const goToProducts = () => {
    window.location.href = route('Productos.index');
};


onMounted(() => {
  startCarousel();
});

onBeforeUnmount(() => {
  clearInterval(intervalId);
});
</script>

<template>
  <!-- Banner Principal con Botón de Compra -->
  <div class="relative z-0">
    <img 
      src="/images/baneer.jpg" 
      alt="Colección Otoño Invierno" 
      class="w-full h-auto min-h-[100px] sm:min-h-[200px] md:min-h-[300px] object-cover" 
    />
    <div class="absolute inset-0 flex items-center justify-center">
      <div class="text-center">
        <h1 class="text-gray-100 text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold">SHATORIA DESING</h1>
        <button 
          @click="goToProducts"
          class="mt-4 bg-black text-white px-4 py-2 sm:px-6 sm:py-3 rounded-full font-semibold hover:bg-blue-400 transition duration-300 ease-in-out"
        >
          Eres lo mas importante para nosotros
        </button>
      </div>
    </div>
  </div>

  <!-- Responsive Brand Carousel -->
  <div class="py-4 sm:py-6 lg:py-8 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <h2 class="text-2xl font-bold mt-6 mb-4 text-gray-800 text-center">Nuestras Marcas</h2>
      
      <div class="relative">
        <!-- Brand Display -->
        <div class="overflow-hidden">
          <transition name="fade">
            <div v-if="brands[currentIndex]" class="flex justify-center items-center mb-4">
              <img :src="brands[currentIndex].image" :alt="brands[currentIndex].name" 
                   class="h-32 sm:h-40 md:h-48 w-auto object-contain transition-all duration-300">
            </div>
          </transition>
        </div>

        <!-- Navigation Dots -->
        <div class="flex justify-center mt-3">
          <button v-for="(_, index) in brands" :key="index"
                  @click="goToBrand(index)"
                  class="mx-1 w-2 h-2 rounded-full focus:outline-none transition-colors duration-200 ease-in-out"
                  :class="index === currentIndex ? 'bg-blue-600' : 'bg-gray-300'">
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Sección Destacados -->
 <div class="py-12 bg-gray-100">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
    <h2 class="text-3xl font-bold text-gray-800 mb-8">Productos Destacados</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
  
      <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="h-48 w-full overflow-hidden mb-4">
          <img src="/images/buenacalidad.jpg" alt="" class="w-full h-full object-cover">
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-2">PRENDAS DE LA MEJOR CALIDAD</h3>
        <p class="text-sm text-gray-500 mb-4">Las novedades de las mejores marcas</p>
        <a href="#" class="text-blue-600 hover:text-blue-800 font-semibold">Ver más</a>
      </div>

      <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="h-48 w-full overflow-hidden mb-4">
          <img src="/images/Saco.webp" alt="" class="w-full h-full object-cover">
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-2">ROPA FORMAL</h3>
        <p class="text-sm text-gray-500 mb-4">La mejor tela y ropa para gente decente</p>
        <a href="#" class="text-blue-600 hover:text-blue-800 font-semibold">Ver más</a>
      </div>

      <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="h-48 w-full overflow-hidden mb-4">
          <img src="/images/tendencia.jpg" alt="" class="w-full h-full object-cover">
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-2">NUEVA MODA</h3>
        <p class="text-sm text-gray-500 mb-4">La mejor moda del momento</p>
        <a href="#" class="text-blue-600 hover:text-blue-800 font-semibold">Ver más</a>
      </div>

      <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="h-48 w-full overflow-hidden mb-4">
          <img src="/images/propio.webp" alt="" class="w-full h-full object-cover">
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-2">PRODUCTOS PROPIOS</h3>
        <p class="text-sm text-gray-500 mb-4">Los outfits más frescos y nuevos</p>
        <a href="#" class="text-blue-600 hover:text-blue-800 font-semibold">Ver más</a>
      </div>
    </div>
  </div>
</div>



  <!-- Sección Acerca de Nosotros -->
 <div class="py-8 sm:py-12 bg-gray-100 font-sans text-center">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Acerca de Nosotros</h2>
    <p class="text-lg text-gray-700 mb-4 leading-relaxed">
      Bienvenido a <strong>Shatoria Design</strong>, tu tienda online de ropa de calidad para hombres, mujeres y niños. 
      Nos especializamos en las últimas tendencias de moda, combinando comodidad, elegancia y estilo único.
    </p>
    <p class="text-lg text-gray-700 leading-relaxed">
      Ya sea que busques ropa casual, elegante o deportiva, en <strong>Shatoria Design</strong> encontrarás lo que necesitas. 
      Nos comprometemos con la satisfacción del cliente, ofreciendo productos de alta calidad a precios accesibles. 
      ¡Explora nuestra colección y descubre tu mejor estilo solo para ti!jj
    </p>
  </div>
</div>

  <!-- Aqui se encuentra la Ubicación -->
  <div class="py-10 sm:py-14 bg-white text-center font-sans">
  <h2 class="text-4xl font-extrabold mb-6 text-gray-800">Nuestra Sucursal</h2>
  <div class="max-w-4xl mx-auto space-y-4 text-lg text-gray-700">
    <p>
      <i class="fas fa-map-marker-alt text-pink-500 mr-2"></i>
      <strong>Dirección:</strong> Local 18, Centro de Moda, Calle 21, San Miguel, La Paz
    </p>
    <p>
      <i class="fas fa-phone-alt text-green-500 mr-2"></i>
      <strong>Teléfono:</strong> <a href="tel:+59167177161" class="hover:underline">+591 671-771-61</a>
    </p>
    <p>
      <i class="fas fa-envelope text-blue-500 mr-2"></i>
      <strong>Email:</strong> 
      <a href="mailto:ShatoriaDesign@gmail.com" class="text-blue-600 hover:underline">ShatoriaDesign@gmail.com</a>
    </p>
  </div>
</div>


  <div>
    <!-- Mensaje de advertencia -->
    <transition name="fade">
      <div v-if="showModal" class="alert">
        Para mejor experiencia, debe completar el registro.
        <div>
          <button @click="goToProfile" class="button">Ir a Perfil</button>
        </div>
      </div>
    </transition>
  </div>
</template>

<style scoped>
.alert {
  background: linear-gradient(135deg, #9ae4e0, #b1ebf3);
  color: black;
  padding: 20px;
  margin: 20px 0;
  border-radius: 8px;
  text-align: center;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  animation: slide-in 0.5s ease-in-out;
}

.button {
  background-color: #dfeb5b;
  border: none;
  color: white;
  padding: 10px 20px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin-top: 10px;
  cursor: pointer;
  border-radius: 4px;
  transition: background-color 0.3s ease; 
}

.button:hover {
  background-color: #45a049; 
}

@keyframes slide-in {
  from {
    transform: translateY(-20px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s;
}

.fade-enter, .fade-leave-to {
  opacity: 0;
}

footer img {
  transition: opacity 0.3s ease;
}

footer img:hover {
  opacity: 0.75; 
}

@media (max-width: 640px) {
  .destacados img {
    width: 75%; 
    height: auto; 
    margin: 0 auto; 
  }
}
</style>