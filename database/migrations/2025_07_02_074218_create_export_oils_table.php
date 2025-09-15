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
        Schema::create('export_oils', function (Blueprint $table) {
            $table->id();
            $table->foreignId('export_id')->constrained('exports')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('oil_products')->cascadeOnDelete();
            $table->string('qty');
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
        Schema::dropIfExists('export_oils');
    }
};
