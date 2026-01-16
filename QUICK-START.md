# 🚀 Quick Start Guide

Get up and running with the All-in-One WP Migration Unlimited Extension in 5 minutes!

## ⚡ Installation (2 minutes)

### Step 1: Install Base Plugin
```
WordPress Admin → Plugins → Add New
Search: "All-in-One WP Migration"
Install & Activate
```

### Step 2: Install This Extension
```
WordPress Admin → Plugins → Add New → Upload Plugin
Choose: all-in-one-wp-migration-unlimited-extension-v1.0.0.zip
Install & Activate
```

### Step 3: Verify
```
Go to: All-in-One WP Migration → Import
Look for: "Maximum upload file size: Unlimited" ✅
```

## 🎯 Usage (1 minute)

1. Go to **All-in-One WP Migration → Import**
2. Click **Import From → File**
3. Select your `.wpress` backup file (any size!)
4. Click **Proceed**
5. Watch the progress bar
6. Done! 🎉

## 📊 File Size Guide

| File Size | Configuration Needed |
|-----------|---------------------|
| < 300 MB | ✅ None - works immediately |
| 300-512 MB | ⚠️ Increase PHP limits (see below) |
| > 512 MB | 🔧 Custom PHP config required |

## ⚙️ PHP Configuration (3 minutes)

**Only needed for files > 300MB**

### nginx Server

**Step 1: Configure PHP (php.ini)**
1. Locate your php.ini file:
```bash
php -i | grep "Loaded Configuration File"
```
2. Edit php.ini and add/modify:
```ini
upload_max_filesize = 512M
post_max_size = 512M
memory_limit = 512M
max_execution_time = 300
max_input_time = 300
```
3. Restart PHP-FPM:
```bash
sudo systemctl restart php-fpm
# or
sudo systemctl restart php8.1-fpm
```

**Step 2: Configure nginx**
1. Edit your nginx config (usually `/etc/nginx/nginx.conf` or site config):
```nginx
http {
    client_max_body_size 512M;
}
```
2. Test configuration:
```bash
sudo nginx -t
```
3. Reload nginx:
```bash
sudo systemctl reload nginx
```
4. Done! ✅

### Apache2 Server

**Step 1: Configure PHP (php.ini)**
1. Locate your php.ini file:
```bash
php -i | grep "Loaded Configuration File"
```
2. Edit php.ini and add/modify:
```ini
upload_max_filesize = 512M
post_max_size = 512M
memory_limit = 512M
max_execution_time = 300
max_input_time = 300
```

**Step 2: Configure Apache**
1. Edit Apache config (usually `/etc/apache2/apache2.conf` or `.htaccess`):
```apache
# In apache2.conf or virtual host
LimitRequestBody 536870912
```
Or create `.htaccess` in WordPress root:
```apache
php_value upload_max_filesize 512M
php_value post_max_size 512M
php_value memory_limit 512M
php_value max_execution_time 300
```
2. Restart Apache:
```bash
sudo systemctl restart apache2
```
3. Done! ✅

### cPanel Hosting
1. cPanel → Software → MultiPHP INI Editor
2. Select your domain
3. Set:
   - `upload_max_filesize`: 512M
   - `post_max_size`: 512M
   - `memory_limit`: 512M
4. Save
5. Done! ✅

### Other Hosting Types
See `INSTALLATION.md` for complete guide covering:
- VPS/Dedicated servers
- WP Engine
- SiteGround
- Bluehost
- Managed WordPress hosting

## 🐛 Troubleshooting (1 minute)

### Problem: Still shows 300 MB limit
**Solution:** Hard refresh browser
- Windows: `Ctrl + Shift + F5`
- Mac: `Cmd + Shift + R`

### Problem: Upload fails (400/415 error)
**Solution:** Increase PHP limits (see above)

### Problem: "Out of date" error
**Solution:** Update both plugins to latest versions

### Problem: Import stalls at 0%
**Solutions:**
1. Check browser console (F12) for errors
2. Clear browser cache completely
3. Try different browser
4. Check PHP error logs

## 📚 Need More Help?

- **Complete Guide:** See `INSTALLATION.md`
- **FAQ:** See `readme.txt`
- **Technical Details:** See `README.md`
- **Changes:** See `CHANGELOG.md`

## 💡 Pro Tips

1. **Test First:** Try a small backup (< 100MB) first
2. **Check Logs:** Enable WordPress debug mode if issues occur
3. **Browser Cache:** Always hard refresh after changes
4. **Backup First:** Keep your backup file safe before importing
5. **Server Resources:** Larger files need more PHP memory

## ✨ What This Extension Does

✅ **Removes the 300 MB restriction**  
✅ **Uploads files in chunks** (handles any size)  
✅ **Shows real-time progress**  
✅ **Automatically retries failed uploads**  
✅ **Works with your server configuration**  

## 🆚 vs Official Extension

| Feature | This (FREE) | Official ($69) |
|---------|-------------|----------------|
| Remove limits | ✅ | ✅ |
| Chunked uploads | ✅ | ✅ |
| Progress tracking | ✅ | ✅ |
| Requires PHP config | ✅ | ✅ |
| **Price** | **$0** | **$69** |

Both extensions work the same way!

## 🎉 Success Checklist

- [ ] All-in-One WP Migration installed
- [ ] This extension installed and activated
- [ ] Import page shows "Unlimited" file size
- [ ] PHP limits configured (if needed)
- [ ] Browser cache cleared
- [ ] Test import successful

## 🚀 You're Ready!

Your WordPress site can now import backups of any size!

**Happy importing!** ✨

---

**Questions?** Check the full documentation files included with the plugin.
