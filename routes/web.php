<?php

use App\Http\Controllers\AbsenController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\PresenceDetailController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.index');
})->name('home');

// Admin
Route::resource('presence', PresenceController::class);
Route::delete('hapus_detail/{id}', [PresenceDetailController::class, 'destroy'])->name('hapus_detail');
Route::get('presence-detail/export-pdf/{id}', [PresenceDetailController::class, 'exportPdf'])->name('presence-detail.export-Pdf');

// Publik
Route::get('absen/{slug}', [AbsenController::class, 'index'])->name('absen.index');
Route::post('absen/{id}/save', [AbsenController::class, 'save'])->name('absen.save');
