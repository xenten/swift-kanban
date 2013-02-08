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
 
class plgfieldsattachment_link extends JPlugin
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
        function plgfieldsattachment_link(& $subject, $config)
	{
		parent::__construct($subject, $config);
                 
        }
	function construct( )
	{
            $name = "link";
            
            if(empty($this->params)){
                    $plugin = JPluginHelper::getPlugin('fieldsattachment', $name);
                    $this->params = new JParameter($plugin->params); 
                }
            
            $lang   =&JFactory::getLanguage();
            $lang->load( 'plg_fieldsattachment_link' );
            //JPlugin::loadLanguage( 'plg_fieldsattachment_link' );
            
            $this->params->set( "name" , $name  );
	}
	  
        function getName()
        {  
                return  $this->params->get( 'name'  ) ;
        }

        function renderHelpConfig(  )
        { 
            $return = "" ;
            $form = $this->form->getFieldset("percha_link");
            $return .= JHtml::_('sliders.panel', JText::_( "JGLOBAL_FIELDSET_LINK_OPTIONS"), "percha_".$this->params->get( 'name', "" ).'-params');
            $return .=   '<fieldset class="panelform" >
			<ul class="adminformlist" style="overflow:hidden;">';
           // foreach ($this->param as $name => $fieldset){
            foreach ($form as $field) {
                $return .=   "<li>".$field->label ." ". $field->input."</li>";
            }
             $return .='</ul> ';
            if(count($form)>0){
            $return .=  '<div><input type="button" value="'.JText::_("Update Config").'" onclick="controler_percha_link()" /></div>';
            }
            $return .=  ' </fieldset>';
            //$return .=  '<script src="'. JURI::base().'../plugins/fieldsattachment/link/controler.js" type="text/javascript"></script> ';
            
            
            return  $return;
        }



        function renderInput($articleid, $fieldsid, $value )
        {  
            $required="";
            
            global $sitepath; 
            JLoader::register('fieldattach',  $sitepath.DS.'components/com_fieldsattach/helpers/fieldattach.php');
           
            $boolrequired = fieldattach::isRequired($fieldsid);
            if($boolrequired) $required="required";
            
            $tmp = explode('|',  $value);
            $url = $tmp[0]; 
            $target = -1;
            if(count($tmp)>1) $text = $tmp[1]; 
            else $text = $url;
            if(count($tmp)>2) $target = $tmp[2];
            
              //Add CSS ***********************
            $str =  '<link rel="stylesheet" href="'.JURI::root() .'plugins/fieldsattachment/link/link.css" type="text/css" />'; 
            $app = JFactory::getApplication();
            $templateDir = JURI::base() . 'templates/' . $app->getTemplate();
            $css =  JPATH_SITE ."/administrator/templates/". $app->getTemplate(). "/html/com_fieldsattach/css/link.css";
            $pathcss= JURI::root()."administrator/templates/". $app->getTemplate()."/html/com_fieldsattach/css/link.css"; 
            if(file_exists($css)){ $str .=  '<link rel="stylesheet" href="'.$pathcss.'" type="text/css" />'; } 

            
            
            $str .= " <script type='text/javascript'>
                
            window.addEvent('domready', function() { 
                        
                        //Add check evrent
                        $$('#field_".$fieldsid."_url').addEvent('change', function(e){ 
                                 mountlink_".$fieldsid."();
                        });
                        
                        $$('#field_".$fieldsid."_text').addEvent('change', function(e){ 
                                 mountlink_".$fieldsid."();
                        });
                        
                        $$('#field_".$fieldsid."_target').addEvent('change', function(e){ 
                                 mountlink_".$fieldsid."();
                        });
                        
                        function mountlink_".$fieldsid."(){
                            var url =$('field_".$fieldsid."_url').value;
                            var text = $('field_".$fieldsid."_text').value;
                            var target = $('field_".$fieldsid."_target').value;
                            var result = '';
                            if(String(url).length>0 || String(text).length>0 )
                            {
                                result = url+'|'+text+'|'+target;
                            }
                            
                            $('field_".$fieldsid."').value= result;
                        }
                        
                          
                });</script>";
            
            
            $str .= '<input  name="field_'.$fieldsid.'" id="field_'.$fieldsid.'" type="hidden" size="150" value="'.$value.'" class="'.$required.'" />';
            $str .= '<ul class="fieldslink">';
            $str .= '<li><div class="label">'.JText::_("TEXT").'</div> <input  name="field_'.$fieldsid.'_text"  id="field_'.$fieldsid.'_text" type="text" size="150" value="'.$text.'" class="customfields" /></li>';
            $str .= '<li><div class="label">'.JText::_("URL").'</div> <input  name="field_'.$fieldsid.'_url" id="field_'.$fieldsid.'_url" type="text" size="150" value="'.$url.'" class="customfields" /></li>';
            $str .= '<li><div class="label">'.JText::_("OPEN IN").'</div> ';
            $str .= '<select  name="field_'.$fieldsid.'_target" id="field_'.$fieldsid.'_target">';
            $str .= '<option value="-1">'.JText::_("AUTO").'</option>';
            $selected = "";
            if($target == "1") $selected='selected="selected"';
            $str .= '<option value="1" '.$selected.' >'.JText::_("SAME WINDOW").'</option>';
            
            $selected = "";
            if($target == "2") $selected='selected="selected"';
            $str .= '<option value="2" '.$selected.' >'.JText::_("NEW WINDOW").'</option>';
            $str .= '</select>';
            $str .= '</li>';
            
            $str .= '</ul>';
            
            return $str ;
        }
 
        function getoptionConfig($valor)
        {
             $name = $this->params->get( 'name'  );
             $return ='<option value="link" ';
             if("link" == $valor)   $return .= 'selected="selected"';
             $return .= '>'.$name.'</option>';
             return $return ;
        }

        function getHTML($articleid, $fieldid, $category = false, $write=false)
        {
             
             
              global $globalreturn;
              $html ='';
              $valor = fieldattach::getValue( $articleid,  $fieldid  );
              $title = fieldattach::getName( $articleid,  $fieldid  );
              
              $tmp = explode('|',  $valor);
              $url = $tmp[0]; 
              if(count($tmp)>1){
                  if(!empty($tmp[1])) $text = $tmp[1];
                  else $text = $url;
              }
              else $text = $url;
              
              $target = -1;
              if(count($tmp)>2) $target = $tmp[2];
             
              
              
              if(!empty($url))
              {
                    $pos = strrpos($url, "http://");
                    
                    //AUTOOO
                    if ($pos === false) { // note: three equal signs
                       $valorhtml = '<a href="'.$url.'" >';
                    }else{
                        $valorhtml = '<a href="'.$url.'" target="_blank">';
                    }
                    
                    //SAME WINDOW
                    if($target == 1) {$valorhtml = '<a href="'.$url.'" >';}
                    
                    //NEW WINDOW
                    if($target == 2) {$valorhtml = '<a href="'.$url.'" target="_blank">';}

                    $valorhtml .= $text.'</a>';
            
                    $html .= '<div id="cel_'.$fieldid.'" class="link">';
                    if(fieldattach::getShowTitle(   $fieldid  ))  $html .= '<span class="title">'.$title.' </span>';
                    $html .= '<span class="value">'.$valorhtml.'</span></div>';
              }
           
            //WRITE THE RESULT
           if($write)
           {
                echo $html;
           }else{
                 $globalreturn=$html;
                return $html; 
           }
        }

        function action()
        {

        }
       

}
