<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import {onMounted, ref} from 'vue';
import axios from "axios";
import Footer from './footer.vue';

const token = ref('');
const shipments = ref('');

const searchByToken = async () => {
    try {
        // Fetch shipment details from the backend using the provided token
        const shipmentsResponse = await axios.get(`/api/shipment-logs/${token.value.trim()}`);
        shipments.value = shipmentsResponse.data;
    } catch (error) {
        alert('Wrong token number, please enter correct token');
    }
}

function formatTime(timestamp) {
    if (!timestamp) return '';

    const now = new Date();
    const updatedAt = new Date(timestamp);
    const diff = now - updatedAt;
    const diffMinutes = Math.floor(diff / 60000);
    const diffHours = Math.floor(diff / 3600000);
    const diffDays = Math.floor(diff / (3600000 * 24));

    if (diff < 60000) {
        return 'Just now';
    } else if (diff < 3600000) {
        return `${diffMinutes} minute${diffMinutes !== 1 ? 's' : ''} ago`;
    } else if (diff < 3600000 * 24) {
        return `${diffHours} hour${diffHours !== 1 ? 's' : ''} ago`;
    } else if (diff < 3600000 * 24 * 7) {
        return `${diffDays} day${diffDays !== 1 ? 's' : ''} ago`;
    } else {
        return updatedAt.toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: 'numeric', hour12: true });
    }
}

function getBackgroundColor(action) {
    if (action === 'create') {
        return 'lightgreen';
    } else if (action === 'update') {
        return 'lightblue';
    } else if (action === 'delete') {
        return 'lightcoral';
    } else {
        return ''; // Default color if action is not recognized
    }
}

</script>

<template>
    <Head title="Logs" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Logs</h2>
        </template>

        <div class="py-6 flex justify-center">
            <div class="max-w-3xl w-full bg-white rounded-lg shadow-md overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <span class="text-lg font-semibold text-gray-800">Get Logs for a Package</span>
                </div>
                <div class="flex flex-col items-center px-6 py-8 space-y-4 md:flex-row md:items-center md:space-x-4 md:space-y-0 justify-center">
                    <div class="flex flex-col w-full md:w-auto">
                        <input type="text" v-model="token" placeholder="Enter token" class="border border-gray-300 p-1 rounded-lg">
                    </div>
                    <div class="flex flex-col w-full md:w-auto">
                        <button @click="searchByToken" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Get Logs</button>
                    </div>
                </div>
                <div v-if="shipments">
                    <!-- Loop through shipment logs and display each entry -->
                    <div class="max-w-3xl w-full bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-800">Shipment Logs</h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 styled-table">
                                <thead>
                                <tr style="background-color: #f2f2f2">
                                    <td class="sticky left-0 z-10 bg-blue-300">Time</td>
                                    <td v-for="log in shipments.shipment_logs" :key="log.id">
                                        {{ formatTime(log.updated_at) }}
                                    </td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="sticky left-0 z-10 bg-blue-300">Item Name</td>
                                    <td v-for="log in shipments.shipment_logs" :key="log.id" :style="{ backgroundColor: log.old_data && log.new_data && JSON.parse(log.old_data).item && JSON.parse(log.new_data).item && JSON.parse(log.old_data).item.name !== JSON.parse(log.new_data).item.name ? getBackgroundColor(log.action) : '' }">
                                        {{ log.new_data && JSON.parse(log.new_data).item ? JSON.parse(log.new_data).item.name : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="sticky left-0 z-10 bg-blue-300">Item Description</td>
                                    <td v-for="log in shipments.shipment_logs" :key="log.id" :style="{ backgroundColor: log.old_data && log.new_data && JSON.parse(log.old_data).item && JSON.parse(log.new_data).item && JSON.parse(log.old_data).item.description !== JSON.parse(log.new_data).item.description ? getBackgroundColor(log.action) : '' }">
                                        {{ log.new_data && JSON.parse(log.new_data).item ? JSON.parse(log.new_data).item.description : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="sticky left-0 z-10 bg-blue-300">Sender Name</td>
                                    <td v-for="log in shipments.shipment_logs" :key="log.id" :style="{ backgroundColor: log.old_data && log.new_data && JSON.parse(log.old_data).sender && JSON.parse(log.new_data).sender && JSON.parse(log.old_data).sender.name !== JSON.parse(log.new_data).sender.name ? getBackgroundColor(log.action) : '' }">
                                        {{ log.new_data && JSON.parse(log.new_data).sender ? JSON.parse(log.new_data).sender.name : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="sticky left-0 z-10 bg-blue-300">Sender Email</td>
                                    <td v-for="log in shipments.shipment_logs" :key="log.id" :style="{ backgroundColor: log.old_data && log.new_data && JSON.parse(log.old_data).sender && JSON.parse(log.new_data).sender && JSON.parse(log.old_data).sender.email !== JSON.parse(log.new_data).sender.email ? getBackgroundColor(log.action) : '' }">
                                        {{ log.new_data && JSON.parse(log.new_data).sender ? JSON.parse(log.new_data).sender.email : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="sticky left-0 z-10 bg-blue-300">Sender Phone</td>
                                    <td v-for="log in shipments.shipment_logs" :key="log.id" :style="{ backgroundColor: log.old_data && log.new_data && JSON.parse(log.old_data).sender && JSON.parse(log.new_data).sender && JSON.parse(log.old_data).sender.phone !== JSON.parse(log.new_data).sender.phone ? getBackgroundColor(log.action) : '' }">
                                        {{ log.new_data && JSON.parse(log.new_data).sender ? JSON.parse(log.new_data).sender.phone : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="sticky left-0 z-10 bg-blue-300">Recipient Name</td>
                                    <td v-for="log in shipments.shipment_logs" :key="log.id" :style="{ backgroundColor: log.old_data && log.new_data && JSON.parse(log.old_data).recipient && JSON.parse(log.new_data).recipient && JSON.parse(log.old_data).recipient.name !== JSON.parse(log.new_data).recipient.name ? getBackgroundColor(log.action) : '' }">
                                        {{ log.new_data && JSON.parse(log.new_data).recipient ? JSON.parse(log.new_data).recipient.name : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="sticky left-0 z-10 bg-blue-300">Recipient Email</td>
                                    <td v-for="log in shipments.shipment_logs" :key="log.id" :style="{ backgroundColor: log.old_data && log.new_data && JSON.parse(log.old_data).recipient && JSON.parse(log.new_data).recipient && JSON.parse(log.old_data).recipient.email !== JSON.parse(log.new_data).recipient.email ? getBackgroundColor(log.action) : '' }">
                                        {{ log.new_data && JSON.parse(log.new_data).recipient ? JSON.parse(log.new_data).recipient.email : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="sticky left-0 z-10 bg-blue-300">Recipient Phone</td>
                                    <td v-for="log in shipments.shipment_logs" :key="log.id" :style="{ backgroundColor: log.old_data && log.new_data && JSON.parse(log.old_data).recipient && JSON.parse(log.new_data).recipient && JSON.parse(log.old_data).recipient.phone !== JSON.parse(log.new_data).recipient.phone ? getBackgroundColor(log.action) : '' }">
                                        {{ log.new_data && JSON.parse(log.new_data).recipient ? JSON.parse(log.new_data).recipient.phone : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="sticky left-0 z-10 bg-blue-300">User Name</td>
                                    <td v-for="log in shipments.shipment_logs" :key="log.id" :style="{ backgroundColor: log.old_data && log.new_data && JSON.parse(log.old_data).user && JSON.parse(log.new_data).user && JSON.parse(log.old_data).user.name !== JSON.parse(log.new_data).user.name ? getBackgroundColor(log.action) : '' }">
                                        {{ log.new_data && JSON.parse(log.new_data).user ? JSON.parse(log.new_data).user.name : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="sticky left-0 z-10 bg-blue-300">User Email</td>
                                    <td v-for="log in shipments.shipment_logs" :key="log.id" :style="{ backgroundColor: log.old_data && log.new_data && JSON.parse(log.old_data).user && JSON.parse(log.new_data).user && JSON.parse(log.old_data).user.email !== JSON.parse(log.new_data).user.email ? getBackgroundColor(log.action) : '' }">
                                        {{ log.new_data && JSON.parse(log.new_data).user ? JSON.parse(log.new_data).user.email : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="sticky left-0 z-10 bg-blue-300">User Phone</td>
                                    <td v-for="log in shipments.shipment_logs" :key="log.id" :style="{ backgroundColor: log.old_data && log.new_data && JSON.parse(log.old_data).user && JSON.parse(log.new_data).user && JSON.parse(log.old_data).user.phone !== JSON.parse(log.new_data).user.phone ? getBackgroundColor(log.action) : '' }">
                                        {{ log.new_data && JSON.parse(log.new_data).user ? JSON.parse(log.new_data).user.phone : '' }}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
    <Footer></Footer>
</template>

<style>
.styled-table {
    width: 100%;
    border-collapse: collapse;
    margin: 1em 0;
}

.styled-table th {
    background-color: #f2f2f2;
    padding: 8px;
    text-align: left;
    font-weight: bold;
}

.styled-table td {
    padding: 8px;
    border-bottom: 1px solid #ddd;
}

.styled-table tbody tr:nth-child(even) {
    background-color: #f2f2f2;
}

.styled-table tbody tr:hover {
    background-color: #ddd;
}
</style>
