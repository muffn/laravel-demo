<?php

use App\Http\Controllers\PollController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PollController::class, 'index']);
Route::post('/polls', [PollController::class, 'store'])->name('polls.store');

Route::get('/p/{participant_token}', [PollController::class, 'participate'])
    ->where('participant_token', '[A-Za-z0-9]{12}')
    ->name('polls.participate');

Route::post('/p/{participant_token}/vote', [PollController::class, 'vote'])
    ->where('participant_token', '[A-Za-z0-9]{12}')
    ->name('polls.vote');

Route::get('/a/{admin_token}', [PollController::class, 'admin'])
    ->where('admin_token', '[A-Za-z0-9]{24}')
    ->name('polls.admin');
