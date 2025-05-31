<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';

defineProps({
    policy: String,
});
</script>

<template>
    <Head title="Privacy Policy" />

    <div class="font-sans text-gray-100 antialiased bg-gradient-to-br from-sky-900 via-slate-800 to-sky-950 min-h-screen">
        <div class="pt-4">
            <div class="min-h-screen flex flex-col items-center pt-10">
                <div class="mb-6">
                    <AuthenticationCardLogo />
                </div>

                <div class="w-full sm:max-w-2xl mt-6 p-8 bg-white/90 text-gray-900 shadow-2xl ring-1 ring-sky-800 backdrop-blur-md rounded-2xl prose max-w-full" v-html="policy" />
            </div>
        </div>
    </div>
</template>

