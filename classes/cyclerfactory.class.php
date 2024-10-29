<?php
/**
 * @file
 * All database interaction is handled here. Pretty self-explanatory.
 */
class PA_BannerCycler_CyclerFactory {
	
	public function deleteCycler($cycler_id) {
		global $wpdb;
		$wpdb->query($wpdb->prepare("DELETE FROM " . PABC_DB_CYCLERS . " WHERE cycler_id=%d", $cycler_id));
	}
	
	public function deleteSlide($slide_id) {
		global $wpdb;
		$wpdb->query($wpdb->prepare("DELETE FROM " . PABC_DB_SLIDES . " WHERE slide_id=%d", $slide_id));
	}
	
	public function getCycler($cycler_id) {
		global $wpdb;
		$cycler = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . PABC_DB_CYCLERS . " WHERE cycler_id=%d", $cycler_id));
		return $cycler;
	}
	
	public function getCyclerByName($cycler_name) {
		global $wpdb;
		$cycler = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . PABC_DB_CYCLERS . " WHERE cycler_name=%s", $cycler_name));
		return $cycler;
	}
	
	public function getCyclers() {
		global $wpdb;
		$cyclers = array();
		$cyclers = $wpdb->get_results("SELECT * FROM " . PABC_DB_CYCLERS);
		return $cyclers;
	}
	
	public function getSlide($slide_id) {
		global $wpdb;
		$slide = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . PABC_DB_SLIDES . " WHERE slide_id=%d", $slide_id));
		return $slide;
	}
	
	public function getSlides($cycler_id) {
		global $wpdb;
		$slides = array();
		$slides = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . PABC_DB_SLIDES . " 
											WHERE cycler_id=%d
											ORDER BY display_order ASC", $cycler_id));
		return $slides;
	}
	
	public function saveCycler($cycler) {
		global $wpdb;
		if ($cycler->cycler_id) {
			// update
			$wpdb->query($wpdb->prepare("UPDATE " . PABC_DB_CYCLERS . " SET cycler_name=%s, jquery_call=%s, 
										html_template=%s, item_template=%s, text1=%s, text2=%s, text3=%s,  text4=%s, image1=%s, image2=%s,
										image3=%s WHERE cycler_id=%d", 
										$cycler->cycler_name,
										$cycler->jquery_call,
										$cycler->html_template,
										$cycler->item_template,
										$cycler->text1,
										$cycler->text2,
										$cycler->text3,
										$cycler->text4,
										$cycler->image1,
										$cycler->image2,
										$cycler->image3,
										$cycler->cycler_id));
		} else {
			// insert
			$wpdb->query($wpdb->prepare("INSERT INTO " . PABC_DB_CYCLERS . " (cycler_name, jquery_call, 
											html_template, item_template, text1, text2, text3, text4, image1, image2, image3) 
											VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", 
											$cycler->cycler_name,
											$cycler->jquery_call,
											$cycler->html_template,
											$cycler->item_template,
											$cycler->text1,
											$cycler->text2,
											$cycler->text3,
											$cycler->text4,
											$cycler->image1,
											$cycler->image2,
											$cycler->image3));
			$cycler->cycler_id = $wpdb->insert_id;
		}
		return $cycler->cycler_id;
	}
	
	public function saveSlide($slide) {
		global $wpdb;
		
		if ($slide->slide_id) {
			// update
			$wpdb->query($wpdb->prepare("UPDATE " . PABC_DB_SLIDES . " SET text1=%s, text2=%s,
										text3=%s, text4=%s, image1=%s, image2=%s,
										image3=%s, display_order=%d WHERE slide_id=%d",
										$slide->text1,
										$slide->text2,
										$slide->text3,
										$slide->text4,
										$slide->image1,
										$slide->image2,
										$slide->image3,
										$slide->display_order,
										$slide->slide_id));
		} else {
			// insert
			$wpdb->query($wpdb->prepare("INSERT INTO " . PABC_DB_SLIDES . " (cycler_id, text1, text2, text3, text4, 
											image1, image2, image3, display_order) 
											VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %d)", 
											$slide->cycler_id, 
											$slide->text1,
											$slide->text2,
											$slide->text3,
											$slide->text4,
											$slide->image1,
											$slide->image2,
											$slide->image3,
											$slide->display_order));
			$slide->slide_id = $wpdb->insert_id;
		}
		return $slide->slide_id;
	}

}