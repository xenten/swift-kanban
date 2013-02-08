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
 
class plgfieldsattachment_select extends JPlugin
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
            $name = "select";
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
            //$str .= "<br> resultado1: ".$tmp;
            $lineas = explode(chr(13),  $tmp);
            //$str .= "<br> resultado2: ".$lineas[0];
              //Add CSS ***********************
            $str =  '<link rel="stylesheet" href="'.JURI::root() .'plugins/fieldsattachment/select/select.css" type="text/css" />'; 
            $app = JFactory::getApplication();
            $templateDir = JURI::base() . 'templates/' . $app->getTemplate();
            $css =  JPATH_SITE ."/administrator/templates/". $app->getTemplate(). "/html/com_fieldsattach/css/select.css";
            $pathcss= JURI::root()."administrator/templates/". $app->getTemplate()."/html/com_fieldsattach/css/select.css"; 
            if(file_exists($css)){ $str .=  '<link rel="stylesheet" href="'.$pathcss.'" type="text/css" />'; } 
            
            
            //FIND SELECT ****************
            $cont = 0;
            $default = 0;
            $selected = -1;
            foreach ($lineas as $linea)
            {
                $tmp = explode('|',  $linea);   
                 if(count($tmp)>=2){ 
                    $tmpdefault = -1;
                    if(count($tmp)>=2) $valor = $tmp[1];
                    if(count($tmp)>=3) $tmpdefault = $tmp[2];
                     
                    
                    if($value == $valor){
                        $selected  = $cont; 
                        break;
                    }

                    if($tmpdefault == "true"){$default  = $cont; }

                    $cont++;
                 }
                 
            }
             
            //RENDER SELECT **************** 
            $cont = 0; 
            $option_selected = $default;
            if($selected >= 0) {$option_selected = $selected;}
             
            
            $str  .= '<select name="field_'.$fieldsid.'" class="customfields '.$required.'">';
            foreach ($lineas as $linea)
            {

                $tmp = explode('|',  $linea);
                $title = $tmp[0];
                if(count($tmp)>=2) $valor = $tmp[1];
				else $valor=$title;
                $str .= '<option value="'.$valor.'" ';
                //if($value == $valor) $str .= 'selected="selected"'; 
                if(($option_selected) == $cont)  $str .= 'selected="selected"'; 
                    
                $str .= ' >';
                $str .= $title;
                $str .= '</option>';
                $cont++;
            }
            $str .= '</select>';
            return  $str;
        }

        function getoptionConfig($valor)
        {
             $name = $this->params->get( 'name'  );
             $return ='<option value="select" ';
             if("select" == $valor)   $return .= 'selected="selected"';
             $return .= '>'.$name.'</option>';
             return $return ;
        }

        function getHTML($articleid, $fieldsid, $category = false, $write=false)
        {
            global $globalreturn;
            //$str  = fieldattach::getSelect($articleid, $fieldsid);
            
             $valor = fieldattach::getValue( $articleid,  $fieldsid, $category  );
            
              $title = fieldattach::getName( $articleid,  $fieldsid , $category );
              $html="";
                
              if(!empty($valor))
              {
                  $isNull= plgfieldsattachment_select::isNull( $fieldsid , $valor, $category );
                  //echo "<br />ISNULL:".$isNull."---<br/>";
                  if(!$isNull){
                    $valorselects = fieldattach::getValueSelect( $fieldsid , $valor );

                    $html .= '<div id="cel_'.$fieldsid.'" class=" ">';
                    if(fieldattach::getShowTitle(   $fieldsid  )) $html .= '<span class="title">'.$title.' </span>';
                    $html .= '<span class="value">'.$valorselects.'</span></div>';
                  }else{
                      $html="";
                  }
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
        
        /**
	 * Return the value of selectfield
	 *
	 * @param	$id	 id of article
         *              $fieldsids  id of field
	 *
	 * @return	value to field.
	 * @since	1.6
	 */
	//public function getValueSelect($articleid, $fieldsids, $category = false )
        public function isNull( $fieldsids, $valor,  $category = false )
	
	{
            //$valor = fieldattach::getValue($articleid, $fieldsids, $category );
            $valortmp = explode(",", $valor);
            
	    $db = &JFactory::getDBO(  );

	    $query = 'SELECT  a.extras  FROM #__fieldsattach  as a WHERE a.id = '.$fieldsids;
 
            //echo "<br/>  ".$query."<br/>";
            $db->setQuery( $query );
	    $extras = $db->loadResult();  
            $retorno = false;
            if(!empty($extras)) {
                   
                   $lineas = explode(chr(13),  $extras); 
                     foreach($lineas as $linea){  
                        $linea = explode("|",  $linea);
                        $value = $linea[0];
                        //if(count($linea)>1){$value = $linea[1];} 
                        
                        if($valor == $value)
                        {
                            if(count($linea)==1) {$retorno = true;break;}
                            
                        }
                            //break;
                    }
                }
            
            
           // echo "VALOR: ".count($tmp)."<br/>";
	    return $retorno;
	}
       

}
