<?php
/*
Plugin Name: Banner Cycler
Plugin URI: http://www.theriddlebrothers.com/our-services/wordpress-plugins/banner-cycler
Description: Display a banner that cycles through images using a cycle effect.
Author: Joshua Riddle
Version: 1.4
Author URI: http://www.theriddlebrothers.com
*/
/*  Copyright 2009  Joshua Riddle  (email : josh@theriddlebrothers.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
/***********************************************************************************
 * Developer comments:
 *
 * This plugin was created as a very specific plugin for banner cyclers which are
 * used on a lot of sites for our company. 
 * It would be great to make it more usable over time so
 * you don't have to paste HTML and jQuery calls into text boxes, but for this
 * initial versions, it was a quick and dirty way to get it working for
 * a production site.
 ***********************************************************************************/
if (!defined('PACYCLER_VERSION')) {
	require('pabc-config.php');
	require('classes/cycler.class.php');
	require('classes/slide.class.php');
	require('classes/cyclerfactory.class.php');
	require('pabc-functions.php');
	
	/**
	 * Bind plugin installation
	 */
	require('pabc-install.php');
	
	// Admin section
	if (is_admin()) {
		include('admin/admin.php');	
	} else {
		// require necessary scripts for public-facing site
		wp_enqueue_script('jquery');	
		wp_enqueue_script('cycler', PACYCLER_CYCLESCRIPT, 'jquery');
	}
	
	// Bind WP actions
	add_shortcode('bannercycler', 'pabc_shortcode');
}