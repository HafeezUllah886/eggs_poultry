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
        Schema::create('sale_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales', 'id');
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->foreignId('product_id')->constrained('products', 'id');
            $table->decimal('price', 10, 2);
            $table->decimal('price_pkr', 10, 2);
            $table->decimal('qty', 10, 2);
            $table->decimal('loose', 10, 2);
            $table->decimal('bonus', 10, 2);
            $table->decimal('pc', 10, 2);
            $table->decimal('amount', 15, 2);
            $table->decimal('amount_pkr', 15, 2);
            $table->string('unit_name')->nullable();
            $table->bigInteger('unit_value')->nullable();
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
        Schema::dropIfExists('sale_details');
    }
};
