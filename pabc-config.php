<?php
/**
 * @file
 * Configuration constants and setup. Change at your own risk.
 */
 
define('PACYCLER_VERSION', '1.2');
define('PACYCLER_DEBUG', 0);
define('PACYCLER_PLUGINURL', '/wp-content/plugins/banner-cycler');
define('PACYCLER_PLUGINDIR', dirname(__FILE__) . '/');
define('PACYCLER_CYCLESCRIPT', PACYCLER_PLUGINURL . '/js/jquery.cycle.all.min.js');
define('PACYCLER_IMAGEUPLOADPATH', ABSPATH . 'wp-content/uploads/pacycler/');
define('PACYCLER_IMAGEUPLOADURL', '/wp-content/uploads/pacycler/');
define('PACYCLER_NUMTEXTFIELDS', 4); // don't change this! not implemented yet
define('PACYCLER_NUMIMAGEFIELDS', 3); // don't change this! not implemented yet
define('PACYCLER_OPTIONSPAGEURL', 'options-general.php?page=banner-cycler/admin/admin.php');

/*
 * Database table definitions
 */
global $wpdb;
define('PABC_DB_CYCLERS', $wpdb->prefix . 'pabc_cyclers');
define('PABC_DB_SLIDES', $wpdb->prefix . 'pabc_slides');

if (PACYCLER_DEBUG) {
	$wpdb->show_errors();
}


/******************************************************
 * Misc. Private Functions
 ******************************************************/
/**
 * Whitelisted PHP eval function
 *
 * Thanks to Maurice: http://us.php.net/manual/en/function.eval.php#86884
 */
function pabc_safe_eval($code) {
	$status = 0;
	
    //Language constructs
    //$bl_constructs = array("print","echo","require","include","if","else", "while","for","switch","exit","break");   
	$bl_constructs = array("require","include", "while","for","switch","exit","break");   

    //Functions
    $funcs = get_defined_functions();
    $funcs = array_merge($funcs['internal'],$funcs['user']);

    //Functions allowed       
        //Math cant be evil, can it?
    $whitelist = array("if", "else", "endif","pow","exp","abs","sin","cos","tan");
   
    //Remove whitelist elements
    foreach($whitelist as $f) {
        unset($funcs[array_search($f,$funcs)]);   
    }
    //Append '(' to prevent confusion (e.g. array() and array_fill())
    foreach($funcs as $key => $val) {
        $funcs[$key] = $val."(";
    }
    $blacklist = array_merge($bl_constructs,$funcs);
   
    //Check
    $status=1;
    foreach($blacklist as $nono) {
        if(strpos($code,$nono) !== false) {
            $status = 0;
			die("You have PHP code that is not allowed in the Banner Cycler plugin. You may only use the following functions: " . implode(",", $whitelist) . ".");
            //return 0;
        }
    }

    //Eval
    return @eval($code);
} 