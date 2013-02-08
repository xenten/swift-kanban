<?php
/*---------------------------------------------------------------
# Package - Helix Framework  
# ---------------------------------------------------------------
# Author - JoomShaper http://www.joomshaper.com
# Copyright (C) 2010 - 2011 JoomShaper.com. All Rights Reserved.
# license - PHP files are licensed under  GNU/GPL V2
# license - CSS  - JS - IMAGE files  are Copyrighted material 
# Websites: http://www.joomshaper.com - http://www.joomxpert.com
-----------------------------------------------------------------*/

defined('JPATH_BASE') or die;
jimport('joomla.form.formfield');

class JFormFieldAsset extends JFormField
{
	protected	$type = 'Asset';
	
	protected function getInput() {
		$doc = & JFactory::getDocument();
		$plg_path = JURI::root().'/plugins/system/helix/elements/';	
		if($this->element['assettype'] == 'js') {
			return $doc->addScript($plg_path . $this->element['filename']);
		} else {
			return $doc->addStyleSheet($plg_path . $this->element['filename']);   
		}	
	}
} 
?>