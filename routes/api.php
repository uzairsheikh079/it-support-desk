<?php

use App\Http\Controllers\Api\V1\TicketController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->name('api.')
    ->middleware(['api.key', 'throttle:60,1'])
    ->group(function (): void {
        Route::apiResource('tickets', TicketController::class);
    });
