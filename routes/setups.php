<?php

use App\Http\Controllers\BranchesController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::resource('branches', BranchesController::class);

});

