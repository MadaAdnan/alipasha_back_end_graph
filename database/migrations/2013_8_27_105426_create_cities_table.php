<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->boolean('is_delivery')->nullable();
            $table->boolean('is_main')->nullable();
            $table->integer('sortable')->nullable();
            $table->boolean('is_active')->nullable()->default(false);
            $table->timestamps();
        });
        Schema::table('cities', function (Blueprint $table) {
            $table->foreignId('city_id')->nullable()->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cities');
    }
};
