<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('oil_purchase_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchaseID')->constrained('oil_purchases', 'id');
            $table->foreignId('productID')->constrained('oil_products', 'id');
            $table->integer('qty');

            $table->decimal('price', 10, 2);
            $table->decimal('amount', 10, 2);
            $table->bigInteger('refID');
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oil_purchase_details');
    }
};
