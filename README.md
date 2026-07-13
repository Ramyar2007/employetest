# Employee Payout Tracker

Laravel 11 + Inertia.js + Vue 3 + Tailwind CSS. Tracks payouts made to employees for completed tasks, with Admin / Staff roles enforced server-side via `spatie/laravel-permission`.

## Roles

| Action | Admin | Staff |
|---|---|---|
| View payouts | ✅ | ✅ |
| Add payout | ✅ | ✅ |
| Edit payout | ✅ | ❌ |
| Change payout status | ✅ | ❌ |
| Delete payout | ✅ | ❌ |
| Manage employees | ✅ | ❌ |

All of the above is enforced at the route level (`role:admin` middleware in `routes/web.php`), not just hidden in the UI.

## Local setup (requires PHP 8.2+, Composer, Node 20+)

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed   # uses sqlite by default, zero config
npm run build                # or `npm run dev` while developing
php artisan serve
```

Seeded logins:
- Admin — `admin@example.com` / `password`
- Staff — `staff@example.com` / `password`

## Deploying (Railway)

Vercel does not run PHP/Laravel — this is deployed on **Railway**, which runs Laravel natively and provides a managed Postgres database.

1. Push this repo to GitHub (see below).
2. On [railway.app](https://railway.app), **New Project → Deploy from GitHub repo** and pick this repo.
3. **New → Database → Add PostgreSQL** in the same project. Railway auto-injects `PGHOST`, `PGPORT`, `PGDATABASE`, `PGUSER`, `PGPASSWORD` — map them to Laravel's expected names in your app service's **Variables** tab:

   ```
   DB_CONNECTION=pgsql
   DB_HOST=${{Postgres.PGHOST}}
   DB_PORT=${{Postgres.PGPORT}}
   DB_DATABASE=${{Postgres.PGDATABASE}}
   DB_USERNAME=${{Postgres.PGUSER}}
   DB_PASSWORD=${{Postgres.PGPASSWORD}}
   ```

4. Add these additional variables on the app service:

   ```
   APP_NAME=Employee Payout Tracker
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=base64:r7vo4cF0VPKKd8UeiMp8DEYBQk6W40jX+M058NqZamU=
   APP_URL=https://<your-railway-domain>.up.railway.app
   SESSION_DRIVER=database
   CACHE_STORE=database
   QUEUE_CONNECTION=sync
   ```

   (The `APP_KEY` above is a valid, ready-to-use key — Laravel just needs *any* stable 32-byte key, this one was generated for this project. Feel free to generate your own with `php artisan key:generate --show` if you have PHP locally.)

5. Railway auto-detects `nixpacks.toml` in this repo, which installs PHP + Composer + Node, runs `composer install`, `npm ci`, `npm run build`, then on start runs migrations, seeds the database, and boots the app. No manual build config needed.
6. Once deployed, generate a public domain under **Settings → Networking → Generate Domain**.

## Pushing to GitHub

```bash
git init
git add .
git commit -m "Employee Payout Tracker"
git branch -M main
git remote add origin <your-repo-url>
git push -u origin main
```
