<?php
/**
 * JFUploader 2.15.x Freeware - for Joomla 1.6.x
 *
 * Copyright (c) 2004-2011 TinyWebGallery
 * written by Michael Dempfle
 *
 * @license GNU / GPL 
 *  
 * For the latest version please go to http://jfu.tinywebgallery.com
**/

defined( '_JEXEC' ) or die( 'Restricted access' );

class HTML_joomla_flash_uploader {
	function showFlash($row, $realfolder, $use_js_include, $jfu_config, $plugin=false) {		
	  global $prefix_path, $tfu_enable_resize, $tfu_insert_resize;
    	
    $language = &JFactory::getLanguage();
    $Cfg_lang = $language->getTag();
		$sess_id = session_id();
		$base_dir = $prefix_path.'components/com_jfuploader/tfu';
		$relative_dir = parse_url(JURI::base());
		$relative_dir = $relative_dir['path'];
          $relative_dir = rtrim($relative_dir,"\\/.") . '/'; // we replace to get a consistent output with different php versions!

		$width=$row->display_width;
		$height=floor($width*(340/650));
		if ($height > 390) $height = floor($height * 0.95);
		// $height=375;
		$extra_settings = '';
		$extra_settings_js = '';
        if ($row->description_mode == "true") {
          $extra_settings .= '&amp;tfu_description_mode=true';
          $extra_settings_js .= 'flashvars.tfu_description_mode="true";';
        }
        if ($row->big_server_view == "true") {
          $extra_settings .= '&amp;big_server_view=true';
          $extra_settings_js .= 'flashvars.big_server_view="true";';
        }
        if ($row->switch_sides == "true") {
          $extra_settings .= '&amp;switch_sides=true';
          $extra_settings_js .= 'flashvars.switch_sides="true";';
        }
        if ($row->hide_remote_view == "true") {
          $extra_settings .= '&amp;hide_remote_view=true';
          $extra_settings_js .= 'flashvars.hide_remote_view="true";';
        }
        if ($prefix_path == '') { // we tell the flash that tfu is in the frontend directory!
          $extra_settings .= '&amp;loc=FE';
          $extra_settings_js .= 'flashvars.loc="FE";';
        }
        if  ($jfu_config['idn_url'] != '') {
           $extra_settings .= '&amp;enable_absolut_path=true';
           $extra_settings_js .= 'flashvars.enable_absolut_path = "true";';
        }
        
        $swf_text_settings_js = '';
        if ($row->swf_text) { // we bring the parameters into the right js format.
          $elements = split("&",$row->swf_text);
          foreach ($elements as $element) {
            $extra_settings_js .= "flashvars." . str_replace("=", "=\"", $element) . "\";";
          }
        }
        
		$output = '';
		
		if ($row->enable_setting=="false") { // no flash only text!
		  if ($plugin) {
		    return JFULanguage::getLanguage($row->text_top_lang,$row->text_top, "TEXT_TOP" , $row->id);
		  } else {
		    echo   JFULanguage::getLanguage($row->text_top_lang,$row->text_top, "TEXT_TOP" , $row->id);
            return;
          }
		}
		
		$jConfig = new JConfig();
		// we can only have a keepalive if Joomla and JFU use the same session!
		if ($jConfig->session_handler != 'database') {
		  echo JHTML::_('behavior.keepalive');
		}
		// now I overwrite the existing function.
    echo '
    <script type="text/javascript">
    function debugError(errorString) { }
       
    function getIDN() {
       return "'.$jfu_config['idn_url'].'/";
    }
    
    function uploadRunning(val) {
       acivateKeep = (val == "true");
       if (oldKeepAlive != null) { 
         oldKeepAlive(); 
       }
    }
    var oldKeepAlive = window.keepAlive;
    var acivateKeep = false;
    window.keepAlive = function() {
      if (acivateKeep && (oldKeepAlive != null)) { 
        oldKeepAlive();   
      }
    }
    </script>';	
		
		if (!file_exists($realfolder) && $realfolder != "") {
		  $output .= "Configuration Error! The destination folder does not exist.";
		  $output .= $realfolder; 
		}
		
		if (!$plugin) {
		$output .= "<h2>";
		$output .= JFULanguage::getLanguage($row->text_title_lang,$row->text_title, "TEXT_TITLE" ,$row->id);
		$output .= "</h2>";
		$output .= JFULanguage::getLanguage($row->text_top_lang,$row->text_top, "TEXT_TOP" , $row->id);
		$output .= "<p>";
		}
        $lang = JFULanguage::mapLangJoomlatoTFU($Cfg_lang);
		
		// object tag is used for noscript and if JS include is disabled in the config.
		$objecttag = '
          <object name="mymovie" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="'.$width.'" height="'.$height.'"  align="middle">
            <param name="allowScriptAccess" value="sameDomain" />
            <param name="movie" value="'.$relative_dir.$prefix_path.'components/com_jfuploader/tfu/tfu_215.swf?joomla=frontend&amp;session_id='.$sess_id.'&amp;lang='.$lang.'&amp;base='.$base_dir.'&amp;relative_dir='.$relative_dir.$extra_settings.'&amp;'.$row->swf_text.'" />
            <param name="quality" value="high" /><param name="bgcolor" value="#ffffff" />';
             if ($width == '650') {
                $objecttag .= '<param name="scale" value="noScale" />';
             }
            $objecttag .= '<param name="allowFullScreen" value="true" />
            <embed src="'.$relative_dir.$prefix_path.'components/com_jfuploader/tfu/tfu_215.swf?joomla=frontend&amp;session_id='.$sess_id.'&amp;lang='.$lang.'&amp;base='.$base_dir.'&amp;relative_dir='.$relative_dir.$extra_settings.'&amp;'.$row->swf_text.'" name="mymovie" quality="high" bgcolor="#ffffff" width="'.$width.'" height="'.$height.'" align="middle" '. (($width == '650') ? 'scale="noScale" ' : '') . ' allowfullscreen="true" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
            </object> ';
		
		
		if ($use_js_include) { // include with Javascript
            $output .= '
			<div style="height:'.$height.'px;"> 
               <!-- include with javascript - best solution because otherwise you get the "klick to activate border in IE" -->
			<script type="text/javascript" src="'.$relative_dir.$prefix_path.'components/com_jfuploader/tfu/swfobject.js"></script>
			<script type="text/javascript">
			   document.write(\'<div style="height:'.$height.'px;" id="flashcontent"><div class="noflash">TWG Flash Uploader requires at least Flash 8.<br>Please update your browser.<\/div><\/div>\');
                var flashvars = {};
                var params = {};    
                var attributes = { id: "flash_tfu", name: "flash_tfu" };	
                flashvars.joomla="frontend";
                flashvars.session_id="'. $sess_id .'";	
                flashvars.lang="'.$lang.'";
                flashvars.base="'.$base_dir.'";
                flashvars.relative_dir="'.$relative_dir.'";
                params.allowfullscreen = "true";		   
                ';
                $output .=  $extra_settings_js;
                $output .=  $swf_text_settings_js;
                if ($row->fix_overlay == "true") {
                  $output .= 'params.wmode ="transparent";
                  '; 
                }   
                if ($width == '650') {
                  $output .= 'params.scale = "noScale";
                  ';
                }
                $output .= '  swfobject.embedSWF("'.$relative_dir.$prefix_path.'components/com_jfuploader/tfu/tfu_215.swf", "flashcontent", "'.$width.'", "'.$height.'", "8.0.0", "", flashvars, params, attributes);
                ';		   
                $output .= '
			</script>
			<!-- end include with Javascript -->
			<!-- static html include -->
			<noscript>
			Please enable Javascript.
			</noscript>
			</div>
        ';
        } else { // include with object tag
          $output .= $objecttag;     
        }
        
        if (!$plugin) {
		$output .= "</p>";
		$output .= "<br>" . JFULanguage::getLanguage($row->text_bottom_lang, $row->text_bottom, "TEXT_BOTTOM" ,$row->id);
		}
			
   if ($plugin) {
          return $output;
        } else {
          echo $output;
     }     
   }   
   
   function wrongId($id, $plugin=false) {
     $output = "<center><div class='errordiv'>";
     if ($id == '') {
        $output .= JText::_('ERR_ID_NO');
     } else if ($id == '1') {
        $output .= JText::_('ERR_ADMIN_ID');
     } else if ($id == '-1') {
        $output .= JText::_('ERR_ID_NO_PROFILE');
     } else if ($id == 'ERR_ID_NO_XTD') {
        $output .= JText::_('ERR_ID_NO_XTD');
     } else {
        $output .= JText::_('ERR_ID_WRONG');
     }
     $output .= "</div></center>"; 
     if ($plugin) {
       return $output;
     } else {
       echo $output;
     }
   }	    
   function noUser($id, $plugin=false) {
     $output = "<center><div class='errordiv'>";
     $output .= JText::_('ERR_NO_USER');
     $output .= "</div></center>"; 
     if ($plugin) {
       return $output;
     } else {
       echo $output;
     }
   
   }
   
function showImageSelector($base_path, $type, $jfu_params) {    

$tfu_show_resize = $jfu_params['tfu_show_resize'];
$tfu_enable_resize = $jfu_params['tfu_enable_resize'];
$tfu_insert_resize = $jfu_params['tfu_insert_resize'];
$tfu_show_border = $jfu_params['tfu_show_border'];
$tfu_show_alignment = $jfu_params['tfu_show_alignment'];
$tfu_show_spacing = $jfu_params['tfu_show_spacing'];
$tfu_show_thumbnail_create = $jfu_params['tfu_show_thumbnail_create'];
$tfu_show_help = $jfu_params['tfu_show_help'];
$tfu_show_image_extra = $jfu_params['tfu_show_image_extra'];
$tfu_show_caption = $jfu_params['tfu_show_caption'];
$tfu_show_ruler = $jfu_params['tfu_show_ruler'];
$tfu_show_google_doc = $jfu_params['tfu_show_google_doc'];
$e_name = $jfu_params['e_name'];

if ($tfu_show_resize == 0 || $tfu_enable_resize == 0) {
  $tfu_show_thumbnail_create = 0;
}

if ($tfu_show_alignment == 0) {
  $tfu_show_caption == 0;
}

// check if image magick is activated AND the thumbs directory (site or admin) does exist.
$image_magic_avail = ($_SESSION['TFU']["USE_IMAGE_MAGIC"] == "true");
$thumbdir_avail = file_exists(dirname(__FILE__) . "/tfu/thumbs") ||  file_exists(dirname(__FILE__) . "/../../administrator/components/com_jfuploader/tfu/thumbs");
$pdf_available = ($image_magic_avail && $thumbdir_avail) ? 'true':'false';

$tfu_enable_resize_att = ($tfu_enable_resize == 0) ? ' disabled="disabled" ' : '';

    echo '<script type="text/Javascript">
          base_path = "' . $base_path . '";
          type = "'. $type .'";         
          tfu_insert_resize = "'. $tfu_insert_resize .'";
          tfu_show_ruler = "'. $tfu_show_ruler .'";
          pdf_available = ' . $pdf_available .';
          e_name = "' . $e_name .'";
          caption_error = "' . str_replace("**", "\\n", JText::_('JFU_XTD_ERROR_CAPTION')) . '";
          size_warning = "' . str_replace("**", "\\n",JText::_('JFU_XTD_SIZE_WARNING')) . '";       
          function errorSize() { alert("'.JText::_('JFU_XTD_ERROR_NO_SIZE').'");};
          function errorImage() { alert("'.JText::_('JFU_XTD_ERROR_NO_IMAGE').'");};
         </script>';
         echo '<div class="jfu_image_div_selector">
         <div id="jfu_image_select">
         <div class="jfu_image_div_panel">
         '.JText::_('JFU_XTD_URL').'<br/><input class="jfu_input" style="width:260px;" type="text" name="jfu_url" id="jfu_url"></input>
         <br/>'.JText::_('JFU_XTD_ALT').' <br/>';
         if ($tfu_show_caption) {
             echo '<input onkeyup="enableCaption();" class="jfu_input" style="width:225px;" type="text" name="jfu_alt" id="jfu_alt"></input> <input type="checkbox" name="jfu_as_caption" disabled="disabled" onclick="checkCaption();" id="jfu_as_caption"><label for="jfu_as_caption"><img id="jfu_caption" alt="'.JText::_('JFU_XTD_CAPTION').'" title="'.JText::_('JFU_XTD_CAPTION').'"  class="jfu_caption" src="components/com_jfuploader/images/1x1.gif"></img></label>';
         } else {
             echo '<input class="jfu_input" style="width:260px;" type="text" name="jfu_alt" id="jfu_alt"></input>';              
         }
         if ($tfu_show_image_extra == 1) {
           echo '<br/>'.JText::_('JFU_XTD_IMAGE_EXTRA').'<br/><input class="jfu_input" style="width:260px;" type="text" name="jfu_image_extra" id="jfu_image_extra"></input>';
         }
         echo '</div>';
         if ($tfu_show_resize == 1 || $tfu_show_border == 1) {
         echo '
         <div onmouseover="showRuler()" onmouseout="hideRuler()" style="float:left; font-family:arial;font-size:12px;line-height:1.4em;padding-right:15px;">';
         if ($tfu_show_resize == 1) {
         echo '
         '.JText::_('JFU_XTD_WIDTH').'<br/><input class="jfu_input" '.$tfu_enable_resize_att.' onkeyup="setJfuHeight();" style="width:50px;" type="text" name="jfu_width" id="jfu_width"></input> ';
         if ($tfu_enable_resize == 1) {
           echo '<img id="jfu_lock" onclick="toggleLock()" alt="'.JText::_('JFU_XTD_LOCKRATIO').'" title="'.JText::_('JFU_XTD_LOCKRATIO').'"  class="jfu_lock" src="components/com_jfuploader/images/1x1.gif"></img>';
         }
         echo '<br/>'.JText::_('JFU_XTD_HEIGHT') . '<br/><input class="jfu_input" '.$tfu_enable_resize_att.' onkeyup="setJfuWidth();"  style="width:50px;" type="text" name="jfu_height" id="jfu_height"></input> ';
         if ($tfu_enable_resize == 1) {
            echo '<img id="jfu_reset" alt="'.JText::_('JFU_XTD_RESETSIZE').'" title="'.JText::_('JFU_XTD_RESETSIZE').'" onclick="jfu_reset_size()" class="jfu_reset" src="components/com_jfuploader/images/1x1.gif"></img>';
         }
         echo '<br/>';
         }
         if ($tfu_show_border == 1) {
         echo JText::_('JFU_XTD_BORDER').'<br/><input class="jfu_input" style="width:50px;" type="text" name="jfu_border" id="jfu_border" value="0"></input>';
         }
         echo '</div>';
         }
         if ($tfu_show_alignment == 1 || $tfu_show_spacing == 1) {
             echo '<div onmouseover="showRuler()" onmouseout="hideRuler()" style="float:left; font-family:arial;font-size:12px;line-height:1.4em;">';
             if ($tfu_show_alignment == 1) {
             echo JText::_('JFU_XTD_FLOAT').'<br/><select class="jfu_select" id="jfu_select" style="width:120px;">
                 <option value="">'.JText::_('JFU_XTD_FLOAT_NONE').'</option>
                 <option value="float:left;">'.JText::_('JFU_XTD_FLOAT_LEFT').'</option>
                 <option value="float:left;clear:left;">'.JText::_('JFU_XTD_FLOAT_LEFT2').'</option>
                 <option value="float:right;">'.JText::_('JFU_XTD_FLOAT_RIGHT').'</option>
                 <option value="float:right;clear:right;">'.JText::_('JFU_XTD_FLOAT_RIGHT2').'</option>
                 </select>';
                 echo '<br/>';
             }
             if ($tfu_show_spacing == 1) {
             echo  JText::_('JFU_XTD_HSPACE').'<br/><input maxlength="8" class="jfu_fade jfu_input" onfocus="if (this.value == \''.JText::_('JFU_XTD_SPACE_DEFAULT').'\') {this.value = \'\'; this.className = \'jfu_not_fade\';}" style="width:113px;" type="text" name="jfu_hspace" id="jfu_hspace" value="'.JText::_('JFU_XTD_SPACE_DEFAULT').'"></input>
                   <br/>'.JText::_('JFU_XTD_VSPACE').'<br/><input maxlength="8" class="jfu_fade jfu_input" onfocus="if (this.value == \''.JText::_('JFU_XTD_SPACE_DEFAULT').'\') {this.value = \'\'; this.className = \'jfu_not_fade\';}" style="width:113px;" type="text" name="jfu_vspace" id="jfu_vspace" value="'.JText::_('JFU_XTD_SPACE_DEFAULT').'"></input>';
             }
             echo '</div>';
         }        
         echo '<div class="jfu_image_buttons_right">       
           <input type="button"  class="jfu_input" style="width:120px;" id="jfu_insert_image"  disabled="disabled" onclick="insert();" value="'.JText::_('JFU_XTD_INSERT_IMAGE').'"></input>';  
         if ($tfu_show_thumbnail_create) {
           echo '<br/><input  class="jfu_input" type="button" style="width:120px;margin-top:5px;" id="jfu_create_thumb" disabled="disabled" onclick="createThumbnail();" value="'.JText::_('JFU_XTD_CREATE_THUMB').'"></input>';
         }
         echo '<br/><input  class="jfu_input" type="button" style="width:120px;margin-top:5px;" onclick="gotoPage(\'link\');" value="'.JText::_('JFU_XTD_SHOW_LINK_SETTINGS').'"></input>';
         if ($tfu_show_help == 1) {
           echo '<br/><input  class="jfu_input" type="button" style="width:120px;margin-top:5px;" id="jfu_help" onclick="window.open(\'http://www.tinywebgallery.com/de/tfu/web_jfu.php#jfu_xtd\')" value="'.JText::_('JFU_XTD_HELP').'"></input>';
         }
         echo '
           </div> 
         </div>
                 
         <div style="display:none;" id="jfu_link_select">';         
         echo '<div class="jfu_image_div_panel">     
         '.JText::_('JFU_XTD_LINK').'<br/><input class="jfu_input" style="width:320px;" type="text" name="jfu_link" id="jfu_link"></input>
         <br/><span id="link_extra_1">'.JText::_('JFU_XTD_LINK_EXTRA_1').'</span><span id="link_extra_2">'.JText::_('JFU_XTD_LINK_EXTRA_2').'</span>
         <br/><input class="jfu_input" style="width:320px;" type="text" name="jfu_link_extra" id="jfu_link_extra"></input>';
          echo '<div style="clear:both;"></div>';
  echo '<div id="jfu_image_div_panel" class="jfu_image_div_panel_bottom">
               '.JText::_('JFU_XTD_LINK_EXTRA_T1').' <a href="#" onclick="return setLightbox();">Lightbox</a>, <a href="#" onclick="return setLytebox();">Lytebox</a>, <a href="#" onclick="return setHighslide();">Highslide</a>. '.JText::_('JFU_XTD_LINK_EXTRA_T2').'</div>';
         echo '<div id="jfu_image_div_panel_2" style="display:none;" class="jfu_image_div_panel_bottom">
               '.JText::_('JFU_XTD_INSERT_LINK_DEFAULT').'</div>';      
         echo '</div>';
         echo '<div class="jfu_image_div_panel" style="padding-right: 0px;">
         '.JText::_('JFU_XTD_TARGET').'<br/><select name="jfu_target" id="jfu_target" class="jfu_select" style="width:150px;">
              <option value="">'.JText::_('JFU_XTD_FLOAT_NONE').'</option>
              <option value="_blank">'.JText::_('JFU_XTD_TARGET_BLANK').'</option>
              <option value="_top">'.JText::_('JFU_XTD_TARGET_TOP').'</option>
              <option value="_self">'.JText::_('JFU_XTD_TARGET_SELF').'</option>
              <option value="_parent">'.JText::_('JFU_XTD_TARGET_PARENT').'</option>
         </select>
         <br>&nbsp;
         <br><input class="middle" disabled="disabled" type="checkbox" name="jfu_google_doc" id="jfu_google_doc"> <label class="middle" for="jfu_google_doc">'.JText::_('JFU_XTD_GOOGLE_TEXT').'</label><img  id="jfu_info" alt="'.JText::_('JFU_XTD_GOOGLE_INFO').'" title="'.JText::_('JFU_XTD_GOOGLE_INFO').'"  class="jfu_info middle" src="components/com_jfuploader/images/1x1.gif"></img>
         </div>'; 
         
         echo '<div class="jfu_image_buttons_right">
         <input type="button"  class="jfu_input" style="width:120px;" id="jfu_insert_link" onclick="insert();" disabled="disabled" value="'.JText::_('JFU_XTD_INSERT_IMAGE').'"></input>
         <br/><input type="button"  class="jfu_input" style="width:120px;margin-top:5px;" id="jfu_insert_link_button" onclick="insertLink();" disabled="disabled" value="'.JText::_('JFU_XTD_INSERT_LINK_BUTTON').'"></input>
         <br/><input type="button"  class="jfu_input" style="width:120px;margin-top:5px;" onclick="gotoPage(\'image\');" value="'.JText::_('JFU_XTD_SHOW_IMAGE_SETTINGS').'"></input>';
         if ($tfu_show_help == 1) {
         echo '
            <br/><input  class="jfu_input" type="button" style="width:120px;margin-top:5px;" id="jfu_help" onclick="window.open(\'http://www.tinywebgallery.com/en/tfu/web_jfu.php#jfu_xtd\')" value="'.JText::_('JFU_XTD_HELP').'"></input>';
         }
             echo '</div>';
         echo '</div>';

         echo '</div>';
         echo '<div style="clear:both;"></div>';

  if ($tfu_show_ruler) {
         $rulersize = 650;
         echo '
         <div style="display:none;width:628px;" id="jfu_ruler_div">
         <table width="'.$rulersize.'" class="jfu_rulertable" cellspacing="0" cellpadding="0">
         <tr class="jfu_ruler_tr">
         <td class="jfu_ruler_noborder" width="25">&nbsp;</td>';   
          for ($i=25; $i<$rulersize; $i += 25) {     
           echo '<td class="jfu_ruler_noborder" width="25"><span class="jfu_rulerlines">&nbsp;</span></td>';
          }
          echo ' 
            </tr>
            <tr class="jfu_ruler_noborder" style="line-height:12px;">'; 
          for ($i=0; $i<$rulersize; $i += 50) {  
          echo '<td class="jfu_ruler_noborder" colspan="2"><div align="center">'.$i.'</div></td>';
          }
          echo '    
       </tr>
     </table>
   </div>';                         
     }
   }
}
?>
