<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import {onMounted, ref} from 'vue';
import axios from "axios";
import Footer from './footer.vue';

const usersLog = ref('');
let groupedLogs = ref({});

// Function to group logs by user ID
function groupLogsByUser(logs) {
    return logs.reduce((acc, log) => {
        // Parse new data
        const newData = JSON.parse(log.new_data);

        // Check if user ID is null
        if (log.user_id === null) {
            if (!acc['nullUser']) {
                acc['nullUser'] = [];
            }
            acc['nullUser'].push(log);
        } else {
            // Extract user ID from new data
            const userId = newData.id;
            if (!acc[userId]) {
                acc[userId] = [];
            }
            acc[userId].push(log);
        }

        return acc;
    }, {});
}

onMounted(async () => {
    try {
        const response = await axios.get('/api/get-users-log');
        usersLog.value = response.data;
        // Group logs by user ID
        groupedLogs.value = groupLogsByUser(response.data.users_log);
    } catch (error) {
        alert('Error fetching data:', error);
    }
});
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
    if (action === 'user-create') {
        return 'lightgreen';
    } else if (action === 'user-update') {
        return 'lightblue';
    } else if (action === 'user-delete') {
        return 'lightcoral';
    } else {
        return ''; // Default color if action is not recognized
    }
}

// Function to parse user data from JSON
function parseUserData(userData) {
    if (!userData) {
        return { name: 'Deleted by same user', email: 'Deleted by same user', phone: 'Deleted by same user', error: true };
    }

    if (typeof userData === 'string') {
        try {
            const user = JSON.parse(userData);
            return { name: user.name, email: user.email, phone: user.phone, error: false };
        } catch (error) {
            alert('Error parsing user data:', error);
            return { name: '', email: '', phone: '', error: true };
        }
    } else if (typeof userData === 'object') {
        return { name: userData.name || '', email: userData.email || '', phone: userData.phone || '', error: false };
    } else {
        alert('Invalid user data format:', userData);
        return { name: '', email: '', phone: '', error: true };
    }
}
</script>

<template>
    <Head title="Users Log" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Logs</h2>
        </template>

        <div class="py-6 flex justify-center">
            <div class="max-w-3xl w-full bg-white rounded-lg shadow-md overflow-hidden">
                <div v-if="usersLog">
                    <!-- Loop through users and display each user's logs -->
                    <div v-for="(userLogs, userId) in groupedLogs" :key="userId">
                        <h2 class="px-6 py-4 text-lg font-semibold text-gray-800">User ID: {{ userId }}</h2>
                        <div class="max-w-3xl w-full bg-white rounded-lg shadow-md overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h2 class="text-lg font-semibold text-gray-800">Log</h2>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 styled-table">
                                    <thead>
                                    <tr style="background-color: #f2f2f2">
                                        <td class="sticky left-0 z-10 bg-blue-300">Time</td>
                                        <!-- Iterate over userLogs for each user -->
                                        <td v-for="log in userLogs" :key="log.id">
                                            {{ formatTime(log.updated_at) }}
                                        </td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td class="sticky left-0 z-10 bg-blue-300">User Name</td>
                                        <td v-for="log in userLogs" :key="log.id" :style="{ backgroundColor: log.old_data && log.new_data && JSON.parse(log.old_data).name !== JSON.parse(log.new_data).name ? getBackgroundColor(log.action) : '' }">
                                            {{ log.new_data && JSON.parse(log.new_data).name ? JSON.parse(log.new_data).name : '' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="sticky left-0 z-10 bg-blue-300">User Email</td>
                                        <td v-for="log in userLogs" :key="log.id" :style="{ backgroundColor: log.old_data && log.new_data && JSON.parse(log.old_data).email !== JSON.parse(log.new_data).email ? getBackgroundColor(log.action) : '' }">
                                            {{ log.new_data && JSON.parse(log.new_data).email ? JSON.parse(log.new_data).email : '' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="sticky left-0 z-10 bg-blue-300">User Phone</td>
                                        <td v-for="log in userLogs" :key="log.id" :style="{ backgroundColor: log.old_data && log.new_data && JSON.parse(log.old_data).phone !== JSON.parse(log.new_data).phone ? getBackgroundColor(log.action) : '' }">
                                            {{ log.new_data && JSON.parse(log.new_data).phone ? JSON.parse(log.new_data).phone : '' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="sticky left-0 z-10 bg-blue-300">User Role</td>
                                        <td v-for="log in userLogs" :key="log.id" :style="{ backgroundColor: log.old_data && log.new_data && JSON.parse(log.old_data).role && JSON.parse(log.new_data).role && JSON.parse(log.old_data).role.name !== JSON.parse(log.new_data).role.name ? getBackgroundColor(log.action) : '' }">
                                            {{ log.new_data && JSON.parse(log.new_data).role ? JSON.parse(log.new_data).role.name : '' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="sticky left-0 z-10 bg-blue-300">User Facility</td>
                                        <td v-for="log in userLogs" :key="log.id" :style="{ backgroundColor: log.old_data && log.new_data && JSON.parse(log.old_data).facility_id !== JSON.parse(log.new_data).facility_id ? getBackgroundColor(log.action) : '' }">
                                            {{ log.new_data && JSON.parse(log.new_data).facility ? JSON.parse(log.new_data).facility.location : '' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="sticky left-0 z-10 bg-blue-300">Officer Name</td>
                                        <td v-for="log in userLogs" :key="log.id" :style="{ backgroundColor: log.old_data && log.new_data && parseUserData(log.user).error ? getBackgroundColor(log.action) : '' }">
                                            {{ parseUserData(log.user).name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="sticky left-0 z-10 bg-blue-300">Officer Email</td>
                                        <td v-for="log in userLogs" :key="log.id" :style="{ backgroundColor: log.old_data && log.new_data && parseUserData(log.user).error ? getBackgroundColor(log.action) : '' }">
                                            {{ parseUserData(log.user).email }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="sticky left-0 z-10 bg-blue-300">Officer Phone</td>
                                        <td v-for="log in userLogs" :key="log.id" :style="{ backgroundColor: log.old_data && log.new_data && parseUserData(log.user).error ? getBackgroundColor(log.action) : '' }">
                                            {{ parseUserData(log.user).phone }}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
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
