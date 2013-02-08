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

<?php if ($this->getParam('logo_type')=='image') { ?>
	<a id="logo" style="width:<?php echo $this->getParam('logo_width') ?>px;height:<?php echo $this->getParam('logo_height') ?>px" href="<?php echo $this->baseUrl?>"></a>
<?php } else { ?>
	<div id="logo-text" style="width:<?php echo $this->getParam('logo_width') ?>px;height:<?php echo $this->getParam('logo_height') ?>px">
		<h1><a title="<?php echo $this->getSitename() ?>" href="<?php echo $this->baseUrl ?>"><span><?php echo $this->getParam('logo_text') ?></span></a></h1>
		<p class="site-slogan"><?php echo $this->getParam('logo_slogan') ?></p>
	</div>
<?php } ?>