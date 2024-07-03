<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            //$table->foreignId('category_id')->nullable()->constrained()->cascadeOnDelete();
            $table->boolean('is_active')->nullable();
            $table->boolean('is_main')->nullable();
            $table->string('type')->nullable()->default('category');
            $table->integer('sortable')->nullable();
            $table->timestamps();
        });
        Schema::create('category_parent', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_parent');
        Schema::dropIfExists('categories');

    }
};
