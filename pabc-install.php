<?php
/**
 * Plugin activation/deactivation. Create necessary
 * tables and directories. Tables are not uninstalled
 * on deactivation by default, but you can comment
 * out the last line in this file to uninstall them
 * the next time you deactivate if needed.
 */
 
/*****************************************************
 * Activation
 *****************************************************/
/**
 * Install tables and attempt to create upload folder.
 */
function pabc_install() {
	// create upload folder
	$upload_info = wp_upload_dir();
	$upload_folder = $upload_info['basedir'];
	
	if (!is_writable($upload_folder)) {
		die("Cycler folder does not exist or is not writable. Please create the folder $upload_folder and give it write permissions.");
		return false;
	}
	if (!file_exists(PACYCLER_IMAGEUPLOADPATH)) {
		mkdir(PACYCLER_IMAGEUPLOADPATH) or die("Could not create folder " . PACYCLER_IMAGEUPLOADPATH . ". Please create this folder manually and give it write permissions.");	
	}
	
	// Setup database
	pabc_install_cycler_db();
	pabc_install_slide_db();
}

/**
 * Create cycler table
 */
function pabc_install_cycler_db() {
	global $wpdb;
	$query = "SHOW TABLES LIKE '" . PABC_DB_CYCLERS . "';";
	$installed = $wpdb->get_row($query);
	
	if (!$installed) {
		pabc_do_cycler_install();
	}
}

/**
 * Generate cycler install tables
 */
function pabc_do_cycler_install() {
	global $wpdb;
	$wpdb->query('CREATE TABLE  `' . PABC_DB_CYCLERS . '` (
				  `cycler_id` int(10) unsigned NOT NULL auto_increment,
				  `cycler_name` varchar(45) NOT NULL,
				  `jquery_call` text,
				  `html_template` text,
				  `item_template` text,
				  `text1` varchar(45) default NULL,
				  `text2` varchar(45) default NULL,
				  `text3` varchar(45) default NULL,
				  `text4` varchar(45) default NULL,
				  `image1` varchar(45) default NULL,
				  `image2` varchar(45) default NULL,
				  `image3` varchar(45) default NULL,
				  PRIMARY KEY  (`cycler_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
				
	// add sample cycler
	$wpdb->query("INSERT INTO `wp_pabc_cyclers` (`cycler_id`,`cycler_name`,`jquery_call`,`html_template`,`item_template`,`text1`,`text2`,`text3`,`text4`,`image1`,`image2`,`image3`)
VALUES
	(1, 'Sample Cycler', '$(\'#cycler\').cycle({ 
				    fx:     \'fade\', 
				    speed:  500, 
				    timeout: 5000, 
				    next:   \'#next2\', 
				    prev:   \'#prev2\',
				    pager: \'#cyclerNum\', 
				    pause:   1,
			            cleartypeNoBg: true
				});', '<div id=\"cycler\"> %slides% </div>
<div id=\"cyclerNav\">
    <a id=\"prev2\">Previous</a>
    <div id=\"cyclerNum\"></div>
     <a id=\"next2\">Next</a>
</div>', '<div class=\"cycler-slide\">
			<a class=\"hitSpace\" href=\"%text3%\">
			<img alt=\"%text1%\" src=\"%image1%\" /></a>
			</div>', 'Title', 'Caption', 'Link', '', 'Large Image', '', '');
");

}

/**
 * Install slides table
 */
function pabc_install_slide_db() {
	global $wpdb;
	$query = "SHOW TABLES LIKE '" . PABC_DB_SLIDES . "';";
	$installed = $wpdb->get_row($query);
	
	if (!$installed) {
		pabc_do_slide_install();
	}

	// check version
	$version = get_option('pabc_version');
	if (!$version) {
		add_option('pabc_version', 1.0);	
		$version = 1.0;
	}
	
	// update to latest version
	update_option('pabc_version', doubleval(PACYCLER_VERSION));	
	
	// VERSION 1.2 UPDATE
	if (doubleval($version) < 1.2) {
		$wpdb->query("ALTER TABLE  " . PABC_DB_SLIDES . " ADD COLUMN display_order INT NOT NULL DEFAULT 0;");
	}
}

function pabc_do_slide_install() {

	global $wpdb;
	$wpdb->query('CREATE TABLE  `' . PABC_DB_SLIDES . '` (
				  `slide_id` int(10) unsigned NOT NULL auto_increment,
				  `cycler_id` int(10),
				  `text1` varchar(600) default NULL,
				  `text2` varchar(600) default NULL,
				  `text3` varchar(600) default NULL,
				  `text4` varchar(600) default NULL,
				  `image1` varchar(600) default NULL,
				  `image2` varchar(600) default NULL,
				  `image3` varchar(600) default NULL,
				  `display_order` int(10) default 0,
				  PRIMARY KEY  (`slide_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
				
}

register_activation_hook(PACYCLER_PLUGINDIR . 'banner-cycler.php', 'pabc_install');

/*****************************************************
 * Deactivation
 *****************************************************/
function pabc_uninstall() {
	global $wpdb;
	//$wpdb->query('DROP TABLE IF EXISTS ' . PABC_DB_SLIDES . '');
	//$wpdb->query('DROP TABLE IF EXISTS ' . PABC_DB_CYCLERS . '');
	// Intentionally not deleting uploads (I figured there is the change that
	// users may not have kept copies of these slides if they need them later
	// and the disk space used will be miniscule.
}
register_deactivation_hook(PACYCLER_PLUGINDIR . 'banner-cycler.php', 'pabc_uninstall');


/*****************************************************
 * Upgrade
 *****************************************************/
function pabc_add_plugin_row() {

	$show_upgrade = false;
	// check version
	/*try {
		$html =  trim(wp_remote_fopen('http://www.wordpress.org/extend/plugins/banner-cycler'));
		$html = strip_tags($html);
		$version = preg_match('/Version: ([0-9.]+)/', $html, $match);
		if (sizeof($match) > 1) {
			if (doubleval($match[1]) > doubleval(PACYCLER_VERSION)) {
				// upgrade available
				$show_upgrade = true;
			}
		}
	} catch(Exception $e) {}

	if ($show_upgrade) {
		echo '<tr class="plugin-update-tr">
				<td colspan="3" class="plugin-update">
					<div class="update-message">There is a new version of Banner Cycler available. 
						<a href="' . get_bloginfo('url') . '/wp-admin/plugin-install.php?tab=plugin-information&amp;plugin=banner-cycler&amp;TB_iframe=true&amp;width=640&amp;height=678" class="thickbox" title="Banner Cycler">View Version Details</a> or <a href="update.php?action=upgrade-plugin&amp;plugin=banner-cycler%2Fbanner-cycler.php&amp;_wpnonce=' . wp_create_nonce(NONCE_KEY) . '">upgrade automatically</a>.
					</div>
				</td>
			</tr>';
	}*/
 
}

add_action('after_plugin_row_banner-cycler/banner-cycler.php', 'pabc_add_plugin_row', 10, 2);