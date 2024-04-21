<script setup>
// Importing necessary components and libraries
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import {onMounted, ref} from 'vue';
import axios from "axios";
import Footer from './footer.vue';

// Initializing reactive variables using ref()
const token = ref('');
const cities = ref('');
const shipments = ref('');
const routeCities = ref('');
const currentCityPosition = ref('');

// Fetching cities data from the backend upon component mount
onMounted(async () => {
    try {
        // Fetch cities
        const citiesResponse = await axios.get('/api/cities');
        cities.value = citiesResponse.data;
    } catch (error) {
        alert('Error fetching data'); // Alerting user in case of error
    }

});

// Function to search shipment details based on token
const searchByToken = async () => {
    try {
        // Fetch shipment details from the backend using the provided token
        const shipmentsResponse = await axios.get(`/api/shipments/${token.value.trim()}`);
        shipments.value = shipmentsResponse.data;
        // Calculating route between facilities
        routeCities.value = findRoute(shipments.value.facility.location, shipments.value.recipient.facility.location, cities.value);
        currentCityPosition.value = routeCities.value.indexOf(shipments.value.current_facility.location);

        // Checking if current city is found in the routeCities array
        if (currentCityPosition.value !== -1) {
            // Current city found
        } else {
            alert('Current city not found in the route');
        }
    } catch (error) {
        alert('Wrong token number, please enter correct token'); // Alerting user for incorrect token
    }
}

// Function to find route between starting and destination points
function findRoute(startingPoint, destinationPoint, cities) {
    // Check if cities is an array and convert to an array if it's not
    if (!Array.isArray(cities)) {
        alert("Cities data is not an array"); // Alerting if cities data is not an array
        return [];
    }

    // Initialize an empty list to store the route
    let route = [];

    // Find the starting city in the cities list
    let startingCity = cities.find(city => city.city_name === startingPoint);

    if (!startingCity) {
        alert("Starting city not found in the cities data"); // Alerting if starting city not found
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
            alert("Cyclic route or destination not found");
            break;
        }

        // Add the next city to the route
        route.push(nextCity.city_name);

        // Update the starting city to the next city
        startingCity = nextCity;
    }

    return route; // Returning the calculated route
}

function fillingLineWidth(index) {
    // Calculate the percentage width of the filling line based on the current city position
    if (typeof currentCityPosition.value !== 'undefined') {
        if (index <= currentCityPosition.value) {
            return 100 / routeCities.value.length * (index + 1); // Calculating percentage width of filling line
        } else {
            return 0; // Filling line width is 0 if index is greater than current city position
        }
    } else {
        return 0; // Filling line width is 0 if current city position is undefined
    }
}
</script>

<template>
    <!-- Setting page title -->
    <Head title="Tracking" />

    <!-- Using authenticated layout -->
    <AuthenticatedLayout>
        <template #header>
            <!-- Header title -->
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Package Tracking</h2>
        </template>

        <div class="py-6 flex justify-center h-96">
            <div class="max-w-3xl w-full bg-white rounded-lg shadow-md overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <!-- Subtitle -->
                    <span class="text-lg font-semibold text-gray-800">Track Your Package</span>
                </div>
                <div class="flex justify-center pt-8">
                    <div class="search-box">
                        <input type="text" v-model="token" placeholder="Enter token" class="border border-gray-300 p-1 rounded-lg mr-2">
                        <!-- Search button -->
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
                        <!-- Conditionally changing filling line color -->
                        <div v-if="index < routeCities.length" class="filling-line" :style="{ width: fillingLineWidth(index) + '%', backgroundColor: shipments.status.name === 'Delivered' ? 'green' : 'blue' }"></div>
                        <div v-if="index === currentCityPosition" class="status-info pb-9">
                            <div class="text-sm font-medium text-gray-600 pt-4">{{ shipments.status.name }}
                                <div>
                                    <div v-if="shipments.status.name === 'Pending'">
                                        <!-- Show processing icon -->
                                        <img src="/gif/pending.gif" alt="Processing">
                                    </div>
                                    <div v-else-if="shipments.status.name === 'In Transit'">
                                        <!-- Show animation of moving truck -->
                                        <img src="/gif/in-transit.gif" alt="Truck Animation">
                                    </div>
                                    <div v-else-if="shipments.status.name === 'Out for Delivery'">
                                        <!-- Show animation of delivery person -->
                                        <img src="/gif/out-for-delivery.gif" alt="Delivery Animation">
                                    </div>
                                    <div v-else-if="shipments.status.name === 'Delivered'">
                                        <!-- Show checkmark animation -->
                                        <img src="/gif/delivered.gif" alt="Checkmark Animation">
                                    </div>
                                    <div v-else-if="shipments.status.name === 'Failed'">
                                        <!-- Show warning sign -->
                                        <img src="/gif/failed.gif" alt="Warning Sign">
                                    </div>
                                </div>
                            </div>
                            <div class="text-sm font-medium text-gray-600 pb-3">{{ new Date(shipments.updated_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) }}</div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
    <!-- Including footer component -->
    <Footer></Footer>
</template>

<style scoped>
.filling-line {
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    background-color: blue; /* Default filling line color */
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
