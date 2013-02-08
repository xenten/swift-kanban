<?php
/**
 * @version		$Id: default_body.php 15 2011-09-02 18:37:15Z cristian $
 * @package		fieldsattach
 * @subpackage		Components
 * @copyright		Copyright (C) 2011 - 2020 Open Source Cristian Grañó, Inc. All rights reserved.
 * @author		Cristian Grañó
 * @link		http://joomlacode.org/gf/project/fieldsattach_1_6/
 * @license		License GNU General Public License version 2 or later
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach($this->items as $i => $item):
        //$ordering	= ($listOrder == 'ordering');
?>
	<tr class="row<?php echo $i % 2; ?>">
		<td>
			<?php echo $item->id; ?>
		</td>
		<td>
			<?php echo JHtml::_('grid.id', $i, $item->id); ?>
		</td>
		<td>
			<a href="<?php echo JRoute::_('index.php?option=com_fieldsattach&task=fieldsattachgroup.edit&id=' . $item->id); ?>">
				<?php echo $item->title; ?>
			</a>
		</td>
                <td>
			 	<?php echo $item->note; ?>
			 
		</td>
                <td align="center">
                        <div ><?php echo '<a href="index.php?option=com_fieldsattach&view=fieldsattachunidades&filter_group_id='.$item->id.'" style="background-color: #555; color:#fff; padding: 4px;">'.JText::_("LIST_OF_FIELDS").'</a>'; ?></div>
		</td>
                <td class="order">                        
                        <input type="text" name="order[]" size="5" value="<?php echo $item->ordering;?>"  disabled class="text-area-order" />
                </td>
                <td class="center">
			<?php echo JHtml::_('jgrid.published', $item->published, $i, 'fieldsattachgroup.', true, 'cb', false, false); ?>
		</td>
	</tr>
<?php endforeach; ?>

