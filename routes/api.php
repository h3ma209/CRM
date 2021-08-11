<?php

use App\Http\Controllers\ContractController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerCredentialsController;
use App\Http\Controllers\LastInvoiceNumberController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\ReceiptDetailController;
use App\Models\CustomerCredentials;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('customer-list', [CustomerController::class, 'list']);
    Route::get('customer/{customer}/contracts', [CustomerController::class, 'contracts']);
    Route::get('customer/{customer}/receipts', [CustomerController::class, 'receipts']);
    Route::post('customer/{customer}/new-monthly-receipt', [CustomerController::class, 'newMonthlyReceipt']);
    Route::apiResource('customer', CustomerController::class);

    Route::get('receipt/new-invoice-no', [ReceiptController::class, 'newInvoiceNumber']);
    Route::apiResource('receipt', ReceiptController::class);
    Route::apiResource('receipt/detail', ReceiptDetailController::class);


    Route::apiResource('contract', ContractController::class);
    Route::apiResource('contract/credential', CustomerCredentialsController::class);
});
