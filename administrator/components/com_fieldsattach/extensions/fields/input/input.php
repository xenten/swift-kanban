<?php
/**
 * @version		$Id: fieldsattachement.php 15 2011-09-02 18:37:15Z cristian $
 * @package		fieldsattach
 * @subpackage		Components
 * @copyright		Copyright (C) 2011 - 2020 Open Source Cristian Grañó, Inc. All rights reserved.
 * @author		Cristian Grañó
 * @link		http://joomlacode.org/gf/project/fieldsattach_1_6/
 * @license		License GNU General Public License version 2 or later
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );
jimport( 'joomla.html.parameter' );

 // require helper file
global $sitepath;
$sitepath = JPATH_BASE ;
$sitepath = str_replace ("administrator", "", $sitepath); 
JLoader::register('fieldattach',  $sitepath.DS.'components/com_fieldsattach/helpers/fieldattach.php');
 

class plgfieldsattachment_input extends JPlugin
{
        /**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @access	protected
	 * @param	object	$subject The object to observe
	 * @param 	array   $config  An array that holds the plugin configuration
	 * @since	1.0
	 */
        function plgfieldsattachment_input(& $subject, $config)
	{
		parent::__construct($subject, $config);
                 
        }
	function construct( )
	{
            $name = "input";
            
            if(empty($this->params)){
                    $plugin = JPluginHelper::getPlugin('fieldsattachment', $name);
                    $this->params = new JParameter($plugin->params); 
                }

            //LOAD LANGUAGE --------------------------------------------------------------
            $lang   =&JFactory::getLanguage();
            $lang->load( 'plg_fieldsattachment_'.$name  );
            $lang = &JFactory::getLanguage(); ;
            $lang_file="plg_fieldsattachment_".$name ;
            $sitepath1 = JPATH_BASE ;
            $sitepath1 = str_replace ("administrator", "", $sitepath1);
            $path = $sitepath1."languages".DS . $lang->getTag() .DS.$lang->getTag().".".$lang_file.".php.ini";
            
            if(JFile::exists($path)){
               JPlugin::loadLanguage( 'plg_fieldsattachment_'.$name );
            }
            //-----------------------------------------------------------------------------
            //JPlugin::loadLanguage( 'plg_fieldsattachment_input' );
            
            $this->params->set( "name" , $name  );
	}
	  
        function getName()
        {  
                return  $this->params->get( 'name'  ) ;
        }

        function renderHelpConfig(  )
        { 
            $return = "" ;
            $form = $this->form->getFieldset("percha_input");
            $return .= JHtml::_('sliders.panel', JText::_( "JGLOBAL_FIELDSET_INPUT_OPTIONS"), "percha_".$this->params->get( 'name', "" ).'-params');
            $return .=   '<fieldset class="panelform" >
			<ul class="adminformlist" style="overflow:hidden;">';
           // foreach ($this->param as $name => $fieldset){
            foreach ($form as $field) {
                $return .=   "<li>".$field->label ." ". $field->input."</li>";
            }
             $return .='</ul> ';
            if(count($form)>0){
            $return .=  '<div><input type="button" value="'.JText::_("Update Config").'" onclick="controler_percha_input()" /></div>';
            }
            $return .=  ' </fieldset>';
            //$return .=  '<script src="'. JURI::base().'../plugins/fieldsattachment/input/controler.js" type="text/javascript"></script> ';
            
            
            return  $return;
        }



        function renderInput($articleid, $fieldsid, $value , $extras = null)
        {
            
            $required="";
            
            global $sitepath; 
            JLoader::register('fieldattach',  $sitepath.DS.'components/com_fieldsattach/helpers/fieldattach.php');
            
            $boolrequired = fieldattach::isRequired($fieldsid);
            
            
            
            if($boolrequired) $required="required";
            
            $maxlenght="";
            $size=30;
            
             //Add CSS ***********************
            $str =  '<link rel="stylesheet" href="'.JURI::root() .'plugins/fieldsattachment/input/input.css" type="text/css" />'; 
            $app = JFactory::getApplication();
            $templateDir = JURI::base() . 'templates/' . $app->getTemplate();
            $css =  JPATH_SITE ."/administrator/templates/". $app->getTemplate(). "/html/com_fieldsattach/css/input.css";
            $pathcss= JURI::root()."administrator/templates/". $app->getTemplate()."/html/com_fieldsattach/css/input.css"; 
            if(file_exists($css)){ $str .=  '<link rel="stylesheet" href="'.$pathcss.'" type="text/css" />'; } 

            
            
            if(!empty($extras))
            {
                //$lineas = explode('":"',  $field->params);
                //$tmp = substr($lineas[1], 0, strlen($lineas[1])-2);
                $tmp = $extras;
                $lineas = explode(chr(13),  $tmp); 
               
                foreach ($lineas as $linea)
                {
                    $tmp = explode('|',  $linea);
                    if(!empty( $tmp[0])) $size = $tmp[0];
                    if(count($tmp)>=1) if(!empty( $tmp[1])) $maxlenght = $tmp[1];
                     
                    
                }
            }
            
            $value = str_replace ('"', '&quot;', $value); 
            
            $str .= '<div class="file"><input  name="field_'.$fieldsid.'" id="field_'.$fieldsid.'" type="text"  value="'.$value.'" class="customfields '.$required.'" size="'.$size.'" maxlength="'.$maxlenght.'" /></div> ';
            
            //$str_file =  dirname(__FILE__).'/tmpl/default.tpl.php';
            //$str .= file_get_contents ($str_file);
            
            
            
            eval('$this_string = \''.$str.'\';');
           
            return  $this_string ;
            //return  '<div style="overflow:hidden;"><input  name="field_'.$fieldsid.'" id="field_'.$fieldsid.'" type="text" size="150" value="'.$value.'" /></div>';
        }

        function getoptionConfig($valor)
        {
             $name = $this->params->get( 'name'  );
             $return ='<option value="input" ';
             if("input" == $valor)   $return .= 'selected="selected"';
             $return .= '>'.$name.'</option>';
             return $return ;
        }

        function getHTML($articleid, $fieldid, $category = false, $write=false)
        { 
            global $globalreturn;
          //$str = fieldattach::getInput($articleid, $fieldid, $category); 
          $html ='';
          $valor = fieldattach::getValue( $articleid,  $fieldid , $category   );
          $title = fieldattach::getName( $articleid,  $fieldid , $category  );

          if(!empty($valor))
          {
              $html .= '<div id="cel_'.$fieldid.'" class=" ">';
              if(fieldattach::getShowTitle(   $fieldid  ))  $html .= '<span class="title">'.$title.' </span>';
              $html .= '<span class="value">'.$valor.'</span></div>';
          }
            
           //WRITE THE RESULT
           if($write)
           {
                echo $html;
           }else{
                $globalreturn = $html;
                return $html; 
           }
        }

        function action()
        {

        }
       

}
