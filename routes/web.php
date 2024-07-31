<?php

use App\Livewire\Tryout;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('do-tryout/{id}', Tryout::class)->name('do-tryout.index');
});
