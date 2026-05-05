# SETUP.md — Local & Production Install

## 1. Prerequisites

- PHP 7.4 or newer (8.x recommended) with `pdo_mysql` extension.
- MySQL 5.7+ or MariaDB 10.3+.
- A web server (Apache, Nginx) — or PHP's built-in dev server.

## 2. Clone & Configure

```bash
git clone https://github.com/Sanjays2402/Court-Case-Management.git
cd Court-Case-Management
cp .env.example .env
```

Edit `.env`:

```
DB_HOST=localhost
DB_PORT=3306
DB_NAME=ccms
DB_USER=root
DB_PASS=yourpassword
APP_URL=http://localhost:8080
APP_ENV=development
```

## 3. Create the Database

```bash
mysql -u root -p < schema.sql
```

This creates a `ccms` database and seeds an admin user
(`admin@ccms.local` / `admin123`). **Change this password immediately** in production.

To regenerate the seed password hash:

```bash
php -r "echo password_hash('YourNewPassword', PASSWORD_DEFAULT) . PHP_EOL;"
```

…then update the `INSERT` at the bottom of `schema.sql` or the `users` row directly.

## 4. Run Locally

### Option A — PHP built-in server (fastest)

```bash
php -S localhost:8080 -t PHP
```

Open <http://localhost:8080>.

### Option B — XAMPP / WAMP / MAMP

1. Copy the `Court-Case-Management` directory into `htdocs/` (XAMPP) or equivalent.
2. Start Apache + MySQL.
3. Visit <http://localhost/Court-Case-Management/PHP/>.

### Option C — Apache vhost

```apache
<VirtualHost *:80>
    ServerName ccms.local
    DocumentRoot /var/www/Court-Case-Management/PHP
    <Directory /var/www/Court-Case-Management/PHP>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Add `127.0.0.1 ccms.local` to `/etc/hosts`.

### Option D — Nginx + PHP-FPM

```nginx
server {
    listen 80;
    server_name ccms.local;
    root /var/www/Court-Case-Management/PHP;
    index index.php;

    location / { try_files $uri $uri/ /index.php?$query_string; }
    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
    location ~ /\.(env|git) { deny all; }
}
```

## 5. Production Hardening

1. **Disable error display** — set `APP_ENV=production` and ensure `display_errors = Off`
   in `php.ini`. Errors should go to `error_log` only.
2. **HTTPS** — terminate TLS at your reverse proxy (Caddy / Nginx / Cloudflare).
   Session cookies auto-flip to `Secure` when `HTTPS` is set.
3. **Block sensitive files** — the example Nginx config above already denies `.env` and
   `.git`. Apache equivalent (`.htaccess`):

   ```apache
   <FilesMatch "^\.">
       Require all denied
   </FilesMatch>
   ```

4. **Rotate the admin password** seeded by `schema.sql`.
5. **Back up the database** regularly (`mysqldump ccms > backup-$(date +%F).sql`).
6. **Rate-limit auth** — add fail2ban or an Nginx `limit_req` zone in front of `login.php`.

## 6. Smoke Test

```bash
# 1. landing
curl -sI http://localhost:8080/index.php | head -1
# 2. signup → dashboard → file case → view case → delete
# (manual via browser is easiest)
```

## 7. Troubleshooting

| Symptom                                    | Fix                                                           |
|--------------------------------------------|---------------------------------------------------------------|
| `Database unavailable`                     | Check `.env` DB creds and that MySQL is running.              |
| 403 / `.htaccess` ignored                  | Apache: `AllowOverride All` in your vhost / `apache2.conf`.   |
| Forms reject as "Invalid session token"    | Cookies blocked or session path not writable; check `php.ini`.|
| `Class "PDO" not found`                    | Install the `pdo_mysql` extension.                            |
| MySQL `ENUM`/`utf8mb4` errors on import    | Upgrade to MySQL 5.7+ / MariaDB 10.3+.                        |
