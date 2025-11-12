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
            $table->boolean('active_bot_like')->nullable()->default(false);
            $table->integer('num_view_as_like')->nullable()->default(3250);
            $table->double('ratio_view_home')->nullable()->default(0.5);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['active_bot_like','num_view_as_like','ratio_view_home']);
        });
    }
};
