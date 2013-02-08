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

if ($this->countModules ( 'right or right1 or right2 or right-mid or right3 or right4 or right-bottom' )) {
	define('_RIGHT', 1);
	
	?>
	<div id="sp-rightcol">
		<div class="sp-inner clearfix">
			<?php 
				if($mods= $this->getModules('right')) {
					$this->renderModules($mods,'sp_xhtml');					
				} 

				if($mods= $this->getModules('right1,right2')) {
					$this->renderModules($mods,'sp_xhtml');					
				}

				if($mods= $this->getModules('right-mid')) {
					$this->renderModules($mods,'sp_xhtml');					
				}	

				if($mods= $this->getModules('right3,right4')) {
					$this->renderModules($mods,'sp_xhtml');					
				}
				
				if($mods= $this->getModules('right-bottom')) {
					$this->renderModules($mods,'sp_xhtml');					
				} 			
			?>
		</div>
	</div>
	<?php
}
?>