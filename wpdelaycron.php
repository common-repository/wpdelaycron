<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Plugin Name: WPDelayCron
 * Plugin URI: http://www.cmagic.biz/wordpress/wpdelaycron/
 * Description: Prevent wordpress from running cron on each page view.
 * Version: 0.0.6
 * Author: Ray Pulsip=== WP Delay Cron ===
Contributors: computermagic
Donate link: http://www.cmagic.biz/wordpress/wpdelaycron/
Tags: cron, delay, high, traffic
Requires at least: 3.0.1
Tested up to: 4.5.2
Stable tag: 0.0.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Prevent cron from running every time a page is viewed. Good for high traffic sites.

== Description ==

The WP Delay Cron plugin will prevent wordpress from running its cron tool for every page view.
For high traffic sites, this can result in a considerable drop in server load.

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Why wouldn't you want to run the cron every page it? =

Wordpress makes a seperate web request to run the cron. For every page hit the server
has to process 2 web requests. This is wastefull and adds up on a high traffic site.

= How long does it delay for?  =

Pick a number in the settings page. This will wait to run the cron until that many
page views occur. If you pick 10, then you will run the cron every 10 page views.

== Screenshots ==

== Changelog ==

= 0.0.6 =
Tested on latest wordpress - 4.5.1

= 0.0.5 =
Fix for multisite installs

= 0.0.4 =
Minor code refactoring

= 0.0.3 =
Misc Changes

= 0.0.2 =
Store counter in wp options instead of a file

= 0.0.1 =
Initial Release.

== Upgrade Notice ==



 * Author URI: http://cmagic.biz
 * License: GPL2
 */
/*  Copyright 2015  Ray Pulsipher  (email : ray@cmagic.biz)

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

wp_delaycron_install_actions();

function wp_delaycron_install_actions()
{
	add_option('wpdelaycron_delay_count', '100', '', 'yes');
	add_option('wpdelaycron_current_count', '0', '', 'yes');

	add_action('plugins_loaded', 'wp_delaycron_plugins_loaded');	
	if (is_admin())
	{
		add_action('admin_init', 'wp_delaycron_admin_init');
		if(is_multisite())
		{
			//add_action('network_admin_menu', 'wp_delaycron_admin_menus');
		}
		add_action('admin_menu', 'wp_delaycron_admin_menus');
	}
}

function wp_delaycron_plugins_loaded()
{
	// WP DelayCron - Prevent running every time
	$delay_count = get_option('wpdelaycron_delay_count', '10') + 0;
	// Read counter
	$cnt =  get_option('wpdelaycron_current_count', '0') + 0;
	$cnt++;
	if ($cnt > $delay_count) {
	        // Reset counter
	        $cnt=0;
	} else {
	        // Turn off cron this time around
	        define('DISABLE_WP_CRON', 'true');
	}
	// Save the current count
	$ret = update_option('wpdelaycron_current_count', "$cnt");
	//if (ret == True) { $ret = '1'; } else { $ret = '0'; }
	//file_put_contents(ABSPATH . 'wp-content/plugins/wpdelaycron/cron_counter', $cnt . 'abc(' .$ret.')');
}

function wp_delaycron_admin_init()
{
	register_setting( 'wpdelaycron_options_group', 'wpdelaycron_delay_count' );
}

function wp_delaycron_admin_menus()
{
	if (is_admin())
	{
		add_menu_page('WPDelayCron', 'WPDelayCron', 'manage_options', 'WPDelayCron', 'wp_delaycron_plugin_options', ''); 
		//add_options_page('WPDelayCron', 'WPDelayCron', 'manage_options', 'wpdelaycron', 'wp_delaycron_plugin_options');
	}
}

function wp_delaycron_plugin_options()
{
	if (!current_user_can( 'manage_options' )) {
		wp_die( __('Insufficient permissions.') );
	}

echo '<div class="wrap">';
echo '<h2>WP Delay Cron Plugin</h2>';

echo '<form method="post" action="options.php">';

settings_fields( 'wpdelaycron_options_group' );
do_settings_sections( 'wpdelaycron_options_group' );

$delay_cnt =  get_option( 'wpdelaycron_delay_count', '100' ) + 0;
$cnt = get_option('wpdelaycron_current_count', '0') + 0;

echo 'Number of page views before running cron: ';
echo '<input type=text name="wpdelaycron_delay_count" value="' . esc_attr( $delay_cnt ) . '" />';
echo '<p>If you set this at 100 and your site gets 1000 page hits per day, then cron will
 run 10 times that day.</p>
<p>Set to 0 to run cron every time.</p>';
echo '<p>You currently have ' . esc_attr( $delay_cnt - $cnt ) . ' page views before the cron runs again.</p>'; 
echo '<p>Plugin written by Ray Pulsipher -
 <a href="http://www.cmagic.biz/">Computer Magic
 <img src="http://cmtk.cmagic.biz/art/wizard.png" border=0 /></a>';

submit_button();

echo '</form>';
echo '</div>';




}

?>
