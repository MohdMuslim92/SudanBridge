<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from "vue";
import Footer from "@/Pages/footer.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});

</script>
<template>
    <Head title="About Us - SudanBridge" />
    <AuthenticatedLayout>
        <div class="min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white flex flex-col justify-center items-center sm:flex-row sm:justify-between">
            <!-- About Us Content -->
            <div class="px-6 py-12 sm:w-3/4 text-center">
                <h1 class="text-4xl font-bold mb-4 text-blue-600">About Us</h1>
                <p class="text-lg text-gray-700 dark:text-gray-300">SudanBridge is a leading logistics and transportation company dedicated to providing efficient shipping solutions for individuals and businesses alike. With our state-of-the-art tracking system and extensive network of partners, we ensure that your packages reach their destinations safely and on time. Our commitment to excellence drives us to continuously innovate and improve our services, making shipping simpler and more reliable for our customers.</p>
                <h2 class="text-2xl font-semibold mt-8 mb-4 text-blue-600">Our Mission</h2>
                <p class="text-lg text-gray-700 dark:text-gray-300">Our mission at SudanBridge is to redefine the logistics industry by delivering exceptional value and service to our customers. We strive to be the preferred choice for shipping needs, offering cost-effective solutions without compromising on quality or reliability.</p>
                <h2 class="text-2xl font-semibold mt-8 mb-4 text-blue-600">Our Team</h2>
                <p class="text-lg text-gray-700 dark:text-gray-300">Our team consists of dedicated professionals who are passionate about delivering superior shipping experiences. From our customer support specialists to our logistics experts, everyone at SudanBridge shares a common goal of exceeding customer expectations and building long-lasting relationships.</p>
            </div>
        </div>
        <Footer></Footer>
    </AuthenticatedLayout>
</template>
<style scoped>
/* Add styles for visual effects */
.text-blue-600 {
    color: #3182ce; /* Blue color */
}

/* Animation for heading */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.text-4xl {
    animation: fadeInUp 1s ease-out; /* Apply animation */
}

/* Animation for paragraphs */
.text-lg {
    animation: fadeInUp 1s ease-out; /* Apply animation */
    animation-delay: 0.3s; /* Delay animation */
}
</style>
