# Installation Guide

Complete guide for installing and configuring the All-in-One WP Migration Unlimited Extension.

## Quick Start

1. **Install All-in-One WP Migration** (if not already installed)
2. **Install this extension** via WordPress admin or manual upload
3. **Activate the plugin**
4. **Configure PHP settings** (for files > 300MB)
5. **Start importing!**

---

## Step-by-Step Installation

### Step 1: Install Required Plugin

This extension requires the free All-in-One WP Migration plugin:

1. Go to **Plugins → Add New**
2. Search for **"All-in-One WP Migration"**
3. Click **Install Now** → **Activate**

### Step 2: Install Unlimited Extension

#### Option A: WordPress Admin (Recommended)

1. Go to **Plugins → Add New → Upload Plugin**
2. Click **Choose File** and select the ZIP file
3. Click **Install Now**
4. Click **Activate Plugin**

#### Option B: Manual Upload via FTP

1. Extract the ZIP file
2. Upload `all-in-one-wp-migration-unlimited-extension` folder to `/wp-content/plugins/`
3. Go to **Plugins** in WordPress admin
4. Find **All-in-One WP Migration Unlimited Extension**
5. Click **Activate**

### Step 3: Verify Installation

1. Go to **All-in-One WP Migration → Import**
2. Look for: **"Maximum upload file size: Unlimited"**
3. If you see this, the extension is working! ✅

---

## PHP Configuration (Optional but Recommended)

For files larger than 300MB, configure your PHP settings:

### Required Settings

```ini
upload_max_filesize = 512M
post_max_size = 512M
memory_limit = 512M
max_execution_time = 300
max_input_time = 300
```

### Configuration by Hosting Type

#### 🔧 nginx Server

**Step 1: Configure PHP (php.ini)**

1. Locate your php.ini file:
```bash
php -i | grep "Loaded Configuration File"
```

2. Edit php.ini (common locations):
   - Ubuntu/Debian: `/etc/php/8.1/fpm/php.ini` (adjust version)
   - CentOS/RHEL: `/etc/php.ini`
   - Custom: Use path from step 1

3. Add or modify these lines:
```ini
upload_max_filesize = 512M
post_max_size = 512M
memory_limit = 512M
max_execution_time = 300
max_input_time = 300
```

4. Restart PHP-FPM:
```bash
# Ubuntu/Debian
sudo systemctl restart php8.1-fpm

# CentOS/RHEL
sudo systemctl restart php-fpm
```

5. Verify PHP changes:
```bash
php -i | grep upload_max_filesize
```

**Step 2: Configure nginx**

1. Edit nginx config (usually `/etc/nginx/nginx.conf` or site config in `/etc/nginx/sites-available/`)

2. Add inside `http` or `server` block:
```nginx
http {
    client_max_body_size 512M;
}
```

Or in your site-specific config:
```nginx
server {
    client_max_body_size 512M;
    # ... other server config
}
```

3. Test configuration:
```bash
sudo nginx -t
```

4. Reload nginx:
```bash
sudo systemctl reload nginx
```

5. Done! ✅

#### 🔧 Apache2 Server

**Step 1: Configure PHP (php.ini)**

1. Locate your php.ini file:
```bash
php -i | grep "Loaded Configuration File"
```

2. Edit php.ini (common locations):
   - Ubuntu/Debian: `/etc/php/8.1/apache2/php.ini` (adjust version)
   - CentOS/RHEL: `/etc/php.ini`
   - Custom: Use path from step 1

3. Add or modify these lines:
```ini
upload_max_filesize = 512M
post_max_size = 512M
memory_limit = 512M
max_execution_time = 300
max_input_time = 300
```

**Step 2: Configure Apache**

**Option A: Edit Apache config**

1. Edit Apache config (usually `/etc/apache2/apache2.conf` or virtual host):
```apache
<Directory /var/www/html>
    LimitRequestBody 536870912
</Directory>
```

2. Restart Apache:
```bash
sudo systemctl restart apache2
```

**Option B: Use .htaccess (if allowed)**

1. Create or edit `.htaccess` in WordPress root:
```apache
php_value upload_max_filesize 512M
php_value post_max_size 512M
php_value memory_limit 512M
php_value max_execution_time 300
```

2. Note: `.htaccess` method requires `AllowOverride` to be enabled in Apache config

3. Restart Apache:
```bash
sudo systemctl restart apache2
```

4. Done! ✅

#### 🔧 cPanel / Shared Hosting

**Method 1: Using cPanel**

1. Log into cPanel
2. Go to **Software → Select PHP Version** or **MultiPHP INI Editor**
3. Adjust the settings:
   - `upload_max_filesize`: 512M
   - `post_max_size`: 512M
   - `memory_limit`: 512M
   - `max_execution_time`: 300
4. Save changes

**Method 2: Create .user.ini file**

1. Create file `.user.ini` in your WordPress root directory
2. Add this content:
```ini
upload_max_filesize = 512M
post_max_size = 512M
memory_limit = 512M
max_execution_time = 300
max_input_time = 300
```
3. Save and wait 5 minutes for changes to propagate

#### 🔧 VPS / Dedicated Server

For VPS or dedicated servers, follow the **nginx** or **Apache2** configuration above based on your web server. The steps are the same - just identify which web server you're using:

- **nginx**: See nginx Server section above
- **Apache2**: See Apache2 Server section above

#### 🔧 WP Engine

WP Engine has default limits. To increase:

1. Contact WP Engine support
2. Request upload limit increase
3. Or use WP Engine's File Manager to upload large backups directly

#### 🔧 SiteGround

1. Go to **Site Tools → Dev → PHP Manager**
2. Select your PHP version
3. Click **Options** tab
4. Increase the limits
5. Save changes

#### 🔧 Bluehost

1. Log into cPanel
2. Go to **Software → MultiPHP INI Editor**
3. Select your domain
4. Adjust limits
5. Apply changes

---

## Testing Your Configuration

### Method 1: WordPress Info

1. Install **"WP Info"** plugin or similar
2. Check PHP configuration values
3. Verify all limits are correct

### Method 2: phpinfo()

1. Create file `info.php` in WordPress root:
```php
<?php phpinfo(); ?>
```

2. Visit `https://yourdomain.com/info.php`
3. Search for `upload_max_filesize`, `post_max_size`, `memory_limit`
4. **Delete info.php when done** (security risk)

### Method 3: Try Small Import

1. Create a test backup < 50MB
2. Try importing through the extension
3. If successful, try progressively larger files

---

## Troubleshooting Installation

### Plugin Not Appearing

**Problem:** Can't find plugin after upload

**Solutions:**
- Check the folder name is correct: `all-in-one-wp-migration-unlimited-extension`
- Verify folder is in `/wp-content/plugins/`
- Check file permissions: `chmod 755` on folder
- Deactivate/reactivate All-in-One WP Migration (base plugin)

### Activation Error

**Problem:** "Plugin generated errors during activation"

**Solutions:**
- Check WordPress version (requires 5.0+)
- Check PHP version (requires 7.0+)
- Ensure All-in-One WP Migration is installed and activated first
- Check for plugin conflicts - temporarily disable other plugins

### Still Shows 300 MB Limit

**Problem:** Limit not showing as "Unlimited"

**Solutions:**
- Hard refresh browser (Cmd+Shift+R or Ctrl+Shift+F5)
- Clear browser cache completely
- Check plugin is actually activated
- Check for JavaScript errors in browser console (F12)

### "Out of Date" Error

**Problem:** Extension says it's out of date

**Solutions:**
- Update to latest version (currently 1.0.0)
- Ensure All-in-One WP Migration is version 7.0 or higher
- Deactivate and reactivate the extension

---

## Uninstallation

### Remove Plugin

1. **Deactivate** the plugin first
2. Go to **Plugins**
3. Find **All-in-One WP Migration Unlimited Extension**
4. Click **Delete**

### Clean Up (Optional)

The plugin doesn't store any data, but if you configured PHP manually:

1. Remove custom `.user.ini` file (if created)
2. Revert php.ini changes (if modified)
3. Restart PHP/web server

---

## Getting Help

If you encounter issues:

1. **Check Requirements:**
   - WordPress 5.0+
   - PHP 7.0+
   - All-in-One WP Migration installed

2. **Review Troubleshooting:**
   - See common issues above
   - Check browser console (F12)
   - Review PHP error logs

3. **Ask for Support:**
   - GitHub Issues: [Report bugs and feature requests](https://github.com/tanjilahmed7/ai1wm-unlimited-extension/issues)
   - Include: WordPress version, PHP version, error messages
   - Provide: Browser console errors if applicable

---

## Next Steps

After successful installation:

1. **Test with small backup first** (< 100MB)
2. **Gradually try larger files**
3. **Monitor import progress**
4. **Check import logs** for any issues

Happy importing! 🚀
