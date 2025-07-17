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
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('permission');
            $table->boolean('admin')->default(false);
            $table->boolean('level1')->default(false);
            $table->boolean('level2')->default(false);
            $table->boolean('user')->default(false);
            $table->boolean('guest')->default(false);
            $table->boolean('api_only')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
    }
};
