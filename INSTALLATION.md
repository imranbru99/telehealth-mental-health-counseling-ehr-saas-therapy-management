# Production & Local Installation Guide
### Mental Health and Counseling Booking SaaS Platform

This document outlines the step-by-step instructions for deploying the **Mental Health and Counseling Booking** SaaS application in local, VPS (Ubuntu/Debian), and production container environments.

---

## 1. System Requirements

- **Operating System:** Linux (Ubuntu 22.04 LTS / 24.04 LTS recommended)
- **PHP:** PHP 8.3 or PHP 8.5+ with the following extensions:
  - `pdo`, `pdo_sqlite`, `pdo_mysql` (or `pdo_pgsql`)
  - `bcmath`, `ctype`, `fileinfo`, `json`, `mbstring`, `openssl`, `tokenizer`, `xml`, `curl`
  - `gd` or `imagick` (for avatar and document processing)
- **Database:** SQLite 3.35+, MySQL 8.0+, or PostgreSQL 15+
- **Node.js & NPM:** Node.js 18.x or 20.x+ and NPM 9.x+
- **Composer:** Composer 2.7+
- **Web Server:** Nginx or Apache (with HTTP/2 and TLS 1.3 enabled)
- **Cache / Queue:** Redis 7+ (optional but recommended in high-concurrency production)

---

## 2. Local Development Setup

### Step 1: Clone the Repository
```bash
git clone <repository-url> /home/imran/project/new/mental-health-and-counseling-booking-sessions-and-notes
cd /home/imran/project/new/mental-health-and-counseling-booking-sessions-and-notes
```

### Step 2: Install Dependencies
```bash
# Install PHP Composer dependencies
composer install --no-interaction --prefer-dist --optimize-autoloader

# Install Node modules
npm install
```

### Step 3: Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

Verify your `.env` configuration:
```env
APP_NAME="Mental Health and Counseling Booking"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
DB_DATABASE=/home/imran/project/new/mental-health-and-counseling-booking-sessions-and-notes/database/database.sqlite

ADMIN_PANEL_PREFIX=ops
```

### Step 4: Run Database Migrations & Seeds
```bash
# Touch database file if using SQLite
touch database/database.sqlite

# Execute migrations and populate synthetic demo data
php artisan migrate:fresh --seed
```

### Step 5: Compile Frontend Assets
```bash
npm run build
```

### Step 6: Verify Test Suite
```bash
php artisan test
```

### Step 7: Launch Local Server
```bash
php artisan serve
```

---

## 3. Production Linux (Nginx) Deployment

### Nginx Server Configuration Block
Create `/etc/nginx/sites-available/mhcb.conf`:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name mentalhealth.yourdomain.com *.mentalhealth.yourdomain.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name mentalhealth.yourdomain.com *.mentalhealth.yourdomain.com;

    root /var/www/mentalhealth/public;
    index index.php index.html;

    # SSL Certificates (Let's Encrypt / Cloudflare)
    ssl_certificate /etc/letsencrypt/live/mentalhealth.yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/mentalhealth.yourdomain.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    # Security Headers for Healthcare Platforms
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;
    add_header Content-Security-Policy "default-src 'self' 'unsafe-inline' 'unsafe-eval' https: wss: data:;" always;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
        fastcgi_read_timeout 300;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### Production Optimizations
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan filament:cache-components
```

### Background Worker (Supervisor)
Create `/etc/supervisor/conf.d/mhcb-worker.conf`:
```ini
[program:mhcb-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/mentalhealth/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/mentalhealth/storage/logs/worker.log
stopwaitsecs=3600
```

---

## 4. Maintenance & Upgrades

```bash
# Pull new releases
git pull origin main

# Update dependencies
composer install --no-dev --optimize-autoloader
npm install && npm run build

# Run migrations
php artisan migrate --force

# Recache system
php artisan optimize
```

---

## 5. Support & Authorship

- **Architect & Author:** Imran Ahmed
- **Email:** me@imrandev.bd
- **Official Website:** [https://imrandev.bd](https://imrandev.bd)
