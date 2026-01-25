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
    Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('is_count');
            $table->dropColumn('options');


        });
        Schema::table('plans', function (Blueprint $table) {
            $table->string('duration')->nullable();
            $table->string('type')->nullable();
            $table->integer('special_count')->nullable();
            $table->integer('products_count')->nullable();
            $table->integer('ads_count')->nullable();
            $table->boolean('special_store')->nullable();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('duration');
            $table->dropColumn('type');
            $table->dropColumn('special_count');
            $table->dropColumn('products_count');
            $table->dropColumn('ads_count');
            $table->dropColumn('special_store');
        });
    }
};
