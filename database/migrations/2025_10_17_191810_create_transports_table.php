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
        Schema::create('transports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transporter_id')->constrained('accounts')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->string('customer_name')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('vehicle_no')->nullable();
            $table->string('from')->nullable();
            $table->string('to')->nullable();
            $table->float('fare')->default(0);
            $table->float('expense')->default(0);
            $table->float('profit')->default(0);
            $table->date('date')->default(now());
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
        Schema::dropIfExists('transports');
    }
};
