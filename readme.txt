=== Affiliatize Me ===
Contributors: smartscrutiny
Tags: links, affiliate, target blank, new window, new tab
Requires at least: 4.0
Requires PHP: 5.2
Tested up to: 6.4
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Make all your links affiliate links.

== Description ==
This plugin searches links on your site for the domain that you specify in the settings, adds your affiliate ID, and makes those links open in a new tab when clicked.

The plugin produces XHTML Strict compliant code and is search engine optimized (SEO).
This is done using JavaScript's `window.open()`-function. It adds only a few lines of vanilla JavaScript to the page, and does not require any external libraries like jQuery.

Most other plugins perform a hack by altering the `target` parameter (i.e. `<a href="http://somewhere.example" target="_blank">`). That method is not XHTML Strict compliant.

This plugin handles the links client-side, which lets search engines follow the links properly. Also, if a browser does not support JavaScript, the plugin is simply inactive, and does not result in any errors.


**Credits**
Inspired by the [External Links in New Window / New Tab](https://wordpress.org/plugins/open-external-links-in-a-new-window/) plugin.

**Known bugs**
The plugin conflicts with other plugins that change the links' `onClick´ attribute.

== Installation ==
1. Copy the plugin to /wp-content/plugins/
1. Activate the plugin.
1. Change the settings in Settings->Affiliatize Me.

== Screenshots ==
1. Affiliatize Me settings


== Changelog ==

= 1.0 =
Ready for production.

