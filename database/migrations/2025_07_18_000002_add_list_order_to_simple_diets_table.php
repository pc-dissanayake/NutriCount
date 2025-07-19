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
            $table->integer('list_order')->nullable()->after('primary_amount_unit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('simple_diets', function (Blueprint $table) {
            $table->dropColumn(['list_order']);
        });
    }
};
