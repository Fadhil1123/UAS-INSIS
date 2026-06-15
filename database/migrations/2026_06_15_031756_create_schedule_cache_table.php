<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedule_cache', function (Blueprint $table) {

            $table->id();

            $table->string('room_code');

            $table->string('course_code')->nullable();

            $table->string('course_name');

            $table->string('lecturer_name');

            $table->date('schedule_date');

            $table->time('start_time');

            $table->time('end_time');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedule_cache');
    }
};