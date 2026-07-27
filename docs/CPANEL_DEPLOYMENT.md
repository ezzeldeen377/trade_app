# 6Valley cPanel Deployment Guide

## Files Ready for Deployment

| File | Size | Description |
|------|------|-------------|
| `deploy-6valley.zip` | ~163MB | Full Laravel project (vendor included, no node_modules) |
| `database/6valley_dump.sql` | ~260KB | Database dump with all config data |
| `.env.production` | Template | Edit with your cPanel credentials |

---

## Pre-Deployment Checklist

- [ ] cPanel hosting with PHP 8.2+ and MySQL
- [ ] SSH/Terminal access enabled
- [ ] Domain pointed to hosting (nameservers or DNS)
- [ ] Note your cPanel username (needed for DB names)

---

## Step-by-Step Deployment

### 1. SSH into Your Server

```bash
ssh your_cpanel_username@your_server_ip
# Enter password when prompted
```

### 2. Navigate to Home Directory

```bash
cd ~
```

### 3. Create a Folder for Laravel Files (Outside public_html)

```bash
mkdir -p ~/6valley
cd ~/6valley
```

### 4. Upload the Zip File

From your local machine (open a NEW terminal):

```bash
scp /Volumes/CrucialX9/node_projects/trade-online/deploy-6valley.zip your_cpanel_username@your_server_ip:~/6valley/
scp /Volumes/CrucialX9/node_projects/trade-online/database/6valley_dump.sql your_cpanel_username@your_server_ip:~/6valley/
```

### 5. Extract on Server (SSH)

```bash
cd ~/6valley
unzip deploy-6valley.zip
```

### 6. Create MySQL Database & User via cPanel

Go to **cPanel > MySQL Databases** and:

1. Create database: `cpaneluser_6valleydb`
2. Create user: `cpaneluser_6valley`
3. Set strong password
4. Add user to database with **ALL PRIVILEGES**

Note the database name, username, and password.

### 7. Import the Database

```bash
mysql -u cpaneluser_6valley -p cpaneluser_6valleydb < ~/6valley/6valley_dump.sql
# Enter password when prompted
```

### 8. Create Production .env File

```bash
cd ~/6valley
cp .env.production .env
nano .env
```

Edit these values:

```env
APP_URL=https://yourdomain.com
DB_DATABASE=cpaneluser_6valleydb
DB_USERNAME=cpaneluser_6valley
DB_PASSWORD=your_strong_password
MAIL_HOST=mail.yourdomain.com
MAIL_USERNAME=noreply@yourdomain.com
MAIL_PASSWORD=your_email_password
```

Save with `Ctrl+X`, then `Y`, then `Enter`.

### 9. Generate App Key

```bash
cd ~/6valley
php artisan key:generate
```

### 10. Configure public_html

The Laravel `public/` folder must be the web root. Move the `public/` contents to `public_html`:

```bash
# Back up original public_html
mv ~/public_html ~/public_html_backup

# Move Laravel's public folder to public_html
mv ~/6valley/public ~/public_html

# Edit index.php to point to Laravel files
nano ~/public_html/index.php
```

Change the two `require` lines in `index.php`:

```php
// FROM:
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

// TO:
require __DIR__.'/../6valley/vendor/autoload.php';
$app = require_once __DIR__.'/../6valley/bootstrap/app.php';
```

Save and exit.

### 11. Set Folder Permissions

```bash
chmod -R 755 ~/6valley/storage
chmod -R 755 ~/6valley/bootstrap/cache
chmod -R 755 ~/public_html
```

### 12. Create Storage Symlink

```bash
cd ~/6valley
php artisan storage:link
```

### 13. Clear & Cache Config

```bash
cd ~/6valley
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache
```

### 14. Set Up Cron Job (Required for 6Valley)

In cPanel, go to **Cron Jobs** and add:

```
* * * * * cd /home/cpaneluser/6valley && php artisan schedule:run >> /dev/null 2>&1
```

### 15. Test the Site

Visit `https://yourdomain.com` — the storefront should load.

**Admin Panel:** `https://yourdomain.com/admin/login`
Login: `admin@admin.com` / `password`

---

## Troubleshooting

### 500 Error
- Check `~/6valley/storage/logs/laravel.log`
- Ensure `.env` has correct DB credentials
- Run `php artisan config:clear`

### Blank Page
- Check PHP version in cPanel (must be 8.2+)
- Check error logs: `tail -50 ~/6valley/storage/logs/laravel.log`

### Assets Not Loading (CSS/JS 404)
- Verify `public_html` contains the contents of `public/`
- Check `APP_URL` in `.env` matches your domain (with https://)

### Database Connection Error
- Verify DB name/username/password in `.env`
- Ensure user has ALL PRIVILEGES on the database

---

## File Structure on Server

```
~/
├── 6valley/              (Laravel root - OUTSIDE public_html)
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── public/           (original - can be kept as backup)
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── vendor/
│   ├── .env
│   └── artisan
│
└── public_html/          (Web root - contents of public/)
    ├── index.php         (modified to point to ~/6valley/)
    ├── .htaccess
    ├── css/
    ├── js/
    └── ...
```
