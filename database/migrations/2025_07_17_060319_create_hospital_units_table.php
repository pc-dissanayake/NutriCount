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
        Schema::create('hospital_units', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('identifier')->nullable();
            $table->boolean('active')->default(true);
            $table->string('type')->nullable(); // ward, clinic, etc.
            $table->string('name');
            $table->string('alias')->nullable();
            $table->text('description')->nullable();
            $table->string('contact')->nullable();
            $table->uuid('part_of')->nullable(); // Reference to parent organization/unit
            $table->string('endpoint')->nullable();
            $table->string('qualification_code')->nullable();
            $table->string('qualification_identifier')->nullable();
            $table->date('qualification_period_start')->nullable();
            $table->date('qualification_period_end')->nullable();
            $table->string('qualification_issuer')->nullable();
            $table->json('tags')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospital_units');
    }
};
