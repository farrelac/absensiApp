<?php

use App\Http\Controllers\PresenceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.index');
}) -> name('home');

Route::resource('presence', PresenceController::class);