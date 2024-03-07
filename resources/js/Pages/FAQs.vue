<script setup>
import { Head } from '@inertiajs/vue3';
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
    <Head title="FAQs - SudanBridge" />
    <AuthenticatedLayout>
        <div class="min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white flex flex-col justify-center items-center sm:flex-row sm:justify-between">
            <!-- FAQs Content -->
            <div class="px-6 py-12 sm:w-3/4 text-left">
                <!-- Heading -->
                <h1 class="text-4xl font-bold mb-8 text-blue-600 max-w-lg mx-auto">Frequently Asked Questions</h1>

                <!-- FAQ List -->
                <div class="max-w-lg mx-auto text-left">
                    <!-- FAQ Item 1 -->
                    <div class="mb-6">
                        <h2 class="text-lg font-semibold mb-2">What payment methods do you accept?</h2>
                        <p>Currently we accept payments via bank transfer and in cash.</p>
                    </div>

                    <!-- FAQ Item 2 -->
                    <div class="mb-6">
                        <h2 class="text-lg font-semibold mb-2">How can I track my order?</h2>
                        <p>Once your order has been shipped, you will receive a tracking number via email. You can use this tracking number to track your order on our website.</p>
                    </div>

                    <!-- FAQ Item 3 -->
                    <div class="mb-6">
                        <h2 class="text-lg font-semibold mb-2">Do you offer international shipping?</h2>
                        <p>No, we currently work locally within Sudan but in the future we are planning to expand our services outside Sudan.</p>
                    </div>

                    <!-- FAQ Item 4 -->
                    <div class="mb-6">
                        <h2 class="text-lg font-semibold mb-2">Can someone else other than the original recipient receive the shipment?</h2>
                        <p>Yes sure, but he or she should present his/her ID, the original recipient ID copy and the email containing the shipment details.</p>
                    </div>
                </div>
            </div>
        </div>
        <Footer></Footer>
    </AuthenticatedLayout>
</template>

<style scoped>
.text-blue-600 {
    color: #3b82f6; /* Blue color */
}

/* hover effect */
.mb-6:hover {
    background-color: rgba(59, 130, 246, 0.1); /* Light blue background on hover */
}

/* custom font */
h1, h2, p {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
</style>
