<?php

use App\Http\Controllers\OilProductsController;
use App\Http\Controllers\OilPurchaseController;
use App\Http\Controllers\OilStockController;
use App\Http\Controllers\StockController;
use App\Http\Middleware\adminCheck;
use Illuminate\Support\Facades\Route;

Route::middleware('auth', adminCheck::class)->group(function () {

   Route::resource('oil_products', OilProductsController::class);

   Route::resource('oil_purchases', OilPurchaseController::class);

   Route::get('oilpurchase/getproduct/{id}', [OilPurchaseController::class, 'getProduct'])->name('oilpurchase.getproduct');

   Route::get('oilpurchase/getproductByCode/{code}', [OilPurchaseController::class, 'getProductByCode'])->name('oilpurchase.getproductByCode');

   Route::get('products/stock/{id}/{from}/{to}', [OilStockController::class, 'show'])->name('stockDetails');
   Route::resource('product_stock', OilStockController::class);

});

