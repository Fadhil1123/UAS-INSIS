<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {

            $table->string('surat_file')
                ->nullable()
                ->after('booking_type');

            $table->string('surat_original_name')
                ->nullable()
                ->after('surat_file');

        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {

            $table->dropColumn([
                'surat_file',
                'surat_original_name'
            ]);

        });
    }
};