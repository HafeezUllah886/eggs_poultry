<?php

use App\Http\Controllers\ProductionController;
use App\Http\Controllers\SaleController;
use App\Http\Middleware\confirmPassword;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    
    Route::resource('production', ProductionController::class);
  
    Route::post('productions/complete', [ProductionController::class, 'complete'])->name('production.complete');
   


});
