<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API tokens
    |--------------------------------------------------------------------------
    |
    | Comma-separated list of tokens accepted in the X-API-Token header, so a
    | token can be issued per consumer and revoked on its own. An empty list
    | means every API request is rejected — a forgotten setting must never
    | leave the endpoints open.
    |
    */

    'api_tokens' => array_values(array_filter(array_map(
        'trim',
        explode(',', (string) env('VOUCHERS_API_TOKENS', ''))
    ))),

    /*
    |--------------------------------------------------------------------------
    | Store creation
    |--------------------------------------------------------------------------
    |
    | When an offer arrives for a shop_url that matches no business, the store
    | is created on the fly. New stores have no city, address or coordinates,
    | so they stay hidden until an admin completes them unless configured
    | otherwise. A default category is required: businesses.category_id is
    | NOT NULL and feed category names are not turned into site categories.
    |
    */

    'autocreate_stores' => (bool) env('VOUCHERS_AUTOCREATE_STORES', true),
    'autocreate_store_active' => (bool) env('VOUCHERS_AUTOCREATE_STORE_ACTIVE', false),
    'default_category_id' => env('VOUCHERS_DEFAULT_CATEGORY_ID'),

    /*
    |--------------------------------------------------------------------------
    | Import behaviour
    |--------------------------------------------------------------------------
    */

    'coupon_active_on_import' => (bool) env('VOUCHERS_COUPON_ACTIVE', true),

    // Offers accepted in a single POST /submit_offers call.
    'max_batch' => (int) env('VOUCHERS_MAX_BATCH', 500),

    // Ceiling for GET /get_offers?per_page=
    'max_per_page' => (int) env('VOUCHERS_MAX_PER_PAGE', 500),

];
