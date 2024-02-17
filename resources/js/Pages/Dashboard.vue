<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const users = ref([]);
const roles = ref([]);
const facilities = ref([]);
const selectedRole = ref({});
const selectedFacility = ref({});
const notification = ref({
    visible: false,
    message: '',
});

let notificationTimer = 100; // initial width of the timer bar

onMounted(async () => {
    try {
        const response = await axios.get('/dashboard/data');
        users.value = response.data.users;
        roles.value = response.data.roles;
        facilities.value = response.data.facilities;
    } catch (error) {
        console.error('Error fetching data:', error);
    }

    // Set selectedRole for each user if user.role_id is not null
    users.value.forEach(user => {
        if (user.role_id !== null) {
            selectedRole.value[user.id] = user.role_id;
        }
    });

    // Set selectedFacility for each user if user.facility_id is not null
    users.value.forEach(user => {
        if (user.facility_id !== null) {
            selectedFacility.value[user.id] = user.facility_id;
        }
    });
});

const updateUserRole = async (userId, roleId) => {
    try {
        await axios.post(`/dashboard/update/${userId}`, { role_id: roleId });
        // Show notification
        notification.value.message = 'Role updated successfully';
        notification.value.visible = true;
        // Start timer to hide notification after 5 seconds
        notificationTimer = 100;
        const interval = setInterval(() => {
            notificationTimer -= 1; // Decrease the width more gradually
            if (notificationTimer <= 0) {
                clearInterval(interval); // Clear the interval when the timer reaches 0
                notification.value.visible = false; // Hide the notification
            }
        }, 50); // Update the timer every 50 milliseconds
        // Refresh data after updating user role
        const response = await axios.get('/dashboard/data');
        users.value = response.data.users;
    } catch (error) {
        console.error('Error updating user role:', error);
    }
};

const updateFacility = async (userId, facilityId) => {
    try {
        console.log(userId);
        await axios.post(`/dashboard/updateFacility/${userId}`, { facility_id: facilityId });
        // Show notification
        notification.value.message = 'Facility updated successfully';
        notification.value.visible = true;
        // Start timer to hide notification after 5 seconds
        notificationTimer = 100;
        const interval = setInterval(() => {
            notificationTimer -= 1; // Decrease the width more gradually
            if (notificationTimer <= 0) {
                clearInterval(interval); // Clear the interval when the timer reaches 0
                notification.value.visible = false; // Hide the notification
            }
        }, 50); // Update the timer every 50 milliseconds
        // Refresh data after updating user role
        const response = await axios.get('/dashboard/data');
        users.value = response.data.users;
    } catch (error) {
        console.error('Error updating user facility:', error);
    }
};

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-6 flex justify-center">
            <div class="max-w-3xl w-full bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                User
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Current Role
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Change Role
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Change Facility
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        <tr class="table-row" v-for="user in users" :key="user.id">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ user.name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ user.role ? user.role.name : 'No Role' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <select v-model="selectedRole[user.id]" class="mr-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</option>
                                    </select>
                                    <button @click="updateUserRole(user.id, selectedRole[user.id])" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-200 focus:ring-opacity-50">Change</button>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <select v-model="selectedFacility[user.id]" class="mr-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option v-for="facility in facilities" :key="facility.id" :value="facility.id">{{ facility.name }} - {{ facility.location }}</option>
                                    </select>
                                    <button @click="updateFacility(user.id, selectedFacility[user.id])" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-200 focus:ring-opacity-50">Change</button>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Notification -->
        <div v-if="notification.visible" class="fixed top-6 right-6 bg-green-500 text-white px-4 py-2 rounded-lg shadow-md transition-opacity duration-300">
            <span>{{ notification.message }}</span>
            <div class="h-1 bg-white mt-1" :style="{ width: `${notificationTimer}%` }"></div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

/* Hover effects */
.table-row:hover {
    background-color: #f3f4f6;
}

/* Animated transitions */
.transition-opacity {
    transition-property: opacity;
}

/* Icons */
.select-icon::before {
    content: "\25BC";
    position: absolute;
    right: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
}

/* Color Scheme */
.bg-blue-600 {
    background-color: #3b82f6;
}

/* Typography */
.text-base {
    font-size: 1rem;
    line-height: 1.5rem;
}

/* Responsive Design */
@media (max-width: 640px) {
    /* Adjustments for small screens */
    .max-w-3xl {
        max-width: 100%;
    }
}
</style>
