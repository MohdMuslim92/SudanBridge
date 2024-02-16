<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use App\Models\Item;
use App\Models\Recipient;
use App\Models\Sender;
use App\Models\Shipment;
use Illuminate\Support\Str; // Import the Str class to use the Str::random() method

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

//        \Log::info($shipments);
        // Return the processed shipments data as a JSON response
        return response()->json($shipments);
    }
    public function store(Request $request)
    {
        // Generate a tracking token
        $trackingToken = Str::random(10); // Generates a random string of length 10
        // Get the current user ID
        $userId = auth()->user()->id;

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
        $recipient->near_facility_id = auth()->user()->facility_id;
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
        $shipment->save();

        // Return a response
        return response()->json(['message' => 'Shipment created successfully'], 201);
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
            'recipient.near_facility_id' => 'required|numeric',
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
            'near_facility_id' => $validatedData['recipient']['near_facility_id'],
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

    public function destroy($id)
    {
        // Find the shipment by id
        $shipment = Shipment::findOrFail($id);

        // Delete the shipment
        $shipment->delete();

        return response()->json(null, 204);
    }
}
