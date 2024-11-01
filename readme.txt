=== WP Delay Cron ===
Contributors: computermagic
Donate link: http://www.cmagic.biz/wordpress/wpdelaycron/
Tags: cron, delay, high, traffic
Requires at least: 3.0.1
Tested up to: 4.5.1
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

1. Upload `wpdelaycron.php` to the `/wp-content/plugins/wpdelaycron/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Why wouldn't you want to run the cron every page it? =

Wordpress makes a seperate web request to run the cron. For every page hit the server
has to process 2 web requests. This is wastefull and adds up on a high traffic site.

= How long does it delay for?  =

Pick a number in the settings page. This will wait to run the cron until that many
page views occur. If you pick 10, then you will run the cron every 10 page views.

= Does this work with multi site installs? =

Yes. The options page will show up under each individual site, not in the network admin site.

== Screenshots ==

== Changelog ==

= 0.0.6 =
Tested on latest wordpress - 4.5.1

= 0.0.5 =
Fix multi site bug

= 0.0.4 = 
Bug Fixes

= 0.0.3 =
Bug Fixes

= 0.0.2 =
Store counter in wp options instead of a file

= 0.0.1 =
Initial Release.

== Upgrade Notice ==


