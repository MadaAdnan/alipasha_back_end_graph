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
            $table->integer('product_count')->nullable()->default(0);// عدد المنتجات في الخطة الحالية
            $table->boolean('is_verified')->nullable()->default(false);// توثيق الحساب
            $table->string('id_color')->nullable()->default('#FF0000');//  لون الهوية
            $table->text('social')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['product_count','is_verified','id_color',]);
        });
    }
};
