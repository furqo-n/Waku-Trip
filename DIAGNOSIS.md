# Website Infinite Loading – Diagnosis & Fixes

## Summary of findings

Several possible causes were identified. Work through these in order.

---

## 1. **Database / cache / session causing slow responses** (most likely)

`php artisan route:list` took ~44 seconds and `php artisan migrate:status` did not finish within 60 seconds, which strongly suggests database or storage bottlenecks.

Your app uses:
- **Session driver**: `database` – every request reads/writes the `sessions` table
- **Cache driver**: `database` – `CurrencyService` uses `Cache::remember('all_currencies')`
- **Queue**: `database`

If MySQL is slow, the database is overloaded, or tables are large/locked, requests can appear to hang.

### Quick test – switch to file-based session and cache

Temporarily update `.env`:

```env
SESSION_DRIVER=file
CACHE_STORE=file
```

Then clear config and test:

```bash
php artisan config:clear
```

Reload the site. If it loads much faster, the problem is likely database performance or lock contention.

---

## 2. **Empty or missing `currencies` table**

`CurrencyService` fetches currencies from the database. If the `currencies` table is empty or missing, behavior may be unpredictable.

### Fix

Run migrations and seed currencies:

```bash
php artisan migrate
php artisan db:seed --class=CurrencySeeder
```

If no seeder exists, insert at least USD:

```sql
INSERT INTO currencies (code, name, symbol, rate, format, is_active, created_at, updated_at) 
VALUES ('USD', 'US Dollar', '$', 1.0, '$%s', 1, NOW(), NOW());
```

---

## 3. **Vite / frontend assets not loading**

The index page uses `@vite(['resources/css/pages/index.css', 'resources/js/season-switcher.js'])`. If the browser cannot load these assets, the page can appear stuck.

### Checks

1. Ensure the Vite build has been run:
   ```bash
   npm run build
   ```

2. Confirm these files exist:
   - `public/build/manifest.json`
   - `public/build/assets/index-*.css`
   - `public/build/assets/season-switcher-*.js`

3. In the browser DevTools (F12) → **Network** tab, see if any asset requests stay in “Pending” or fail (404/500).

---

## 4. **External resources timing out**

The page loads:

- Google Fonts
- Bootstrap (jsDelivr)
- Material Icons
- Other CDN assets

If any of these are slow or blocked, the page can seem to load indefinitely.

### Test

1. Open DevTools (F12) → **Network** tab.
2. Reload the page.
3. See which requests never complete (remain “Pending”).

---

## 5. **Session lock contention** (multiple tabs or requests)

With the `database` session driver, each request locks the session row. If the same user opens multiple tabs or triggers many parallel requests, they can block each other.

### Quick test

1. Close other tabs for the same site.
2. Use a private/incognito window.
3. If it loads there but not in normal browsing, session locking is likely involved.

---

## 6. **Heavy queries on the home page**

`HomeController` runs several queries:

- Categories
- Seasonal tours (with `media` and `images`)
- Trending tours (with `reviews`)
- Experiences

If tables are large and indexes are missing, these can be slow.

### Check query time

Add temporary logging in `HomeController::index()` before the `return`:

```php
\Log::info('HomeController queries completed in: ' . (microtime(true) - LARAVEL_START) . 's');
```

Then inspect `storage/logs/laravel.log` after a request.

---

## Recommended order of steps

1. Switch `SESSION_DRIVER` and `CACHE_STORE` to `file` and retest.
2. Ensure MySQL is running (e.g. via XAMPP Control Panel).
3. Run migrations and seed/insert `currencies`.
4. Run `npm run build` and verify build assets exist.
5. Use DevTools Network tab to see which requests hang.
6. Test in an incognito window to rule out session locking.

---

## Useful commands

```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Check routes (should complete in a few seconds)
php artisan route:list

# Check migration status
php artisan migrate:status

# Rebuild frontend assets
npm run build
```

---

## Contacting support

If the issue continues, share:

- Output of `php artisan migrate:status`
- Contents of `storage/logs/laravel.log` (last 50 lines, with sensitive data removed)
- Screenshot of the Network tab in DevTools showing pending/failed requests
