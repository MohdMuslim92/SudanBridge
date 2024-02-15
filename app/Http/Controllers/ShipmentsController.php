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
            'item:id,name',
            'sender:id,name',
            'recipient:id,name,near_facility_id',
            'recipient.facility:id,location'])->get();

        \Log::info($shipments);
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Update the shipment
        $shipment->update($validatedData);

        return response()->json($shipment, 200);
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
