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
        Schema::create('oil_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendorID')->constrained('accounts', 'id');
            $table->date('date');
            $table->decimal('tax', 10, 2)->nullable();
            $table->decimal('tax_value', 10, 2)->nullable();
            $table->decimal('total', 10, 2)->nullable();
            $table->text('note')->nullable();
            $table->bigInteger('refID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oil_purchases');
    }
};
