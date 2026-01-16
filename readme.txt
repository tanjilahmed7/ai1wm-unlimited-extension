=== All-in-One WP Migration Unlimited Extension ===
Contributors: yourusername
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
For optimal performance with very large files (> 300MB), you may need to adjust your PHP settings:

* `upload_max_filesize` - Recommended: 512M or higher
* `post_max_size` - Recommended: 512M or higher  
* `memory_limit` - Recommended: 512M or higher
* `max_execution_time` - Recommended: 300 seconds

For Local by Flywheel users, these settings can be adjusted in `conf/php/php.ini.hbs`. For other hosting environments, consult your hosting provider or modify your `php.ini` file.

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

No configuration needed! Once activated, the extension automatically removes the file size restrictions.

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

This usually means your server's PHP upload limits are lower than your backup file size. Increase the PHP settings mentioned in the Requirements section and restart your web server.

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
https://github.com/yourusername/ai1wm-unlimited-extension/issues

== Credits ==

This extension is inspired by and compatible with ServMask's All-in-One WP Migration plugin.
