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
        Schema::create('receive_t_t_s', function (Blueprint $table) {
            $table->id();
            $table->string('received_from');
            $table->foreignId('bank_id')->constrained('accounts', 'id')->cascadeOnDelete();
            $table->date('date');
            $table->decimal('total_dirham', 10, 2)->default(0);
            $table->decimal('bank_charges', 10, 2)->default(0);
            $table->decimal('dirham_received', 10, 2)->default(0);
            $table->decimal('rate', 10, 7)->default(0);
            $table->decimal('total_yen', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receive_t_t_s');
    }
};
