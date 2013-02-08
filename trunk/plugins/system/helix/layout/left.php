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

if ($this->countModules ( 'left or left1 or left2 or left-mid or left3 or left4 or left-bottom' )) {
	define('_LEFT', 1);
	?>
	<div id="sp-leftcol">
		<div class="sp-inner clearfix">
			<?php 
				if($mods= $this->getModules('left')) {
					$this->renderModules($mods,'sp_xhtml');					
				} 

				if($mods= $this->getModules('left1,left2')) {
					$this->renderModules($mods,'sp_xhtml');					
				}

				if($mods= $this->getModules('left-mid')) {
					$this->renderModules($mods,'sp_xhtml');					
				}	

				if($mods= $this->getModules('left3,left4')) {
					$this->renderModules($mods,'sp_xhtml');					
				}
				
				if($mods= $this->getModules('left-bottom')) {
					$this->renderModules($mods,'sp_xhtml');					
				} 			
			?>
			
		</div>
	</div>
	<?php
}
?>