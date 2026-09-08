# Booking System — Project Overview

> Laravel 12 · MySQL · Multi-service booking platform (flights, hotels, tours, bus)

---

## Tech Stack

| Layer              | Technology                                         |
| ------------------ | -------------------------------------------------- |
| Framework          | Laravel 12, PHP 8.2                                |
| Frontend           | Blade templates, Alpine.js, TailwindCSS            |
| Admin Dashboard    | Tyro Dashboard (`hasinhayder/tyro-dashboard` v1.7) |
| Auth               | Tyro Login (`hasinhayder/tyro`) + Sanctum (API)    |
| Image Optimization | `spatie/laravel-image-optimizer`                   |
| Database           | MySQL / MariaDB                                    |

---

## Directory Structure

```
app/
├── Http/Controllers/Admin/     # All admin CRUD controllers
├── Models/                     # Eloquent models
├── Helpers/settings.php        # get_setting() helper
├── Observers/                  # Model observers
└── Providers/
    └── AppServiceProvider.php  # Gate auth + dynamic config overrides

resources/views/admin/
├── layouts/master.blade.php    # Main layout (horizontal menu by default)
├── layouts/partials/horizontal-menu.blade.php
├── layouts/sidebar.blade.php
├── layouts/header.blade.php
├── dashboard.blade.php
├── hotels/                     # Hotel CRUD views
├── cities/                     # City CRUD views
├── countries/                  # Country CRUD views
├── bookings/hotel/             # Hotel booking CRUD views
└── settings/index.blade.php    # App settings (logo, name, favicon)

resources/views/public/
├── layouts/app.blade.php        # Master layout for public pages
├── components/                  # Shared components (navbar, footer)
├── index.blade.php             # Home page (Dynamic featured hotels)
├── all-hotel.blade.php         # Hotel listing page (Dynamic)
└── ...                          # Other public pages (About, Contact, etc.)

database/migrations/            # All migrations (see Migration Status below)
```

---

## Database Schema

Full schema defined in `db_schema.md`. Key tables:

### Identity & Users

- `users` — name, email, phone, password, status (active/inactive/banned), SoftDeletes
- `user_profiles` — 1:1 with users (DOB, gender, passport, address, profile_photo)
- Roles via Tyro pivot table `user_roles` (user_id ↔ role_id)

### Geography

- `countries` → `cities`

### Hotels

- `hotels` (SoftDeletes) → `room_types`, `hotel_amenities`, `hotel_photos`, `hotel_policies`

### Bookings

- `bookings` — Central hub (booking_type: flight/hotel/tour/bus)
- `hotel_bookings` — 1:1 with bookings
- `booking_passengers` — Unified passenger manifest (future)

### Not Yet Implemented

- Flights (airlines, airports, flights, flight_classes, flight_bookings)
- Tours (tour_packages, tour_itineraries, tour_bookings)
- Bus (bus_operators, bus_routes, bus_bookings)
- Payments (payments, emi_plans, payment_transactions)
- Promotions (campaigns, coupons, coupon_usages)
- Insurance (insurance_plans, travel_insurances)
- Reviews (reviews — polymorphic)
- Financial Audit (price_breakdown)

---

## Migration Status

| Migration                                                                 | Status                                          |
| ------------------------------------------------------------------------- | ----------------------------------------------- |
| `0001_01_01_000000_create_users_table`                                    | ✅ Done (added phone, status enum, SoftDeletes) |
| `0001_01_01_000001_create_cache_table`                                    | ✅ Done                                         |
| `0001_01_01_000002_create_jobs_table`                                     | ✅ Done                                         |
| `0001_01_01_000003_create_user_profiles_table`                            | ✅ Done                                         |
| `2026_05_13_100000_create_countries_table`                                | ✅ Done                                         |
| `2026_05_13_100001_create_cities_table`                                   | ✅ Done                                         |
| `2026_05_13_100002_create_bookings_table`                                 | ✅ Done                                         |
| `2026_05_13_100003_create_hotels_table`                                   | ✅ Done                                         |
| `2026_05_13_100004_create_hotel_amenities_table`                          | ✅ Done                                         |
| `2026_05_13_100005_create_hotel_photos_table`                             | ✅ Done                                         |
| `2026_05_13_100006_create_room_types_table`                               | ✅ Done                                         |
| `2026_05_13_100007_create_hotel_bookings_table`                           | ✅ Done                                         |
| `2026_05_13_115105_add_description_and_featured_to_hotel_amenities_table` | ✅ Done                                         |
| `2026_05_14_041618_add_seo_fields_to_hotels_table`                        | ✅ Done                                         |
| `2026_05_14_044614_create_hotel_policies_table`                           | ✅ Done                                         |
| `2026_02_02_085518_create_personal_access_tokens_table`                   | ✅ Done                                         |
| `2026_02_03_073742_create_settings_table`                                 | ✅ Done                                         |
| `2026_02_03_085903_add_is_active_to_roles_table`                          | ✅ Done                                         |

---

## Key Conventions & Patterns

### Settings System

- `Setting` model stores key-value pairs in DB
- Helper: `get_setting($key, $default)` — defined in `app/Helpers/settings.php`
- `app_logo` stored as relative path (`uploads/settings/logo.png`), served via `Storage::url()`
- `app_name`, `app_favicon` also stored here

### Dynamic Config (AppServiceProvider)

- Dashboard & login logos pulled from `get_setting('app_logo')` at runtime
- Converted to full URL via `Storage::url()` before setting config
- Config caching unaffected (override happens at boot)

### Route Model Binding

- `Hotel` model uses `slug` as route key (`getRouteKeyName()`)
- All hotel routes use slugs, not numeric IDs
- Other models use default numeric IDs

### Booking Architecture

- `bookings` table is the central hub with `booking_type` enum
- Service-specific tables (`hotel_bookings`, `flight_bookings`, etc.) link via `booking_id`
- `booking_passengers` is the unified passenger manifest (shared across all types)

### Admin Routes

- Prefix: `/dashboard/*`
- Named with `admin.` prefix (e.g., `admin.hotels.index`)
- Hotel bookings: `/dashboard/bookings/hotel/*` with `admin.bookings.hotel.*` names
- AJAX: `GET /dashboard/bookings/hotel-room-types/{hotel:slug}` → returns room types JSON

### Menu System

- Horizontal menu in `resources/views/admin/layouts/partials/horizontal-menu.blade.php`
- Uses CSS hover for sub-menus (`class="menu nav-item relative"`)
- Default theme: horizontal (`$themeConfig.menu = 'horizontal'` in `custom.js`)
- Administration menu visible only to `super-admin` role

---

## Admin Controllers

| Controller                | Routes                      | Notes                        |
| ------------------------- | --------------------------- | ---------------------------- |
| `DashboardController`     | `/dashboard`                | Stats overview               |
| `CountryController`       | `admin.countries.*`         | Full CRUD                    |
| `CityController`          | `admin.cities.*`            | Full CRUD                    |
| `HotelController`         | `admin.hotels.*`            | Full CRUD                    |
| `HotelRoomTypeController` | `admin.hotels.room-types.*` | Nested under hotel           |
| `HotelAmenityController`  | `admin.hotels.amenities.*`  | Nested under hotel           |
| `HotelPhotoController`    | `admin.hotels.photos.*`     | Nested under hotel           |
| `HotelPolicyController`   | `admin.hotels.policies.*`   | Nested under hotel           |
| `HotelBookingController`  | `admin.bookings.hotel.*`    | CRUD + AJAX getRoomTypes     |
| `SettingController`       | `admin.settings.*`          | Upload logo, name, favicon   |
| `RoleController`          | `tyro-dashboard.roles.*`    | Local override of Tyro roles |

---

## Tyro Package Integration

### Tyro Dashboard

- Admin panel with user/role/privilege management
- Config: `config/tyro-dashboard.php`
- Admin roles: `admin`, `super-admin`
- Sidebar menu items from `config/menu.php` (not yet created)

### Tyro Login

- Auth pages with customizable branding
- Config: `config/tyro-login.php`
- Role assignment on registration via `user_roles` pivot

### Roles

- Uses Tyro's role system (not enum on users table)
- `users` table has `status` enum only (active/inactive/banned)
- Role assignment via `user_roles` pivot table

---

## Important Notes

1. **Storage symlink**: Run `php artisan storage:link` for logo/favicon access
2. **Config cache**: Runtime config overrides in AppServiceProvider work regardless of `config:cache`
3. **Gate authorization**: `AppServiceProvider` checks Tyro roles/privileges via `Gate::before()`
4. **No `config/menu.php` yet**: Sidebar menu items not configured — menu is hardcoded in Blade
