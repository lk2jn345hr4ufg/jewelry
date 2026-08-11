<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            // Unique offer identifier supplied by the API client — the key the
            // importer upserts on. Nullable because coupons added by hand in the
            // admin panel have none (MySQL allows many NULLs in a unique index).
            $table->string('slug')->nullable()->unique()->after('business_id');
            $table->string('coupon_type', 20)->nullable()->after('title'); // code | deal
            $table->date('starts_at')->nullable()->after('discount');
            $table->string('deep_link', 500)->nullable()->after('starts_at');
            // Countries / categories / sources as received, for API round-trip.
            $table->json('meta')->nullable()->after('deep_link');
            $table->string('origin', 20)->default('admin')->after('is_active'); // admin | api
            $table->index(['is_active', 'expires_at']);
        });
    }

    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropIndex(['is_active', 'expires_at']);
            $table->dropUnique(['slug']);
            $table->dropColumn(['slug', 'coupon_type', 'starts_at', 'deep_link', 'meta', 'origin']);
        });
    }
};
