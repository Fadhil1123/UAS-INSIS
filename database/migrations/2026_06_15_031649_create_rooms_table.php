<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {

            $table->id();

            $table->string('room_code')->unique();

            $table->string('room_name');

            $table->enum('category', [
                'kelas',
                'laboratorium',
                'aula',
                'rapat',
                'lainnya'
            ]);

            $table->integer('capacity');

            $table->string('photo')->nullable();

            $table->text('description')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};