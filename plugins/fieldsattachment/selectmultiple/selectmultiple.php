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
 
class plgfieldsattachment_selectmultiple extends JPlugin
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
            $name = "selectmultiple";
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

        function renderInput($articleid, $fieldsid, $value, $extras )
        {
            $required="";
              global $sitepath; 
            JLoader::register('fieldattach',  $sitepath.DS.'components/com_fieldsattach/helpers/fieldattach.php');
          
            $boolrequired = fieldattach::isRequired($fieldsid);
            if($boolrequired) $required="required";
            
            $tmp = $extras;
            
            $lineas = explode(chr(13),  $tmp);
            
              //Add CSS ***********************
            $str =  '<link rel="stylesheet" href="'.JURI::root() .'plugins/fieldsattachment/selectmultiple/selectmultiple.css" type="text/css" />'; 
            $app = JFactory::getApplication();
            $templateDir = JURI::base() . 'templates/' . $app->getTemplate();
            $css =  JPATH_SITE ."/administrator/templates/". $app->getTemplate(). "/html/com_fieldsattach/css/selectmultiple.css";
            $pathcss= JURI::root()."administrator/templates/". $app->getTemplate()."/html/com_fieldsattach/css/selectmultiple.css"; 
            if(file_exists($css)){ $str .=  '<link rel="stylesheet" href="'.$pathcss.'" type="text/css" />'; } 

            //$str .= "<br> resultado2: ".;
            $str .= '<select name="field_'.$fieldsid.'[]" multiple="multiple" class="customfields '.$required.'">';
            foreach ($lineas as $linea)
            {
                $arrays = explode(",", $value);
                $tmp = explode('|',  $linea);
                $title = $tmp[0]; 
	        if(count($tmp)>1) $valor = $tmp[1];
                $str .= '<option value="'.$valor.'" ';
               // if (in_array($valor, $arrays)) {
                foreach ($arrays as $obj)
                { 
                    if(!empty($obj)) if(trim($obj) == trim($valor)) { $str .= ' selected="selected"'; break;   }
                }
               // if($value == $valor) {}
                $str .= ' >';
                $str .= $title;
                $str .= '</option>';

            }
            $str .= '</select>';
              
             
            return  $str;
        }

        function getoptionConfig($valor)
        {
             $name = $this->params->get( 'name'  );
             $return ='<option value="selectmultiple" ';
             if("selectmultiple" == $valor)   $return .= 'selected="selected" ' ;
             $return .= '>'.$name.'</option>';
             return $return ;
        }

        function getHTML($articleid, $fieldsid, $category = false, $write=false)
        {
	    $valores = fieldattach::getValue( $articleid,  $fieldsid  );
            $title = fieldattach::getName( $articleid,  $fieldsid  );
	    $extras =	fieldattach::getExtra($fieldsid);
            $db = &JFactory::getDBO(  );
	    $query = 'SELECT a.extras FROM #__fieldsattach as a  WHERE a.id = '.$fieldsid;


            $db->setQuery( $query );
	    $extras  = $db->loadResult();
            
            $str  =  '<div>';
            if(fieldattach::getShowTitle(   $fieldsid  ))  $str .= '<span class="title">'.$title.' </span>';
	    //$str .= "<br> resultado1: ".$extras;
            $lineas = array();
            //echo count($extras);
            $lineas = explode(chr(13),  $extras);
	    //$str .= "<br> resultado2: ".$lineas[0]; 
           // echo $extras."<br />---- ".$extras."-----<br /><br />";
           /* foreach ($lineas as $linea)
	    { 
                echo "<br />---- ".$linea."-----<br /><br />";
            }*/
            
	    foreach ($lineas as $linea)
	    { 
            global $globalreturn;
	        $tmp = explode('|',  $linea);
                $arrays = explode(",", $valores);
	        $title = $tmp[0];
                $valor="";
	        if(count($tmp)>1) $valor = $tmp[1]; 
                
                foreach ($arrays as $obj)
                { 
                    if(!empty($obj)) if(trim($obj) == trim($valor)) { if(!empty($title)) $str .=  $title."<br />";   }
                }  

	    } 
            $str .= ' </div>';
           //WRITE THE RESULT
           if($write)
           {
                echo $str;
           }else{
                $globalreturn = $str;
                return $str; 
           }
        }

	private function getfieldsvaluearray($fieldsid, $articleid, $value)
        {
            $result ="";
            $db	= & JFactory::getDBO();
            $query = 'SELECT a.value FROM #__fieldsattach_values as a WHERE a.fieldsid='. $fieldsid.' AND a.articleid='.$articleid  ;
            //echo "<br>";
            $db->setQuery( $query );
            $elid = $db->loadObject();
            $return ="";
            if(!empty($elid))
            { 
                $tmp = explode(",",$elid->value); 
                foreach($tmp as $obj)
                {
                    $obj = str_replace(" ","",$obj);
                    $value = str_replace(" ","",$value);
                    //echo "<br>".$obj ."==". $value." -> ".strcmp($obj, $value)." (".strlen($obj).")";
                    if(strcmp($obj, $value) == 0)
                    {
                        //echo "SIIIIIIIIIIIIIIIII" ;
                        return true;
                    }
                }
            }
            return false ;
        }

        function action()
        {

        }

	 
       

}
