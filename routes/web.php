<?php

use App\Http\Controllers\PractitionerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ThankYouController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/search-physician', [PractitionerController::class, 'search_physician'])->name('search_physician');
Route::post('/search-physician-action', [PractitionerController::class, 'search_physician_action'])->name('search_physician_action');
Route::get('/download-physician-pdf', [PractitionerController::class, 'download_physician_pdf'])->name('download_physician_pdf');

// Thank You Page Routes
Route::get('/thank-you/{referenceId}', [ThankYouController::class, 'show'])->name('thank-you.show');
Route::get('/thank-you/{referenceId}/download', [ThankYouController::class, 'downloadPdf'])->name('thank-you.download');
