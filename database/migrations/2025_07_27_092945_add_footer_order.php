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
            $table->string('footer_order')->nullable();
            $table->boolean('send_via_email')->nullable();
            $table->boolean('send_via_whatsapp')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('footer_order');
            $table->dropColumn('send_via_email');
            $table->dropColumn('send_via_whatsapp');
        });
    }
};
