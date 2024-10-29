<?php

/**
 * Add necessary stylesheets and scripts
 */
function pabc_admin_init() {
	wp_enqueue_style('pacycler-admin', PACYCLER_PLUGINURL . '/css/pacycler-admin.css');
}

/**
 * Display admin menu and handle display routing
 */
function pabc_admin_menu() {
	add_submenu_page('options-general.php', 'Banner Cycler', 'Banner Cycler', 8, __FILE__, 'pabc_admin_routing');	
}

/**
 * Display cycler admin page
 */
function pabc_admin_cycler() {
	
	// handle postbacks
	if ($_SERVER['REQUEST_METHOD'] == 'POST') pabc_admin_cycler_postback();
	
	if ($_GET['pacycler_action'] == 'delete') {
		PA_BannerCycler_CyclerFactory::deleteCycler(intval($_GET['pacycler_cyclerid']));
		
		$_SESSION['PACYCLER_MESSAGE'] = 'Cycler has been deleted.';
		//wp_safe_redirect(PACYCLER_OPTIONSPAGEURL . '&view=cycler&deleted=1');
		//exit();
	}
	
	// messages
	if (isset($_SESSION['PACYCLER_MESSAGE'])) $message = $_SESSION['PACYCLER_MESSAGE'];
	
	// load all cyclers
	$cyclers = PA_BannerCycler_CyclerFactory::getCyclers();
	
	// load current cycler
	if (isset($_GET['pacycler_id'])) {
		$current_cycler = PA_BannerCycler_CyclerFactory::getCycler(intval($_GET['pacycler_id']));	
	}
	
	$view_file = 'views/admin-options.php';	
	include(PACYCLER_PLUGINDIR . $view_file);
}

/**
 * Save posted cycler
 */
function pabc_admin_cycler_postback() {
	if (!isset($_POST['pacycler_postback'])) return;
	
	// Delete
	if (isset($_POST['pacycler_delete'])) {
		$cycler_id = $_POST['pacycler_id'];
		PA_BannerCycler_CyclerFactory::deleteCycler($cycler_id);
		
		$_SESSION['PACYCLER_MESSAGE'] = 'Cycler has been deleted.';
		//wp_safe_redirect(PACYCLER_OPTIONSPAGEURL . '&view=cycler&deleted=1');
		//exit();
	}

	// Save
	$cycler = new PA_BannerCycler_Cycler;
	$cycler->cycler_id = stripslashes($_POST['pacycler_id']);
	$cycler->cycler_name = stripslashes($_POST['pacycler_name']);	
	$cycler->jquery_call = stripslashes($_POST['pacycler_jquerycall']);
	$cycler->html_template = stripslashes($_POST['pacycler_htmltemplate']);
	$cycler->item_template = stripslashes($_POST['pacycler_itemtemplate']);
	for ($i=0; $i < PACYCLER_NUMTEXTFIELDS; $i++) {
		$cycler->{'text' . ($i+1)} = stripslashes($_POST['pacycler_text' . ($i+1)]);
	}
	for ($i=0; $i <= PACYCLER_NUMTEXTFIELDS; $i++) {
		$cycler->{'image' . ($i+1)} = stripslashes($_POST['pacycler_image' . ($i+1)]);
	}
	$cycler_id = PA_BannerCycler_CyclerFactory::saveCycler($cycler);
	
	$_SESSION['PACYCLER_MESSAGE'] = 'Cycler has been saved.';
	//wp_redirect(PACYCLER_OPTIONSPAGEURL . '&view=cycler&saved=1&pacycler_id=' . $cycler_id);
	//exit();
}

/**
 * Display slide admin page
 */
function pabc_admin_slides() {
	
	// handle postbacks
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		pabc_admin_slides_postback();
	}
	if (isset($_GET['pacycler_action'])) {
		if ($_GET['pacycler_action'] == 'delete') {
			PA_BannerCycler_CyclerFactory::deleteSlide(intval($_GET['pacycler_slideid']));
			
			$_SESSION['PACYCLER_MESSAGE'] = 'Slide has been deleted.';
			//wp_safe_redirect(PACYCLER_OPTIONSPAGEURL . '&view=slide&deleted=1&pacycler_id=' . $_GET['pacycler_id']);
			//exit();
		}
	}
	
	// messages
	if (isset($_SESSION['PACYCLER_MESSAGE'])) $message = $_SESSION['PACYCLER_MESSAGE'];
		
	// load all cyclers
	$cyclers = PA_BannerCycler_CyclerFactory::getCyclers();
	
	// load cycler
	$cycler_id = (isset($_GET['pacycler_id']) ? $_GET['pacycler_id'] : null);
	if ($cycler_id) {
		$current_cycler = PA_BannerCycler_CyclerFactory::getCycler($cycler_id);
		$slides = PA_BannerCycler_CyclerFactory::getSlides($cycler_id);
	}
	
	// load slide
	$slide_id = (isset($_GET['pacycler_slideid']) ? $_GET['pacycler_slideid'] : null);
	if ($slide_id) {
		$current_slide = PA_BannerCycler_CyclerFactory::getSlide($slide_id);
		$current_cycler = PA_BannerCycler_CyclerFactory::getCycler($current_slide->cycler_id);
		$slides = PA_BannerCycler_CyclerFactory::getSlides($current_cycler->cycler_id);
	}
	
	$view_file = 'views/edit-slide.php';	
	include(PACYCLER_PLUGINDIR . $view_file);
}

/**
 * Handle postback for slides page
 */
function pabc_admin_slides_postback() {
	if (!isset($_POST['pacycler_postback'])) return;
	
	// open a cycler for slide management
	if (($_POST['pacycler_doaction'] == 'Apply') && ($_POST['pacycler_cycleraction'] == 'delete')) {
		$ids = $_POST['pacycler_delete'];
		if ($ids) {
			foreach($ids as $id) {
				PA_BannerCycler_CyclerFactory::deleteSlide($id);
			}	
			
			$_SESSION['PACYCLER_MESSAGE'] = 'Selected slides have been deleted.';
			return;
			//wp_safe_redirect(PACYCLER_OPTIONSPAGEURL . '&view=slide&deleted=1&pacycler_id=' . $_GET['pacycler_id']);
			//exit();
		}
	}
	
	// save slide
	if ($_POST['pacycler_saveslide']) {
		$slide = new PA_BannerCycler_Slide;
		if (isset($_GET['pacycler_slideid'])) {
			$slide = PA_BannerCycler_CyclerFactory::getSlide($_GET['pacycler_slideid']);
		}
		$slide->cycler_id = intval($_GET['pacycler_id']);
		for($i=0; $i<PACYCLER_NUMTEXTFIELDS; $i++) {
			if (isset($_POST['pacycler_text' . ($i+1)])) {
				$slide->{'text' . ($i+1)} = stripslashes($_POST['pacycler_text' . ($i+1)]);
			}
		}
		for($i=0; $i<PACYCLER_NUMIMAGEFIELDS; $i++) {
			if (isset($_FILES['pacycler_image' . ($i+1)])) {
				if ($_FILES['pacycler_image' . ($i+1)]['name'] != '') {
					$file = $_FILES['pacycler_image' . ($i+1)];
					$slide->{'image' . ($i+1)} = $file['name'];
					$save_path = PACYCLER_IMAGEUPLOADPATH . $file['name'];
					move_uploaded_file($file['tmp_name'], $save_path);
				}
			}
		}
		$slide->display_order = $_POST['pacycler_slide_order'];
		
		PA_BannerCycler_CyclerFactory::saveSlide($slide);
		$_SESSION['PACYCLER_MESSAGE'] = 'Slide has been saved.';
		return;
		//wp_safe_redirect(PACYCLER_OPTIONSPAGEURL . '&view=slide&saved=1&pacycler_id=' . $_GET['pacycler_id']);
		//exit();
	}
}

/**
 * Handle administrative page display
 */
function pabc_admin_routing() {
	$view = (isset($_GET['view']) ? $_GET['view'] : '');		
	switch($view) {
		case 'cycler':
			pabc_admin_cycler();
			break;
		case 'slide':
		default:
			pabc_admin_slides();
			break;
	}
}

/********************************************
 * ACTIONS
 ********************************************/
add_action('admin_init', 'pabc_admin_init');
add_action('admin_menu', 'pabc_admin_menu');