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
            //
            $table->string('provinsi_id')->nullable();
            $table->string('kabupaten_id')->nullable();
            $table->string('kecamatan_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn('provinsi_id');
            $table->dropColumn('kabupaten_id');
            $table->dropColumn('kecamatan_id');
        });
    }
};
