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
        Schema::create('simple_diets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('DietName_en');
            $table->string('DietName_si')->nullable();
            $table->string('DietName_tm')->nullable();
            $table->boolean('active')->default(true);
            $table->decimal('primary_amount_value',10,2)->nullable();
            $table->char('primary_amount_unit')->nullable();
            $table->integer(column: 'list_order');
            $table->boolean('multiply_values')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simple_diets');
    }
};
