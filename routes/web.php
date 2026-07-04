<?php

use App\Http\Controllers\GrievanceController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public portal (no login)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/grm-process', [HomeController::class, 'process'])->name('process');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
Route::get('/resources', [HomeController::class, 'resources'])->name('resources');
Route::get('/privacy-policy', [HomeController::class, 'privacy'])->name('privacy');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/resources/download/{file}', [HomeController::class, 'downloadDoc'])->name('resources.download');

Route::get('/submit', [GrievanceController::class, 'create'])->name('grievance.create');
Route::post('/submit', [GrievanceController::class, 'store'])->name('grievance.store');
Route::get('/submitted/{trackingId}', [GrievanceController::class, 'submitted'])->name('grievance.submitted');

Route::get('/track', [GrievanceController::class, 'trackForm'])->name('track');
Route::post('/track', [GrievanceController::class, 'track'])->name('track.search');
Route::get('/track/{trackingId}/acknowledgment', [GrievanceController::class, 'acknowledgmentPdf'])->name('grievance.ack');
Route::get('/track/{trackingId}/resolution', [GrievanceController::class, 'resolutionPdf'])->name('grievance.resolution');
Route::post('/track/{trackingId}/feedback', [GrievanceController::class, 'feedback'])->name('grievance.feedback');
Route::post('/track/{trackingId}/reopen', [GrievanceController::class, 'reopen'])->name('grievance.reopen');

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
