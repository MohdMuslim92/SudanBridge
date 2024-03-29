<template>
    <div class="custom-modal">
        <div class="modal-content">
            <!-- Modal header -->
            <div class="modal-header">
                <h5 class="modal-title">{{ title }}</h5>
                <button type="button" class="close" aria-label="Close" @click="closeModal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="modal-body" id="modal-body">
                <!-- Display shipment details -->
                <div>
                    <p><strong>Shipment ID:</strong> {{ shipment.id }}</p>
                    <p><strong>Item Name:</strong> {{ shipment.item.name }}</p>
                    <p><strong>Sender:</strong> {{ shipment.sender.name }}</p>
                    <p><strong>Recipient:</strong> {{ shipment.recipient.name }} - {{ shipment.recipient.phone }}</p>
                    <p><strong>Address:</strong> {{ shipment.recipient.address.state.name }} -
                        {{ shipment.recipient.address.locality.name }} -
                        {{ shipment.recipient.address.street }} - {{ shipment.recipient.address.details }}</p>
                </div>
                <!-- Display QR code -->
                <img :src="qrCodeData" alt="QR Code" class="qr-code-image">
            </div>
            <!-- Buttons for printing and downloading -->
            <div class="buttons">
                <button @click="printDetailsAndQR">Print</button>
                <button @click="downloadAsPDF">Download as PDF</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        title: String, // Title of the modal
        shipment: Object, // Shipment details object
        qrCodeData: String, // QR code data as string
        isModalOpen: Boolean,
        closeModal: Function
    },
    methods: {
        async loadScript(src) {
            return new Promise((resolve, reject) => {
                const script = document.createElement('script');
                script.src = src;
                script.onload = resolve;
                script.onerror = reject;
                document.head.appendChild(script);
            });
        },
        // Method to print shipment details and QR code
        async printDetailsAndQR() {
            try {
                // Dynamically import printJS library
                const { default: printJS } = await import('print-js');
                if (printJS) {
                    // Use printJS to print modal body content
                    printJS({
                        printable: 'modal-body',
                        type: 'html',
                    });
                } else {
                    // Alert if printJS library not loaded correctly
                    alert('printJS library not loaded correctly.');
                }
            } catch (error) {
                // Alert if printing fails
                alert('Error printing shipment details');
            }
        },
        // Method to download modal content as PDF
        async downloadAsPDF() {
            try {
                // Load html2pdf library dynamically
                await this.loadScript('https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js');
                const html2pdf = window.html2pdf;
                if (html2pdf) {
                    // Generate PDF from modal body content and save
                    html2pdf().from(document.getElementById('modal-body')).save();
                } else {
                    // Alert if html2pdf library not loaded correctly
                    alert('html2pdf library not loaded correctly.');
                }
            } catch (error) {
                // Alert if there's an error in saving PDF
                alert('Error saving pdf file');
            }
        }
    }
}
</script>

<style scoped>
.custom-modal {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 9999;
    background-color: rgba(0, 0, 0, 0.5);
    width: 80%;
    max-width: 600px;
    border-radius: 8px;
    padding: 20px;
}

.modal-content {
    background-color: #fff;
    border-radius: 8px;
    padding: 20px;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.modal-title {
    font-size: 1.2rem;
}

.close {
    background: none;
    border: none;
    cursor: pointer;
    padding: 0;
    font-size: 1.5rem;
}

.modal-body {
    display: flex;
    justify-content: space-between;
}

.qr-code-image {
    float: right;
    width: 150px;
    height: 150px;
}
.buttons {
    text-align: center;
    margin-top: 20px;
}

.buttons button {
    margin: 0 10px;
}
</style>
