<script setup>
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

const shipments = ref([]);
const facilitiesResponse = ref([]);
const modalShipment = ref(null)
const states = ref([
    { id: 1, name: 'state1' },
    { id: 2, name: 'state2' }
]);
const cities = ref([
    { id: 1, name: 'city1' },
    { id: 2, name: 'city2' }
]);


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


// Add data properties to control the visibility of add shipment sections
const itemDetails = ref(false);
const senderDetails = ref(false);
const recipientDetails = ref(false);

// Add data properties to control the visibility of sections in the modal
const showItemDetails = ref(false);
const showSenderDetails = ref(false);
const showRecipientDetails = ref(false);

const displayedShipments = ref([]);
let currentIndex = 0;

// Function to toggle visibility of item details in add shipment section
const toggleItem = () => {
    itemDetails.value = !itemDetails.value;
    senderDetails.value = false;
    recipientDetails.value = false;
};

// Function to toggle visibility of sender details in add shipment section
const toggleSender = () => {
    senderDetails.value = !senderDetails.value;
    itemDetails.value = false;
    recipientDetails.value = false;
};

// Function to toggle visibility of recipient details in add shipment section
const toggleRecipient = () => {
    recipientDetails.value = !recipientDetails.value;
    itemDetails.value = false;
    senderDetails.value = false;
};


// Function to toggle visibility of item details section
const toggleItemDetails = () => {
    showItemDetails.value = !showItemDetails.value;
};

// Function to toggle visibility of sender details section
const toggleSenderDetails = () => {
    showSenderDetails.value = !showSenderDetails.value;
};

// Function to toggle visibility of recipient details section
const toggleRecipientDetails = () => {
    showRecipientDetails.value = !showRecipientDetails.value;
};

const updateDisplayedShipments = () => {
    displayedShipments.value = shipments.value.slice(currentIndex, currentIndex + 2);
};

const scrollShipments = (direction) => {
    const newIndex = currentIndex + direction * 2;
    if (newIndex >= 0 && newIndex < shipments.value.length) {
        currentIndex = newIndex;
        updateDisplayedShipments();
    }
};
// Fetch user data and shipments on component mount
onMounted(async () => {
    try {
        // Fetch shipments associated with the user
        const response = await axios.get('/api/shipments');
        shipments.value = response.data;
        updateDisplayedShipments();
    } catch (error) {
        console.error('Error fetching data:', error);
    }

    try {
        // Fetch facilities
        const facilities = await axios.get('/api/facilities');
        facilitiesResponse.value = facilities.data;
        } catch (error) {
        console.error('Error fetching data:', error);
    }
});

// Function to add a new shipment
const addShipment = async () => {
    try {
        const response = await form.post('/api/shipments');
        itemDetails.value = false;
        senderDetails.value = false;
        recipientDetails.value = false;

        // Show notification
        showNotification("Shipment created successfully");

        // Reset form fields
        form.reset();

        // Fetch updated shipment list
        const updatedResponse = await axios.get('/api/shipments');
        shipments.value = updatedResponse.data;
    } catch (error) {
        console.error('Error adding shipment:', error);
    }
};

// Function to open shipment details modal
const openShipmentDetailsModal = (shipment) => {
    modalShipment.value = shipment;
    $('#shipmentDetailsModal').modal('show'); // Manually trigger the Bootstrap modal
};

// Function to close shipment details modal
const closeModal = () => {
    $('#shipmentDetailsModal').modal('hide');
};

// Function to delete a shipment
const deleteShipment = async (shipmentId) => {
    // Show confirmation dialog
    const isConfirmed = confirm("Are you sure you want to delete this shipment?");

    // If user confirms deletion
    if (isConfirmed) {
        try {
            // Make an HTTP request to delete the shipment
            await axios.delete(`/api/shipments/${shipmentId}`);

            // Show notification
            showNotification("Shipment deleted successfully");

            // Refetch shipments after deletion
            const response = await axios.get('/api/shipments');
            shipments.value = response.data;
            updateDisplayedShipments();
        } catch (error) {
            console.error('Error deleting shipment:', error);
            // Show an error message
            alert("Error deleting shipment. Please try again later.");
        }
    }
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

// Function to save changes made to shipment details
const saveChanges = async () => {
    try {
        // Make a request to update the shipment details
        if (modalShipment.value) {
            const response = await axios.put(`/api/shipments/${modalShipment.value.id}`, modalShipment.value);
            // Show notification
            showNotification("Shipment details updated");
            // Refetch shipments after deletion
            const shipmentResponse = await axios.get('/api/shipments');
            shipments.value = shipmentResponse.data;
            // Close the Modal
            $('#shipmentDetailsModal').modal('hide');
        } else {
            console.error('No shipment selected.');
        }
    } catch (error) {
        console.error('Error saving changes:', error);
    }
};


</script>

<template>
    <Head title="User Dashboard" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">User Dashboard</h2>
        </template>

        <br>
        <h1 class="welcome-message">Welcome, {{ $page.props.auth.user.name }}!</h1>
        <div class="py-12 flex justify-center">
            <div class="max-w-3xl w-full bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <!-- Form to add a new shipment -->
                <h3>Add a Shipment</h3>
                <form @submit.prevent="addShipment">
                    <div class="bg-purple-300 rounded-md">
                        <button @click="toggleItem" type="button" class="w-full bg-purple-500 text-white
                                    text-left p-2 rounded-md mb-1 flex justify-between items-center">
                            <span>Item Details</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 transform rotate-0 transition-transform" :class="{ 'rotate-180': showItemDetails }" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 12a1 1 0 0 1-1-1V7a1 1 0 0 1 2 0v4a1 1 0 0 1-1 1zM8 9a1 1 0 0 1 0-2h4a1 1 0 0 1 0 2H8z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div v-show="itemDetails" class="border border-purple-500 rounded-md p-4">
                        <label for="itemName" class="block text-gray-700 text-sm font-bold mb-2">Item Name</label>
                        <input type="text" id="itemName" v-model="form.itemName" class="form-input w-full">
                        <label for="itemDescription" class="block text-gray-700 text-sm font-bold mb-2">Item Description</label>
                        <textarea id="itemDescription" v-model="form.itemDescription" class="form-textarea w-full"></textarea>
                    </div>
                    </div>
                    <div class="bg-purple-300 rounded-md">
                        <button @click="toggleSender" type="button" class="w-full bg-purple-500 text-white
                                    text-left p-2 rounded-md mb-1 flex justify-between items-center">
                            <span>Sender Details</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 transform rotate-0 transition-transform" :class="{ 'rotate-180': showItemDetails }" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 12a1 1 0 0 1-1-1V7a1 1 0 0 1 2 0v4a1 1 0 0 1-1 1zM8 9a1 1 0 0 1 0-2h4a1 1 0 0 1 0 2H8z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div v-show="senderDetails" class="border border-purple-500 rounded-md p-4">
                            <label for="senderName" class="block text-gray-700 text-sm font-bold mb-2">Sender Name</label>
                            <input type="text" id="senderName" v-model="form.senderName" class="form-input w-full">
                            <label for="senderEmail" class="block text-gray-700 text-sm font-bold mb-2">Sender Email</label>
                            <input type="email" id="senderEmail" v-model="form.senderEmail" class="form-input w-full">
                            <label for="senderPhone" class="block text-gray-700 text-sm font-bold mb-2">Sender Phone</label>
                            <input type="text" id="senderPhone" v-model="form.senderPhone" class="form-input w-full">
                            <label for="senderState" class="block text-gray-700 text-sm font-bold mb-2">Sender State</label>
                            <!-- Dropdown menu for sender state -->
                            <select id="senderState" v-model="form.senderState" class="form-select w-full">
                                <option value="state1">State 1</option>
                                <option value="state2">State 2</option>
                            </select>
                            <label for="senderCity" class="block text-gray-700 text-sm font-bold mb-2">Sender City</label>
                            <!-- Dropdown menu for sender city -->
                            <select id="senderCity" v-model="form.senderCity" class="form-select w-full">
                                <option value="city1">City 1</option>
                                <option value="city2">City 2</option>
                            </select>
                            <label for="senderStreet" class="block text-gray-700 text-sm font-bold mb-2">Sender Street</label>
                            <input type="text" id="senderStreet" v-model="form.senderStreet" class="form-input w-full">
                            <label for="senderAddressDetails" class="block text-gray-700 text-sm font-bold mb-2">Sender Address Details</label>
                            <textarea id="senderAddressDetails" v-model="form.senderAddressDetails" class="form-textarea w-full"></textarea>
                        </div>
                    </div>

                    <!-- Recipient Information -->
                    <div class="bg-purple-300 rounded-md">
                        <button @click="toggleRecipient" type="button" class="w-full bg-purple-500 text-white
                                    text-left p-2 rounded-md mb-1 flex justify-between items-center">
                            <span>Recipient Details</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 transform rotate-0 transition-transform" :class="{ 'rotate-180': showItemDetails }" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 12a1 1 0 0 1-1-1V7a1 1 0 0 1 2 0v4a1 1 0 0 1-1 1zM8 9a1 1 0 0 1 0-2h4a1 1 0 0 1 0 2H8z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div v-show="recipientDetails" class="border border-purple-500 rounded-md p-4">
                            <label for="recipientName" class="block text-gray-700 text-sm font-bold mb-2">Recipient Name</label>
                            <input type="text" id="recipientName" v-model="form.recipientName" class="form-input w-full">
                            <label for="recipientEmail" class="block text-gray-700 text-sm font-bold mb-2">Recipient Email</label>
                            <input type="email" id="recipientEmail" v-model="form.recipientEmail" class="form-input w-full">
                            <label for="recipientPhone" class="block text-gray-700 text-sm font-bold mb-2">Recipient Phone</label>
                            <input type="text" id="recipientPhone" v-model="form.recipientPhone" class="form-input w-full">
                            <label for="recipientState" class="block text-gray-700 text-sm font-bold mb-2">Recipient State</label>
                            <!-- Dropdown menu for recipient state -->
                            <select id="recipientState" v-model="form.recipientState" class="form-select w-full">
                                <option value="state1">State 1</option>
                                <option value="state2">State 2</option>
                            </select>
                            <label for="recipientCity" class="block text-gray-700 text-sm font-bold mb-2">Recipient City</label>
                            <!-- Dropdown menu for recipient city -->
                            <select id="recipientCity" v-model="form.recipientCity" class="form-select w-full">
                                <option value="city1">City 1</option>
                                <option value="city2">City 2</option>
                            </select>
                            <label for="recipientStreet" class="block text-gray-700 text-sm font-bold mb-2">Recipient Street</label>
                            <input type="text" id="recipientStreet" v-model="form.recipientStreet" class="form-input w-full">
                            <label for="recipientAddressDetails" class="block text-gray-700 text-sm font-bold mb-2">Recipient Address Details</label>
                            <textarea id="recipientAddressDetails" v-model="form.recipientAddressDetails" class="form-textarea w-full"></textarea>
                            <label for="recipientNearFacility" class="block text-gray-700 text-sm font-bold mb-2">Recipient Near Facility</label>
                            <!-- Dropdown menu for recipient near facility -->
                            <select id="recipientNearFacility" v-model="form.recipientNearFacility" class="form-select w-full">
                                <!-- Loop through the facilities and generate options dynamically -->
                                <option v-for="facility in facilitiesResponse" :key="facility.id" :value="facility.id">
                                    {{ facility.name }} - {{ facility.location }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-4">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Shipment</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="py-12 flex justify-center">
            <div class="max-w-3xl w-full bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3>My Shipments</h3>
                <div class="shipment-cards-container">
                    <div class="shipment-card" v-for="shipment in displayedShipments" :key="shipment.id">
                        <div>
                            <span><strong>Shipment ID:</strong> {{ shipment.id }}</span><br>
                            <span><strong>Item Name:</strong> {{ shipment.item.name }}</span><br>
                            <span><strong>Sender Name:</strong> {{ shipment.sender.name }}</span><br>
                            <span><strong>Recipient Name:</strong> {{ shipment.recipient.name }}</span><br>
                            <span><strong>Facility:</strong> {{ shipment.recipient.facility ? shipment.recipient.facility.location : 'N/A' }}</span><br>
                            <span><strong>Tracking Token:</strong> {{ shipment.tracking_token }}</span>
                        </div>
                        <div>
                            <button @click="openShipmentDetailsModal(shipment)" class="btn btn-primary">Details/Update</button>
                            <button @click="deleteShipment(shipment.id)" class="btn btn-danger">Delete</button>
                            <div id="notification-container"></div>

                        </div>
                    </div>
                </div>
                <div class="navigation-arrows">
                    <button @click="scrollShipments(-1)" class="arrow-btn left-arrow">&gt;</button>
                    <button @click="scrollShipments(1)" class="arrow-btn right-arrow">&gt;</button>
                </div>
                <!-- Modal for displaying shipment details -->
                <div class="modal fade" id="shipmentDetailsModal" tabindex="-1" role="dialog" aria-labelledby="shipmentDetailsModalLabel" aria-hidden="true">
                    <div class="modal-dialog max-w-lg mx-auto" role="document">
                        <div class="modal-content bg-white rounded-md shadow-lg">
                            <div class="modal-header bg-blue-500 rounded-t-md">
                                <h5 class="modal-title text-lg font-bold text-white" id="shipmentDetailsModalLabel">Shipment Details</h5>
                                <button type="button" class="close" aria-label="Close" @click="closeModal">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body p-4" v-if="modalShipment">
                                <!-- ID Section -->
                                <div class="bg-purple-300 px-4 py-4 rounded-md mb-0.5">
                                    <p class="text-lg font-bold text-center text-purple-800">Shipment ID: {{ modalShipment.id }}</p>
                                </div>
                                <!-- Item Details Section -->
                                <div class="bg-purple-300 rounded-md">
                                    <button @click="toggleItemDetails" class="w-full bg-purple-500 text-white
                                    text-left p-2 rounded-md mb-1 flex justify-between items-center">
                                        <span>Item Details</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 transform rotate-0 transition-transform" :class="{ 'rotate-180': showItemDetails }" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 12a1 1 0 0 1-1-1V7a1 1 0 0 1 2 0v4a1 1 0 0 1-1 1zM8 9a1 1 0 0 1 0-2h4a1 1 0 0 1 0 2H8z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <div v-show="showItemDetails" class="border border-purple-500 rounded-md p-4">
                                        <p><strong>Item Name:</strong> <input type="text" v-model="modalShipment.item.name" class="input-field"></p>
                                        <p><strong>Item Description:</strong> <input type="text" v-model="modalShipment.item.description" class="input-field"></p>
                                    </div>
                                </div>
                                <!-- Sender Details Section -->
                                <div class="bg-purple-300 rounded-md">
                                    <button @click="toggleSenderDetails" class="w-full bg-purple-500 text-white text-left p-2 rounded-md mb-1 flex justify-between items-center">
                                        <span>Sender Details</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 transform rotate-0 transition-transform" :class="{ 'rotate-180': showSenderDetails }" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 12a1 1 0 0 1-1-1V7a1 1 0 0 1 2 0v4a1 1 0 0 1-1 1zM8 9a1 1 0 0 1 0-2h4a1 1 0 0 1 0 2H8z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <div v-show="showSenderDetails" class="border border-purple-500 rounded-md p-4">
                                        <p><strong>Sender Name:</strong> <input type="text" v-model="modalShipment.sender.name" class="input-field"></p>
                                        <p><strong>Sender Email:</strong> <input type="text" v-model="modalShipment.sender.email" class="input-field"></p>
                                        <p><strong>Sender Phone:</strong> <input type="text" v-model="modalShipment.sender.phone" class="input-field"></p>
                                        <div>
                                            <label for="state">Sender State:</label>
                                            <select v-model="modalShipment.sender.address.state" id="state" class="form-select">
                                                <option disabled value="">Select State</option>
                                                <option v-for="state in states" :key="state.id" :value="state.name" :selected="state.name === modalShipment.sender.address.state">{{ state.name }}</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="city">Sender City:</label>
                                            <select v-model="modalShipment.sender.address.city" id="city" class="form-select">
                                                <option disabled value="">Select City</option>
                                                <option v-for="city in cities" :key="city.id" :value="city.name" :selected="city.name === modalShipment.sender.address.city">{{ city.name }}</option>
                                            </select>
                                        </div>
                                        <p><strong>Sender Street:</strong> <input type="text" v-model="modalShipment.sender.address.street"></p>
                                        <p><strong>Sender Address Details:</strong> <input type="text" v-model="modalShipment.sender.address.details"></p>
                                    </div>
                                </div>
                                <!-- Recipient Details Section -->
                                <div class="bg-purple-300 rounded-md">
                                    <button @click="toggleRecipientDetails" class="w-full bg-purple-500 text-white text-left p-2 rounded-md mb-0.5 flex justify-between items-center">
                                        <span>Recipient Details</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 transform rotate-0 transition-transform" :class="{ 'rotate-180': showRecipientDetails }" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 12a1 1 0 0 1-1-1V7a1 1 0 0 1 2 0v4a1 1 0 0 1-1 1zM8 9a1 1 0 0 1 0-2h4a1 1 0 0 1 0 2H8z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <div v-show="showRecipientDetails" class="border border-purple-500 rounded-md p-4">
                                        <p><strong>Recipient Name:</strong> <input type="text" v-model="modalShipment.recipient.name" class="input-field"></p>
                                        <p><strong>Recipient Email:</strong> <input type="text" v-model="modalShipment.recipient.email" class="input-field"></p>
                                        <p><strong>Recipient Phone:</strong> <input type="text" v-model="modalShipment.recipient.phone" class="input-field"></p>
                                        <div>
                                            <label for="state">Recipient State:</label>
                                            <select v-model="modalShipment.recipient.address.state" id="state" class="form-select">
                                                <option disabled value="">Select State</option>
                                                <option v-for="state in states" :key="state.id" :value="state.name" :selected="state.name === modalShipment.recipient.address.state">{{ state.name }}</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="city">Recipient City:</label>
                                            <select v-model="modalShipment.recipient.address.city" id="city" class="form-select">
                                                <option disabled value="">Select City</option>
                                                <option v-for="city in cities" :key="city.id" :value="city.name" :selected="city.name === modalShipment.recipient.address.city">{{ city.name }}</option>
                                            </select>
                                        </div>
                                        <p><strong>Recipient Street:</strong> <input type="text" v-model="modalShipment.recipient.address.street"></p>
                                        <p><strong>Recipient Address Details:</strong> <input type="text" v-model="modalShipment.recipient.address.details"></p>
                                        <div>
                                            <label for="facility" class="text-purple-800">Facility: {{ modalShipment.recipient.facility.id }}</label>
                                            <select v-model="modalShipment.recipient.facility" id="facility" class="form-select input-field">
                                                <option disabled value="">Select Facility</option>
                                                <option v-for="facility in facilitiesResponse"
                                                        :key="facility.id"
                                                        :value="String(facility.id)"
                                                        :selected="String(facility.id) === String(modalShipment.recipient.facility.id)">
                                                    {{ facility.name }} - {{ facility.location }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- Tracking Token Section -->
                                <div class="bg-purple-400 px-4 py-4 rounded-md mb-4">
                                    <p class="text-lg font-bold text-center text-purple-800">Tracking Token: {{ modalShipment.tracking_token }}</p>
                                </div>
                                <!-- Save button -->
                                <button @click="saveChanges" class="mt-4 w-full bg-blue-500 text-white hover:bg-purple-700 py-2 px-4 rounded-md">Save</button>
                            </div>
                            <div class="modal-body p-4" v-else>
                                Loading...
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.welcome-message {
    padding-left: 20px;
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

.notification {
    background-color: #4CAF50;
    padding: 15px;
    margin-bottom: 10px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    transition: opacity 0.5s ease-out;
}

.notification.fade-out {
    opacity: 0;
}

.shipment-cards-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}

.shipment-card {
    width: calc(50% - 10px);
    margin-bottom: 20px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.navigation-arrows {
    margin-top: 20px;
    text-align: center;
}

.arrow-btn {
    font-size: 20px;
    margin: 0 10px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    cursor: pointer;
}

.left-arrow {
    transform: rotate(180deg);
}
</style>
