<?php

use App\Http\Controllers\Api\V1\ApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Versioned REST API v1
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    // Public Authentication
    Route::post('/auth/login', [ApiController::class, 'login']);

    // Public Catalog & Availability
    Route::get('/practitioners', [ApiController::class, 'practitioners']);
    Route::get('/services', [ApiController::class, 'services']);
    Route::get('/branches', [ApiController::class, 'branches']);
    Route::get('/appointments/slots', [ApiController::class, 'appointmentSlots']);
    Route::post('/appointments', [ApiController::class, 'bookAppointment']);
    Route::get('/modules', [ApiController::class, 'modules']);

    // Authenticated Endpoints
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [ApiController::class, 'logout']);
        Route::get('/auth/user', [ApiController::class, 'user']);

        Route::get('/appointments', [ApiController::class, 'appointments']);
        Route::get('/therapy-sessions', [ApiController::class, 'therapySessions']);
        Route::get('/clinical-notes', [ApiController::class, 'clinicalNotes']);
        Route::get('/invoices', [ApiController::class, 'invoices']);
    });
});
