<?php
/**
	* Plugin Name: S2BD Bridge
	* Plugin URI: http://buddy-wds.com/developpements/extensions/s2bd-bridge/
	* Description: Allows the linking of user custom fields (S2Member plugin) with forums (bbPress plugin) and activity notifications by email (Digests bbPress plugin) for users who are subscribed.
	* Version: 1.0.1
	* Author: Ludovic Rousselle
	* Author URI: http://buddy-wds.com/
	* Text Domain: s2bd-bridge
	* Domain Path: /languages
*/

/**
	Copyright 2014  Ludovic Rousselle  (email : webbud65@gmail.com)

	This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

### Security acces to file directly
defined('ABSPATH') or die("No script kiddies please!");

### Create Text Domain For Translations
add_action( 'plugins_loaded', 'digestbridge_textdomain' );
function digestbridge_textdomain() {
	//load_plugin_textdomain( 's2bd-bridge', false, dirname( plugin_basename( __FILE__ ) ) );
	// with language detection :
	load_plugin_textdomain('s2bd-bridge', false, basename( dirname( __FILE__ ) ) . '/languages' );
}

### Function: Digest Bridge Manager Menu
add_action('admin_menu', 'digestbridge_menu');
function digestbridge_menu() {
	if (function_exists('add_menu_page')) {
		add_menu_page(__('S2BD Bridge', 's2bd-bridge'), __('S2BD Bridge', 's2bd-bridge'), 'manage_database', 's2bd-bridge/s2bd-bridge-manager.php', '', 'dashicons-text');
	}
	if (function_exists('add_submenu_page')) {
		add_submenu_page('s2bd-bridge/s2bd-bridge-manager.php', __('General settings', 's2bd-bridge'), __('General settings', 's2bd-bridge'), 'manage_database', 's2bd-bridge/general-settings.php');	
		add_submenu_page('s2bd-bridge/s2bd-bridge-manager.php', __('Fields settings', 's2bd-bridge'), __('Fields settings', 's2bd-bridge'), 'manage_database', 's2bd-bridge/fields-settings.php');		
		add_submenu_page('s2bd-bridge/s2bd-bridge-manager.php', __('Forums settings', 's2bd-bridge'), __('Forums settings', 's2bd-bridge'), 'manage_database', 's2bd-bridge/forums-settings.php');		
		add_submenu_page('s2bd-bridge/s2bd-bridge-manager.php', __('Fields per member', 's2bd-bridge'), __('Fields per member', 's2bd-bridge'), 'manage_database', 's2bd-bridge/fields-per-member.php');	
		add_submenu_page('s2bd-bridge/s2bd-bridge-manager.php', __('Forums per member', 's2bd-bridge'), __('Forums per member', 's2bd-bridge'), 'manage_database', 's2bd-bridge/forums-per-member.php');	
		add_submenu_page('s2bd-bridge/s2bd-bridge-manager.php', __('Statistics', 's2bd-bridge'), __('Statistics', 's2bd-bridge'), 'manage_database', 's2bd-bridge/forums-subscriptions-stats.php');	
		add_submenu_page('s2bd-bridge/s2bd-bridge-manager.php', __('Inscribe members', 's2bd-bridge'), __('Inscribe members', 's2bd-bridge'), 'manage_database', 's2bd-bridge/inscribe-members.php');
	}
}

### Function: Activate Plugin
register_activation_hook( __FILE__, 'digestbridge_activation' );
function digestbridge_activation( $network_wide )
{
	if ( is_multisite() && $network_wide )
	{
		$ms_sites = wp_get_sites();
		if( 0 < sizeof( $ms_sites ) )
		{
			foreach ( $ms_sites as $ms_site )
			{
				switch_to_blog( $ms_site['blog_id'] );
				add_option( $option_name, $option );
				digestbridge_activate();
			}
		}
		restore_current_blog();
	}
	else
	{
		add_option( $option_name, $option );
		digestbridge_activate();
	}
}
### 
//register_activation_hook( __FILE__, 'digestbridge_activate' );
function digestbridge_activate() {
	// Set 'manage_database' Capabilities To Administrator
	$role = get_role( 'administrator' );
	if( !$role->has_cap( 'manage_database') )
	{
		$role->add_cap( 'manage_database' );
	}
}
### 
register_activation_hook( __FILE__, 'digestbridge_install' );
global $digestbridge_db_version;
$digestbridge_db_version = '1.0';
function digestbridge_install() {
	// Clear the permalinks after the post type has been registered
    flush_rewrite_rules();
	// Create tables
	global $wpdb;
	global $digestbridge_db_version;
	$charset_collate = '';
	if ( ! empty( $wpdb->charset ) ) {
	  $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
	}
	if ( ! empty( $wpdb->collate ) ) {
	  $charset_collate .= " COLLATE {$wpdb->collate}";
	}
	// Table 1
	$table_name1 = $wpdb->prefix . 'sbd_options';
	$sql1 = "CREATE TABLE IF NOT EXISTS $table_name1 (
	`id` int(11) NOT NULL auto_increment,
	`user_role` varchar(255) default NULL,
	`default_forums` varchar(255) default NULL,
	`s_values` varchar(100) default NULL,
	`variant_forum` varchar(100) default NULL,
	`variant_operator` varchar(3) default NULL,
	PRIMARY KEY (`id`)
	) $charset_collate;";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql1 );
	// Table 2
	$table_name2 = $wpdb->prefix . 'sbd_relations';
	$sql2 = "CREATE TABLE IF NOT EXISTS $table_name2 (
	`id` int(11) NOT NULL auto_increment,
	`field` varchar(255) default NULL,
	`forum` int(11) default NULL,
	PRIMARY KEY (`id`)
	) $charset_collate;";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql2 );

	//add_option( 'digestbridge_db_version', $digestbridge_db_version );
}

### Or see uninstall.php file
register_deactivation_hook( __FILE__, 'digestbridge_deactivate' );
function digestbridge_deactivate() {
	// Our post type will be automatically removed, so no need to unregister it
	
	// Clear the permalinks to remove our post type's rules
    flush_rewrite_rules();
	// Drop tables
	global $wpdb;
	// Table 1
	$table_name1 = $wpdb->prefix . 'sbd_options';
	$sql1 = "DROP TABLE $table_name1";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql1 );
	// Table 2
	$table_name2 = $wpdb->prefix . 'sbd_relations';
	$sql2 = "DROP TABLE $table_name2";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql2 );
}
### UPGRADE plugin
//http://wordpress.stackexchange.com/questions/25910/uninstall-activate-deactivate-a-plugin-typical-features-how-to/25979#25979
?>