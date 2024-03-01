<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import {onMounted, ref} from 'vue';
import axios from "axios";
import Footer from './footer.vue';

const token = ref('');
const cities = ref('');
const shipments = ref('');
const routeCities = ref('');
const currentCityPosition = ref('');

onMounted(async () => {
    try {
        // Fetch cities
        const citiesResponse = await axios.get('/api/cities');
        cities.value = citiesResponse.data;
    } catch (error) {
        console.error('Error fetching data:', error);
    }

});

const searchByToken = async () => {
    try {
        // Fetch shipment details from the backend using the provided token
        const shipmentsResponse = await axios.get(`/api/shipments/${token.value}`);
        shipments.value = shipmentsResponse.data;
        routeCities.value = findRoute(shipments.value.facility.location, shipments.value.recipient.facility.location, cities.value);
        currentCityPosition.value = routeCities.value.indexOf(shipments.value.user.facility.location);

        // Ensure that the current city is found in the routeCities array
        if (currentCityPosition.value !== -1) {
        } else {
            alert('Current city not found in the route');
        }
    } catch (error) {
        alert('Wrong token number, please enter correct token');
    }
}

function findRoute(startingPoint, destinationPoint, cities) {
    // Check if cities is an array and convert to an array if it's not
    if (!Array.isArray(cities)) {
        // Log an error and return an empty route
        console.error("Cities data is not an array");
        return [];
    }

    // Initialize an empty list to store the route
    let route = [];

    // Find the starting city in the cities list
    let startingCity = cities.find(city => city.city_name === startingPoint);

    // Check if the starting city exists
    if (!startingCity) {
        console.error("Starting city not found in the cities data");
        return [];
    }

    // Add the starting city to the route
    route.push(startingCity.city_name);


    // Iterate through the cities until the destination point is reached or the route becomes cyclic
    while (true) {
        // Check if the current city is the destination point
        if (startingCity.city_name === destinationPoint) {
            // Destination reached, break the loop
            break;
        }

        // Move to the next city
        let nextCity = cities.find(city => city.city_name === startingCity.next_city);

        // Check if the next city exists
        if (!nextCity || route.includes(nextCity.city_name)) {
            // Cyclic route or destination not found, break the loop
            console.error("Cyclic route or destination not found");
            break;
        }

        // Add the next city to the route
        route.push(nextCity.city_name);

        // Update the starting city to the next city
        startingCity = nextCity;
    }

    return route;
}

function fillingLineWidth(index) {
    // Calculate the percentage width of the filling line based on the current city position
    if (typeof currentCityPosition.value !== 'undefined') {
        if (index <= currentCityPosition.value) {
            return 100 / routeCities.value.length * (index + 1);
        } else {
            return 0;
        }
    } else {
        return 0;
    }
}
</script>

<template>
    <Head title="Tracking" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Package Tracking</h2>
        </template>

        <div class="py-6 flex justify-center h-96">
            <div class="max-w-3xl w-full bg-white rounded-lg shadow-md overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <span class="text-lg font-semibold text-gray-800">Track Your Package</span>
                </div>
                <div class="flex justify-center pt-8">
                    <div class="search-box">
                        <input type="text" v-model="token" placeholder="Enter token" class="border border-gray-300 p-1 rounded-lg mr-2">
                        <button @click="searchByToken" class="search-button bg-blue-500 text-white px-4 py-1 rounded-lg">Track</button>
                    </div>
                </div>
                <div v-if="shipments">
                </div>
                <!-- Mockup of tracking route -->
                <div class="flex justify-center items-center mt-8 relative">
                    <!-- Rendering cities dynamically from routeCities -->
                    <template v-for="(city, index) in routeCities" :key="index">
                        <!-- Calculate the width of each city block -->
                        <div class="city relative flex flex-col items-center" :style="{ width: 100 / routeCities.length + '%' }">
                            <div class="w-4 h-4 rounded-full"
                                 :class="{ 'bg-blue-500': index < currentCityPosition, 'bg-white': index === currentCityPosition || index < currentCityPosition }">
                            </div>
                            <div class="mt-1" :class="{ 'text-green-400': index < currentCityPosition, 'text-white': index === currentCityPosition || index < currentCityPosition, 'font-extrabold': index < currentCityPosition }">{{ city }}</div>
                        </div>
                        <!-- Filling line between cities -->
                        <div v-if="index < routeCities.length" class="filling-line" :style="{ width: fillingLineWidth(index) + '%' }"></div>
                        <div v-if="index === currentCityPosition" class="status-info pb-9">
                            <div class="text-sm font-medium text-gray-600 pt-4">{{ shipments.status.name }}</div>
                            <div class="text-sm font-medium text-gray-600 pb-3">{{ new Date(shipments.updated_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) }}</div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
    <Footer></Footer>
</template>

<style scoped>
.filling-line {
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    background-color: blue;
    z-index: 1;
}

.status-info {
    position: static;
    width: 7em;
    text-align: center;
    transform: translateX(-50%);
    background-color: white;
    padding: 8px;
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    z-index: 2;
}

.city {
    font-weight: bolder;
    font-size: 1em;
    text-align: center;
    z-index: 5;
}
</style>
