<script setup>
// Import necessary modules
import {defineProps, ref, watch, getCurrentInstance} from "vue";
import axios from "axios";
// Import the useFacilities function
import useFacilities from '../facilities.js';
// Import the ShipmentQr component
import ShipmentQrComponent from './ShipmentQrComponent.vue';

const instance = getCurrentInstance(); // Get the current component instance

// Destructure facilitiesResponse from the useFacilities function
const { facilitiesResponse } = useFacilities();

// Define reactive variables
const isModalOpen = ref(false);
const modalShipment = ref(null)
const showItemDetails = ref('');
const showSenderDetails = ref('');
const showRecipientDetails = ref('');
const states = ref([]);
const localities = ref([]);
const recipientLocalities = ref([]);
const senderState = ref('');
const senderLocality = ref('');
const recipientState = ref('');
const recipientLocality = ref('');

// Define toggle functions
const toggleItemDetails = () => {
  showItemDetails.value = !showItemDetails.value;
};
const toggleSenderDetails = () => {
  showSenderDetails.value = !showSenderDetails.value;
};
const toggleRecipientDetails = () => {
  showRecipientDetails.value = !showRecipientDetails.value;
};

// Define props received from the parent component
const props = defineProps({
  showNotification: Function,
  closeModal: Function,
  scrollToLastShipment: Function,
  updateDisplayedShipments: Function,
  shipments: Array, // Define prop to receive shipments data
  displayedShipments: Array, // Define prop to receive displayedshipments data
  currentIndex: Number  // Define prop to receive currentIndex as a Number type
});

// Initialize displayedShipments and currentIndex
const displayedShipments = ref([]);
displayedShipments.value = props.displayedShipments;
const currentIndex = ref(null);

// Watch props for changes and update displayedShipments and currentIndex accordingly
watch(props, (newProps) => {
  if (newProps.currentIndex !== undefined) {
    currentIndex.value = newProps.currentIndex;
  } else {
    alert("current index is null");
  }
}, { immediate: true });

watch(props, (newProps) => {
  if (newProps.displayedShipments !== undefined) {
    displayedShipments.value = newProps.displayedShipments;
  } else {
    alert("Please reload the page");
  }
}, { immediate: true });

// Function to open shipment details modal
const openShipmentDetailsModal = async (shipment) => {
  modalShipment.value = shipment;
  $('#shipmentDetailsModal').modal('show'); // Manually trigger the Bootstrap modal

  senderState.value = modalShipment.value.sender.address.state_id ;
  senderLocality.value = modalShipment.value.sender.address.locality_id;
  recipientState.value = modalShipment.value.recipient.address.state_id;
  recipientLocality.value = modalShipment.value.recipient.address.locality_id;

  // Fetch states from the backend
  const statesResponse = await axios.get('/api/states');
  states.value = statesResponse.data;

  // Fetch localities based on the selected state
  await fetchLocalities();
  await fetchLocalitiesForRecipient();

};

// Function to fetch localities based on the selected state
const fetchLocalities = async () => {
  try {
    if (senderState) {
      const localitiesResponse = await axios.get(`/api/states/${senderState.value}/localities`);
      localities.value = localitiesResponse.data;
    }
  } catch (error) {
    alert('Error fetching localities, please reload the page');
  }
};

// Function to fetch localities for recipient based on the selected state
const fetchLocalitiesForRecipient = async () => {
  try {
    if (recipientState) {
      const localitiesRecipientResponse = await axios.get(`/api/states/${recipientState.value}/localities`);
      recipientLocalities.value = localitiesRecipientResponse.data;
    }
  } catch (error) {
    alert('Error fetching localities, please reload the page');
  }
};

// Watch senderState for changes and fetch localities accordingly
watch(() => senderState.value, fetchLocalities);

// Watch for changes in recipientState and fetch localities accordingly
watch(() => recipientState.value, fetchLocalitiesForRecipient);

// Function to open the modal
const openModal = () => {
  isModalOpen.value = true;
};

// Function to open shipment details and QR code modal
const openShipmentQrModal = (shipment) => {
  modalShipment.value = shipment;
  openModal();
  $('#shipmentQrModal').modal('show'); // Manually trigger the Bootstrap modal
};

// Function to scroll shipments
const scrollShipments = (direction) => {
  const newIndex = currentIndex.value + direction * 2;
  if (newIndex >= 0 && newIndex < props.shipments.length) {
    currentIndex.value = newIndex;
    // Emit the event to update currentIndex in the parent component
    instance.emit('update-current-index', newIndex);
    props.updateDisplayedShipments();
  }
};

// Function to save changes made to shipment details
const saveChanges = async () => {
  try {
    // Make a request to update the shipment details
    if (modalShipment.value) {
      // Update sender state and locality IDs in modalShipment
      modalShipment.value.sender.address.state_id = senderState;
      modalShipment.value.sender.address.locality_id = senderLocality;

      // Update recipient state and locality IDs in modalShipment
      modalShipment.value.recipient.address.state_id = recipientState;
      modalShipment.value.recipient.address.locality_id = recipientLocality;

      const response = await axios.put(`/api/shipments/${modalShipment.value.id}`, modalShipment.value);
      // Show notification
      props.showNotification("Shipment details updated");
      // Refetch shipments after update
      const shipmentResponse = await axios.get('/api/shipments');
      props.shipments.value = shipmentResponse.data;
      // Close the Modal
      props.closeModal();
    } else {
      alert('No shipment selected.');
    }
  } catch (error) {
    alert('Error saving changes');
  }
};

// Function to Re-Fetch shipments
const refetchShipments = async () => {
  const response = await axios.get('/api/shipments');
  // Emit an event to notify the parent component
  instance.emit('update-displayed-shipments', response.data);
}

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
      props.showNotification("Shipment deleted successfully");

      // Call the refetchShipments to display updated shipments list
      await refetchShipments();

      // Scroll to the last shipment
      props.scrollToLastShipment();

      // Emit an event to notify the parent component to update displayed shipments
      instance.emit('shipments-updated', props.shipments);

      props.updateDisplayedShipments();
    } catch (error) {
      alert('Error deleting shipment');
      // Show an error message
      alert("Error deleting shipment. Please try again later.");
    }
  }
};

</script>

<template>
  <!-- Container for shipment cards -->
  <div class="py-12 flex justify-center">
    <div class="max-w-3xl w-full bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
      <h3>My Shipments</h3>
      <div class="shipment-cards-container">
        <!-- Iterate over displayed shipments -->
        <div class="shipment-card" v-for="(shipment, index) in displayedShipments" :key="shipment.id">
          <div>
            <div class="d-flex justify-content-between align-items-center">
              <!-- Shipment ID -->
              <span><strong>Shipment ID:</strong> {{ shipment.id }}</span>
              <!-- Document Icon -->
              <button @click="openShipmentQrModal(shipment)" class="btn btn-primary">
                <i class="bi bi-file-text"></i> <!-- Bootstrap document icon -->
              </button>
            </div>
            <!-- Shipment details -->
            <span><strong>Item Name:</strong> {{ shipment.item.name }}</span><br>
            <span><strong>Sender Name:</strong> {{ shipment.sender.name }}</span><br>
            <span><strong>Recipient Name:</strong> {{ shipment.recipient.name }}</span><br>
            <span><strong>Facility:</strong> {{ shipment.recipient.facility ? shipment.recipient.facility.location : 'N/A' }}</span><br>
            <span><strong>Tracking Token:</strong> {{ shipment.tracking_token }}</span>
          </div>
          <!-- Buttons for actions -->
          <div>
            <button @click="openShipmentDetailsModal(shipment)" class="btn btn-primary">Details/Update</button>
            <button @click="deleteShipment(shipment.id)" class="btn btn-danger">Delete</button>
          </div>
        </div>
      </div>
      <!-- Navigation arrows -->
      <div class="navigation-arrows">
        <button @click="() => scrollShipments(-1)" class="arrow-btn left-arrow">&gt;</button>
        <button @click="() => scrollShipments(1)" class="arrow-btn right-arrow">&gt;</button>
      </div>
      <!-- Modal for displaying shipment details -->
      <div class="modal fade" id="shipmentDetailsModal" tabindex="-1" role="dialog" aria-labelledby="shipmentDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog max-w-lg mx-auto" role="document">
          <div class="modal-content bg-white rounded-md shadow-lg">
            <div class="modal-header bg-blue-500 rounded-t-md">
              <!-- Modal title -->
              <h5 class="modal-title text-lg font-bold text-white" id="shipmentDetailsModalLabel">Shipment Details</h5>
              <!-- Close button -->
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
                    <select v-model="senderState" id="state" class="form-select">
                      <option disabled value="">Select State</option>
                      <option v-for="state in states" :key="state.id" :value="state.id" :selected="state.name === senderState">{{ state.name }}</option>
                    </select>
                  </div>
                  <div>
                    <label for="locality">Sender Locality:</label>
                    <select v-model="senderLocality" id="locality" class="form-select">
                      <option disabled value="">Select Locality</option>
                      <option v-for="locality in localities" :key="locality.id" :value="locality.id" :selected="locality.name === senderLocality">{{ locality.name }}</option>
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
                    <select v-model="recipientState" id="state" class="form-select">
                      <option disabled value="">Select State</option>
                      <option v-for="state in states" :key="state.id" :value="state.id" :selected="state.name === recipientState">{{ state.name }}</option>
                    </select>
                  </div>
                  <div>
                    <label for="locality">Recipient Locality:</label>
                    <select v-model="recipientLocality" id="locality" class="form-select">
                      <option disabled value="">Select Locality</option>
                      <option v-for="locality in recipientLocalities" :key="locality.id" :value="locality.id" :selected="locality.name === recipientLocality">{{ locality.name }}</option>
                    </select>
                  </div>
                  <p><strong>Recipient Street:</strong> <input type="text" v-model="modalShipment.recipient.address.street"></p>
                  <p><strong>Recipient Address Details:</strong> <input type="text" v-model="modalShipment.recipient.address.details"></p>
                  <div>
                    <label for="facility" class="text-purple-800">Facility: </label>
                    <select v-model="modalShipment.recipient.facility.id" id="facility" class="form-select input-field">
                      <option disabled value="">Select Facility</option>
                      <option v-for="facility in facilitiesResponse" :key="facility.id" :value="facility.id">
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
      <!-- Modal for displaying shipment details and QR code -->
      <div class="modal fade" id="shipmentQrModal" tabindex="-1" role="dialog" aria-labelledby="shipmentQrModalLabel" aria-hidden="true">
        <div class="modal-dialog max-w-lg mx-auto" role="document">
          <div class="modal-content bg-white rounded-md shadow-lg">
            <!-- Use ShipmentQrComponent and pass shipment details and QR code data -->
            <ShipmentQrComponent v-if="isModalOpen"
                                 :title="'Shipment Details & QR Code'"
                                 :shipment="modalShipment"
                                 :qrCodeData="'../../' + modalShipment.qr_code_image"
                                 :isModalOpen="isModalOpen" @close="closeModal" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
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
