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
        Schema::create('communities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->foreignId('manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('type')->nullable()->default('channel');
            $table->string('url')->nullable();
            $table->boolean('is_global')->nullable()->default(false);
            $table->dateTime('last_update')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('community_user', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('community_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_manager')->nullable()->default(false);
            $table->boolean('notify')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('community_user');
        Schema::dropIfExists('communities');
    }
};
