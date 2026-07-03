# Deploy Changelog - NutriCount

All times are in local server time (+02:00) on **2026-05-19**.

---

### [06:26:15] Database Provisioning
- Created PostgreSQL user `nutricount_user` inside the `pg-db` LXC container (`10.248.10.101` / `fd42:7811:ed73:f4c:216:3eff:fe1e:29b9`).
- Created PostgreSQL database `nutricount_db` owned by `nutricount_user`.

### [06:26:25] Repository Cloning
- Cloned the `pc-dissanayake/NutriCount` repository from GitHub into `/var/www/nutricount.prasanjith.com`.

### [06:26:33] Environment Setup & Configuration
- Copied `.env.example` to `.env`.
- Configured `.env` settings for production:
  - App Name: `NutriCount`
  - Environment: `production`
  - Debug: `false`
  - App URL: `https://nutricount.prasanjith.com`
  - Configured PostgreSQL connection using the LXC IPv6 socket and credentials.

### [06:27:13] Composer Dependencies Installation
- Ran `composer install --no-dev --optimize-autoloader` to safely install all production PHP packages.

### [06:27:45] Key Generation
- Generated the application key using `php -d memory_limit=-1 artisan key:generate`.

### [06:27:53] Permissions Calibration
- Reset directory ownership to `admin:www-data` across all files.
- Hardened permissions to `775` on the `storage` and `bootstrap/cache` directories to allow write access for PHP-FPM (`www-data`).

### [06:28:20] Asset Compilation
- Installed npm packages and built production bundles using Vite via `npm install && npm run build`.

### [06:28:42] Migration Optimization (Upstream Bug Fixes)
- Corrected a bug in the repository where migration order attempts to alter a table (`simple_diets`) before it is created:
  - Renamed the column-addition migrations (`2025_07_18_000001_add_primary_amount_to_simple_diets_table.php`, `2025_07_18_000002_add_list_order_to_simple_diets_table.php`, `2025_07_18_000003_create_hospital_unit_diet_amounts_table.php`) to execute *after* the `simple_diets` creation migration (`2025_07_18_052422_create_simple_diets_table.php`).
- Removed redundant/duplicate migration files (`2025_07_18_060001` and `2025_07_18_060002`) after confirming their columns were already declared in the main table creation migration.

### [06:37:26] Database Migrations Execution
- Ran `php -d memory_limit=-1 artisan migrate:fresh --force` to clean out half-migrated schemas and successfully build all tables.

### [06:37:50] Seeder Correction
- Bypassed user-factory dependency on `Faker\Factory` (which is not available in non-dev composer installs) by commenting out the factory-based user creation block in `DatabaseSeeder.php`.

### [06:38:05] Database Seeding
- Seeded default hospital configuration settings successfully via `php -d memory_limit=-1 artisan db:seed --force`.

### [06:38:45] Framework Compilation
- Ran `php -d memory_limit=-1 artisan optimize` to compile configurations, routes, views, and icons for optimal loading speeds.

### [06:38:51] Caddy Server Configuration
- Appended a server block for `nutricount.prasanjith.com` in `/etc/caddy/Caddyfile`, configuring standard routing, security profiles, compression, and forwarding to PHP 8.4 FastCGI socket (`/run/php/php8.4-fpm.sock`).

### [06:39:04] Caddy Hot Reload & SSL Issuance
- Hot-reloaded the Caddy web server using `sudo systemctl reload caddy`.
- Let's Encrypt successfully verified domain ownership and issued an SSL/TLS certificate for `nutricount.prasanjith.com`.

### [06:41:04] visual Verification
- Visually reviewed the live website using a browser subagent, confirming correct asset compiling, correct Tailwind styles, functional elements (PIN login system, navbar, and hero banners), and robust server health (HTTP 200).
