<?php
/**
 * JFUploader 2.15.x Freeware - for Joomla 1.6.x
 *
 * Copyright (c) 2004-2011 TinyWebGallery
 * written by Michael Dempfle
 *
 * @license GNU / GPL 
 *   
 * For the latest version please go to http://jfu.tinywebgallery.com
**/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once( JApplicationHelper::getPath( 'toolbar_html' ) );

if ($task) {
    $act = $task;
}

switch ( $act ) {
	case "edit":
	case "edituser":
	case "newConfig":
		    TOOLBAR_jfuploader::_EDIT();
		break;
	case "config":   
         	  TOOLBAR_jfuploader::_CONFIG();	
		break;
	case "user":
		TOOLBAR_jfuploader::_USER();	
		break;	
	case "upload":
		TOOLBAR_jfuploader::_UPLOAD();	
		break;
		case "plugins":
		TOOLBAR_jfuploader::_PLUGINS();	
		break;
	case "help":
		TOOLBAR_jfuploader::_HELP();	
		break;
	default:
		TOOLBAR_jfuploader::_UPLOAD();
		break;
}
?>