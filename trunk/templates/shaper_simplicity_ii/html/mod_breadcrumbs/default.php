<?php
/**
 * @version		$Id: default.php 20338 2011-01-18 08:44:38Z infograf768 $
 * @package		Joomla.Site
 * @subpackage	mod_breadcrumbs
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>
<span class="breadcrumbs">
	<?php if ($params->get('showHere', 1))
	{
		echo '<span class="showhome">' .JText::_('MOD_BREADCRUMBS_HERE').'</span>';
	}
	?>
	<?php for ($i = 0; $i < $count; $i ++) :

		$name = $list[$i]->name;
		
		if ($i < $count -1) {
			if(!empty($list[$i]->link)) {
				echo '<a href="'.$list[$i]->link.'">'.$name.'</a>';
			} else {
				echo '<span class="separator">'.$name.'</span>';
			}
		} else {
			echo '<span class="current">'.$name.'</span>';
		}
	endfor; ?>
</span>
