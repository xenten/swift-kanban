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
class JFormFieldLine extends JFormField {
	protected $type = 'Line';
	protected function getInput() {
		$text  	= (string) $this->element['text'];
		return '<div class="line_separator'.(($text != '') ? ' hasText' : '').'" title="'. JText::_($this->element['desc']) .'"><span>' . JText::_($text) . '</span></div>';
	}
}

?>