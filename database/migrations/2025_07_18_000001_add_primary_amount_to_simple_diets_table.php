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
        Schema::table('simple_diets', function (Blueprint $table) {
            $table->decimal('primary_amount_value', 10, 2)->nullable()->after('active');
            $table->string('primary_amount_unit')->nullable()->after('primary_amount_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('simple_diets', function (Blueprint $table) {
            $table->dropColumn(['primary_amount_value', 'primary_amount_unit']);
        });
    }
};
