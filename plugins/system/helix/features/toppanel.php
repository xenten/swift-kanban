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

<?php if /*--- Module Position panel1 to panel6 ---*/ ($mods= $this->getModules('panel1,panel2,panel3,panel4,panel5,panel6')) { ?>
<!--Start Top Panel-->
<div class="sp-toppanel-wrap clearfix">
	<div id="sp-toppanel" class="clearfix">
		<div class="sp-wrap clearfix">
			<div id="sp-top" class="sp-inner clearfix">
				<?php $this->renderModules($mods,'sp_flat');?>
			</div>
			<div id="toppanel-handler">
				<div class="handler-left">
					<div class="handler-right">
						<div class="handler-mid">
							<?php echo JText::_('TOP_PANEL') ?>
						</div>	
					</div>	
				</div>	
			</div>
		</div>
	</div>
</div>
<?php $this->addCSS('toppanel.css') ?>
<?php $this->addJS('toppanel.js') ?>
<!--End Top Panel-->
<?php } ?>