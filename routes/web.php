<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ThankYouController;

Route::get('/', function () {
    return view('welcome');
});

// Thank You Page Routes
Route::get('/thank-you/{referenceId}', [ThankYouController::class, 'show'])->name('thank-you.show');
Route::get('/thank-you/{referenceId}/download', [ThankYouController::class, 'downloadPdf'])->name('thank-you.download');
