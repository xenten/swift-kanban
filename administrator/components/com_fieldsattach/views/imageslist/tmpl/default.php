<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_media
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;
?>
<?php 
/*
CHANGE FOR CJamesRun ***
if (count($this->images) > 0 || count($this->folders) > 0) { 
*/
?>
<?php if (count($this->images) > 0 || count($this->folders) > 0 || count($this->docs) > 0) { ?>
<div class="manager">

		<?php for ($i=0, $n=count($this->folders); $i<$n; $i++) :
			$this->setFolder($i);
			echo $this->loadTemplate('folder');
		endfor; ?>

		<?php for ($i=0, $n=count($this->images); $i<$n; $i++) :
			$this->setImage($i);
			echo $this->loadTemplate('image');
		endfor; ?>
                <?php for ($i=0, $n=count($this->docs); $i<$n; $i++) :
			$this->setDocument($i);
			echo $this->loadTemplate('doc');
                        //echo '<br>'.$this->docs[i].title;
		endfor; ?>

</div>
<?php } else { ?>
	<div id="media-noimages">
		<p><?php echo JText::_('COM_MEDIA_NO_IMAGES_FOUND'); ?></p>
	</div>
<?php } ?>
