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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->nullable()->constrained();
//            $table->foreignId('plan_id')->nullable()->constrained();
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('is_active')->default(true)->nullable();
            $table->string('level')->nullable()->default('user');
//            $table->enum('user_type', [TypeUserEnum::ORGANIZATION->value, TypeUserEnum::VENDOR->value, TypeUserEnum::PERSONAL->value])->default(TypeUserEnum::PERSONAL->value)->nullable();
            $table->string('device_token')->nullable();
//            $table->string('img')->nullable();
            $table->string('affiliate')->nullable();
            $table->dateTime('send_at')->nullable();
            $table->string('code_verified')->nullable();
            $table->string('api_token')->nullable();
            $table->string('reset_password')->nullable();
            $table->boolean('is_receive_notification')->nullable();
            $table->date('notify_date')->nullable();
            $table->boolean('is_seller')->nullable()->default(false);
//  seller
            $table->string('seller_name')->nullable();
            $table->string('address')->nullable();
            $table->string('info')->nullable();
            $table->boolean('is_active_seller')->nullable()->default(true);
            $table->string('level_seller')->nullable()->default(1);
            $table->boolean('is_default_active')->nullable()->default(true);
            $table->boolean('is_restaurant')->nullable()->default(true);
            $table->boolean('is_special')->nullable()->default(true);
            $table->time('open_time')->nullable();
            $table->time('close_time')->nullable();
            $table->boolean('is_delivery')->nullable()->default(true);

            $table->rememberToken();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            //section_id
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
