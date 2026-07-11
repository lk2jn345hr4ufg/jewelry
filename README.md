# Gleamion.com — Jewelry Business Directory

A mobile-friendly business listing website built with **Laravel 11 + MySQL**, dedicated exclusively to jewelry businesses: stores, custom ateliers, watch shops, repairers, gold buyers and more.

## Features

**Public site**
- **Home page** — search with live auto-suggestions, "Browse by City" grid with a *Load more* button, popular categories, the 10 most recently added businesses, and the latest approved reviews.
- **City pages** (`/jewelry/{city}`) — breadcrumbs, search with auto-suggest, links to the biggest cities, category filters for that city, an OpenStreetMap/Leaflet map of every listing, and a business list with *Load more*.
- **Category-in-city pages** (`/jewelry/{city}/{category}`) — the same layout filtered to one category.
- **Business profiles** (`/business/{slug}`) — breadcrumbs, about text, address, map, opening hours; a sidebar with address, phone numbers, website, hours and a link back to the category+city listing; active coupons; alternative stores in the same city; approved reviews and a review form with a 1–5 star rating (new reviews are held for moderation).
- **Stores, Coupons & Deals** (`/deals`) — active coupons with a show/copy code interaction.

**Admin panel** (`/admin`, session-authenticated, admin-only middleware)
- Dashboard with counts, pending reviews and latest profiles.
- Categories — add / edit / delete, and connect categories to cities (checkboxes sync the `category_city` pivot).
- Cities — add / edit / delete with state, coordinates and population (population ranks the "biggest cities" links); connect cities to categories.
- Businesses — full CRUD: category & city selects, contact details, coordinates, per-day opening hours, active/hidden toggle, name search.
- Reviews — moderation queue with pending / approved / rejected tabs, approve / reject / delete actions.
- Coupons — full CRUD with code, discount label, expiry date and active toggle.

## Requirements

- PHP ≥ 8.2 with the usual Laravel extensions (`pdo_mysql`, `mbstring`, `openssl`, `xml`, `ctype`, `json`)
- Composer
- MySQL 5.7+ / MariaDB 10.3+

## Installation

```bash
# 1. Install PHP dependencies (vendor/ is not included in this archive)
composer install

# 2. Environment
cp .env.example .env
php artisan key:generate

# 3. Create the database, then set credentials in .env
#    DB_DATABASE=jewelry_directory  DB_USERNAME=...  DB_PASSWORD=...
mysql -u root -p -e "CREATE DATABASE jewelry_directory CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 4. Migrate and seed demo data (12 cities, 8 categories, ~36 businesses,
#    reviews, coupons and the admin account)
php artisan migrate --seed

# 5. Run it
php artisan serve
# → http://localhost:8000
```

## Admin access

| URL | Email | Password |
|---|---|---|
| `http://localhost:8000/admin/login` | `admin@example.com` | `password` |

Change these credentials immediately for any non-local deployment (edit the user in the `users` table or via tinker).

## Project notes

- **Maps** use Leaflet + OpenStreetMap tiles — no API key required.
- **Styling** uses the Tailwind CDN plus a small hand-written design system (Cormorant Garamond / Jost, gold "facet" motif). The CDN build is convenient for development; for production, compile Tailwind locally instead.
- **Slugs** are generated automatically for cities, categories and businesses from the business, city or category name.
- **Reviews** are created with `status = pending` and only appear publicly once approved in the admin panel.
- **Load more / auto-suggest** endpoints: `GET /cities/load?offset=`, `GET /jewelry/{city}/businesses?offset=`, `GET /search/suggest?q=`.
- Sessions and cache use the `file` drivers, so no extra tables are needed beyond the migrations included.

## Structure

```
app/Http/Controllers          Public controllers (Home, City, Business, Search, Coupon)
app/Http/Controllers/Admin    Admin CRUD + auth controllers
app/Http/Middleware/AdminOnly Admin gate middleware (alias: "admin")
app/Models                    City, Category, Business, Review, Coupon, User
database/migrations           users + directory tables (cities, categories,
                              category_city, businesses, reviews, coupons)
database/seeders              Demo data + admin account
resources/views               Blade templates (public + admin)
routes/web.php                All routes
```
