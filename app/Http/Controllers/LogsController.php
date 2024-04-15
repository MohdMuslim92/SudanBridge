<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use Illuminate\Http\Request;
use Inertia\Inertia;


class LogsController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Logs');
    }

    public function UsersLogDisplay(Request $request)
    {
        return Inertia::render('Users-Log');
    }

    public function getShipmentByToken($token)
    {
        try {
            // Retrieve shipment logs based on shipment token
            $shipmentLogs = Logs::where('token', $token)->with('user')->get();

            // Return the shipment details and logs as JSON response
            return response()->json(['shipment_logs' => $shipmentLogs]);
        } catch (QueryException $e) {
            // Log the error
            \Log::error('Error retrieving shipment logs: ' . $e->getMessage());

            // Return a JSON response with an error message
            return response()->json(['error' => 'An error occurred while retrieving shipment logs.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deletedShipments(Request $request)
    {
        // Fetch shipment logs where action is 'delete'
        $deletedShipmentsLog = Logs::where('action', 'delete')->get();

        return response()->json(['deleted_shipments_log' => $deletedShipmentsLog]);
    }
    public function getUsersLog(Request $request)
    {
        try {
            // Retrieve users log based on actions
            $usersLog = Logs::whereIn('action', ['user-create', 'user-update', 'user-delete'])->with('user')->get();

            // Return the users details and log as JSON response
            return response()->json(['users_log' => $usersLog]);
        } catch (QueryException $e) {
            // Log the error message
            \Log::error('Error retrieving users log: ' . $e->getMessage());

            // Return a JSON response with an error message
            return response()->json(['error' => 'An error occurred while retrieving users log.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
