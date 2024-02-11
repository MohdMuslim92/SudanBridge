<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';


</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12 flex justify-center">
            <div class="max-w-3xl w-full bg-white overflow-hidden shadow-sm sm:rounded-lg">
                You are now logged in!
            </div>
        </div>
    </AuthenticatedLayout>
</template>
