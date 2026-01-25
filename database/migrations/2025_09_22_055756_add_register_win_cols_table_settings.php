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
        Schema::table('settings', function (Blueprint $table) {
            $table->boolean('is_active_register_win')->nullable()->default(false);
            $table->double('register_win_amount')->nullable()->default(0);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->double('register_win_amount')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('is_active_register_win');
            $table->dropColumn('register_win_amount');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('register_win_amount');
        });
    }
};
