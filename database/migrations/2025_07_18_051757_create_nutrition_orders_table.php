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
        Schema::create('nutrition_orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('identifier')->nullable();
            $table->string('status')->nullable();
            $table->string('intent')->nullable();
            $table->uuid('patient_id')->nullable();
            $table->uuid('orderer_id')->nullable();
            $table->dateTime('date_time')->nullable();
            $table->json('oral_diet')->nullable();
            $table->json('supplement')->nullable();
            $table->json('enteral_formula')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nutrition_orders');
    }
};
