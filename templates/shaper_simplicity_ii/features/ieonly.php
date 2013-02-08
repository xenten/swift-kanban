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
?>

<?php
	if ($this->isIE(7) || $this->isIE(8)) $this->API->addStylesheet($this->themeUrl.'/css/iecss3.css.php?url='. JURI::base().'templates/'.$this->API->template);
	if ($this->isIE(7)) $this->addCSS('IE7_only.css');
	if (($this->getDirection() == 'rtl') && $this->isIE(7)) $this->addCSS('IE7_rtl.css');
?>