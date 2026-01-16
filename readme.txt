=== All-in-One WP Migration Unlimited Extension ===
Contributors: tanjilahmed7
Tags: migration, backup, import, unlimited, file-size
Requires at least: 5.0
Tested up to: 6.5
Requires PHP: 7.0
Stable tag: 1.0.0
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Remove file size restrictions from All-in-One WP Migration. Import backups of any size without the 300 MB upload limit.

== Description ==

**All-in-One WP Migration Unlimited Extension** removes the file size restrictions imposed by the free version of All-in-One WP Migration, allowing you to import backup files of any size.

= Key Features =

* **Unlimited File Size:** Remove the 300 MB upload restriction
* **Automatic Configuration:** Works with your existing server setup
* **Progress Tracking:** Real-time upload progress with chunked uploads
* **Retry Logic:** Automatically handles failed uploads
* **Compatible:** Works seamlessly with All-in-One WP Migration 7.0+

= How It Works =

This extension removes the JavaScript-based file size restriction and implements a chunked upload system that works with your server's PHP configuration. Simply activate the extension and start importing larger backup files immediately.

= Requirements =

**Required Plugin:**
* All-in-One WP Migration (free version) - must be installed and activated

**Server Requirements:**
For optimal performance with very large files (> 300MB), you may need to adjust your PHP and web server settings:

* PHP `upload_max_filesize` - Recommended: 512M or higher
* PHP `post_max_size` - Recommended: 512M or higher  
* PHP `memory_limit` - Recommended: 512M or higher
* PHP `max_execution_time` - Recommended: 300 seconds
* nginx `client_max_body_size` - Recommended: 512M or higher (if using nginx)
* Apache `LimitRequestBody` - Recommended: 512M or higher (if using Apache)

**Configuration:**
* **nginx servers:** Edit `/etc/nginx/nginx.conf` and add `client_max_body_size 512M;` in the `http` or `server` block, then edit PHP-FPM php.ini and restart both services.
* **Apache servers:** Edit php.ini and Apache config (or use `.htaccess` if allowed), then restart Apache.
* **cPanel/Shared hosting:** Use cPanel's MultiPHP INI Editor or create a `.user.ini` file in your WordPress root directory.
* For detailed instructions, see the INSTALLATION.md file included with this plugin.

= Privacy Policy =

This plugin does not collect, store, or transmit any user data. All backup file processing happens locally on your WordPress installation.

== Installation ==

= Automatic Installation =

1. Go to your WordPress admin panel
2. Navigate to Plugins → Add New
3. Search for "All-in-One WP Migration Unlimited Extension"
4. Click "Install Now" and then "Activate"

= Manual Installation =

1. Download the plugin ZIP file
2. Go to Plugins → Add New → Upload Plugin
3. Choose the downloaded ZIP file
4. Click "Install Now" and then "Activate"

= Configuration =

No configuration needed for files under 300MB! Once activated, the extension automatically removes the file size restrictions.

For files larger than 300MB, you may need to configure your PHP and web server settings (nginx or Apache2). See the Requirements section above and the INSTALLATION.md file for detailed instructions.

== Frequently Asked Questions ==

= Does this plugin require All-in-One WP Migration? =

Yes, this is an extension for All-in-One WP Migration and requires the base plugin to be installed and activated.

= Will this work with any file size? =

The extension removes JavaScript restrictions, but your server's PHP configuration may still limit upload sizes. For files larger than your server's limits, you may need to adjust PHP settings (see Requirements section).

= Does it work with the premium version of All-in-One WP Migration? =

This extension is designed for the free version. If you have the official premium extensions, you don't need this plugin.

= How do I know if it's working? =

After activation, go to All-in-One WP Migration → Import. You should see "Maximum upload file size: Unlimited" instead of "300 MB" or another limit.

= What if my import fails with a 400 or 415 error? =

This usually means your server's PHP upload limits or web server limits are lower than your backup file size. 

* **For nginx:** Increase PHP limits in php.ini AND add `client_max_body_size 512M;` to your nginx config, then restart PHP-FPM and nginx.
* **For Apache:** Increase PHP limits in php.ini AND configure `LimitRequestBody` in Apache config (or use `.htaccess`), then restart Apache.
* **For cPanel:** Use the MultiPHP INI Editor to increase PHP limits.

See the Requirements section for specific values and the INSTALLATION.md file for detailed step-by-step instructions.

= Can I use this on multisite? =

Yes, this plugin supports WordPress multisite installations.

== Screenshots ==

1. Import page showing "Unlimited" file size restriction removed
2. Upload progress with chunked upload system
3. Successful import of large backup file

== Changelog ==

= 1.0.0 - 2026-01-17 =
* Initial release
* Remove JavaScript file size restrictions
* Implement chunked upload system
* Add automatic retry logic for failed uploads
* Add progress tracking for large file uploads
* Compatible with All-in-One WP Migration 7.0+

== Upgrade Notice ==

= 1.0.0 =
Initial release of the Unlimited Extension. Remove the 300 MB file size limit and import backups of any size!

== Support ==

For support, feature requests, or bug reports, please visit:
https://github.com/tanjilahmed7/ai1wm-unlimited-extension/issues

== Credits ==

This extension is inspired by and compatible with ServMask's All-in-One WP Migration plugin.
