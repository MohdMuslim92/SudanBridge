<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\ShipmentLog;
use Illuminate\Http\Request;
use Inertia\Inertia;


class LogsController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Logs');
    }

    public function getShipmentByToken($token)
    {
        try {
            // Retrieve shipment logs based on shipment token
            $shipmentLogs = ShipmentLog::where('token', $token)->get();

            // Return the shipment details and logs as JSON response
            return response()->json(['shipment_logs' => $shipmentLogs]);
        } catch (QueryException $e) {
            // Log the error
            \Log::error('Error retrieving shipment logs: ' . $e->getMessage());

            // Return a JSON response with an error message
            return response()->json(['error' => 'An error occurred while retrieving shipment logs.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
