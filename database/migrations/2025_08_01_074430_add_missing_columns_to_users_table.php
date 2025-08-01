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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->nullable()->after('password');
            }
            if (!Schema::hasColumn('users', 'active')) {
                $table->boolean('active')->default(false)->after('role');
            }
            if (!Schema::hasColumn('users', 'default_lang')) {
                $table->string('default_lang')->default('Eng')->after('active');
            }
            if (!Schema::hasColumn('users', 'units_assigned')) {
                $table->json('units_assigned')->nullable()->after('default_lang');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'units_assigned')) {
                $table->dropColumn('units_assigned');
            }
            if (Schema::hasColumn('users', 'default_lang')) {
                $table->dropColumn('default_lang');
            }
            if (Schema::hasColumn('users', 'active')) {
                $table->dropColumn('active');
            }
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
    }
};
