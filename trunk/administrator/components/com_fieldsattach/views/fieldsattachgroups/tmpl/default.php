<?php
/**
 * @version		$Id: default.php 15 2011-09-02 18:37:15Z cristian $
 * @package		fieldsattach
 * @subpackage		Components
 * @copyright		Copyright (C) 2011 - 2020 Open Source Cristian Grañó, Inc. All rights reserved.
 * @author		Cristian Grañó
 * @link		http://joomlacode.org/gf/project/fieldsattach_1_6/
 * @license		License GNU General Public License version 2 or later
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');   

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

$listOrder	= $this->state->get('list.ordering');
$saveOrder	= $listOrder=='ordering';
?>
<form action="<?php echo JRoute::_('index.php?option=com_fieldsattach&view=fieldsattachgroups'); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-select fltrt">
                    <select name="filter_category_id" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_CATEGORY');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('category.options', 'com_content'), 'value', 'text', $this->state->get('filter.category_id'));?>
		   </select>
                    <select name="filter_for" class="inputbox" onchange="this.form.submit()">
				<option value="-1"><?php echo JText::_('JOPTION_SELECT_FOR');?></option>
                                <option value="0" <?php if($this->state->get('filter.for') ==0) echo "selected"?>><?php echo JText::_('JOPTION_SELECT_FOR_ARTICLES');?></option>
                                <option value="1" <?php if($this->state->get('filter.for') ==1) echo "selected"?>><?php echo JText::_('JOPTION_SELECT_FOR_CATEGORY');?></option>

 
		    </select>
                    <select name="filter_language" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_LANGUAGE');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('contentlanguage.existing', true, true), 'value', 'text', $this->state->get('filter.language'));?>
		    </select>
                    <select name="filter_published" class="inputbox" onchange="this.form.submit()">
				<option value="-1"><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
                                <option value="1" <?php if($this->state->get('filter.published') ==1) echo "selected"?>><?php echo JText::_('JPUBLISHED');?></option>
                                <option value="0" <?php if($this->state->get('filter.published') ==0) echo "selected"?>><?php echo JText::_('JUNPUBLISHED');?></option>
        	   </select>
                     
		</div> 
	</fieldset>
     <table class="adminlist">
		<thead><?php echo $this->loadTemplate('head');?></thead>
		<tfoot><?php echo $this->loadTemplate('foot');?></tfoot>
		<tbody><?php echo $this->loadTemplate('body');?></tbody>
	</table>
	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
