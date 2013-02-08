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
class JFormFieldTitle extends JFormField
{
	protected $type = 'Title';	
	protected function getInput() {
		$html = '<span class="tab-text">' . $this->element['label'] . '</span>';
		return $html;	
	}	
}