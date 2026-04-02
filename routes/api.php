<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MedicationController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\AlertController;
use App\Http\Controllers\Api\ExportController;
use Illuminate\Http\Request;

// Rutas públicas
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas con Sanctum
Route::middleware('api.auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Módulo de farmacovigilancia
    Route::get('/medications/search', [MedicationController::class, 'search']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::get('/customers/{id}', [CustomerController::class, 'show']);
    Route::post('/alerts/send', [AlertController::class, 'send']);

    Route::get('/orders/export/excel', [ExportController::class, 'exportExcel']);
    Route::get('/orders/export/pdf', [ExportController::class, 'exportPDF']);
});