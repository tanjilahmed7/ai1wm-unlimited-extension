# All-in-One WP Migration Unlimited Extension

Remove file size restrictions from All-in-One WP Migration. Import backups of any size without the 300 MB upload limit.

## Features

✅ **Unlimited File Size** - Remove the 300 MB restriction  
✅ **Chunked Uploads** - Upload large files in manageable chunks  
✅ **Progress Tracking** - Real-time upload progress display  
✅ **Automatic Retry** - Handles failed uploads automatically  
✅ **Zero Configuration** - Works out of the box  
✅ **Multisite Support** - Compatible with WordPress multisite  

## Requirements

- **WordPress:** 5.0 or higher
- **PHP:** 7.0 or higher  
- **Required Plugin:** [All-in-One WP Migration](https://wordpress.org/plugins/all-in-one-wp-migration/) (free version)

## Installation

### Method 1: WordPress Admin

1. Download the latest release ZIP file
2. Go to **Plugins → Add New → Upload Plugin**
3. Choose the ZIP file and click **Install Now**
4. Click **Activate**

### Method 2: Manual Installation

1. Download and extract the ZIP file
2. Upload the `all-in-one-wp-migration-unlimited-extension` folder to `/wp-content/plugins/`
3. Activate the plugin through the **Plugins** menu in WordPress

## Configuration

### For Optimal Performance

For very large files (> 300MB), you may need to adjust your PHP settings. The extension will work with your current settings, but increasing these limits ensures smooth operation:

```ini
upload_max_filesize = 512M
post_max_size = 512M
memory_limit = 512M
max_execution_time = 300
```

### Configuration by Environment

**Local by Flywheel:**
1. Stop your site
2. Edit `conf/php/php.ini.hbs`
3. Add the settings above
4. Restart your site

**cPanel / Shared Hosting:**
- Create a `php.ini` or `.user.ini` file in your WordPress root
- Add the settings above
- Wait 5 minutes for changes to take effect

**VPS / Dedicated Server:**
- Edit `/etc/php/7.x/fpm/php.ini` (adjust path for your PHP version)
- Add the settings above
- Restart PHP-FPM: `sudo systemctl restart php7.x-fpm`

**WP Engine / Managed Hosting:**
- Contact support to increase PHP limits
- Most managed hosts provide a control panel option

## Usage

1. Activate the plugin
2. Go to **All-in-One WP Migration → Import**
3. You'll see **"Maximum upload file size: Unlimited"**
4. Select your backup file and import!

## Troubleshooting

### "The uploaded file is missing a temporary path"
**Solution:** Ensure your server's `upload_max_filesize` is sufficient. Increase it in `php.ini`.

### "Invalid file data" (415 error)
**Solution:** This occurs if subsequent chunks fail validation. Increase PHP `upload_max_filesize` so the entire file uploads successfully.

### Import stalls at 0%
**Solution:** Check browser console for errors. Clear browser cache and hard refresh (Cmd+Shift+R / Ctrl+Shift+F5).

### "Out of date" error
**Solution:** Update to the latest version of both this extension and All-in-One WP Migration.

## How It Works

This extension:

1. **Removes JavaScript restrictions** - The free version of All-in-One WP Migration limits file size in the browser
2. **Implements chunked uploads** - Large files are uploaded in manageable pieces  
3. **Preserves authentication** - Maintains WordPress security during upload
4. **Provides progress tracking** - Shows real-time upload status

## Comparison with Official Extension

This extension provides the same core functionality as the official ServMask Unlimited Extension:

| Feature | This Extension | Official |
|---------|----------------|----------|
| Remove file size limit | ✅ | ✅ |
| Chunked uploads | ✅ | ✅ |
| Progress tracking | ✅ | ✅ |
| Retry logic | ✅ | ✅ |
| Requires PHP config | ✅ | ✅ |
| **Price** | **Free** | **$69** |

## Development

### File Structure

```
all-in-one-wp-migration-unlimited-extension/
├── all-in-one-wp-migration-unlimited-extension.php  # Main plugin file
├── assets/
│   └── javascript/
│       └── uploader-v3.js                            # Chunked upload handler
├── README.md                                          # This file
└── readme.txt                                         # WordPress.org readme
```

### Contributing

Contributions are welcome! Please:

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

## License

This plugin is licensed under the GNU General Public License v3.0 or later.

## Credits

- Inspired by [ServMask's All-in-One WP Migration](https://servmask.com/)
- Compatible with All-in-One WP Migration 7.0+

## Support

For issues, feature requests, or questions:

- **GitHub Issues:** [Report a bug](https://github.com/yourusername/ai1wm-unlimited-extension/issues)
- **Documentation:** See readme.txt for detailed FAQ

## Changelog

### 1.0.0 - 2026-01-17
- ✨ Initial release
- ✅ Remove JavaScript file size restrictions
- ✅ Implement chunked upload system  
- ✅ Add automatic retry logic
- ✅ Add real-time progress tracking
- ✅ Compatible with All-in-One WP Migration 7.0+

---

**Made with ❤️ for the WordPress community**
