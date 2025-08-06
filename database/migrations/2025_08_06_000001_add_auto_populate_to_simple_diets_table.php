<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('simple_diets', function (Blueprint $table) {
            $table->boolean('auto_populate')->default(false)->after('multiply_values');
        });
    }

    public function down(): void
    {
        Schema::table('simple_diets', function (Blueprint $table) {
            $table->dropColumn('auto_populate');
        });
    }
};
