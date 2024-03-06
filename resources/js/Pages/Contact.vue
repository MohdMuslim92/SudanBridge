<script setup>
import Footer from "@/Pages/footer.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
</script>

<template>
    <AuthenticatedLayout>
        <div class="min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white flex flex-col justify-center items-center sm:flex-row sm:justify-between">
            <!-- Contact Us Content -->
            <div class="px-6 py-12 sm:w-3/4 text-center">
                <!-- Heading -->
                <h1 class="text-4xl font-bold mb-4 text-blue-600">Contact Us</h1>

                <!-- Contact Form -->
                <form class="max-w-lg mx-auto">
                    <!-- Name Input -->
                    <div class="mb-4 text-left">
                        <label for="name" class="block text-lg font-semibold mb-1">Your Name</label>
                        <input type="text" id="name" class="w-full py-2 px-4 border border-gray-300 rounded-md" placeholder="Enter your name" required>
                    </div>

                    <!-- Email Input -->
                    <div class="mb-4 text-left">
                        <label for="email" class="block text-lg font-semibold mb-1">Your Email</label>
                        <input type="email" id="email" class="w-full py-2 px-4 border border-gray-300 rounded-md" placeholder="Enter your email" required>
                    </div>

                    <!-- Message Input -->
                    <div class="mb-4 text-left">
                        <label for="message" class="block text-lg font-semibold mb-1">Your Message</label>
                        <textarea id="message" rows="4" class="w-full py-2 px-4 border border-gray-300 rounded-md" placeholder="Enter your message" required></textarea>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">Send Message</button>
                </form>

                <!-- Contact Information -->
                <div class="mt-8 text-lg text-gray-700 dark:text-gray-300">
                    <p><strong>Email:</strong> info@sudanbridge.com</p>
                    <p><strong>Phone:</strong> +1234567890</p>
                    <p><strong>Address:</strong> 123 Main Street, Khartoum, Sudan</p>
                </div>
            </div>
        </div>
        <Footer></Footer>
    </AuthenticatedLayout>
</template>
