<?php

use App\Http\Controllers\PollController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PollController::class, 'index']);
Route::post('/polls', [PollController::class, 'store'])->name('polls.store');
