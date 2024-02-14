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

        <div class="py-12 flex justify-center">
            <div class="max-w-3xl w-full bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="w-full">
                    <thead>
                    <tr>
                        <th class="px-4 py-2">User</th>
                        <th class="px-4 py-2">Current Role</th>
                        <th class="px-4 py-2">Change Role</th>
                        <th class="px-4 py-2">Change Facility</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="user in users" :key="user.id" class="border-b">
                        <td class="px-4 py-2">{{ user.name }}</td>
                        <td class="px-4 py-2">{{ user.role ? user.role.name : 'No Role' }}</td>
                        <td class="px-4 py-2 items-center">
                            <select v-model="selectedRole[user.id]" class="mr-2">
                                <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</option>
                            </select>
                            <button @click="updateUserRole(user.id, selectedRole[user.id])" class="px-4 py-2 bg-blue-500 text-white rounded">Change</button>
                        </td>
                        <td class="px-4 py-2 items-center">
                            <select v-model="selectedFacility[user.id]" class="mr-2">
                                <option v-for="facility in facilities" :key="facility.id" :value="facility.id">{{ facility.name }} - {{ facility.location }}</option>
                            </select>
                            <button @click="updateFacility(user.id, selectedFacility[user.id])" class="px-4 py-2 bg-blue-500 text-white rounded">Change</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Notification -->
        <div v-if="notification.visible" class="absolute top-16 right-4 bg-green-500 text-white py-2 px-4 rounded shadow-lg">
            <span>{{ notification.message }}</span>
            <div class="h-1 bg-white mt-1" :style="{ width: `${notificationTimer}%` }"></div>
        </div>
    </AuthenticatedLayout>
</template>
