<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('devices_backup', function (Blueprint $table) {
            $table->id();
            $table->string('year');
            $table->string('month');
            $table->string('day');
            $table->string('hour');
            $table->string('temperature');
            $table->string('humidity');
            $table->string('ammonia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices_backup');
    }
};
