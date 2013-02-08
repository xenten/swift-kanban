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
//no direct accees
defined ('_JEXEC') or die ('resticted aceess');
?>

<?php if($this->getParam('validator')) { ?>
	<?php echo JText::_('Valid') ?> <a target="_blank" href="http://validator.w3.org/check/referer">XHTML</a> <?php echo JText::_('and') ?> <a target="_blank" href="http://jigsaw.w3.org/css-validator/check/referer?profile=css3">CSS</a>
<?php } ?>