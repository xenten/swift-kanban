<?php
/*---------------------------------------------------------------
# Package - Joomla Template based on Helix Framework   
# ---------------------------------------------------------------
# Author - JoomShaper http://www.joomshaper.com
# Copyright (C) 2010 - 2011 JoomShaper.com. All Rights Reserved.
# license - PHP files are licensed under  GNU/GPL V2
# license - CSS  - JS - IMAGE files  are Copyrighted material 
# Websites: http://www.joomshaper.com - http://www.joomxpert.com
-----------------------------------------------------------------*/
//no direct accees
defined ('_JEXEC') or die ('resticted aceess');

$docs = JFactory::getDocument();
$helix_path = JPATH_PLUGINS.DS.'system'.DS.'helix'.DS.'core'.DS.'class.helper.php';
if (file_exists($helix_path)) {
    require_once($helix_path);
	$helix = new helixHelper($docs);
}
else {
    echo JText::_('Helix framework not found.');
    die;
}
