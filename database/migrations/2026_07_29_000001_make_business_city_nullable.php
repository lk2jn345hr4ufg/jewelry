<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop the FK, make the column nullable, re-add the FK as nullOnDelete.
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropForeign(['city_id']);
        });

        Schema::table('businesses', function (Blueprint $table) {
            $table->foreignId('city_id')->nullable()->change();
        });

        Schema::table('businesses', function (Blueprint $table) {
            $table->foreign('city_id')->references('id')->on('cities')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropForeign(['city_id']);
        });

        Schema::table('businesses', function (Blueprint $table) {
            $table->foreignId('city_id')->nullable(false)->change();
        });

        Schema::table('businesses', function (Blueprint $table) {
            $table->foreign('city_id')->references('id')->on('cities')->cascadeOnDelete();
        });
    }
};
