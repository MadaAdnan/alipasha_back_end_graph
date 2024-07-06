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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->nullable()->constrained()->nullOnDelete();
            $table->double('dollar_value')->nullable()->default(32);
            $table->double('point_value')->nullable()->default(1);
            $table->double('num_point_for_register')->nullable()->default(1);
            $table->double('less_amount_point_pull')->nullable()->default(1);
            $table->double('longitude')->nullable();
            $table->double('latitude')->nullable();



            $table->longText('privacy')->nullable();
            $table->longText('about')->nullable();

            $table->text('social')->nullable();

            $table->string('address')->nullable();
            $table->string('current_version')->nullable();
            $table->string('msg_delivery')->nullable();
            $table->string('weather_api')->nullable();
            $table->string('email_delivery')->nullable();
            $table->string('email_support')->nullable();
            $table->string('msg_chat')->nullable();
            $table->string('live_id')->nullable();
            $table->string('whats_msg')->nullable();
            $table->text('url_for_download')->nullable();
            $table->string('advice_url')->nullable();
            $table->boolean('available_country')->nullable()->default(false);
            $table->boolean('available_any_email')->nullable()->default(false);
            $table->boolean('auto_update_exchange')->nullable()->default(false);
            $table->boolean('active_points')->nullable()->default(false);
            $table->boolean('delivery_service')->nullable();
            $table->boolean('active_live')->nullable();
            $table->boolean('send_notification_hobbies')->nullable()->default(false);
            $table->boolean('force_upgrade')->nullable()->default(false);
            $table->boolean('active_advice')->nullable()->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
