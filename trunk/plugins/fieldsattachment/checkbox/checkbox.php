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
 
class plgfieldsattachment_checkbox extends JPlugin
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
            $name = "checkbox";
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

            if(JFile::exists($path)){
              // JPlugin::loadLanguage( 'plg_fieldsattachment_'.$name );
            }
            //-----------------------------------------------------------------------------
	}
	  
        function getName()
        {  
                return $this->params->get( 'name', "" );
        }
 


        function renderInput($articleid, $fieldsid, $value, $extras=null )
        {  
            global $sitepath; 
            JLoader::register('fieldattach',  $sitepath.DS.'components/com_fieldsattach/helpers/fieldattach.php');
            
            $required="";
            $boolrequired = fieldattach::isRequired($fieldsid);
            if($boolrequired) $required="required";
            
            $tmp = explode("|", $extras);
            $nombre = $tmp[0];
             $valor="";
            if(count($tmp)>1) $valor = $tmp[1];
            //$str .= "<br> resultado1: ".$tmp;
            $str =  '<div style="float:left;"><label for="field_'.$fieldsid.'">'.$nombre.'</label><input id="field_'.$fieldsid.'" name="field_'.$fieldsid.'" type="checkbox" ';
            if($value == $valor) $str .=  ' value="'.$valor.'" ';
            $str .=  ' class="customfields '.$required.'" ';
            if($value == $valor) $str .= ' checked = "checked" '; {}
            $str .= ' /></div><div style="float:left;"></div>'  ;
            
            $str .= " <script type='text/javascript'>
                
            window.addEvent('domready', function() { 
                        var field_".$fieldsid."_value='".$valor."';
                        //Add check evrent
                        $$('#field_".$fieldsid."').addEvent('click', function(e){ 
                                if($(this).get('checked')){ 
                                    $(this).value=field_".$fieldsid."_value;
                                }else{
                                    $(this).value='';
                                }
                        });
                        
                         /*
                        //validate-checkbox
                        document.formvalidator.setHandler('checkbox', function (value) {  
                                 alert(value); 
                                 return false
                                
                        }	);*/
                });</script>";
            return  $str;
        }

        function getoptionConfig($valor)
        {
             $name = $this->params->get( 'name'  );
             $return ='<option value="checkbox" ';
             if("checkbox" == $valor)   $return .= 'selected="selected"';
             $return .= '>'.$name.'</option>';
             return $return ;
        }
 
        
        function getHTML($articleid, $fieldid, $category = false, $write=false)
        {
            global $globalreturn;
            $html =' ';
            $valor = fieldattach::getValue($articleid, $fieldid, $category);
	        if(!empty($valor))    $html = '<div class="field_'.$fieldid.'">'.fieldattach::getName($articleid, $fieldid, $category).'</div>';
            
            
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


