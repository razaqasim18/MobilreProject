<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/devices/{enterpriseId}', [DeviceController::class, 'getAllDevices']);
Route::post('/devices/{enterpriseId}/lock/{deviceId}', [DeviceController::class, 'lockDevice']);
Route::post('/devices/{enterpriseId}/wipe/{deviceId}', [DeviceController::class, 'wipeDevice']);
Route::post('/devices/{enterpriseId}/message/{deviceId}', [DeviceController::class, 'showMessage']);
Route::delete('/devices/{enterpriseId}/{deviceId}', [DeviceController::class, 'removeDevice']);
Route::get('/enrollment/{enterpriseId}', [DeviceController::class, 'generateEnrollmentToken']);
