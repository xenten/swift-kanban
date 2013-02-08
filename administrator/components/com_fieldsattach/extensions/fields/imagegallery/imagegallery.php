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
JLoader::register('fieldattach',  $sitepath.'components/com_fieldsattach/helpers/fieldattach.php'); 
JLoader::register('fieldsattachHelper',   $sitepath.DS.'administrator/components/com_fieldsattach/helpers/fieldsattach.php');
 
class plgfieldsattachment_imagegallery extends JPlugin
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
            $name = "imagegallery"; 
            if(empty($this->params)){
                    
                    $plugin = JPluginHelper::getPlugin('fieldsattachment', $name);
                    $this->params = new JParameter($plugin->params); 
                }
                 
            $this->params->set( "name" , $name  );

	   // $lang   =&JFactory::getLanguage();
           //  $lang->load( 'plg_fieldsattachment_input' );
           //  JPlugin::loadLanguage( 'plg_fieldsattachment_'.$name );
            //LOAD LANGUAGE --------------------------------------------------------------
            $lang   =&JFactory::getLanguage();
            $lang->load( 'plg_fieldsattachment_'.$name  ); 
            //-----------------------------------------------------------------------------
	}
	  
        function getName()
        {  
                return $this->params->get( 'name', "" );
        }
        
        function renderInput($articleid, $fieldsid, $value, $extras=null )
        {
            $directory = "";
            if ( JFactory::getApplication()->isAdmin()) {
                $directory = "";
            }
        
        	$session =& JFactory::getSession();
        	$session->set('articleid',$articleid);
			$session->set('fieldsattachid',$fieldsid);
			
            $sitepath  =  fieldsattachHelper::getabsoluteURL();
            $str_gallery = '<div id="gallery_'.$fieldsid.'" class="galleryfield" style="margin-top:50px;">'.plgfieldsattachment_imagegallery::getGallery1($articleid, $fieldsid).'</div>';
			$str ='';
           /*$str .=  '<div style=" position:relative; width:150px;  overflow: hidden;">
                <div  class="button2-left" >
                <div class="image" >';
            if ((JRequest::getVar('option')=='com_categories' && JRequest::getVar('layout')=="edit"   ))
            {
                $str  .='<a class="modal-button" title="Article" href="'.$sitepath.'/administrator/index.php?option=com_fieldsattach&amp;view=fieldsattachimages&amp;tmpl=component&amp;catid='.$articleid.'&amp;fieldsattachid='.$fieldsid.'&amp;reset=1" onclick="IeCursorFix(); return false;" rel="{handler: \'iframe\', size: {x: 980, y: 500}}">';
             }else{
                $str  .='<a class="modal-button" title="Article" href="'.$sitepath.'/administrator/index.php?option=com_fieldsattach&amp;view=fieldsattachimages&amp;tmpl=component&amp;articleid='.$articleid.'&amp;catid='.$articleid.'&amp;fieldsattachid='.$fieldsid.'&amp;reset=1" onclick="IeCursorFix(); return false;" rel="{handler: \'iframe\', size: {x: 980, y: 500}}">';
            }
             $str  .=JText::_("Gallery administrator");
             $str  .=' 
                    </a>
                    </div>
                    </div>
                    </div>';*/
			/*$str .= '<div style="position:relative; float:right; top:0px;"><a class=\'modal\' rel=\'{handler: "iframe", size: {x: 980, y: 500}}\' href=\'index.php?option=com_fieldsattach&view=fieldsattachimage&layout=edit&tmpl=component&reset=2\'></a>
 			';
            $str .= "<a href='#' onclick='update_gallery".$fieldsid."();return false;'><img src='components/com_fieldsattach/images/icon-refresh.png' alt='refresh' /></a>";
			$str .= "<a class='modal' rel='{handler: \"iframe\", size: {x: 980, y: 500}}' href='index.php?option=com_fieldsattach&view=fieldsattachimage&layout=edit&tmpl=component&reset=2'><img src='components/com_fieldsattach/images/icon-32-new.png' alt='refresh' /></a>";
			$str .= '</div>';*/
                    $str .= $str_gallery;
            
			
                    $str .= "<script type=\"text/javascript\">
                                        
						window.addEvent('domready', function(){
								   
								  update_gallery".$fieldsid."(); 
								   
									 
						}); 
                                               
						
						function update_gallery".$fieldsid."()
						{
                                                        
							 
						  	var url_".$fieldsid." = \"".JURI::base(false)."/index.php?option=com_fieldsattach&view=fieldsattachimagesajax&tmpl=component&catid=".$articleid."&fieldsid=".$fieldsid."\";
						 	 
                                                        var xmlhttp;
                                                        if (window.XMLHttpRequest)
                                                        {// code for IE7+, Firefox, Chrome, Opera, Safari
                                                        xmlhttp=new XMLHttpRequest();
                                                        }
                                                        else
                                                        {// code for IE6, IE5
                                                        xmlhttp=new ActiveXObject(\"Microsoft.XMLHTTP\");
                                                        }
                                                        xmlhttp.onreadystatechange=function()
                                                        {
                                                        if (xmlhttp.readyState==4 && xmlhttp.status==200)
                                                            {
                                                                 document.getElementById(\"gallery_".$fieldsid."\").innerHTML=xmlhttp.responseText;
                                                                 SqueezeBox.initialize({});
                                                                 SqueezeBox.assign($$('#gallery_".$fieldsid." a.modal'), { parse: 'rel'});
                                                      
                                                            }
                                                        }
                                                        xmlhttp.open(\"GET\",  url_".$fieldsid." ,true);
                                                        xmlhttp.send(); 
						}
				</script>";
            return  $str  ;
        }

        function getoptionConfig($valor)
        {
             $name = $this->params->get( 'name'  );
             $return ='<option value="imagegallery" ';
             if("imagegallery" == $valor)   $return .= 'selected="selected"';
             $return .= '>'.$name.'</option>';
             return $return ;
        }

        function getHTML($articleid, $fieldsid, $category = false, $write=false)
        {
            //$str =   fieldattach::getImageGallery($articleid, $fieldsid,$category);
            global $globalreturn;
            $html =  '<ul class="gallery">';
            $db = &JFactory::getDBO(  );
	    $query = 'SELECT  a.* FROM #__fieldsattach_images as a  WHERE a.fieldsattachid = '.$fieldsid.' AND a.articleid= '.$articleid.' ORDER BY a.ordering';
            if($category)
            {
                $query = 'SELECT  a.* FROM #__fieldsattach_images as a  WHERE a.fieldsattachid = '.$fieldsid.' AND a.catid= '.$articleid.' ORDER BY a.ordering';

            }
            $db->setQuery( $query );
	    $result = $db->loadObjectList();
            $firs_link = '';
            $cont = 0;

            $sitepath  =  fieldsattachHelper::getabsoluteURL();

            if(!empty($result)){
                foreach ($result as $obj){
                    //if (JFile::exists( JPATH_SITE .DS."images".DS."documents".DS. $articleid .DS. $result->value)  )
                    $html .=  '<li>' ;
                    if (JFile::exists( JPATH_SITE .DS. $obj->image2)  )
                    {
                        $html .=  '<a href="'.$sitepath.''.$obj->image1.'" id="imgFiche" class="nyroModal" title="'.$obj->title.'" rel="gal_'.$articleid.'">';
                        $html .=  '<img src="'.$sitepath.''.$obj->image2.'"  alt="'.$obj->title.'" />';
                    }else{$html .=  '<img src="'.$sitepath.''.$obj->image1.'"  alt="'.$obj->title.'" />';}

                    if (JFile::exists( JPATH_SITE .DS. $obj->image2)  )
                    {
                        $html .=  '</a>';
                    }
                    $html .=  '</li>';
                    $cont++;
                }
            }
            $html .=  '</ul>';
             
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
        
        function getGallery1($articleid, $fieldsattachid)
        {
            // Load the tooltip behavior. 


        $db = JFactory::getDbo();
        $directory = "administrator/";
        if ( JFactory::getApplication()->isAdmin()) {
             $directory = "";
        }

        $query = $db->getQuery(true);
         // Select some fields
		$query->select('*');

		// From the hello table
		$query->from('#__fieldsattach_images');
        $query->where("articleid = ".$articleid." AND fieldsattachid=".$fieldsattachid);

        $query->order("ordering");
 
               // $db = JFactory::getDbo();

		 $db->setQuery($query);
		 //$str = $query;
		 $rows= $db->loadObjectList();
		 	$str = '<div style="position:relative; float:left; top:-50px;"><a class=\'modal\' rel=\'{handler: "iframe", size: {x: 980, y: 500}}\' href=\''.JURI::base(false).'/'.$directory.'index.php?option=com_fieldsattach&view=fieldsattachimage&layout=edit&tmpl=component&reset=2\'></a>
 			';
            $str .= "<a href='#' onclick='update_gallery".$fieldsattachid."();return false;'><img src='".JURI::base(false)."/".$directory."components/com_fieldsattach/images/icon-refresh.png' alt='refresh' /></a>";
			//$str .= "<a class='modal' rel='{handler: \"iframe\", size: {x: 980, y: 500}}' href='index.php?option=com_fieldsattach&view=fieldsattachimage&layout=edit&tmpl=component&reset=2&fieldsattachid=".$fieldsattachid."'><img src='components/com_fieldsattach/images/icon-32-new.png' alt='refresh' /></a>";
			$str .= '</div>';
         

             $str .= "<ul style='overflow:hidden;'>";
             $sitepath  =  fieldsattachHelper::getabsoluteURL(); 
             if($rows>0){
               foreach ($rows as $row)
                {
                  //$url_edit ='index.php?option=com_fieldsattach&amp;task=fieldsattachimage.edit&amp;id='.$row->id.'&amp;tmpl=component&amp;reset=2&amp;fieldsattachid='.$fieldsattachid.'&amp;direct=true';
				  $url_edit =JURI::base(false).'/index.php?option=com_fieldsattach&view=fieldsattachimage&tmpl=component&layout=edit&id='.$row->id.'&fieldsattachid='.$fieldsattachid.'&reset=2';
				  $url_delete =JURI::base(false).'/index.php?option=com_fieldsattach&amp;view=fieldsattachimages&amp;task=delete&amp;id='.$row->id.'&amp;tmpl=component&amp;fieldsid='.$fieldsattachid;
                  $str.= '<li style="width:150px; height:150px; margin: 0px 10px 10px 0; overflow:hidden; float:left; border:1px solid #ddd;">
                  <div style="overflow:hidden;margin-bottom:8px;"><div style="width:32px;float:left;"> <a class="modal" href="'.$sitepath.''.$row->image1.'"><img src="'.JURI::base(false).$directory.'components/com_fieldsattach/images/icon-zoom.png" alt="zoom" /></a>
                  </div>
                  <div style="width:32px;float:right;"><a class="modal" href="'.$url_delete.'" rel="{handler: \'iframe\', size: {x: 980, y: 500}}"><img src="'.JURI::base(false).$directory.'/components/com_fieldsattach/images/icon-32-delete.png" alt="zoom" /></a>
                  </div></div>
               	  <div><a class="modal"  href="'.$url_edit.'" rel="{handler: \'iframe\', size: {x: 980, y: 500}}" ><img src="'.$sitepath.''.$row->image1.'" alt="'.$row->title.'" width="150" /></a>
                  </div>
                  </li>';

                }
             }
			$str .= "<li style='width:80px; background:url(".JURI::base(false)."/".$directory."components/com_fieldsattach/images/icon-32-new.png) no-repeat 50px 40px; height:10px; margin: 0px 10px 10px 0; overflow:hidden; float:left; border:1px solid #ddd;padding:80px 20px 60px 35px;'>
			<a class='modal' rel='{handler: \"iframe\", size: {x: 980, y: 500}}' href='".JURI::base(false)."index.php?option=com_fieldsattach&view=fieldsattachimage&layout=edit&tmpl=component&reset=2&fieldsattachid=".$fieldsattachid."'>";
			$str .= JText::_("NEW IMAGE").'</a></li>';
		
            $str .= "</ul>";
            
             return $str;
        }
        
       

         

}
