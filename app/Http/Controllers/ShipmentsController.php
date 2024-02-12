<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shipment;

class ShipmentsController extends Controller
{
    public function index()
    {
        // Retrieve all shipments
        $shipments = Shipment::all();
        return response()->json($shipments);
    }

    public function store(Request $request)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Create a new shipment
        $shipment = Shipment::create($validatedData);

        return response()->json($shipment, 201);
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
