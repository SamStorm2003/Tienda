<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
</script>

<template>
  <AppLayout title="Dashboard">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <i class="fas fa-home mr-2"></i> Inicio
      </h2>
    </template>

    <div class="py-10">
      <div class="">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <Welcome />
        </div>
      </div>
    </div>
  </AppLayout>
  <footer class="bg-gray-800 text-white py-8">
    <div class="flex justify-center space-x-8">
        <a href="https://www.instagram.com/" target="_blank">
          <i class="fab fa-instagram text-4xl hover:opacity-75"></i>
        </a>
        <a href="https://www.tiktok.com/" target="_blank">
          <i class="fab fa-tiktok text-4xl hover:opacity-75"></i>
        </a>
        <a href="https://wa.me/59167177161?text=Más%20información,%20por%20favor." target="_blank">
          <i class="fab fa-whatsapp text-4xl hover:opacity-75"></i>
        </a>
      </div>
    <div class="container mx-auto text-center mt-4">
      <p>&copy;Todos los derechos reservados. Shatoria Desing Next</p>
    </div>
  </footer>
</template>
