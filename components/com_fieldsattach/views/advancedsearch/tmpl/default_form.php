<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_search
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
$lang = JFactory::getLanguage();
$upper_limit = $lang->getUpperLimitSearchWord();
?>

<form id="searchForm" action="<?php echo JRoute::_('index.php?option=com_fieldsattach');?>" method="post">

	<fieldset class="word">
		<label for="search-searchword">
			<?php echo JText::_('COM_SEARCH_SEARCH_KEYWORD'); ?>
		</label>
		<input type="text" name="searchword" id="search-searchword" size="30" maxlength="<?php echo $upper_limit; ?>" value="<?php echo $this->escape($this->origkeyword); ?>" class="inputbox" />
		<button name="Search" onclick="this.form.submit()" class="button"><?php echo JText::_('COM_SEARCH_SEARCH');?></button>
		<input type="hidden" name="option" value="com_fieldsattach" />
                <input type="hidden" name="task" value="advancedsearch" />  
                <input type="hidden" name="advancedsearchcategories" value="<?php echo $this->advancedsearchcategories;?>" /> 
                <input type="hidden" name="fields" id="filterfields" value="<?php echo $this->fields;?>" /> 
	
	</fieldset>

	<div  class="searchintro<?php echo $this->params->get('pageclass_sfx'); ?>">
		<?php if (!empty($this->searchword)):?>
		<p><?php echo JText::plural('COM_SEARCH_SEARCH_KEYWORD_N_RESULTS', $this->total);?></p>
		<?php endif;?>
	</div>
        <fieldset id="filterfieldsattach" class="phrases filterfieldsattach">
                <legend><?php echo JText::_('COM_FIELDSATTACH_FILTER');?></legend>
                <?php 
                $arrayoffields = explode("," , $this->fields);
                //echo count($arrayoffields);
                foreach ($arrayoffields as $fieldsid) :
                    $val = explode("_" , $fieldsid);
                     
                    $info = FieldsattachViewAdvancedSearch::getInfo($val[0]);
                    $type = $info->type;
                    $title = $info->title;
                    $valor = "";
                    if(count($val)>0) $valor= $val[1];
                   ?>
                <div class="field"><p><label for="field_<?php $val[0]?>"><?php echo $title;?></lable></p>
                  <?php  if($type == "select" || $type == "selectmultiple" )
                    {
                        //echo " -- select";
                        //NEW
                        //JPluginHelper::importPlugin('fieldsattachment'); // very important
                        echo FieldsattachViewAdvancedSearch::renderSelect( $val[0], $valor);
                 
                    }else{
                        //echo " -- text";
                        ?>
                        <input name="field_<?php echo $val[0];?>" value="<?php echo $valor;?>" onchange="changefilter1()" />
                    <?php
                    }
                ?></div>
                <?php endforeach; ?>    
        </fieldset>

	<fieldset class="phrases">
		<legend><?php echo JText::_('COM_SEARCH_FOR');?>
		</legend>
			<div class="phrases-box">
			<?php echo $this->lists['searchphrase']; ?>
			</div>
			<div class="ordering-box">
			<label for="ordering" class="ordering">
				<?php echo JText::_('COM_SEARCH_ORDERING');?>
			</label>
			<?php echo $this->lists['ordering'];?>
			</div>
	</fieldset>

	<?php 
        /*if ($this->params->get('search_areas', 1)) : ?>
		<fieldset>
		<legend><?php echo JText::_('COM_SEARCH_SEARCH_ONLY');?></legend>
		<?php foreach ($this->searchareas['search'] as $val => $txt) :
			$checked = is_array($this->searchareas['active']) && in_array($val, $this->searchareas['active']) ? 'checked="checked"' : '';
		?>
		<input type="checkbox" name="areas[]" value="<?php echo $val;?>" id="area-<?php echo $val;?>" <?php echo $checked;?> />
			<label for="area-<?php echo $val;?>">
				<?php echo JText::_($txt); ?>
			</label>
		<?php endforeach; ?>
		</fieldset>
	<?php endif; */?>

<?php if ($this->total > 0) : ?>

	 
<p class="counter">
		<?php echo $this->pagination->getPagesCounter(); ?>
	</p>

<?php endif; ?>

</form>
