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
JLoader::register('fieldsattachHelper',   $sitepath.DS.'administrator/components/com_fieldsattach/helpers/fieldsattach.php');

 
class plgfieldsattachment_vimeo extends JPlugin
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
            $name = "vimeo";
            if(empty($this->params)){
                    $plugin = JPluginHelper::getPlugin('fieldsattachment', $name);
                    $this->params = new JParameter($plugin->params); 
                }
            $this->params->set( "name" , $name  );

            $lang   =&JFactory::getLanguage();
            $lang->load( 'plg_fieldsattachment_vimeo' );
            //JPlugin::loadLanguage( 'plg_fieldsattachment_'.$name );
	}
	  
        function getName()
        {  
                return $this->params->get( 'name', "" );
        }

        function renderHelpConfig(  )
        {
            $return = "" ;
            $sitepath  =  fieldsattachHelper::getabsoluteURL();
            $form = $this->form->getFieldset("percha_vimeo");
            $return .= JHtml::_('sliders.panel', JText::_( "JGLOBAL_FIELDSET_VIMEO_OPTIONS"), "percha_".$this->params->get( 'name', "" ).'-params');
            $return .=   '<fieldset class="panelform" >
			<ul class="adminformlist" style="overflow:hidden;">';
           // foreach ($this->param as $name => $fieldset){
            foreach ($form as $field) {
                $return .=   "<li>".$field->label ." ". $field->input."</li>";
            }
            $return .=  '<div><input type="button" value="'.JText::_("Update Config").'" onclick="controler_percha_vimeo()" /></div>';
            $return .=  '</ul> </fieldset>';
            $return .=  '<script src="'. $sitepath.'plugins/fieldsattachment/vimeo/controler.js" type="text/javascript"></script> ';
                         
            
            return  $return;
        }



        function renderInput($articleid, $fieldsid, $value, $extras = null )
        {
             $required="";
            global $sitepath; 
            JLoader::register('fieldattach',  $sitepath.DS.'components/com_fieldsattach/helpers/fieldattach.php');
            
            
            $boolrequired = fieldattach::isRequired($fieldsid);
            if($boolrequired) $required="required";
            
             //Add CSS ***********************
            $str =  '<link rel="stylesheet" href="'.JURI::root() .'plugins/fieldsattachment/vimeo/vimeo.css" type="text/css" />'; 
            $app = JFactory::getApplication();
            $templateDir = JURI::base() . 'templates/' . $app->getTemplate();
            $css =  JPATH_SITE ."/administrator/templates/". $app->getTemplate(). "/html/com_fieldsattach/css/vimeo.css";
            $pathcss= JURI::root()."administrator/templates/". $app->getTemplate()."/html/com_fieldsattach/css/vimeo.css"; 
            if(file_exists($css)){ $str .=  '<link rel="stylesheet" href="'.$pathcss.'" type="text/css" />'; } 

             $str  .= '<div class="vimeo"><div class="file">';
             $str .= '<span>'.JText::_("CODE").'</span>';
             $str  .= '<input  name="field_'.$fieldsid.'" type="text" size="150" value="'.$value.'" class="customfields '.$required.'" />';
             $str  .= '</div>';
             $str  .= '<iframe src="http://player.vimeo.com/video/'.$value    .'" frameborder="0"></iframe>';
             $str .= '</div>';
             return $str  ;
        }

        function getoptionConfig($valor)
        {
             $name = $this->params->get( 'name'  );
             $return ='<option value="vimeo" ';
             if("vimeo" == $valor)   $return .= 'selected="selected"';
             $return .= '>'.$name.'</option>';
             return $return ;
        }

        function getHTML($articleid, $fieldsid, $category = false, $write=false)
        {
            global $globalreturn;
            //$str  =  fieldattach::getVimeoVideo($articleid, $fieldsid, $category );
            
            $extrainfo = fieldattach::getExtra($fieldsids);
            $width="300";
            $height="300";

            if((count($extrainfo) >= 1)&&(!empty($extrainfo[0]))) $width= $extrainfo[0];
            if((count($extrainfo) >= 2)&&(!empty($extrainfo[1]))) $height= $extrainfo[1];

            $code = fieldattach::getValue(  $articleid, $fieldsid, $category);
            if(!empty($code)){
                $html  = '<div id="cel_'.$fieldsid.'" class="vimeo">';
                $html .= '<iframe src="http://player.vimeo.com/video/'.$code.'" width="'.$width.'" height="'.$height.'" frameborder="0"></iframe>';
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
