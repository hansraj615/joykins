<?php

use Illuminate\Support\Facades\Route;

// Override Bagisto REST API endpoint to include review images
Route::middleware('api')->prefix('api/v1')->group(function () {
    Route::get('products/{id}/reviews', [\App\Http\Controllers\Api\ProductReviewController::class, 'index']);
    Route::get('customer/get', [\App\Http\Controllers\Api\CustomerController::class, 'get']);
});
