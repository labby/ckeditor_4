<?php

/**
 *	@module			ckeditor
 *	@version		see info.php of this module
 *	@authors		Dietrich Roland Pehlke, erpe
 *	@copyright		2012-2018 Dietrich Roland Pehlke, erpe
 *	@license		GNU General Public License
 *	@license terms	see info.php of this module
 *
 */
// include class.secure.php to protect this file and the whole CMS!
if (defined('LEPTON_PATH')) {	
	include(LEPTON_PATH.'/framework/class.secure.php'); 
} else {
	$root = "../";
	$level = 1;
	while (($level < 10) && (!file_exists($root.'/framework/class.secure.php'))) {
		$root .= "../";
		$level += 1;
	}
	if (file_exists($root.'/framework/class.secure.php')) { 
		include($root.'/framework/class.secure.php'); 
	} else {
		trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
	}
}
// end include class.secure.php

/**
 *	WYSIWYG-Admin
 */
 
require_once( dirname(__FILE__)."/class.editorinfo.php");

$ck_info = new editorinfo_CKEDITOR();
$ck_info->wysiwyg_admin_init( $database );

?>