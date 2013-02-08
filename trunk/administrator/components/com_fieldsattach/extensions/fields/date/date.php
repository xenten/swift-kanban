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
// require helper file
global $sitepath;
$sitepath = JPATH_BASE ;
$sitepath = str_replace ("administrator", "", $sitepath); 
JLoader::register('fieldattach',  $sitepath.DS.'components/com_fieldsattach/helpers/fieldattach.php');
 
class plgfieldsattachment_date extends JPlugin
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
         
	function construct( )
	{
            $name = "date";
            if(empty($this->params)){
                    $plugin = JPluginHelper::getPlugin('fieldsattachment', $name);
                    $this->params = new JParameter($plugin->params); 
                }
            $this->params->set( "name" , $name  );

             //LOAD LANGUAGE --------------------------------------------------------------
            $lang   =&JFactory::getLanguage();
            $lang->load( 'plg_fieldsattachment_'.$name  );
            $lang = &JFactory::getLanguage(); ;
            $lang_file="plg_fieldsattachment_".$name ;
            $sitepath1 = JPATH_BASE ;
            $sitepath1 = str_replace ("administrator", "", $sitepath1);
            $path = $sitepath1."languages".DS . $lang->getTag() .DS.$lang->getTag().".".$lang_file.".php.ini";
 
            //-----------------------------------------------------------------------------
	}
	  
        function getName()
        {  
                return $this->params->get( 'name', "" );
        }
 


        function renderInput($articleid, $fieldsid, $value, $extras=null )
        {  
            $required="";
            
            global $sitepath; 
            JLoader::register('fieldattach',  $sitepath.DS.'components/com_fieldsattach/helpers/fieldattach.php');
           
            $boolrequired = fieldattach::isRequired($fieldsid);
            if($boolrequired) $required="required";
            
            $extrainfo = fieldattach::getExtra($fieldsid); 
			 
            $format = $extrainfo[0]; 
	    if(empty($format)){$format ="%Y-%m-%d";}
			 
            $value = str_replace ("/", "-", $value);
            //echo "Format:".$format;
            //echo " value:".$value;
            //$date = new JDate( $value , NULL);
            //$format="%Y-%m-%d";
            $valor="";
            
              //Add CSS ***********************
            $str =  '<link rel="stylesheet" href="'.JURI::root() .'plugins/fieldsattachment/date/date.css" type="text/css" />'; 
            $app = JFactory::getApplication();
            $templateDir = JURI::base() . 'templates/' . $app->getTemplate();
            $css =  JPATH_SITE ."/administrator/templates/". $app->getTemplate(). "/html/com_fieldsattach/css/date.css";
            $pathcss= JURI::root()."administrator/templates/". $app->getTemplate()."/html/com_fieldsattach/css/date.css"; 
            if(file_exists($css)){ $str .=  '<link rel="stylesheet" href="'.$pathcss.'" type="text/css" />'; } 

           
            //$str .= "<br> resultado1: ".$tmp;
           // $str =  '<div style="float:left;"><label for="field_'.$fieldsid.'">'.$nombre.'</label><input name="field_'.$fieldsid.'" type="input"  value="'.$valor.'" ';
            //$str .= ' /></div><div style="float:left;"></div>'  ;
             $str .= '<div class="date">';
            $str .= JHTML::_('calendar', $value, 'field_'.$fieldsid , 'field_'.$fieldsid , $format,array('class'=>'customfields inputbox '.$required, 'size'=>'25',  'maxlength'=>'19'));
             $str .= '<script>window.addEvent(\'domready\', function() {Calendar.setup({
				// Id of the input field
				inputField:  "field_'.$fieldsid.'",
				// Format of the input field
				ifFormat: "'.$format.'",
				// Trigger for the calendar (button ID)
				button: "field_'.$fieldsid.'_img",
				// Alignment (defaults to "Bl")
				align: "Tl",
				singleClick: true,
				firstDay: 0
				});}); </script>';
             $str .= '</div>';
             return  $str;
        }

        function getoptionConfig($valor)
        {
             $name = $this->params->get( 'name'  );
             $return ='<option value="date" ';
             if("date" == $valor)   $return .= 'selected="selected"';
             $return .= '>'.$name.'</option>';
             return $return ;
        }

        function getHTML($articleid, $fieldid, $category = false, $write=false)
        {
            global $globalreturn;
            $html="";
            $extrainfo = fieldattach::getExtra($fieldid); 
            $format = $extrainfo[0]; 
	        if(empty($format)){$format ="%Y-%m-%d";}
            
            $valor = fieldattach::getValue($articleid, $fieldid, $category);
	    if(!empty($valor)){
            $html = '<div class="field_'.$fieldid.'" >';
                 if(fieldattach::getShowTitle(   $fieldid  ))  $html .='<span class="title">'.fieldattach::getName($articleid, $fieldid, $category).'<span> ';
          
            
            $valor = str_replace ("/", "-", $valor);
            $date = new JDate( $valor );
            //$str .= '<span class="date">'.$date->toFormat( $format ).'</span>';
              
            $html .= '<span class="date">'.$date->toFormat($format).'</span>';
            $html .= '</div>';
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
