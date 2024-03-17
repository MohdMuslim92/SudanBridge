<script setup>
import Footer from "@/Pages/footer.vue";

const script1 = document.createElement('script');
script1.src = 'https://code.jquery.com/jquery-3.6.0.min.js';
document.head.appendChild(script1);

const script2 = document.createElement('script');
script2.src = 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js';
document.head.appendChild(script2);
import 'jquery';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';
import SearchTokenModal from './SearchTokenModal.vue';
// Import the AddShipmentModal component
import AddShipmentComponent from './AddShipmentComponent.vue';
// Import the ShipmentDetails component
import ShipmentDetailsComponent from './ShipmentDetailsComponent.vue';
// Import the useFacilities function
import useFacilities from '../facilities.js';

// Destructure facilitiesResponse from the useFacilities function
const { facilitiesResponse } = useFacilities();

const facilityLocation = ref(null);

const form = useForm({
    itemName: '',
    itemDescription: '',
    senderName: '',
    senderEmail: '',
    senderPhone: '',
    senderState: '',
    senderCity: '',
    senderStreet: '',
    senderAddressDetails: '',
    recipientName: '',
    recipientEmail: '',
    recipientPhone: '',
    recipientState: '',
    recipientCity: '',
    recipientStreet: '',
    recipientAddressDetails: '',
    recipientNearFacility: ''
});

// Fetch user data and shipments on component mount
onMounted(async () => {
    try {
        // Fetch the user ID from the backend
        const locationResponse = await axios.get('/api/location');
        facilityLocation.value = locationResponse.data.facility_location;

        // Extract query parameters from URL
        const queryParams = new URLSearchParams(window.location.search);
        // Get the value of the 'status' parameter
        const status = queryParams.get('status');

        // Check if the status matches the desired message
        if (status === 'Shipment status updated successfully') {
            // If it matches, call the showNotification function to display the confirmation
            showNotification(status);
        } else if (status && status !== 'Shipment status updated successfully') {
            alert('Error updating the shipment status')
        }

    } catch (error) {
        console.error('Error fetching user data:', error);
    }
});

// Function to close shipment details modal
const closeModal = () => {
    $('#shipmentDetailsModal').modal('hide');
    $('#shipmentByTokenModal').modal('hide');
};

// Function to display a notification
const showNotification = (message) => {
    const notificationContainer = document.getElementById('notification-container');

    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'notification';
    notification.textContent = message;

    // Apply styles for notification
    notification.style.backgroundColor = 'green'; // Green background
    notification.style.color = 'white'; // White text
    notification.style.fontWeight = 'bold'; // Bold text
    notification.style.padding = '10px'; // Increase padding for a bigger box
    // Append notification to container
    notificationContainer.appendChild(notification);

// Fade out and remove notification after 5 seconds
    setTimeout(() => {
        notification.classList.add('fade-out');
        setTimeout(() => {
            notification.remove();
        }, 500); // Fading animation duration
    }, 5000); // Notification display duration
};

</script>

<template>
    <Head title="User Dashboard" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">

    <AuthenticatedLayout>
        <template #header>
            <div class="header-container">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Facility: {{ facilityLocation }}</h2>
                <search-token-modal
                    :showNotification="showNotification"
                    :closeModal="closeModal"
                ></search-token-modal>
            </div>
        </template>

        <br>
        <div class="welcome-container pt-4">
            <h1 class="welcome-message">Welcome, {{ $page.props.auth.user.name }}</h1>
        </div>
        <!-- Include the AddShipmentComponent -->
        <AddShipmentComponent :showNotification="showNotification" :closeModal="closeModal" />
        <!-- Include the ShipmentDetailsComponent -->
        <ShipmentDetailsComponent
            :showNotification="showNotification"
            :closeModal="closeModal"
        ></ShipmentDetailsComponent>
        <!-- Display notification after Shipment updated -->
        <div id="notification-container"></div>
    </AuthenticatedLayout>
    <Footer></Footer>
</template>

<style scoped>
.header-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* Media query for smaller screens */
@media screen and (max-width: 768px) {
    .header-container {
        flex-direction: column; /* Stack elements vertically */
        align-items: flex-start; /* Align elements to the start */
    }
}

.welcome-container {
    display: flex;
    justify-content: center;
}

.welcome-message {
    font-size: 24px;
    font-weight: bold;
    color: #333;
}

#notification-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
}

</style>
