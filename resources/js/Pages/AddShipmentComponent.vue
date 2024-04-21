<script setup>
// Import necessary modules and functions
import { ref, defineProps, onMounted, watch, getCurrentInstance } from "vue";
import axios from "axios";
import { useForm } from '@inertiajs/vue3';
// Import the useFacilities function
import useFacilities from '../facilities.js';

const instance = getCurrentInstance(); // Get the current component instance

// Destructure facilitiesResponse from the useFacilities function
const { facilitiesResponse } = useFacilities();

// Define props received by the component
const props = defineProps({
  showNotification: Function,
  scrollToLastShipment: Function,
  updateDisplayedShipments: Function,
  shipments: Array

});

// Define reactive form data
const form = useForm({
  itemName: '',
  itemDescription: '',
  senderName: '',
  senderEmail: '',
  senderPhone: '',
  senderState: '',
  senderLocality: '',
  senderStreet: '',
  senderAddressDetails: '',
  recipientName: '',
  recipientEmail: '',
  recipientPhone: '',
  recipientState: '',
  recipientLocality: '',
  recipientStreet: '',
  recipientAddressDetails: '',
  recipientNearFacility: ''
});

// Define reactive variables for controlling section visibility
const showItemDetails = ref(false);

// Define reactive arrays for storing states and localities
const states = ref([]);
const localities = ref([]);
const recipientLocalities = ref([]);

// Define reactive variables to control the visibility of add shipment sections
const itemDetails = ref(false);
const senderDetails = ref(false);
const recipientDetails = ref(false);

// Function to fetch localities based on the selected state
const fetchLocalities = async () => {
  try {
    if (form.senderState) {
      const localitiesResponse = await axios.get(`/api/states/${form.senderState}/localities`);
      localities.value = localitiesResponse.data;
    }
  } catch (error) {
    alert('Error fetching localities, please reload the page');
  }
};

// Function to fetch localities for recipient based on the selected state
const fetchLocalitiesForRecipient = async () => {
  try {
    if (form.recipientState) {
      const localitiesRecipientResponse = await axios.get(`/api/states/${form.recipientState}/localities`);
      recipientLocalities.value = localitiesRecipientResponse.data;
    }
  } catch (error) {
    alert('Error fetching localities, please reload the page');
  }
};

// Fetch states and localities on component mount
onMounted(async () => {
  try {
    // Fetch states from the backend
    const statesResponse = await axios.get('/api/states');
    states.value = statesResponse.data;

    // Fetch localities based on the selected state
    await fetchLocalities();
    await fetchLocalitiesForRecipient();
  } catch (error) {
    alert('Error fetching states, please reload the page');
  }
});

// Watch for changes in senderState and fetch localities accordingly
watch(() => form.senderState, fetchLocalities);
// Watch for changes in recipientState and fetch localities accordingly
watch(() => form.recipientState, fetchLocalitiesForRecipient);

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

// Function to Re-Fetch shipments
const refetchShipments = async () => {
  const response = await axios.get('/api/shipments');
  // Emit an event to notify the parent component
  instance.emit('update-displayed-shipments', response.data);
}

// Function to add a new shipment
const addShipment = async () => {
  try {
    const formData = form.data(); // Retrieve form data as a plain JavaScript object
    // Make a request to add the shipment using form data
    const response = await axios.post('/api/shipments', formData);
    if (response.status === 200) {
      // Show success notification
      props.showNotification("Shipment added successfully");

      // Call the refetchShipments to display updated shipments list
      await refetchShipments();

      // Scroll to the last shipment
      props.scrollToLastShipment();

      // Emit an event to notify the parent component to update displayed shipments
      instance.emit('shipments-updated', props.shipments);

      // Update displayed shipments
      props.updateDisplayedShipments();
    } else {
      // Alert the error message
      alert("Error adding shipment, please try again");
    }
  } catch (error) {
    // Alert the error message
    alert('Error adding shipment, please try again');
  }};
</script>

<template>
  <!-- Main container -->
  <div class="py-12 flex justify-center">
    <!-- Card container -->
    <div class="max-w-3xl w-full bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
      <!-- Form to add a new shipment -->
      <h3>Add a Shipment</h3>
      <!-- Form submission handler -->
      <form @submit.prevent="addShipment">
        <!-- Item Details section -->
        <div class="bg-purple-300 rounded-md">
          <!-- Button to toggle item details -->
          <button @click="toggleItem" type="button" class="w-full bg-purple-500 text-white
                                    text-left p-2 rounded-md mb-1 flex justify-between items-center">
            <span>Item Details</span>
            <!-- SVG icon for toggle -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 transform rotate-0 transition-transform" :class="{ 'rotate-180': showItemDetails }" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 12a1 1 0 0 1-1-1V7a1 1 0 0 1 2 0v4a1 1 0 0 1-1 1zM8 9a1 1 0 0 1 0-2h4a1 1 0 0 1 0 2H8z" clip-rule="evenodd" />
            </svg>
          </button>
          <!-- Item Details form -->
          <div v-show="itemDetails" class="border border-purple-500 rounded-md p-4">
            <!-- Item Name -->
            <label for="itemName" class="block text-gray-700 text-sm font-bold mb-2">Item Name</label>
            <input type="text" id="itemName" v-model="form.itemName" class="form-input w-full">
            <!-- Item Description -->
            <label for="itemDescription" class="block text-gray-700 text-sm font-bold mb-2">Item Description</label>
            <textarea id="itemDescription" v-model="form.itemDescription" class="form-textarea w-full"></textarea>
          </div>
        </div>
        <!-- Sender Details section -->
        <div class="bg-purple-300 rounded-md">
          <!-- Button to toggle sender details -->
          <button @click="toggleSender" type="button" class="w-full bg-purple-500 text-white
                                    text-left p-2 rounded-md mb-1 flex justify-between items-center">
            <span>Sender Details</span>
            <!-- SVG icon for toggle -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 transform rotate-0 transition-transform" :class="{ 'rotate-180': showItemDetails }" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 12a1 1 0 0 1-1-1V7a1 1 0 0 1 2 0v4a1 1 0 0 1-1 1zM8 9a1 1 0 0 1 0-2h4a1 1 0 0 1 0 2H8z" clip-rule="evenodd" />
            </svg>
          </button>
          <!-- Sender Details form -->
          <div v-show="senderDetails" class="border border-purple-500 rounded-md p-4">
            <!-- Sender Name -->
            <label for="senderName" class="block text-gray-700 text-sm font-bold mb-2">Sender Name</label>
            <input type="text" id="senderName" v-model="form.senderName" class="form-input w-full">
            <!-- Sender Email -->
            <label for="senderEmail" class="block text-gray-700 text-sm font-bold mb-2">Sender Email</label>
            <input type="email" id="senderEmail" v-model="form.senderEmail" class="form-input w-full">
            <!-- Sender Phone -->
            <label for="senderPhone" class="block text-gray-700 text-sm font-bold mb-2">Sender Phone</label>
            <input type="text" id="senderPhone" v-model="form.senderPhone" class="form-input w-full">
            <!-- Sender State -->
            <label for="senderState" class="block text-gray-700 text-sm font-bold mb-2">Sender State</label>
            <!-- Dropdown menu for sender state -->
            <select id="senderState" v-model="form.senderState" class="form-select w-full">
              <option v-for="state in states" :key="state.id" :value="state.id">{{ state.name }}</option>
            </select>

            <!-- Sender Locality -->
            <label for="senderLocality" class="block text-gray-700 text-sm font-bold mb-2">Sender Locality</label>
            <!-- Dropdown menu for sender locality -->
            <select id="senderLocality" v-model="form.senderLocality" class="form-select w-full">
              <option v-for="locality in localities" :key="locality.id" :value="locality.id">{{ locality.name }}</option>
            </select>
            <!-- Sender Street -->
            <label for="senderStreet" class="block text-gray-700 text-sm font-bold mb-2">Sender Street</label>
            <input type="text" id="senderStreet" v-model="form.senderStreet" class="form-input w-full">
            <!-- Sender Address Details -->
            <label for="senderAddressDetails" class="block text-gray-700 text-sm font-bold mb-2">Sender Address Details</label>
            <textarea id="senderAddressDetails" v-model="form.senderAddressDetails" class="form-textarea w-full"></textarea>
          </div>
        </div>

        <!-- Recipient Details section -->
        <div class="bg-purple-300 rounded-md">
          <!-- Button to toggle recipient details -->
          <button @click="toggleRecipient" type="button" class="w-full bg-purple-500 text-white
                                    text-left p-2 rounded-md mb-1 flex justify-between items-center">
            <span>Recipient Details</span>
            <!-- SVG icon for toggle -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 transform rotate-0 transition-transform" :class="{ 'rotate-180': showItemDetails }" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 12a1 1 0 0 1-1-1V7a1 1 0 0 1 2 0v4a1 1 0 0 1-1 1zM8 9a1 1 0 0 1 0-2h4a1 1 0 0 1 0 2H8z" clip-rule="evenodd" />
            </svg>
          </button>
          <!-- Recipient Details form -->
          <div v-show="recipientDetails" class="border border-purple-500 rounded-md p-4">
            <!-- Recipient Name -->
            <label for="recipientName" class="block text-gray-700 text-sm font-bold mb-2">Recipient Name</label>
            <input type="text" id="recipientName" v-model="form.recipientName" class="form-input w-full">
            <!-- Recipient Email -->
            <label for="recipientEmail" class="block text-gray-700 text-sm font-bold mb-2">Recipient Email</label>
            <input type="email" id="recipientEmail" v-model="form.recipientEmail" class="form-input w-full">
            <!-- Recipient Phone -->
            <label for="recipientPhone" class="block text-gray-700 text-sm font-bold mb-2">Recipient Phone</label>
            <input type="text" id="recipientPhone" v-model="form.recipientPhone" class="form-input w-full">
            <!-- Recipient State -->
            <label for="recipientState" class="block text-gray-700 text-sm font-bold mb-2">Recipient State</label>
            <!-- Dropdown menu for Recipient state -->
            <select id="recipientState" v-model="form.recipientState" class="form-select w-full">
              <option v-for="state in states" :key="state.id" :value="state.id">{{ state.name }}</option>
            </select>

            <!-- Recipient Locality -->
            <label for="recipientLocality" class="block text-gray-700 text-sm font-bold mb-2">Recipient Locality</label>
            <!-- Dropdown menu for Recipient locality -->
            <select id="recipientLocality" v-model="form.recipientLocality" class="form-select w-full">
              <option v-for="locality in recipientLocalities" :key="locality.id" :value="locality.id">{{ locality.name }}</option>
            </select>
            <!-- Recipient Street -->
            <label for="recipientStreet" class="block text-gray-700 text-sm font-bold mb-2">Recipient Street</label>
            <input type="text" id="recipientStreet" v-model="form.recipientStreet" class="form-input w-full">
            <!-- Recipient Address Details -->
            <label for="recipientAddressDetails" class="block text-gray-700 text-sm font-bold mb-2">Recipient Address Details</label>
            <textarea id="recipientAddressDetails" v-model="form.recipientAddressDetails" class="form-textarea w-full"></textarea>
            <!-- Recipient Near Facility -->
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
        <!-- Submit button -->
        <div class="mb-4">
          <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Shipment</button>
        </div>
      </form>
    </div>
  </div>
</template>
