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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->foreignId('supplierID')->constrained('accounts', 'id');
            $table->string('supplierName')->nullable();
            $table->date('date');
            $table->float('rate')->default(1);
            $table->string('rate_type')->default('multiply');
            $table->float('total')->default(0);
            $table->float('totalPKR')->default(0);
            $table->string("inv")->nullable();
            $table->text('notes')->nullable();
            $table->bigInteger('refID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
