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
            $table->foreignId('support_id')->after('email_support')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('delivery_id')->after('email_delivery')->nullable()->constrained('users')->nullOnDelete();
            $table->dropColumn('email_delivery');
            $table->dropColumn('email_support');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('email_delivery')->nullable();
            $table->string('email_support')->nullable();
            $table->dropConstrainedForeignId('support_id');
            $table->dropConstrainedForeignId('delivery_id');
        });
    }
};
