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
        Schema::create('purchase_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchaseID')->constrained('purchases', 'id');
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->foreignId('productID')->constrained('products', 'id');
            $table->decimal('price', 10, 2);
            $table->decimal('pricePKR', 10, 2);
            $table->decimal('qty', 10, 2);
            $table->decimal('loose', 10, 2);
            $table->decimal('bonus', 10, 2);
            $table->decimal('pc', 10, 2);
            $table->decimal('amount', 15, 2);
            $table->decimal('amountPKR', 15, 2);
            $table->string('unit_name')->nullable();
            $table->bigInteger('unit_value')->nullable();
            $table->date('expiry')->nullable();
            $table->date('date');
            $table->bigInteger('refID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_details');
    }
};
