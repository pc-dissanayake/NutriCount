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
        Schema::create('hospital_unit_diet_amounts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('hospital_unit_id');
            $table->uuid('simple_diet_id');
            $table->uuid('patient_id')->nullable();
            $table->date('date');
            $table->decimal('amount', 10, 2)->nullable();
            $table->timestamps();

            $table->foreign('hospital_unit_id')->references('id')->on('hospital_units')->onDelete('cascade');
            $table->foreign('simple_diet_id')->references('id')->on('simple_diets')->onDelete('cascade');
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('set null');
            $table->unique(['hospital_unit_id', 'simple_diet_id', 'patient_id', 'date'], 'unique_diet_per_day');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospital_unit_diet_amounts');
    }
};
