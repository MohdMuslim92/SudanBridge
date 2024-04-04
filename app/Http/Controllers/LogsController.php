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
        // Get the shipment id based on it's token
        $shipment = Shipment::select('id')
            ->where('tracking_token', $token)
            ->first();

        if ($shipment) {
            // Retrieve shipment logs where shipment_id equals $shipment->id
            $shipmentLogs = ShipmentLog::where('shipment_id', $shipment->id)->get();

            // Return the shipment details and logs as JSON response
            return response()->json(['shipment_logs' => $shipmentLogs]);
        } else {
            // If shipment not found, return a message
            return response()->json(['error' => 'Shipment not found']);
        }
    }

}
