<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from "vue";

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
});

// Data for the cards
const cards = [
    {
        title: 'Fast and Reliable Service',
        description: 'Get your packages delivered promptly with our efficient and reliable service. Experience swift deliveries that you can trust.',
        image: '/img/feature1.png'
    },
    {
        title: 'Wide Coverage Area',
        description: 'Our delivery service extends far and wide, reaching even the most remote locations. No matter where you are, we\'ve got you covered.',
        image: '/img/feature2.png'
    },
    {
        title: 'Secure Handling',
        description: 'Rest assured, your packages are in safe hands from drop-off to delivery. Our secure handling ensures your items arrive intact and on time.',
        image: '/img/feature3.png'
    },
    {
        title: 'Transparent Tracking',
        description: 'Keep track of your packages every step of the way with our real-time tracking feature. Stay informed and enjoy peace of mind knowing where your delivery is at all times.',
        image: '/img/feature4.png'
    },
    {
        title: 'Exceptional Customer Support',
        description: 'Our dedicated customer support team is here to assist you every step of the way. From inquiries to concerns, we\'re committed to providing prompt and attentive support whenever you need it.',
        image: '/img/feature5.png'
    },
    {
        title: 'Cost-Effective Solutions',
        description: 'Save money without compromising on quality with our cost-effective delivery solutions. Enjoy competitive pricing and value for money that suits your budget.',
        image: '/img/feature6.png'
    }
];

// State for tracking current card index
let currentIndex = ref(0);

let timer = null;

// Automatically switch to the next card every 5 seconds
function startTimer() {
    timer = setInterval(() => {
        currentIndex.value = (currentIndex.value + 1) % cards.length;
    }, 5000);
}

function stopTimer() {
    clearInterval(timer);
}

startTimer();

</script>

<template>
    <Head title="Welcome" />

    <div class="min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white flex flex-col justify-center items-center sm:flex-row sm:justify-between">
        <!-- Navigation Links -->
        <div class="p-6 absolute top-0 right-0 text-start z-10">
            <Link
                v-if="$page.props.auth.user"
                :href="route('dashboard')"
                class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
            >Dashboard</Link>

            <template v-else>
                <Link
                    :href="route('tracking')"
                    class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                >Track Package</Link>

                <Link
                    :href="route('login')"
                    class="pl-3 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                >Contact Us</Link>

                <Link
                    v-if="canRegister"
                    :href="route('register')"
                    class="ms-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                >About Us</Link>
            </template>
        </div>

        <div class="flex flex-col max-w-7xl m-auto sm:flex-row-reverse items-center w-full sm:w-auto">
            <!-- Header Image -->
            <div class="w-full sm:w-2/5 relative z-0 mb-6 sm:mb-0 sm:mr-6">
                <img class="w-full object-contain" src="../../../public/img/header.png" alt="Sudan Bridge Header">
            </div>

            <!-- Header Message and Brief -->
            <div class="w-full sm:w-3/5 p-6 lg:p-8 text-start z-10">
                <h1 class="text-5xl font-semibold text-gray-800 dark:text-gray-200">Reliable Delivery Solutions Across Sudan</h1>
                <p class="mt-4 leading-10 text-xl text-gray-600 dark:text-gray-400">Welcome to Sudan Bridge, the leading delivery service provider in
                    Sudan. We understand the importance of timely deliveries and are dedicated to bringing convenience to your doorstep.
                    Whether it's documents, packages, or everyday essentials, our extensive network ensures prompt and reliable delivery
                    services to every corner of Sudan. With a focus on efficiency and customer satisfaction, we're redefining delivery
                    standards and making it easier than ever to send and receive items across the country.</p>
            </div>
        </div>
    </div>

    <section class="py-16 max-w-6xl m-auto sm:flex-row-reverse items-center w-full sm:w-auto">
        <div class="container mx-auto">
            <h2 class="text-3xl font-semibold mb-8 p-6">Hassle-Free Package Handling</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Step 1: Package Drop-off -->
                <div class="flex flex-col items-center justify-center bg-white rounded-lg shadow-md p-6">
                    <div class="hoverEffect">
                        <img src="/img/step1.png" alt="Package Drop-off" class="w-32 h-32 mb-4">
                        <p class="text-lg font-medium text-center">Package Drop-off</p>
                        <p class="text-gray-600 text-left">Visit one of our convenient drop-off locations and hand over your package to our friendly staff. We'll take care of the rest.</p>
                    </div>
                </div>

                <!-- Step 2: Processing & Preparation -->
                <div class="flex flex-col items-center justify-center bg-white rounded-lg shadow-md p-6">
                    <div class="hoverEffect">
                        <img src="/img/step2.png" alt="Processing & Preparation" class="w-32 h-32 mb-4">
                        <p class="text-lg font-medium text-left">Processing & Preparation</p>
                        <p class="text-gray-600 text-left">Our dedicated team swings into action as soon as your package is received. We carefully process and prepare it for swift delivery.</p>
                    </div>
                </div>

                <!-- Step 3: Swift & Secure Delivery -->
                <div class="flex flex-col items-center justify-center bg-white rounded-lg shadow-md p-6">
                    <div class="hoverEffect">
                        <img src="/img/step3.png" alt="Swift & Secure Delivery" class="w-32 h-32 mb-4">
                        <p class="text-lg font-medium text-left">Swift & Secure Delivery</p>
                        <p class="text-gray-600 text-left">Relax as we swiftly dispatch your package for delivery. Our experienced couriers ensure it reaches its destination securely and on time, with real-time tracking available for your peace of mind.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Services carousel presentation -->
    <section class="py-16  max-w-6xl m-auto sm:flex-row-reverse items-center w-full sm:w-auto">
        <div class="container mx-auto">
            <h2 class="text-3xl font-semibold mb-8 p-6">Explore Our Premier Services</h2>

            <!-- Vue transition component for slide effect -->
            <transition name="fade" mode="out-in">
                <!-- Display cards one by one -->
                <div :key="currentIndex">
                    <!-- Card layout -->
                    <div class="flex flex-col md:flex-row items-center justify-center mb-8"
                         @mouseenter="stopTimer"
                         @mouseleave="startTimer"
                         @touchstart="stopTimer"
                         @touchend="startTimer">
                        <!-- Image part -->
                        <div class="w-full md:w-1/2 border-r md:border-r-0 md:pr-8">
                            <div class="image-container h-64">
                                <img :src="cards[currentIndex].image" alt="Card Image" class="w-full h-full object-contain">
                            </div>
                        </div>
                        <!-- Text part -->
                        <div class="w-full text-2xl md:w-1/2 md:pl-8 mt-4 md:mt-0 p-6">
                            <h3 class="font-semibold mb-2">{{ cards[currentIndex].title }}</h3>
                            <p class="text-gray-600">{{ cards[currentIndex].description }}</p>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </section>

    <footer class="bg-gray-900 text-white py-8">
        <div class="container mx-auto flex flex-col md:flex-row justify-between items-center px-4">
            <div class="flex items-center space-x-4">
                <a href="https://github.com/MohdMuslim92" target="_blank" class="text-gray-300 hover:text-white">
                    <img src="../../../public/svg/github.svg" alt="Github" class="h-6 w-6 invert-colors" />
                </a>
                <a href="https://twitter.com/MohdMuslim92" target="_blank" class="text-gray-300 hover:text-white">
                    <img src="../../../public/svg/X.svg" alt="Twitter X" class="h-6 w-6 invert-colors" />
                </a>
                <a href="https://www.linkedin.com/in/mohdmuslim92/" target="_blank" class="text-gray-300 hover:text-white">
                    <img src="../../../public/svg/linkedin.svg" alt="LinkedIn" class="h-6 w-6 invert-colors" />
                </a>
            </div>
            <nav class="flex flex-wrap justify-center mt-4 md:mt-0">
                <a href="#" class="mx-2 text-gray-300 hover:text-white">About Us</a>
                <a href="#" class="mx-2 text-gray-300 hover:text-white">Contact</a>
                <a href="#" class="mx-2 text-gray-300 hover:text-white">FAQs</a>
            </nav>
        </div>
    </footer>
</template>

<style>
.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}
@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}

/* Visual effect for the steps */
.hoverEffect {
    position: relative;
    transition: transform 0.3s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.hoverEffect:hover {
    transform: scale(1.1);
}
/* Define fade animation for the services */
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.5s;
}
.fade-enter, .fade-leave-to {
    opacity: 0;
}

.invert-colors {
    filter: invert(1);
}

/* Footer styling */
footer {
    height: 100px; /* Set a fixed height for the footer */
    background-color: #333;
    color: #fff; /* Adjust text color */
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 20px;
}

/* Adjust link styling */
footer a {
    color: #fff;
    text-decoration: none;
    margin-right: 10px;
    transition: color 0.3s ease;
}

footer a:hover {
    color: #ccc;
}
</style>
