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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('sub1_id')->nullable()->constrained('categories')->cascadeOnDelete();
            $table->foreignId('sub2_id')->nullable()->constrained('categories')->cascadeOnDelete();
            $table->foreignId('sub3_id')->nullable()->constrained('categories')->cascadeOnDelete();
            $table->foreignId('sub4_id')->nullable()->constrained('categories')->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->longText('info')->nullable();
            $table->text('expert')->nullable();
            $table->text('tags')->nullable();
            $table->boolean('is_discount')->nullable()->default(false);
            $table->string('active')->nullable()->default('pending');
            $table->boolean('is_delivery')->nullable()->default(false);
            $table->boolean('is_available')->nullable()->default(false);
            $table->string('level')->nullable()->default('normal');
            $table->string('video')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('url')->nullable();

            $table->string('price')->nullable();
            $table->string('discount')->nullable();
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->string('code')->nullable();
            $table->string('type')->nullable()->default(\App\Enums\CategoryTypeEnum::PRODUCT->value);
            $table->softDeletes();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
