<?php
/**
 * @file
 * This file houses any functions meant for display with this plugin. 
 * Of course you could just call the other functions directly if needed, but it could have 
 * adverse effects (anxiety, stress, sleep deprevation).
 */
 
/**
 * Display or return text for a cycler based on admin settings and slides added.
 * @param	string|integer		$cycler_identifier	Name or ID of cycler to display.
 *
 * If $cycler is an integer it will retrieve the cycler by ID, otherwise it will retrieve by name (string).
 * Example:
 * pabc_display('My Cycler');		// display cycler by name
 * pabc_display(3);					// display cycler by ID
 */
function pabc_display($cycler_identifier) {
	if (is_integer($cycler_identifier)) {
		$cycler = PA_BannerCycler_CyclerFactory::getCycler($cycler_identifier);	
	} else {
		$cycler = PA_BannerCycler_CyclerFactory::getCyclerByName($cycler_identifier);
	}
	$slides = PA_BannerCycler_CyclerFactory::getSlides($cycler->cycler_id);
	
	// jquery call
	$template_path = get_bloginfo('stylesheet_directory');
	
	$jquery_call = $cycler->jquery_call;
	$jquery_call = preg_replace('/%templatepath%/i', $template_path, $jquery_call);
	$jquery_call = preg_replace('/%imagespath%/i', PACYCLER_IMAGEUPLOADURL, $jquery_call);
	
	// Begin output
	$output = '<!--BEGIN Banner Cycler Output -->';
	$output .=  '<script type="text/javascript">
	                var $ = jQuery;
					jQuery(function() {
						' . $jquery_call . '
					});
				 </script>';
	
	// Get HTML for slides
	$item_template = $cycler->item_template;
	
	$slides_html = '';
	if ($slides) {
		foreach($slides as $slide) {
			$slide_html = $item_template;
			$slide_html = preg_replace('/%text1%/i', htmlspecialchars($slide->text1), $slide_html);
			$slide_html = preg_replace('/%text2%/i', htmlspecialchars($slide->text2), $slide_html);
			$slide_html = preg_replace('/%text3%/i', htmlspecialchars($slide->text3), $slide_html);
			$slide_html = preg_replace('/%text4%/i', htmlspecialchars($slide->text4), $slide_html);
			$slide_html = preg_replace('/%image1%/i', PACYCLER_IMAGEUPLOADURL . $slide->image1, $slide_html);
			$slide_html = preg_replace('/%image2%/i', PACYCLER_IMAGEUPLOADURL . $slide->image2, $slide_html);
			$slide_html = preg_replace('/%image3%/i', PACYCLER_IMAGEUPLOADURL . $slide->image3, $slide_html);
			$slide_html = preg_replace('/%templatepath%/i', $template_path, $slide_html);
			$slide_html = preg_replace('/%imagespath%/i', PACYCLER_IMAGEUPLOADURL, $slide_html);
			$slides_html .= $slide_html;
		}
	}
	
	// Insert slides into HTML template
	$html_template = $cycler->html_template;
	$html_template = preg_replace('/%templatepath%/i', $template_path, $html_template);
	$html_template = preg_replace('/%imagespath%/i', PACYCLER_IMAGEUPLOADURL, $html_template);
			
	$output .= preg_replace('/%slides%/i', $slides_html, $html_template);
	
	$output .= '<!--END Banner Cycler Output-->';
	
	// output content and evaluate php code in template
	pabc_safe_eval("?>$output<?php ");
}

/**
 * WordPress shortcode handler for displaying cycler in post content.
 * Function added by "Colin Ligertwood" <colin@brainbits.ca> 
 * 
 * Usage:
 *      [bannercycler cycler="Cycler Name"]
 *
 * @param	array		$atts		Shortcode attributes
 * 
 */
function pabc_shortcode($atts){
    extract(shortcode_atts(array(
            'cycler' => '',
    ), $atts));

    if (strlen($cycler)){
   		pabc_display($cycler);
    }
}

add_shortcode('bannercycler', 'pabc_shortcode');
