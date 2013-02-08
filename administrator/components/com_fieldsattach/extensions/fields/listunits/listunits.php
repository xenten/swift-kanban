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
 
 
class plgfieldsattachment_listunits extends JPlugin
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
            $name = "listunits";
            if(empty($this->params)){
                    $plugin = JPluginHelper::getPlugin('fieldsattachment', $name);
                    $this->params = new JParameter($plugin->params); 
                }
            $this->params->set( "name" , $name  );
	}
	  
        function getName()
        {  
                return $this->params->get( 'name', "" );
        } 

        function renderInput($articleid, $fieldsid, $value, $extras = null )
        { 
              //Add CSS ***********************
            $str =  '<link rel="stylesheet" href="'.JURI::root() .'plugins/fieldsattachment/listunits/listunits.css" type="text/css" />'; 
            $app = JFactory::getApplication();
            $templateDir = JURI::base() . 'templates/' . $app->getTemplate();
            $css =  JPATH_SITE ."/administrator/templates/". $app->getTemplate(). "/html/com_fieldsattach/css/listunits.css";
            $pathcss= JURI::root()."administrator/templates/". $app->getTemplate()."/html/com_fieldsattach/css/listunits.css"; 
            if(file_exists($css)){ $str .=  '<link rel="stylesheet" href="'.$pathcss.'" type="text/css" />'; } 

            //ADD JAVASSCRIPT FUNCTION *** 
            $str  .= '<script src="'.JURI::root().'plugins/fieldsattachment/listunits/listunits.js" type="text/javascript"></script>';
            $str  .= "<script  type='text/javascript'> window.addEvent('domready', function(){ \n ";
            $str  .= " //addRow('".$fieldsid."'); \n   ";
            $str  .= " editRow('".$fieldsid."');\n ";
            $str .= " });</script>    ";
            //$doc =& JFactory::getDocument();
            //$doc->addScriptDeclaration( $str );

            //READ EXTRA INFO ***
            $tmp = $extras;
            $galeria ="";
            //$str .= "<br> resultado1: ".$tmp;
            $lineas = explode(chr(13),  $tmp);
            //$str .= "<br> resultado2: ".$lineas[0];
            foreach ($lineas as $linea)
            {
                $tmp = explode('|',  $linea);
                $contador = 0;
                $num_row = 0;


                $str .=  '<div><table id="table_insert_'.$fieldsid.'"  >
                <tbody id="my_table_insert_'.$fieldsid.'"> ';

               if(count($tmp)>0){
                foreach ($tmp as $obj)
                    {
                     $value1="";
                     $str .= '<tr>';
                     $name  = str_replace (" ", "_", $obj);
                     $str .= '<td>'.$obj.'</td>';
                     $str .= '<td><input id="'.$name.'_'.$fieldsid.'" name="'.$name.'" class="json_'.$fieldsid.'" type="text" size="120" value="'.$value1.'" /></td>';
                     $str .= '</tr>';
                    }
                }


                $contador = 0;

                $str .= '</tbody></table>';
                $str .='<input type="button" id="addRow'.$fieldsid.'" class="field_'.$fieldsid.' addbutton" value="Add Row" /> ';
                $str .= '<table  id="table_result_'.$fieldsid.'" class="listunits table_result_'.$fieldsid.'" cellspacing="0">';
                $str .= '<thead><tr>';
                if(count($tmp)>0){
                foreach ($tmp as $obj)
                    {
                     $value1="";
                      $str .= '<td class="normal" >'.$obj.'</td>';

                     //$str .= '<td><input name="obj_'.$contador.'" type="text" size="40" value="'.$value.'" /></td>';
                     $contador++;
                    }
                    }
                // $str .= '<td><a href="#" id="delete'.$num_row.'" class="delete">'.JText::_("DELETE").'</a></td>';
                $str .= '<td class="action">'.JText::_("EDIT").'</td>';
                $str .= '<td class="action">'.JText::_("DELETE").'</td>';
                
               
                $str .= '</tr></thead>';
                $str .='<tbody id="table_result_body_'.$fieldsid.'" class="my_body_insert field_'.$fieldsid.'">';
                $valor = $value ;
                //echo $valor."<br>";
                $json = explode("},", $valor);



                if(count($json)>0)
                {
                    $i = 0;
                    foreach ($json as $linea )
                    {
                        //$linea =  substr($linea, 0 , strlen($linea)-1);
                        $linea = str_replace("},", "", $linea);
                        $linea = str_replace("}", "", $linea);
                        $linea =   $linea. '}';
                        //echo  $linea;
                       // $jsonobj = json_decode('{"Modelo":"asd","Largo_mts":"sdafsfas","Acción":"dfasdf","Tramos":"","Plegado":"","ø_Base":"","Peso_g":"","Cajas":"","CÓDIGO":""}');
                        $jsonobj = json_decode( $linea );

                        if($i%2) {$color="#eee";} else{$color="#fff";}
                        $str .='<tr id="tr_'.$i.'" class="el_field_'.$fieldsid.'" >';
                        $delete = false;
                        $cont = 0;
                        foreach ($tmp as $obj)
                        {
                             $value="";
                             $name  = str_replace (" ", "_", $obj);
                             if(!empty($jsonobj->{$name})){
                                 $str .= '<td style=" font-size:11px;   padding:7px; color:#333; " class="'.$name.'" id="td_'.$name.'_'.$i.'">';
                                 $str .=  $jsonobj->{$name};
                                 $str .=' </td>';
                                 $delete = true;
                             }else{$str .= '<td></td>';}
                             $cont++;
                        }
                       
                        if($delete) $str .='<td style="   font-size:11px;   padding:7px; color:#333;   "  ><a href="#" class="editrow'.$fieldsid.'" id="editrow_'.$fieldsid.'_'.$i.'" >Edit</a><a href="#" class="updaterow'.$fieldsid.'" id="updaterow_'.$fieldsid.'_'.$i.'" >Update</a></td>';
                        if($delete) $str .='<td style="   font-size:11px;   padding:7px; color:#333;   "><a href="#" class="deleterow deleterow'.$fieldsid.'" id="deleterow_'.$fieldsid.'_'.$i.'" >Delete</a></td>';
                       
                         $i++;
                        //$str .='<td>ss'. count($json);

                        JError::raiseWarning( 100, $obj );


                        $str .='</tr>';

                    }
                }

                $str .='</tbody>';
                $str .= '</table>';
                $str .= '</div>';
                 
            }
            $valor = htmlspecialchars( $valor );
            $str .= '<input name="field_'.$fieldsid.'" id="field_'.$fieldsid.'"  class="alljson" type="hidden" size="150" value="'.$valor.'" />';
 
            return  $str;
        }

        function getoptionConfig($valor)
        {
             $name = $this->params->get( 'name'  );
             $return ='<option value="listunits" ';
             if("listunits" == $valor)   $return .= 'selected="selected"';
             $return .= '>'.$name.'</option>';
             return $return ;
        } 
        
         function getHTML($articleid, $fieldid, $category = false, $write=false)
        {
            global $globalreturn;
            //$str = fieldattach::getListUnits($articleid, $fieldid, $category);
            //return $str;
            $fieldsids = $fieldid;
            
            $html ='<div>';
            $extrainfo = fieldattach::getExtra($fieldsids);
            $title = fieldattach::getName( $articleid,  $fieldsids , $category  );
            if(fieldattach::getShowTitle(   $fieldsids  ))  $html .= '<div class="title">'.$title.' </div>';
            $html .='<table><thead><tr>';
            foreach ($extrainfo as $result )
                {
                     $html .='<th>'.$result.'</th>';
                }
            $html .='</tr></thead>';
            $valor = fieldattach::getValue($articleid, $fieldsids, $category);
            $valor = str_replace("&quot;",  '"', $valor );
            $json = explode("},", $valor);
            $i = 0;
            foreach ($json as $linea )
            {
                //$linea =  substr($linea, 0 , strlen($linea)-1);

                $linea = str_replace("},", "", $linea);
                $linea = str_replace("}", "", $linea);
                $linea =   $linea. '}';

               // $jsonobj = json_decode('{"Modelo":"asd","Largo_mts":"sdafsfas","Acción":"dfasdf","Tramos":"","Plegado":"","ø_Base":"","Peso_g":"","Cajas":"","CÓDIGO":""}');
                $jsonobj = json_decode( $linea );
                $html .='<tr>';
                foreach ($extrainfo as $obj )
                {
                     if(isset( $jsonobj->{$obj})) $html .='<td>'.  $jsonobj->{$obj} .'</td>';
                }
                $html .='</tr>';
            }


            $html .='</table></div>';

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
