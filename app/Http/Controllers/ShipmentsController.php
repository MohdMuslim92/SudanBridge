<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use App\Models\Item;
use App\Models\Recipient;
use App\Models\Sender;
use App\Models\Shipment;
use Illuminate\Support\Str; // Import the Str class to use the Str::random() method
use Illuminate\Support\Facades\Mail;
use App\Mail\ShipmentCreated;
use App\Mail\ShipmentCreatedForRecipient;
use App\Mail\SenderShipmentOutForDelivery;
use App\Mail\RecipientShipmentOutForDelivery;
use App\Mail\ShipmentDelivered;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\Log;

class ShipmentsController extends Controller
{
    public function index()
    {
        // Retrieve all shipments with related data
        $shipments = Shipment::with([
            'item',
            'sender',
            'sender.address',
            'recipient',
            'recipient.address',
            'recipient.facility'
        ])->get();

        // Return the processed shipments data as a JSON response
        return response()->json($shipments);
    }

    public function getShipmentByToken($token)
    {
        $shipment = Shipment::with([
            'item',
            'sender',
            'sender.address',
            'recipient',
            'recipient.address',
            'recipient.facility',
            'user.facility',
            'facility',
            'status'
        ])->where('tracking_token', $token)->first();

        if ($shipment) {
            // Return the shipment details as JSON response
            return response()->json($shipment);
        } else {
            // If shipment not found, return a message
            return response()->json(['error' => 'Shipment not found']);
        }
    }

    public function store(Request $request)
    {
        // Generate a random string of length 10
        $trackingToken = Str::random(10);
        // Get the current user ID
        $userId = auth()->user()->id;
        $origin_facility = auth()->user()->facility_id;

        // Validate incoming request data
        $validatedData = $request->validate([
            'itemName' => 'required|string',
            'itemDescription' => 'required|string',
            'senderName' => 'required|string',
            'senderEmail' => 'required|email',
            'senderPhone' => 'required|string',
            'senderState' => 'required|string',
            'senderCity' => 'required|string',
            'senderStreet' => 'required|string',
            'senderAddressDetails' => 'nullable|string',
            'recipientName' => 'required|string',
            'recipientEmail' => 'required|email',
            'recipientPhone' => 'required|string',
            'recipientState' => 'required|string',
            'recipientCity' => 'required|string',
            'recipientStreet' => 'required|string',
            'recipientAddressDetails' => 'nullable|string',
            'recipientNearFacility' => 'required|numeric',
        ]);

        // Create an address for the sender
        $senderAddress = new Address();
        $senderAddress->street = $validatedData['senderStreet'];
        $senderAddress->city = $validatedData['senderCity'];
        $senderAddress->state = $validatedData['senderState'];
        $senderAddress->details = $validatedData['senderAddressDetails'];
        $senderAddress->save();

        // Create an address for the recipient
        $recipientAddress = new Address();
        $recipientAddress->street = $validatedData['recipientStreet'];
        $recipientAddress->city = $validatedData['recipientCity'];
        $recipientAddress->state = $validatedData['recipientState'];
        $recipientAddress->details = $validatedData['recipientAddressDetails'];
        $recipientAddress->save();

        // Create a sender
        $sender = new Sender();
        $sender->name = $validatedData['senderName'];
        $sender->email = $validatedData['senderEmail'];
        $sender->phone = $validatedData['senderPhone'];
        $sender->address_id = $senderAddress->id; // Assign the address ID
        $sender->save();

        // Create a recipient
        $recipient = new Recipient();
        $recipient->name = $validatedData['recipientName'];
        $recipient->email = $validatedData['recipientEmail'];
        $recipient->phone = $validatedData['recipientPhone'];
        $recipient->address_id = $recipientAddress->id; // Assign the address ID
        // Assign the user's facility ID
        $recipient->near_facility_id = $validatedData['recipientNearFacility'];
        $recipient->save();

        // Create an item
        $item = new Item();
        $item->name = $validatedData['itemName'];
        $item->description = $validatedData['itemDescription'];
        $item->save();

        // Create a shipment
        $shipment = new Shipment();
        $shipment->item_id = $item->id; // Assign the item ID
        $shipment->sender_id = $sender->id; // Assign the sender ID
        $shipment->recipient_id = $recipient->id; // Assign the recipient ID
        $shipment->tracking_token = $trackingToken; // Assign the tracking token
        $shipment->user_id = $userId; // Assign the current user ID
        $shipment->origin_facility_id = $origin_facility; // Assign the current user facility ID

        // Generate QR code content (URL)
        $qrCodeContent = route('shipments.update_status_via_qr', ['tracking_token' => $trackingToken]);

        // Create QR code SVG
        $renderer = new ImageRenderer(
            new RendererStyle(400),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $qrCodeSvgPath = public_path('qrcodes/' . $trackingToken . '.svg');
        $writer->writeFile($qrCodeContent, $qrCodeSvgPath);

        // Update shipment with QR code image path
        $shipment->qr_code_image = 'qrcodes/' . $trackingToken . '.svg';

        $shipment->save();

        // Send email to the sender
        Mail::to($validatedData['senderEmail'])->send(new ShipmentCreated($shipment));
        // Send email to the recipient
        Mail::to($validatedData['recipientEmail'])->send(new ShipmentCreatedForRecipient($shipment));
    }

    public function update(Request $request, $id)
    {
        // Find the shipment by id
        $shipment = Shipment::findOrFail($id);

        // Validate incoming request data
        $validatedData = $request->validate([
            'item.name' => 'required|string',
            'item.description' => 'required|string',
            'sender.name' => 'required|string',
            'sender.email' => 'required|email',
            'sender.phone' => 'required|string',
            'sender.address.street' => 'required|string',
            'sender.address.city' => 'required|string',
            'sender.address.state' => 'required|string',
            'sender.address.details' => 'required|string',
            'recipient.name' => 'required|string',
            'recipient.email' => 'required|email',
            'recipient.phone' => 'required|string',
            'recipient.address.details' => 'required|string',
            'recipient.address.street' => 'required|string',
            'recipient.address.city' => 'required|string',
            'recipient.address.state' => 'required|string',
            'recipient.facility.id' => 'required|numeric',
        ]);

        // Update the item
        $shipment->item->update([
            'name' => $validatedData['item']['name'],
            'description' => $validatedData['item']['description'],
        ]);

        // Update the sender
        $shipment->sender->update([
            'name' => $validatedData['sender']['name'],
            'email' => $validatedData['sender']['email'],
            'phone' => $validatedData['sender']['phone'],
        ]);

        // Update the sender's address
        $shipment->sender->address->update([
            'street' => $validatedData['sender']['address']['street'],
            'city' => $validatedData['sender']['address']['city'],
            'state' => $validatedData['sender']['address']['state'],
            'details' => $validatedData['sender']['address']['details'],
        ]);

        // Update the recipient
        $shipment->recipient->update([
            'name' => $validatedData['recipient']['name'],
            'email' => $validatedData['recipient']['email'],
            'phone' => $validatedData['recipient']['phone'],
            'near_facility_id' => $validatedData['recipient']['facility']['id'],
        ]);

        // Update the recipient's address
        $shipment->recipient->address->update([
            'street' => $validatedData['recipient']['address']['street'],
            'city' => $validatedData['recipient']['address']['city'],
            'state' => $validatedData['recipient']['address']['state'],
            'details' => $validatedData['recipient']['address']['details'],
        ]);

        // Return a response
        return response()->json(['message' => 'Shipment updated successfully'], 200);
    }

    public function updateStatus(Request $request, $token)
    {
        try {
            // Validate incoming request data
            $validatedData = $request->validate([
                'status_id' => 'required|numeric',
            ]);

            // Find the shipment by token
            $shipment = Shipment::where('tracking_token', $token)->firstOrFail();

            // Update the shipment status
            $shipment->status_id = $validatedData['status_id'];
            $shipment->user_id = auth()->user()->id;
            $shipment->save();
            if ($shipment->recipient->near_facility_id === auth()->user()->facility_id)
            {
                // Check if the shipment is pending at the last facility
                if ($shipment->status_id === 1) {
                    // Send email notification to sender
                    Mail::to($shipment->sender->email)->send(new SenderShipmentOutForDelivery($shipment));
                    // Send email notification to recipient
                    Mail::to($shipment->recipient->email)->send(new RecipientShipmentOutForDelivery($shipment));
                }
                // Check if the shipment has delivered to the recipient
                if ($shipment->status_id === 4)
                {
                    // Send email notification to sender
                    Mail::to($shipment->sender->email)->send(new ShipmentDelivered($shipment, $shipment->sender->name));
                    // Send email notification to recipient
                    Mail::to($shipment->recipient->email)->send(new ShipmentDelivered($shipment, $shipment->recipient->name));
                }
            }
            // Return a success response
            return response()->json(['message' => 'Shipment status updated successfully'], 200);
        } catch (\Exception $e) {
            // Handle any errors
            return response()->json(['error' => 'Failed to update shipment status'], 400);
        }
    }

    public function updateStatusViaQR(Request $request, $token)
    {
        try {
            // Get all the shipment details
            $shipmentDetails = Shipment::where('tracking_token', $token)->firstOrFail();

            // Check if the shipment is at the last facility
            if (auth()->user()->facility->location === $shipmentDetails->recipient->facility->location)
            {
                // Check if the shipment is Pending
                if ($shipmentDetails->status_id === 1)
                {
                    // Change the shipment to out for delivery and change the user_id
                    $shipmentDetails->status_id = 3;
                    $shipmentDetails->user_id = auth()->user()->id;
                    // Send email notification to sender
                    Mail::to($shipmentDetails->sender->email)->send(new SenderShipmentOutForDelivery($shipmentDetails));
                    // Send email notification to recipient
                    Mail::to($shipmentDetails->recipient->email)->send(new RecipientShipmentOutForDelivery($shipmentDetails));
                }
                // Check if the shipment is Out for delivery
                elseif ($shipmentDetails->status_id === 3) {
                    // Change the shipment to Delivered and change the user_id
                    $shipmentDetails->status_id = 4;
                    $shipmentDetails->user_id = auth()->user()->id;
                    // Send email notification to sender
                    Mail::to($shipmentDetails->sender->email)->send(new ShipmentDelivered($shipmentDetails, $shipmentDetails->sender->name));
                    // Send email notification to recipient
                    Mail::to($shipmentDetails->recipient->email)->send(new ShipmentDelivered($shipmentDetails, $shipmentDetails->recipient->name));
                }
                // Check if the shipment is Delivered
                elseif ($shipmentDetails->status_id === 4) {
                    // Do nothing
                } else {
                    // Change the shipment to Pending and change the user_id
                    $shipmentDetails->status_id = 1;
                    $shipmentDetails->user_id = auth()->user()->id;
                }
            }
            // If the shipment is not at the last facility
            else {
                // Check if the shipment is Pending
                if ($shipmentDetails->status_id === 1) {
                    // Change the shipment to In Transit and change the user_id
                    $shipmentDetails->status_id = 2;
                    $shipmentDetails->user_id = auth()->user()->id;
                } else {
                    // Change the shipment to Pending and change the user_id
                    $shipmentDetails->status_id = 1;
                    $shipmentDetails->user_id = auth()->user()->id;
                }
            }
            $shipmentDetails->save();
            // Send success message with the url
            $message = 'Shipment status updated successfully';
            // Redirect to the user dashboard with a success message as query parameter
            return redirect()->route('user.dashboard', ['status' => $message]);

        } catch (\Exception $e) {
            // Handle any errors
            $message = 'Error update Shipment status';
            // Redirect to the user dashboard with a success message as query parameter
            return redirect()->route('user.dashboard', ['status' => $message]);
        }
    }

    public function destroy($id)
    {
        // Find the shipment by id
        $shipment = Shipment::findOrFail($id);

        // Delete the shipment
        $shipment->delete();

        return response()->json(null, 204);
    }
}
