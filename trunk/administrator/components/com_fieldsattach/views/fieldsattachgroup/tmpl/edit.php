<?php
/**
 * @version		$Id: edit.php 15 2011-09-02 18:37:15Z cristian $
 * @package		fieldsattach
 * @subpackage		Components
 * @copyright		Copyright (C) 2011 - 2020 Open Source Cristian Grañó, Inc. All rights reserved.
 * @author		Cristian Grañó
 * @link		http://joomlacode.org/gf/project/fieldsattach_1_6/
 * @license		License GNU General Public License version 2 or later
 */
// No direct access
defined('_JEXEC') or die('Restricted access');
$dir = dirname(__FILE__);
JLoader::register('fieldsattachHelper',   $dir.'administrator/components/com_fieldsattach/helpers/fieldsattach.php');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
$params = $this->form->getFieldsets('params');
 

$articlesid = explode(",",$this->item->articlesid);
 

$str ='
    //FUNCTION AD LI =========================================
    function init_obj(){
    ';
if($articlesid)
{
    foreach($articlesid as $articleid)
    {
        //$str .='alert("'.getTitle($articleid).'");';
        $str .='var title = "'.fieldsattachHelper::getTitle($articleid).'" ;';
        if(!empty($articleid)) $str .= 'obj.AddId(  '.$articleid.', title);';
    }
}

$str .='
     //alert("init '.$articlesid.'");
     var myArray = String(document.id("jform_articlesid").value).split(\',\');
}';

$document = JFactory::getDocument();  
$document->addScriptDeclaration($str)


?> 
<form action="<?php echo JRoute::_('index.php?option=com_fieldsattach&task=save&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="fieldsattach-form" class="form-validate">
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_fieldsattach_fieldsattach_DETAILS' ); ?></legend>
			<ul class="adminformlist">
<?php foreach($this->form->getFieldset('details') as $field): ?>
                            <?php if($field->id != "jform_position"){?>
                            <li style="overflow:hidden;"><?php echo $field->label;?>
                            <?php echo $field->input;   ?></li>
                        <?php } ?>
<?php endforeach; ?> 
			</ul>
                </fieldset>
                <fieldset class="adminform" >
                            <legend><?php echo JText::_( 'COM_FIELDSATTACH_FIELDSATTACH_LINKS' ); ?></legend>
                            <ul class="adminformlist">
                                <li>
                                    <?php echo $this->form->getField('relationinformation')->label ;    ?> 
                                </li>
                                <li  style="  padding:  25px  0px  0px  0px;">
                                    <fieldset class="adminform" style="width:90%; float: none; padding:  10px 10px 10px 10px;" >
                                            <legend><?php echo JText::_( 'COM_fieldsattach_fieldsattach_CATEGORY_LINKS' ); ?></legend>
                                            <div style="overflow:hidden;"><?php echo $this->form->getField('catid')->label ;    ?>
                                            <?php echo $this->form->getField('catid')->input ;    ?>
                                            <?php echo $this->form->getField('recursive')->label ;    ?>
                                            <?php echo $this->form->getField('recursive')->input ;    ?>
                                           
                                            </div>
                                            <div style="overflow:hidden; margin: 15px 0 10px 0; padding: 13px 0 0 0; border-top: #ccc 1px solid;">
                                            <?php echo   JText::_('GROUP_SELECT_FOR_DESCRIPTION')  ;    ?>
                                             </div>
                                             <div style="overflow:hidden;">
                                            <?php echo $this->form->getField('group_for')->label ;    ?>
                                            <?php echo $this->form->getField('group_for')->input ;    ?>
                                            </div>
                                    </fieldset>
                                </li>
                                <li style="text-align:center; font-size: 20px;   padding: 20px 0 50px 150px;">
                                    <?php echo $this->form->getField('otro')->label ;    ?>
                                </li>
                                <li>
                                      <fieldset class="adminform" style="width:90%; float: none; padding:  10px 10px 10px 10px;" >
                                            <legend><?php echo JText::_( 'COM_FIELDSATTACH_FIELDSATTACH_ARTICLES_LINKS' ); ?></legend>
                                             <?php echo $this->form->getField('selectarticle')->input ;    ?>
                                            <?php echo $this->form->getField('articlesid')->input ;    ?>
                                            <div style="width:100%; overflow: hidden;">
                                                <ul id="articleslist">
                                                    
                                                </ul>
                                            </div>

                                          
                                    </fieldset> 
                                </li> 

                            </ul>
                    </fieldset>
             
	</div>
       
	<div class="width-40 fltrt">
            <?php foreach($this->form->getFieldset('details') as $field): ?>
            <?php if($field->id == "jform_position"){?>
                           <?php $jform_position_value = $field->value; ?>

                        <?php } ?>
            <?php endforeach; ?>
            <fieldset class="adminform">
                <legend><?php echo JText::_( 'COM_FIELDSATTACH_FIELDSATTACH_POSITION' ); ?></legend>
                     <p><?php  echo  JText::_("COM_FIELDSATTACH_FIELDSATTACH_FIELD_POSITION_INTRO");?></p>
                          <fieldset id="jform_position" class="radio">
                           <div style="overflow:hidden; margin-bottom: 30px;"><img src="components/com_fieldsattach/images/position1.png" alt="" /><br />
                           <input type="radio" id="jform_position0" name="jform[position]" value="1" <?php if($jform_position_value==1) echo "checked"; ?> />
                           <label for="jform_position0"><?php  echo  JText::_("BEFORE_PERMISION");?></label>
                           </div>
                           <div style="overflow:hidden; margin-bottom: 30px;">
                           <img src="components/com_fieldsattach/images/position0.png" alt="" /><br />
                           <input type="radio" id="jform_position1" name="jform[position]" value="0" <?php if($jform_position_value==0) echo "checked"; ?> />
                           <label for="jform_position1"><?php  echo  JText::_("AFTER_METADATA");?></label>
                            </div>
                       </fieldset>

                </fieldset>
 
	</div>

	<div>
		<input type="hidden" name="task" value="fieldsattachunidad.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

