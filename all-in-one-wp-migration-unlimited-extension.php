<?php
/**
 * Plugin Name: All-in-One WP Migration Unlimited Extension
 * Plugin URI: https://github.com/tanjilahmed7/ai1wm-unlimited-extension
 * Description: Remove file size restrictions from All-in-One WP Migration imports. Upload and restore backups of any size without any limit.
 * Version: 1.0.0
 * Author: Tanjill Ahmed
 * Author URI: tanjilahmed.com
 * License: GPLv3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Requires at least: 5.0
 * Tested up to: 6.5
 * Requires PHP: 7.0
 * Requires Plugins: all-in-one-wp-migration
 * Text Domain: ai1wm-unlimited-extension
 * Domain Path: /languages
 * Network: True
 *
 * Copyright (C) 2026
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access not allowed' );
}

// Define plugin constants that AI1WM checks for (with safety checks)
if ( ! defined( 'AI1WMUE_PLUGIN_BASENAME' ) ) {
	define( 'AI1WMUE_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}
if ( ! defined( 'AI1WMUE_PLUGIN_NAME' ) ) {
	define( 'AI1WMUE_PLUGIN_NAME', 'all-in-one-wp-migration-unlimited-extension' );
}
if ( ! defined( 'AI1WMUE_VERSION' ) ) {
	define( 'AI1WMUE_VERSION', '1.0.0' );
}
if ( ! defined( 'AI1WMUE_PLUGIN_TITLE' ) ) {
	define( 'AI1WMUE_PLUGIN_TITLE', 'Unlimited Extension' );
}
if ( ! defined( 'AI1WMUE_PLUGIN_SHORT' ) ) {
	define( 'AI1WMUE_PLUGIN_SHORT', 'unlimited' );
}
if ( ! defined( 'AI1WMUE_PLUGIN_KEY' ) ) {
	define( 'AI1WMUE_PLUGIN_KEY', 'ai1wmue_plugin_key' );
}
if ( ! defined( 'AI1WMUE_PURCHASE_ID' ) ) {
	define( 'AI1WMUE_PURCHASE_ID', 'unlimited-extension-v1' );
}
if ( ! defined( 'AI1WMUE_PLUGIN_ABOUT' ) ) {
	define( 'AI1WMUE_PLUGIN_ABOUT', 'https://plugin-updates.wp-migration.com/unlimited-extension.json' );
}
if ( ! defined( 'AI1WMUE_PLUGIN_CHECK' ) ) {
	define( 'AI1WMUE_PLUGIN_CHECK', 'https://redirect.wp-migration.com/v1/check/unlimited-extension' );
}

/**
 * Initialize the plugin
 */
function ai1wmue_init() {
	// Check if AI1WM is active
	if ( ! defined( 'AI1WM_PLUGIN_NAME' ) ) {
		add_action( 'admin_notices', 'ai1wmue_missing_ai1wm_notice' );
		return;
	}

	// Register with AI1WM extension system
	add_filter( 'ai1wm_extensions', 'ai1wmue_register_extension' );
	
	// Override the "pro" message
	add_filter( 'ai1wm_pro', 'ai1wmue_pro_message', 999 );
	
	// Return unlimited file size for AI1WM's internal checks
	add_filter( 'ai1wm_max_file_size', 'ai1wmue_max_file_size', 999 );
	
	// Enqueue scripts to override JavaScript file size check only
	add_action( 'admin_enqueue_scripts', 'ai1wmue_enqueue_import_scripts', 5 );
	
	// Hook into import to ensure total_archive_size is set
	add_action( 'wp_ajax_ai1wm_import', 'ai1wmue_set_total_archive_size', 0 );
}
add_action( 'plugins_loaded', 'ai1wmue_init', 20 );

/**
 * Register extension with AI1WM's extension system
 */
function ai1wmue_register_extension( $extensions ) {
	$extensions[ AI1WMUE_PLUGIN_NAME ] = array(
		'key'      => AI1WMUE_PURCHASE_ID,
		'title'    => AI1WMUE_PLUGIN_TITLE,
		'about'    => AI1WMUE_PLUGIN_ABOUT,
		'check'    => AI1WMUE_PLUGIN_CHECK,
		'basename' => AI1WMUE_PLUGIN_BASENAME,
		'version'  => AI1WMUE_VERSION,
		'requires' => '2.0', // Minimum extension version
		'short'    => AI1WMUE_PLUGIN_SHORT,
	);
	return $extensions;
}

/**
 * Override the pro message - inspired by official extension
 */
function ai1wmue_pro_message( $message ) {
	return '<span style="color: #00a32a; font-weight: 500;">Maximum upload file size: <strong>Unlimited</strong></span>';
}

/**
 * Return unlimited file size for AI1WM's internal filters
 * Returns a very large number to avoid division by zero errors
 * Our custom uploader passes the actual file size separately
 */
function ai1wmue_max_file_size( $size ) {
	// Return PHP's max integer (usually 9223372036854775807 on 64-bit systems)
	// This is effectively unlimited for WordPress sites
	// Our custom uploader passes the actual total_archive_size in params
	return PHP_INT_MAX;
}


/**
 * Set total_archive_size if not already set (fallback for JavaScript)
 * Prevents division by zero errors in the main plugin's progress calculation
 */
function ai1wmue_set_total_archive_size() {
	if ( isset( $_POST['file'] ) && ! isset( $_POST['total_archive_size'] ) ) {
		$_POST['total_archive_size'] = PHP_INT_MAX;
	}
}

/**
 * Enqueue scripts for import page - Custom chunked uploader
 * Inspired by official extension, handles uploads in 5MB chunks
 */
function ai1wmue_enqueue_import_scripts( $hook ) {
	if ( stripos( $hook, 'ai1wm_import' ) === false ) {
		return;
	}

	// Enqueue our custom uploader that handles chunked uploads
	// Use UNIQUE HANDLE NAME to force WordPress to bypass cache completely!
	$timestamp = time();
	$handle = 'ai1wmue_uploader_' . $timestamp;
	wp_enqueue_script(
		$handle,
		plugins_url( 'assets/javascript/uploader-v3.js', __FILE__ ) . '?cachebust=' . $timestamp,
		array( 'jquery', 'ai1wm_servmask', 'ai1wm_util', 'ai1wm_import' ),
		AI1WMUE_VERSION . '.' . $timestamp,
		true
	);

	// Localize ai1wm_uploader (base configuration)
	wp_localize_script(
		$handle, // Use the unique handle
		'ai1wm_uploader',
		array(
			'max_file_size' => PHP_INT_MAX, // Unlimited
			'url'           => wp_make_link_relative( add_query_arg( array( 'ai1wm_import' => 1 ), admin_url( 'admin-ajax.php?action=ai1wm_import' ) ) ),
			'chunk_size'    => apply_filters( 'ai1wm_max_chunk_size', 5 * 1024 * 1024 ), // 5MB chunks
		)
	);

	// Localize ai1wmue_uploader (extension-specific configuration) - SAME AS OFFICIAL
	wp_localize_script(
		$handle, // Use the unique handle
		'ai1wmue_uploader',
		array(
			'chunk_size'  => apply_filters( 'ai1wm_max_chunk_size', 5 * 1024 * 1024 ), // 5MB chunks
			'max_retries' => apply_filters( 'ai1wm_max_chunk_retries', 10 ), // Retry logic
			'url'         => wp_make_link_relative( add_query_arg( array( 'ai1wm_import' => 1 ), admin_url( 'admin-ajax.php?action=ai1wm_import' ) ) ),
			'params'      => array(
				'priority'   => 5,
				'secret_key' => get_option( AI1WM_SECRET_KEY ),
			),
			'filters'     => array(
				'ai1wm_archive_extension' => array( '.wpress' ), // Supported extensions
				'ai1wm_archive_size'      => apply_filters( 'ai1wm_max_file_size', 0 ), // Unlimited
			),
		)
	);
}

/**
 * Display notice when AI1WM is missing
 */
function ai1wmue_missing_ai1wm_notice() {
	$screen = get_current_screen();
	if ( $screen && $screen->id === 'plugins' ) {
		?>
		<div class="notice notice-error">
			<p>
				<strong>All-in-One WP Migration Unlimited Extension</strong> requires the 
				<a href="https://wordpress.org/plugins/all-in-one-wp-migration/" target="_blank">All-in-One WP Migration plugin</a> 
				to be installed and activated.
			</p>
		</div>
		<?php
	}
}

/**
 * Plugin activation hook - Attempt to create PHP config overrides
 */
function ai1wmue_activate() {
	// Store activation key
	update_option( 'ai1wmue_plugin_key', AI1WMUE_PURCHASE_ID, false );
	
	// Try to create .user.ini for PHP-FPM servers
	ai1wmue_create_user_ini();
	
	// Try to add PHP overrides to .htaccess for Apache servers
	ai1wmue_update_htaccess( 'add' );
	
	// Set a transient to show admin notice about configuration
	set_transient( 'ai1wmue_activation_notice', true, 60 );
}
register_activation_hook( __FILE__, 'ai1wmue_activate' );

/**
 * Plugin deactivation hook - Clean up config files
 */
function ai1wmue_deactivate() {
	delete_option( 'ai1wmue_plugin_key' );
	
	// Remove .user.ini if we created it
	ai1wmue_remove_user_ini();
	
	// Remove PHP overrides from .htaccess
	ai1wmue_update_htaccess( 'remove' );
}
register_deactivation_hook( __FILE__, 'ai1wmue_deactivate' );

/**
 * Create .user.ini file with PHP overrides (for PHP-FPM servers)
 */
function ai1wmue_create_user_ini() {
	$user_ini_path = ABSPATH . '.user.ini';
	
	// Check if we can write to the directory
	if ( ! is_writable( ABSPATH ) ) {
		return false;
	}
	
	$settings = "; AI1WM Unlimited Extension - PHP Upload Settings\n";
	$settings .= "upload_max_filesize = 2048M\n";
	$settings .= "post_max_size = 2048M\n";
	$settings .= "memory_limit = 512M\n";
	$settings .= "max_execution_time = 600\n";
	$settings .= "max_input_time = 600\n";
	
	// If file exists, append our settings
	if ( file_exists( $user_ini_path ) ) {
		$existing = file_get_contents( $user_ini_path );
		// Only add if not already present
		if ( strpos( $existing, 'AI1WM Unlimited Extension' ) === false ) {
			file_put_contents( $user_ini_path, $existing . "\n" . $settings );
			return true;
		}
	} else {
		// Create new file
		file_put_contents( $user_ini_path, $settings );
		return true;
	}
	
	return false;
}

/**
 * Remove .user.ini settings added by this plugin
 */
function ai1wmue_remove_user_ini() {
	$user_ini_path = ABSPATH . '.user.ini';
	
	if ( ! file_exists( $user_ini_path ) ) {
		return;
	}
	
	$content = file_get_contents( $user_ini_path );
	
	// Remove our section
	$content = preg_replace(
		'/; AI1WM Unlimited Extension - PHP Upload Settings\n.*?\n.*?\n.*?\n.*?\n.*?\n/s',
		'',
		$content
	);
	
	// If file is now empty, delete it
	if ( trim( $content ) === '' ) {
		unlink( $user_ini_path );
	} else {
		file_put_contents( $user_ini_path, $content );
	}
}

/**
 * Add or remove PHP overrides in .htaccess (for Apache servers)
 */
function ai1wmue_update_htaccess( $action = 'add' ) {
	$htaccess_path = ABSPATH . '.htaccess';
	
	// Only works on Apache servers with .htaccess
	if ( ! file_exists( $htaccess_path ) || ! is_writable( $htaccess_path ) ) {
		return false;
	}
	
	$content = file_get_contents( $htaccess_path );
	
	$marker_begin = '# BEGIN AI1WM Unlimited Extension';
	$marker_end = '# END AI1WM Unlimited Extension';
	
	$settings = "$marker_begin\n";
	$settings .= "<IfModule mod_php7.c>\n";
	$settings .= "php_value upload_max_filesize 2048M\n";
	$settings .= "php_value post_max_size 2048M\n";
	$settings .= "php_value memory_limit 512M\n";
	$settings .= "php_value max_execution_time 600\n";
	$settings .= "php_value max_input_time 600\n";
	$settings .= "</IfModule>\n";
	$settings .= "$marker_end\n";
	
	if ( $action === 'add' ) {
		// Remove existing if present
		$content = preg_replace( "/$marker_begin.*?$marker_end\n/s", '', $content );
		
		// Add at the beginning
		$content = $settings . $content;
		
		file_put_contents( $htaccess_path, $content );
		return true;
	} else {
		// Remove
		$content = preg_replace( "/$marker_begin.*?$marker_end\n/s", '', $content );
		file_put_contents( $htaccess_path, $content );
		return true;
	}
}

/**
 * Show admin notice after activation
 */
function ai1wmue_activation_notice() {
	if ( ! get_transient( 'ai1wmue_activation_notice' ) ) {
		return;
	}
	
	delete_transient( 'ai1wmue_activation_notice' );
	
	$message = '<strong>🎉 AI1WM Unlimited Extension activated!</strong><br><br>';
	
	$message .= '✅ <strong>Custom chunked uploader loaded</strong> - Import files of ANY size!<br>';
	$message .= '✅ <strong>No manual configuration needed</strong> - Works with default server settings<br>';
	$message .= '✅ <strong>Smart retry logic</strong> - Automatically handles upload interruptions<br><br>';
	
	// Check if we also optimized PHP settings
	$user_ini_exists = file_exists( ABSPATH . '.user.ini' );
	$htaccess_modified = false;
	
	if ( file_exists( ABSPATH . '.htaccess' ) ) {
		$htaccess_content = file_get_contents( ABSPATH . '.htaccess' );
		$htaccess_modified = strpos( $htaccess_content, 'AI1WM Unlimited Extension' ) !== false;
	}
	
	if ( $user_ini_exists || $htaccess_modified ) {
		$message .= '🚀 <strong>Bonus:</strong> PHP settings auto-optimized for even faster uploads!<br>';
	}
	
	$message .= '<br><strong>Ready to import!</strong> Go to All-in-One WP Migration → Import';
	
	echo '<div class="notice notice-success is-dismissible"><p>' . $message . '</p></div>';
}
add_action( 'admin_notices', 'ai1wmue_activation_notice' );
