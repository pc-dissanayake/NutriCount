<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles-permissions', function (Blueprint $table) {
            $table->id();
            $table->string('permission')->unique();
            $table->boolean('admin')->default(true);
            $table->boolean('user')->default(false);
            $table->boolean('guest')->default(false);
            $table->boolean('api_only')->default(false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
