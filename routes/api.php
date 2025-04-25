<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    CreditController,
    CustomerController
};

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::resource('customers', CustomerController::class)->except(['create', 'edit']);

Route::get('/customers/{customer}/credits', [CreditController::class, 'getCustomerCredits']);
Route::post('/customers/{customer}/credits', [CreditController::class, 'addCustomerCredits']);
