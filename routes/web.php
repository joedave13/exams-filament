<?php

use App\Filament\Pages\TryoutOnline;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('do-tryout/{id}', TryoutOnline::class)->name('do-tryout.index');
});
