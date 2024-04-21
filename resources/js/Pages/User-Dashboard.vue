<script setup>
import Footer from "@/Pages/footer.vue";

const script1 = document.createElement('script');
script1.src = 'https://code.jquery.com/jquery-3.6.0.min.js';
document.head.appendChild(script1);

const script2 = document.createElement('script');
script2.src = 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js';
document.head.appendChild(script2);

const script3 = document.createElement('script');
script3.src = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js';
document.head.appendChild(script3);

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, onMounted, watch } from 'vue';
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

// Define reactive variables and functions
const facilityLocation = ref(null);
const currentIndex = ref(0);
const shipments = ref([]);
const displayedShipments = ref([]);
const updateDisplayedShipments = () => {
  displayedShipments.value = shipments.value.slice(currentIndex.value, currentIndex.value + 2);
};

const form = useForm({
  // Define form fields and initial values
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

// Function to fetch user data and shipments on component mount
onMounted(async () => {
  try {
    // Fetch the user's facility location from the backend
    const locationResponse = await axios.get('/api/location');
    facilityLocation.value = locationResponse.data.facility_location;

    // Extract query parameters from URL
    const queryParams = new URLSearchParams(window.location.search);
    // Get the value of the 'status' parameter
    const status = queryParams.get('status');

    // Check if the status matches the desired message
    if (status === 'Shipment status updated successfully') {
      // Show notification if shipment status is updated successfully
      showNotification(status);
    } else if (status && status !== 'Shipment status updated successfully') {
      alert('Error updating the shipment status')
    }

    // Fetch shipments from the backend
    const response = await axios.get('/api/shipments');
    shipments.value = response.data;
    updateDisplayedShipments();

  } catch (error) {
    alert('Error fetching user data, please reload the page');
  }
});

// Watch for changes to currentIndex and emit event to child component
watch(currentIndex, (newValue, oldValue) => {
  if (newValue !== oldValue) {
    // Emit an event to notify child component of currentIndex change
    handleUpdateCurrentIndex(newValue);
  }
});

// Function to handle update-current-index event from child component
const handleUpdateCurrentIndex = (newIndex) => {
  currentIndex.value = newIndex; // Update currentIndex when event is received
};

// Function to update-displayed-shipments event from child component
const handleUpdateDisplayedShipments = (newDisplayedShipments) => {
  shipments.value = newDisplayedShipments;
  updateDisplayedShipments();
};

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

// Function to scroll to the last shipment
const scrollToLastShipment = () => {
  // Set currentIndex to display the last shipment
  currentIndex.value = Math.max(0, shipments.value.length - 2);
  updateDisplayedShipments();
};
</script>

<template>
  <!-- Set the page title -->
  <Head title="User Dashboard" />
  <!-- Import Bootstrap CSS stylesheets -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Import Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

  <!-- AuthenticatedLayout component -->
  <AuthenticatedLayout>
    <!-- Header section -->
    <template #header>
      <!-- Container for header elements -->
      <div class="header-container">
        <!-- Display facility location -->
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Facility: {{ facilityLocation }}</h2>
        <!-- SearchTokenModal component -->
        <search-token-modal
            :showNotification="showNotification"
            :closeModal="closeModal"
        ></search-token-modal>
      </div>
    </template>

    <br>
    <!-- Welcome message section -->
    <div class="welcome-container pt-4">
      <!-- Display welcome message -->
      <h1 class="welcome-message">Welcome, {{ $page.props.auth.user.name }}</h1>
    </div>

    <!-- Include the AddShipmentComponent -->
    <AddShipmentComponent
        :showNotification="showNotification"
        :closeModal="closeModal"
        :scrollToLastShipment="scrollToLastShipment"
        :updateDisplayedShipments="updateDisplayedShipments"
        :shipments="shipments"
        @update-displayed-shipments="handleUpdateDisplayedShipments"
    />
    <!-- Include the ShipmentDetailsComponent -->
    <ShipmentDetailsComponent
        :showNotification="showNotification"
        :closeModal="closeModal"
        :scrollToLastShipment="scrollToLastShipment"
        :updateDisplayedShipments="updateDisplayedShipments"
        :shipments="shipments"
        :displayedShipments="displayedShipments"
        :currentIndex="currentIndex"
        @update-current-index="handleUpdateCurrentIndex"
        @update-displayed-shipments="handleUpdateDisplayedShipments"

    >
    </ShipmentDetailsComponent>
    <!-- Notification container -->
    <div id="notification-container"></div>
  </AuthenticatedLayout>
  <!-- Footer component -->
  <Footer></Footer>
</template>

<style scoped>
/* Styles for header container */
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

/* Styles for welcome container */
.welcome-container {
  display: flex;
  justify-content: center;
}

/* Styles for welcome message */
.welcome-message {
  font-size: 24px;
  font-weight: bold;
  color: #333;
}

/* Styles for notification container */
#notification-container {
  position: fixed;
  top: 20px;
  right: 20px;
  z-index: 9999;
}

</style>
