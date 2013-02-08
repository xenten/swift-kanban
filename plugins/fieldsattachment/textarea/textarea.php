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
$sitepath = JPATH_BASE ;
$sitepath = str_replace ("administrator", "", $sitepath); 
JLoader::register('fieldattach',  $sitepath.DS.'components/com_fieldsattach/helpers/fieldattach.php');
JLoader::register('fieldsattachHelper',   $sitepath.DS.'administrator/components/com_fieldsattach/helpers/fieldsattach.php');
 
 
class plgfieldsattachment_textarea extends JPlugin
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
        function plgfieldsattachment_textarea(& $subject, $config)
	{
		parent::__construct($subject, $config);
        }
	function construct( )
	{
            $name = "textarea";
            if(empty($this->params)){
                    $plugin = JPluginHelper::getPlugin('fieldsattachment', $name);
                    $this->params = new JParameter($plugin->params); 
                }
            $this->params->set( "name" , $name  );

            //Load the Plugin language file out of the administration
            $lang = & JFactory::getLanguage();
            $lang->load('plg_fieldsattachment_'.$this->params->get( "name" ), JPATH_ADMINISTRATOR);
            
	}
	  
        function getName()
        {  
                return $this->params->get( 'name', "" );
        } 


        function renderInput($articleid, $fieldsid, $value, $extras=null )
        {
            $required="";
            $boolrequired = fieldattach::isRequired($fieldsid);
            if($boolrequired) $required="required";
            
            $tmp = $extras;
            $lineas = explode(chr(13),  $tmp);
            $height = 300;
            $str=""; 
            
            //Add CSS ***********************
            $str =  '<link rel="stylesheet" href="'.JURI::root() .'plugins/fieldsattachment/textarea/textarea.css" type="text/css" />'; 
            $app = JFactory::getApplication();
            $templateDir = JURI::base() . 'templates/' . $app->getTemplate();
            $css =  JPATH_SITE ."/administrator/templates/". $app->getTemplate(). "/html/com_fieldsattach/css/textarea.css";
            $pathcss= JURI::root()."administrator/templates/". $app->getTemplate()."/html/com_fieldsattach/css/textarea.css"; 
            if(file_exists($css)){ $str .=  '<link rel="stylesheet" href="'.$pathcss.'" type="text/css" />'; } 


            if($lineas[0] == "RichText")
            {
                $editor =& JFactory::getEditor();
                $str .=  $editor->display('field_'.$fieldsid.'', $value , '100%', ''.$height.'', '60', '20', true);

            }else{
                 $str .= '<textarea style="width:100%; height:'.$height.'px;" name="field_'.$fieldsid.'" >'.$value.'</textarea>';

            }
            
            //$str .= '<script>window.addEvent("load", function() { $("field_'.$fieldsid.'").addClass("'.$required.'"); } );</script>';
            return  $str;
        }

        function getoptionConfig($valor)
        {
             $name = $this->params->get( 'name'  );
             $return ='<option value="textarea" ';
             if("textarea" == $valor)   $return .= 'selected="selected"';
             $return .= '>'.$name.'</option>';
             return $return ;
        }

        function getHTML($articleid, $fieldsid, $category = false, $write=false )
        {
            global $globalreturn;
            $html =   fieldattach::getInput($articleid, $fieldsid, $category);
             
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
