<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Footer from "@/Pages/footer.vue";

// Define a reactive reference to store user status ID
const userStatusId = ref(0);
// Define message as a ref to make it reactive
const message = ref('');

// Function to fetch user status on component mount
onMounted(async () => {
    try {
        // Fetch user status from API
        const response = await axios.get('/on-hold-status');
        // Update userStatusId value with fetched data
        userStatusId.value = response.data.user_status_id;

        // Update message after fetching user status
        message.value = getUserStatusMessage(userStatusId.value);
    } catch (error) {
        // Handle error if fetching user status fails
        alert('Error occurred, please reload the page');
    }
});

// Function to get user status message based on userStatusId
const getUserStatusMessage = (userStatusId) => {
    switch (userStatusId) {
        case 1:
            return 'Your account is pending approval.';
        case 3:
            return 'Your account has been suspended.';
        case 4:
            return 'Your account is inactive.';
        case 5:
            return 'Your account has been deleted.';
        default:
            return 'Your account is currently on hold.';
    }
};
</script>

<template>
    <!-- Set the title for the page -->
    <Head title="On Hold" />

    <!-- Use the authenticated layout -->
    <AuthenticatedLayout>
        <template #header>
            <!-- Display a header with attention message -->
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Attention!</h2>
        </template>

        <div class="py-6 flex justify-center">
            <!-- Create a container with maximum width and shadow for message display -->
            <div class="max-w-lg px-6 py-4 bg-white shadow-md rounded-lg">
                <!-- Display the dynamic message -->
                <p class="text-lg text-gray-700">{{ message }}</p>
            </div>
        </div>
    </AuthenticatedLayout>
    <!-- Include the footer component -->
    <Footer></Footer>
</template>
