<?php

use App\Http\Controllers\AccountAdjustmentController;
use App\Http\Controllers\AccountsController;
use App\Http\Controllers\ExpenseCategoriesController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\StaffAmountAdjustmentController;
use App\Http\Controllers\TransferController;
use App\Http\Middleware\confirmPassword;
use App\Models\attachment;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('account/view/{filter}', [AccountsController::class, 'index'])->name('accountsList');
    Route::get('account/statement/{id}/{from}/{to}/{orderbooker?}', [AccountsController::class, 'show'])->name('accountStatement');
    Route::get('account/status/{id}', [AccountsController::class, 'status'])->name('account.status');
    Route::resource('account', AccountsController::class);

    
    Route::resource('accounts_adjustments', AccountAdjustmentController::class);
    Route::get('accounts_adjustments/delete/{ref}', [AccountAdjustmentController::class, 'delete'])->name('accounts_adjustments.delete')->middleware(confirmPassword::class);

    Route::resource('transfers', TransferController::class);
    Route::get('transfer/delete/{ref}', [TransferController::class, 'delete'])->name('transfers.delete')->middleware(confirmPassword::class);

    Route::resource('expenses', ExpensesController::class);
    Route::get('expense/delete/{ref}', [ExpensesController::class, 'delete'])->name('expense.delete')->middleware(confirmPassword::class);

    Route::resource('expense_categories', ExpenseCategoriesController::class);


    Route::get('/accountbalance/{id}', function ($id) {
        // Call your Laravel helper function here
        $result = getAccountBalance($id);

        return response()->json(['data' => $result]);
    });


});
