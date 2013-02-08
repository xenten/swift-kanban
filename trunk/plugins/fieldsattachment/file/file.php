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
 

 
class plgfieldsattachment_file extends JPlugin
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
            $name = "file";
            if(empty($this->params)){
                    $plugin = JPluginHelper::getPlugin('fieldsattachment', $name);
                    $this->params = new JParameter($plugin->params); 
                }
            $this->params->set( "name" , $name  );
            //$this->params->set( "path" , '..'.DS.'images'.DS.'documents'  );

           /* $sitepath = JURI::base() ;
            $pos = strrpos($sitepath, "administrator");
            if(!empty($pos)){$sitepath  = JURI::base().'..'.DS;}*/

            $sitepath  =  fieldsattachHelper::getabsoluteURL();

            $this->params->set( "path" , $sitepath .'images'.DS.'documents' );
           // $this->params->set( "sitepath" , $sitepath );

            $this->params->set( "documentpath" , JPATH_INSTALLATION.DS.'..'.DS.'images'.DS.'documents'    ); 

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
	}
	  
        function getName()
        {  
                return $this->params->get( 'name', "" );
        }

         



        function renderInput($articleid, $fieldsid, $value, $extras = null )
        {
            
            $directory = "";
            if ( JFactory::getApplication()->isAdmin()) {
                $directory = "";
            }
            
            $required="";
            
             global $sitepath; 
            JLoader::register('fieldattach',  $sitepath.DS.'components/com_fieldsattach/helpers/fieldattach.php');
           
            
           
            $boolrequired = fieldattach::isRequired($fieldsid);
            if($boolrequired) $required="required";
            
            
            $tmp = explode('|',  $value);
            $url = $tmp[0]; 
            if(count($tmp)>1) $title = $tmp[1];
            else $title = $url;
            
            
            
            $selectable="";
             if(!empty($extras))
            {
                $tmp = $extras;
                $lineas = explode(chr(13),  $tmp);
                if(count($lineas)>0) $selectable = $lineas[0]; 
             }
             
             //Mirar si existe fichero
            $file = $url;
            $path = $this->params->get( "path" );
            $documentpath = $this->params->get( "documentpath" ); 
            
            
            if(empty($path))
            {
                    /*$sitepath = JURI::base() ;
                    $pos = strrpos($sitepath, "administrator");
                    if(!empty($pos)){$sitepath  = JURI::base().'..'.DS;}*/

                    $sitepath  =  fieldsattachHelper::getabsoluteURL();

                    $this->params->set( "path" , $sitepath .'images'.DS.'documents' );
                    $documentpath=  JPATH_INSTALLATION.DS.'..'.DS.'images'.DS.'documents';

            }

            $mainframe = JFactory::getApplication();
            if ($mainframe->isAdmin()) {

            }

            if ((JRequest::getVar('option')=='com_categories' && JRequest::getVar('layout')=="edit" && JRequest::getVar('extension')=="com_content"  ))
            {
                    $documentpath = str_replace ("documents", "documentscategories", $documentpath);
            }
            $str = '';
            
            //Add CSS ***********************
            $str .=  '<link rel="stylesheet" href="'.JURI::root() .'plugins/fieldsattachment/file/file.css" type="text/css" />'; 
            $app = JFactory::getApplication();
            $templateDir = JURI::base() . 'templates/' . $app->getTemplate();
            $css =  JPATH_SITE ."/administrator/templates/". $app->getTemplate(). "/html/com_fieldsattach/css/file.css";
            $pathcss= JURI::root()."administrator/templates/". $app->getTemplate()."/html/com_fieldsattach/css/file.css"; 
            if(file_exists($css)){ $str .=  '<link rel="stylesheet" href="'.$pathcss.'" type="text/css" />'; } 

            $str .=  '<div class="filefield">'  ;
            $str .= '<div class="file" style="overflow:hidden;">';
            $str .= '<label>'.JText::_("TITLE").'</label>';
            $str .= '<div class="input"> <input  name="field_'.$fieldsid.'_title" id="field_'.$fieldsid.'_title" type="text" size="50"  value="'.$title.'" class="customfields" /></div>';
            $str .= '</div>';
            $str .= '<div class="file" style="overflow:hidden;">';
            $str .= '<label>'.JText::_("FILE").'</label>';
            $file_absolute ="";
            if($selectable=="selectable")
            {
                $file_url  =  fieldsattachHelper::getabsoluteURL().$file;

            }else{
                $tmpfile = explode("/",$file);
                $file = $tmpfile[count($tmpfile)-1];
                $file_absolute = $documentpath.DS. $articleid .DS.  $file; 
                    
                $file_absolute = str_replace("installation/../", "", $file_absolute);
            } 
            
            
             
            
            $str .= '<div  class="file" style="overflow:hidden;">';
            if($selectable=="selectable")
            {

                $str .= '<span><input name="field_'.$fieldsid.'_value" id="field_'.$fieldsid.'_value" type="text"   value="'.$url.'" size="50"  class="'.$required.'" /></span>';

                $str .= '<div class="button2-left">  
                        <div class="blank">
                                <a class="modal modal-button" title="Select File" href="'.$directory.'index.php?option=com_fieldsattach&amp;view=images&amp;tmpl=component&amp;asset=140&amp;author=&amp;fieldid=field_'.$fieldsid.'_value&amp;folder=" rel="{handler: \'iframe\', size: {x: 800, y: 500}}">
                                        Select File</a>
                        </div>
                        </div>  
                        <script> function jInsertFieldValue(txt, field){ $(field).value= txt ; mountlink_'.$fieldsid.'();}</script> '; 
               // $str .='</div>';

            }else{
                 
                $str .= '<div><input name="field_'.$fieldsid.'_upload" id="field_'.$fieldsid.'_upload" type="file" size="20" class="customfields"  /></div>';
                $str .= '<div><input name="field_'.$fieldsid.'_value" id="field_'.$fieldsid.'_value" type="hidden"   value="'.$url.'"  /></div> ';
             
                    
            }
            $str .= '</div>';
            $str .= '</div>';
             $str .= '<div  class="file" style="overflow:hidden;">';
            // if (JFile::exists( $file_absolute ) && (!empty($file)))
            if ( (file_exists( $file_absolute )  && (!empty($file)))||($selectable && !empty($file)))
            {
              
                if($selectable=="selectable")
                {
                    $str.= '<label for="field_'.$fieldsid.'_delete1">';
                    $str .= JTEXT::_("Checkbox for delete file");
                    $str .= '</label>';
                    $str .= '<input name="field_'.$fieldsid.'_delete1" type="checkbox" onclick="javascript: $(\'field_'.$fieldsid.'\').value= \'\' ;"   />';
                    $str .= '';

                }else{
                    $str .= '<div style="padding:10px 0 10px 0; font-size:14px;"><a href="'. $path .DS. $articleid  .DS.  $file.'">'.$file.'</a></div>';
          
                    $str.= '<label for="field_'.$fieldsid.'_delete">';
                    $str .= JTEXT::_("Checkbox for delete file");
                    $str .= '</label>';
                    $str .= '<input name="field_'.$fieldsid.'_delete" id="field_'.$fieldsid.'_delete" type="checkbox"   />';
                    $str .= '';
                }
                
            
            }else{
                  ////($value="";
                    
            }
            $str .= '</div>';
            
            $str .= '</div>';
            
            
            $str .= '<input name="field_'.$fieldsid.'" id="field_'.$fieldsid.'" type="hidden"   value="'.$value.'" class="customfields '.$required.'" /> ';
             //<script>function jInsertFieldValue(txt, field){ $(field).value= txt ;}</script>';
             
            $str .= " <script type='text/javascript'>
                function mountlink_".$fieldsid."(){ 
                            var title = $('field_".$fieldsid."_title').value; 
                            var upload = $('field_".$fieldsid."_value').value;
                            
                            var result = upload;
                            if(String(title).length>0 || String(upload).length>0 )
                            {
                                result = upload+'|'+title;
                            }
                            
                            $('field_".$fieldsid."').value= result;
                        }
             window.addEvent('domready', function() { 
                        
                        //Add check evrent
                        $$('#field_".$fieldsid."_title').addEvent('change', function(e){ 
                            
                                 mountlink_".$fieldsid."();
                                 
                        });
                        
                        $$('#field_".$fieldsid."_upload').addEvent('change', function(e){ 
                                  var tmp = $('field_".$fieldsid."_upload').value; 
                                  $('field_".$fieldsid."_value').value = tmp
                                  
                        });
                        
                        $$('#field_".$fieldsid."_value').addEvent('change', function(e){ 
                                 mountlink_".$fieldsid."(); 
                                     
                        });
                        
                        $$('#field_".$fieldsid."_upload').addEvent('change', function(e){ 
                                 mountlink_".$fieldsid."();
                        });
                        
                        
                        
                          
                });</script>";
             
            //$str .= $this->path .DS. $file;
            //$str .= JPATH_SITE .DS."images".DS."documents".DS. $id .DS. $file;

            // $str .= '</table>';
            return  $str;
        }
        
       
        function getoptionConfig($valor)
        {
             $name = $this->params->get( 'name'  );
             $return ='<option value="file" ';
             if("file" == $valor)   $return .= 'selected="selected"';
             $return .= '>'.$name.'</option>';
             return $return ;
        }

        function getHTML($articleid, $fieldsid, $category = false, $write=false)
        {
           global $globalreturn;
           //$str  = fieldattach::getFileDownload($articleid, $fieldsid, $category );
           //GET Extras ***************************
            $fieldsids = $fieldsid;
            $extras = fieldattach::getExtra($fieldsids);
            
            $html="";
            
            if(!empty($extras))
            { 
                if(count($extras)>0) $selectable = $extras[0]; 
            }
             
            //GET Values ***************************
            $valor = fieldattach::getValue( $articleid,  $fieldsids, $category  );
            $title = fieldattach::getName($articleid,  $fieldsids);
            $directorio ="documents";
            
            $tmpfile = explode("|",$valor);
            $file =  $tmpfile[0];
            $titlefile = JText::_("DOWNLOAD");
            if(count($tmpfile)>1){
                $titlefile = $tmpfile[1];
            }

            if($category) {
                   $directorio = 'documentscategories' ;
            }
            
            //Build url link
            if($selectable=="selectable")
            {
                $file_absolute  =  fieldsattachHelper::getabsoluteURL().$file;

            }else{  
                $file_absolute = 'images/'.$directorio.'/'.$articleid.'/'.  $file; 
                 
            } 
            
            
            if(!empty($valor)) {
                $html .= '<div class="download">';
                if($selectable=="selectable"){
                    if(fieldattach::getShowTitle(   $fieldsids  ))  $html .= '<span class="title">'.$title.' </span>';
                    $html .=  '<a href="'.$file_absolute.'"   alt="'.$titlefile.'" class="downloads" target="_blank" />'.$titlefile.'</a>';
                  
                }else{
                    if (JFile::exists( JPATH_SITE .DS."images".DS.$directorio.DS. $articleid .DS. $file)  )
                    {
                        if(fieldattach::getShowTitle(   $fieldsids  ))  $html .= '<span class="title">'.$title.' </span>';
                            $html .=  '<a href="'.$file_absolute.'"   alt="'.$titlefile.'" class="downloads" target="_blank" />'.$titlefile.'</a>';
                    }
                }
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

        function action( $articleid, $fieldsid, $fieldsvalueid)
        {
           $path = $this->params->get( "path" );
           $file = "field_". $fieldsid."_upload";
           $documentpath = $this->params->get( "documentpath");
           if(empty($documentpath))
           {
                /*$sitepath = JURI::base() ;
                $pos = strrpos($sitepath, "administrator");
                if(!empty($pos)){$sitepath  = JURI::base().'..'.DS;}*/

                $sitepath  =  fieldsattachHelper::getabsoluteURL();
                
                $this->params->set( "path" , $sitepath .'images'.DS.'documents' );
                //$documentpath=  JPATH_INSTALLATION.DS.'..'.DS.'images'.DS.'documents';
                $documentpath=  JPATH_BASE.DS.'images'.DS.'documents';
           }
            
           
           
           
           plgfieldsattachment_file::deleteFile($file, $articleid, $fieldsid, $fieldsvalueid, $documentpath);
           plgfieldsattachment_file::uploadFile($file, $articleid, $fieldsid, $fieldsvalueid, $documentpath);
        }
        
        
        
         /**
	 * UPLOAD A FILE	 *
	 * @return	nothing
	 * @since	1.6
	 */
        function uploadFile($file, $articleid, $fieldsid,  $fieldsvalueid,  $path = null)
        {
            if(!empty($_FILES[$file]['tmp_name'])){
                
                
                $SafeFile = $_FILES[$file]['name'];

                $SafeFile = str_replace("#", "No.", $SafeFile);
                $SafeFile = str_replace("$", "Dollar", $SafeFile);
                $SafeFile = str_replace("%", "Percent", $SafeFile);
                $SafeFile = str_replace("^", "", $SafeFile);
                $SafeFile = str_replace("&", "and", $SafeFile);
                $SafeFile = str_replace("*", "", $SafeFile);
                $SafeFile = str_replace("?", "", $SafeFile);
                
                $title= JRequest::getVar("field_".$fieldsid."_title","");

                //Error::raiseWarning( 100, $file. " NAMETMP:".$SafeFile." ID:: ". $articleid. " ->  fieldsid ".$fieldsid ." PATH:".$path  );
                //JError::raiseWarning( 100,   $path .DS. $articleid .DS.  $_FILES[$file]["name"] );

                if(!JFile::upload($_FILES[$file]['tmp_name'] , $path .DS. $articleid .DS.  $_FILES[$file]["name"]))
                {
                    JError::raiseWarning( 100,  JTEXT::_("Uploda file Error")   );
                }else
                {
                    $app = JFactory::getApplication();
                    $app->enqueueMessage( JTEXT::_("Uploda file OK")  );
                    $nombreficherofinal = $_FILES[$file]["name"];



                    /*if (file_exists( $path .DS. $articleid .DS. $nombreficherofinal))
                    {

                        //$nombreficherofinal = $fieldsid."_".$nombreficherofinal;
                        $app->enqueueMessage( JTEXT::_("Name image changed " ). $nombreficherofinal  );
                        //JError::raiseWarning( 100, $_FILES[$file]["name"]. " ". JTEXT::_("already exists. "). " -> Name changed ".$nombreficherofinal   );
                        JFile::move($path .DS. $articleid .DS.$_FILES[$file]["name"], $path .DS. $articleid .DS.$nombreficherofinal);
                    }*/
                    //UPDATE
                    $db	= & JFactory::getDBO();
                    if ((JRequest::getVar('option')=='com_categories' && JRequest::getVar('layout')=="edit"   ))
                    {
                        $query = 'UPDATE  #__fieldsattach_categories_values SET value="'. $nombreficherofinal.'|'.$title.'" WHERE id='.$fieldsvalueid ;
                    }else{
                        $query = 'UPDATE  #__fieldsattach_values SET value="'. $nombreficherofinal.'|'.$title.'" WHERE id='.$fieldsvalueid ;
                    }

                    $db->setQuery($query);
                    $db->query();

                    return $nombreficherofinal;
                }
            }
        }
        
         /**
	 * DELETE A FILE	 *
	 * @return	nothing
	 * @since	1.6
	 */
        function deleteFile($file, $articleid, $fieldsid,  $fieldsvalueid,  $path = null)
        {
            $deletefile = JRequest::getVar("field_". $fieldsid.'_delete');
            $file = JRequest::getVar("field_". $fieldsid);
            
            
            $tmpfile = explode("|",$file);
            $file = $tmpfile[0];

            if($deletefile){

                     //echo $this->path .DS. $file ;
                     $deleted= false;
                     if(empty($selectable)){
                         if(!JFile::delete( $path .DS. $articleid .DS.  $file) )
                         {
                              JError::raiseWarning( 100,  JTEXT::_("Delete file Error")." ".$path   );

                         } else
                         {
                             $deleted = true;
                         }
                     }
                     if((!empty($selectable)||($deleted)))
                        {

                            //UPDATE
                            $db	= & JFactory::getDBO();
                            $query = 'UPDATE  #__fieldsattach_values SET value="" WHERE fieldsid='.$fieldsid. ' AND articleid='.$articleid ;
                            $db->setQuery($query);
                            $db->query();
                            $app = JFactory::getApplication();
                            $app->enqueueMessage( JTEXT::_("Delete image")   );


                        }

                    }
        }
       

}
