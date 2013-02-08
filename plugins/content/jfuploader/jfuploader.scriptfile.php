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
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Script file of JFUploader plugin
 */
class plgContentJFUploaderInstallerScript
{ 
  function install($parent) { 
     // I activate the plugin
	$db = JFactory::getDbo();
     $tableExtensions = $db->nameQuote("#__extensions");
     $columnElement   = $db->nameQuote("element");
     $columnType      = $db->nameQuote("type");
     $columnEnabled   = $db->nameQuote("enabled");
     
     // Enable plugin
     $db->setQuery("UPDATE $tableExtensions SET $columnEnabled=1 WHERE $columnElement='jfuploader' AND $columnType='plugin'");
     $db->query();
     
     echo '<p>'. JText::_('JFU_PLUGIN_ENABLED') .'</p>';    
  } 
}
?>