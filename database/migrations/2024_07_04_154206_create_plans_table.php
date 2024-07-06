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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->string('is_active')->nullable()->default(false);
            $table->string('price')->nullable();
            $table->string('is_discount')->nullable()->default(false);
            $table->string('discount')->nullable();
            $table->string('is_count')->nullable()->default(false);
            $table->text('info')->nullable();
            $table->text('items')->nullable();
            $table->text('options')->nullable();
            $table->string('sortable')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
