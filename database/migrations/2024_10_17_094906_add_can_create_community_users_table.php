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
            $table->boolean('can_create_group')->nullable()->default(false)->after('is_seller');
            $table->integer('count_group')->nullable()->default(0)->after('is_seller');
            $table->boolean('can_create_channel')->nullable()->default(false)->after('is_seller');
            $table->integer('count_channel')->nullable()->default(0)->after('is_seller');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('can_create_group');
            $table->dropColumn('count_group');
            $table->dropColumn('can_create_channel');
            $table->dropColumn('count_channel');
        });
    }
};
