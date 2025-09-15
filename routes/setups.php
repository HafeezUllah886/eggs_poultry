<?php

use App\Http\Controllers\AuctionsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MakersController;
use App\Http\Controllers\PartsController;
use App\Http\Controllers\PaymentCategoriesController;
use App\Http\Controllers\YardController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::resource('yards', YardController::class);
    Route::get('yards/delete/{id}', [YardController::class, 'destroy'])->name('yard.delete');
    Route::resource('auctions', AuctionsController::class);
    Route::resource('makers', MakersController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('parts', PartsController::class);

});

