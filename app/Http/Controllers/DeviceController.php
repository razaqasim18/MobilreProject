<?php

namespace App\Http\Controllers;

use Google\Client;
use Google\Service\AndroidManagement;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    protected $androidService;

    public function __construct()
    {
        $client = new Client();
        $client->useApplicationDefaultCredentials();
        $client->addScope(AndroidManagement::ANDROIDMANAGEMENT);

        $this->androidService = new AndroidManagement($client);
    }

    // Get all devices
    public function getAllDevices($enterpriseId)
    {
        $devices = $this->androidService->enterprises_devices->listEnterprisesDevices($enterpriseId);
        return response()->json($devices->getDevices());
    }

    // Lock a device
    public function lockDevice(Request $request, $enterpriseId, $deviceId)
    {
        $deviceName = "enterprises/$enterpriseId/devices/$deviceId";
        $response = $this->androidService->enterprises_devices->lock($deviceName);
        return response()->json($response);
    }

    // Wipe a device
    public function wipeDevice(Request $request, $enterpriseId, $deviceId)
    {
        $deviceName = "enterprises/$enterpriseId/devices/$deviceId";
        $response = $this->androidService->enterprises_devices->wipe($deviceName);
        return response()->json($response);
    }

    // Show message on device
    public function showMessage(Request $request, $enterpriseId, $deviceId)
    {
        $deviceName = "enterprises/$enterpriseId/devices/$deviceId";
        $message = $request->input('message');
        $policy = [
            'applications' => [
                [
                    'packageName' => 'com.example.yourapp',
                    'lockTaskAllowed' => true,
                    'message' => $message,
                ]
            ]
        ];
        $response = $this->androidService->enterprises_devices->patch($deviceName, $policy);
        return response()->json($response);
    }

    // Remove a device
    public function removeDevice(Request $request, $enterpriseId, $deviceId)
    {
        $deviceName = "enterprises/$enterpriseId/devices/$deviceId";
        $response = $this->androidService->enterprises_devices->delete($deviceName);
        return response()->json($response);
    }

    // Generate enrollment link or QR code
    public function generateEnrollmentToken($enterpriseId)
    {
        $token = new AndroidManagement\EnrollmentToken();
        $response = $this->androidService->enterprises_enrollmentTokens->create("enterprises/$enterpriseId", $token);
        return response()->json($response);
    }
}
