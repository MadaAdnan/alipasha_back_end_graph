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
        Schema::table('settings', function (Blueprint $table) {
            $table->boolean('is_add_likes')->nullable()->default(false);
        });
        Schema::table('products', function (Blueprint $table) {
            $table->integer('num_likes')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('is_add_likes');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('num_likes');
        });
    }
};
