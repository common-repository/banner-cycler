=== Banner Cycler ===
Contributors: jkriddle 
Tags: banners, jquery
Requires at least: 2.7
Tested up to: 2.9
Version: 1.4
Stable tag: 1.4
	
Add rotating banner areas that cycle through various slides uploaded by an administrator. The current version is a developer-centric plugin and is not recommended for users not comfortable editing page templates. I do hope to update the plugin in the future to add short-code placement of the cyclers.

== Description ==

This plugin allows you to create "cyclers" which you may upload slides into. These cyclers may be added to a page template and cycle through the various slides automatically, or as configured in the admin area.

UPGRADE NOTICE
If you are upgrading the plugin from 1.1 or below, do NOT deactivate your plugin before uploading the new files. Earlier versions of the plugin removed your slides when deactivated. In order to upgrade, manually upload the new version's files, then deactivate and reactivate the plugin.

Users of 1.2 and above will not have to worry about this issue.

Thanks to Colin Ligertwood (http://brainbits.ca) this plugin now includes a shortcode to display a cycler within a page/post.
Use the shortcode format:
[bannercycler cycler="Cycler Name Here"]
	
== Installation ==

Extract the zip file and just drop the contents in the wp-content/plugins/ directory of your WordPress installation and then activate the Plugin from Plugins page. 

After activating the plugin, a menu item under "Options" will be created named "Banner Cycler". Also a default "Sample Cycler" will be created that you may begin adding slides to. A cycler MUST have more than one slide to work properly.

To insert a cycler into a page or post, use the shortcode format:
[bannercycler cycler="Cycler Name Here"]

If displaying a cycler in a template file add the pabc_display('CYCLER NAME HERE') function to your template. Using the sample cycler you may add code such as:

`<?php if (function_exists('pabc_display')) pabc_display('Sample Cycler'); ?>`

== Upgrade Instructions ==

If you are upgrading from version 1.1 or lower you will need to de-activate the plugin and re-activate it after uploading your files in order to update your database settings. The plugin will not function without these steps.

== Changelog ==

= 1.2 =
* Added ability to set display order for slides.

= 1.1 =
* Merged shortcode functionality.
