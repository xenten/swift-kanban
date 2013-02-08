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

<?php
	if ($this->getParam('show_helix_logo')) {
	$helix_url='http://helix.joomshaper.com';
	$helix_title='Helix Framework';
	$helix_logo=$this->getParam('helix_logo');
?>
<div id="powered-by" class="helix-logo helix-logo-<?php echo $helix_logo ?>">
	<a target="_blank" title="<?php echo $helix_title ?>" href="<?php echo $helix_url ?>"><?php echo $helix_title ?></a>
</div> 
<?php } ?>