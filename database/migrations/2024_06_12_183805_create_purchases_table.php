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
            $table->foreignId('transporter_id')->constrained('accounts', 'id')->cascadeOnDelete();
            $table->string("year")->nullable();
            $table->string("category")->nullable();
            $table->string("maker")->nullable();
            $table->string("model")->nullable();
            $table->string("chassis")->unique();
            $table->string("loot")->nullable();
            $table->string("yard")->nullable();
            $table->date('date')->nullable();
            $table->string("auction")->nullable();
            $table->float("price")->nullable();
            $table->float("ptax")->nullable();
            $table->float("afee")->nullable();
            $table->float("atax")->nullable();
            $table->float("transport_charges")->nullable();
            $table->float("total")->nullable();
            $table->float("recycle")->nullable();
            $table->date('adate')->nullable();
            $table->date('ddate')->nullable();
            $table->string("number_plate")->nullable();
            $table->date('nvalidity')->nullable();
            $table->text('notes')->nullable();
            $table->string("status")->default("Available");
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
