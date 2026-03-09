<?php

// routes/api.php
use App\Http\Controllers\Api\DashboardController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/dashboard/summary', [DashboardController::class, 'summary']);
});