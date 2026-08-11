<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->string('logo_url', 500)->nullable()->after('website');
            // Normalised host ("example.com") used to match incoming shop_url.
            // Deliberately NOT unique: a chain has one domain and many branches.
            $table->string('website_host')->nullable()->index()->after('logo_url');
            $table->string('origin', 20)->default('admin')->after('is_active'); // admin | api
        });

        $this->backfillHosts();
    }

    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropIndex(['website_host']);
            $table->dropColumn(['logo_url', 'website_host', 'origin']);
        });
    }

    /**
     * Fills website_host from the existing website column.
     *
     * The host is parsed inline rather than through App\Support\StoreUrl so this
     * migration keeps producing the same result if that class later changes.
     */
    protected function backfillHosts(): void
    {
        DB::table('businesses')
            ->select('id', 'website')
            ->whereNotNull('website')
            ->where('website', '!=', '')
            ->orderBy('id')
            ->chunk(500, function ($rows) {
                foreach ($rows as $row) {
                    $url = trim((string) $row->website);
                    if (! preg_match('~^https?://~i', $url)) {
                        $url = 'https://'.$url;
                    }

                    $host = parse_url($url, PHP_URL_HOST);
                    if (! $host) {
                        continue;
                    }

                    $host = preg_replace('/^www\./i', '', mb_strtolower($host));

                    DB::table('businesses')->where('id', $row->id)->update(['website_host' => $host]);
                }
            });
    }
};
