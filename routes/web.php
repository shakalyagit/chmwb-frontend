<?php

use App\Http\Controllers\NoticeController;
use App\Http\Controllers\PractitionerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ThankYouController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/search-physician', [PractitionerController::class, 'search_physician'])->name('search_physician');
Route::post('/search-physician-action', [PractitionerController::class, 'search_physician_action'])->name('search_physician_action');
Route::get('/download-physician-pdf', [PractitionerController::class, 'download_physician_pdf'])->name('download_physician_pdf');
Route::get('/notice-board', [NoticeController::class, 'notice_board'])->name('notice_board');
Route::post('/notice-board/filter', [NoticeController::class, 'filter'])->name('notice.filter');
Route::get('/notice/{encryptedId}', [NoticeController::class, 'show'])->name('notice.show');
Route::get('/media-download/{id}', [NoticeController::class, 'download'])->name('media.download');

// Thank You Page Routes
Route::get('/thank-you/{referenceId}', [ThankYouController::class, 'show'])->name('thank-you.show');
Route::get('/thank-you/{referenceId}/download', [ThankYouController::class, 'downloadPdf'])->name('thank-you.download');
