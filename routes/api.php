<?php

use App\Http\Controllers\NoteController;
use Illuminate\Routing\Middleware\ThrottleRequests;

Route::group(['prefix' => 'notes'], function () {
    Route::post('', [NoteController::class, 'store'])
        ->middleware(ThrottleRequests::using('note-store'))
        ->name('notes.store');

    Route::get('{note:slug}', [NoteController::class, 'show'])
        ->middleware(ThrottleRequests::using('note-show'))
        ->name('notes.show');
});
