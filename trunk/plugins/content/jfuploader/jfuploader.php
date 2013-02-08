<?php
/**
 * JFUploader 2.15.x Freeware - Plugin for Joomla 1.6.x
 *
 * Copyright (c) 2004-2011 TinyWebGallery
 * written by Michael Dempfle
 * 
 * @license GNU / GPL  
 *
 * For the latest version please go to http://jfu.tinywebgallery.com
**/
defined( '_JEXEC' ) or die( 'Restricted access' );
if (!defined('_VALID_TWG')) {
  define('_VALID_TWG', '42');
}

jimport('joomla.plugin.plugin');
jimport( 'joomla.html.parameter' );

class plgContentJFUploader extends JPlugin
{

public function onContentPrepare ($context, &$article, &$params, $limitstart) {
   $this->botJFUploader($article, $params, $limitstart);
}

private function botJFUploader( &$row, &$params, $page=0 ) 
{
  global $prefix_path;
  $mainframe = &JFactory::getApplication(); 

 $regex = '/\{jfuploader.*}/';
 $regexhide = '/<!--\s*\{jfuploader.*}\s*-->/';
 
 $plugin =& JPluginHelper::getPlugin('content', 'jfuploader');
 $pluginParams = new JParameter( $plugin->params );
 
 // check if the plugin has been published 
 if (!$pluginParams->get( 'enabled', 1 )) {
    $row->text = preg_replace( $regex, '', $row->text );
    return true;
 }

 
 if (!file_exists("administrator/components/com_jfuploader/jfuploader.class.php")) {
   $mycode .= "<div style='padding:10px; margin:10px; border: 1px solid #555555;color: #000000;background-color: #f8f8f8; text-align:center; width:360px;'><b>Installation error</b><br>The JFUploader component can not be found. This component is required. Please install JFUploader first before you use this plugin.</div>";
   $row->text = preg_replace ($regex, $mycode, $row->text);
   return;
}
   $skip_error_handling = "true"; // avoids that the jfu logfile is used for everything!
   $debug_file = '';

   @ob_start();   
   if (file_exists('components/com_jfuploader/tfu/tfu_helper.php')) { // frontend!
     require_once('components/com_jfuploader/tfu/tfu_helper.php'); 
   } else {
      require_once("administrator/components/com_jfuploader/tfu/tfu_helper.php");
   }
   @ob_end_clean();   
   
   require_once("components/com_jfuploader/jfuploader.html.php");
   require_once("administrator/components/com_jfuploader/jfuploader.class.php");
  	
  	
	// find all instances of mambot and put in $matches
	preg_match_all( $regex, $row->text, $matches );

	// Number of mambots
 	$count = count( $matches[0] );
 	// only processes if there are any instances of the plugin in the text
   if ( $count ) {
     JFUHelper::printCss();	
     JPlugin::loadLanguage( 'com_jfuploader' );
 	   preg_match ( '/{jfuploader.*id=([\w_-]+).*}/', $row->text, $treffer ); 
 	   $id = $treffer[1];
       preg_match ( '/{jfuploader.*type=([0,1]{1}).*}/', $row->text, $treffer ); 
 	   $selector = $treffer[1];
 	  preg_match ( '/{jfuploader.*securitytoken=([\w_-]+).*}/', $row->text, $treffer ); 
 	   $securitytoken = $treffer[1];
 	   preg_match ( '/{jfuploader.*twgpath=([\/\w_-]+).*}/', $row->text, $treffer ); 
 	    if (isset($treffer[1])) {
           $twgpath = $treffer[1];
         } else {
           $twgpath = '';
         }
 	  
      echo '<!-- JFU type: \'' . $selector . '\' id: \'' .  $id . '\' -->';   

 	  if (isset($selector) && isset($id)) { 	  
 	      if ($selector == "0" && $id == "1") { // admin profile!
            $mycode = HTML_joomla_flash_uploader::wrongId($id,true);
          } else if ($this->securityTokenIsValid($selector, $id, $securitytoken))  {
            $user	= JFactory::getUser();
      		  $old_error = error_reporting(0);
      		  $myId = JFUHelper::getProfileId($selector, $id, $user);
      		  error_reporting($old_error);
              if ($myId >=0) {
        		     $mycode = $this->showFlashPlugin($myId, $twgpath);
        		  } else {
        		     $mycode = HTML_joomla_flash_uploader::wrongId($id, true); 
              }
          } else {
            $mycode = "<div class='errordiv'>". JText::_("ERR_PLUGIN") ."</div>";
          }
    } else { 
      $mycode = "<div class='errordiv'>". JText::_("ERR_PLUGIN") ."</div>";
    }
      // Replace the text
      $row->text = preg_replace ($regexhide, $mycode, $row->text);
      $row->text = preg_replace ($regex, $mycode, $row->text);
    }
    
    // we remove the JFU error handler
    if ($old_error_handler) {
      set_error_handler($old_error_handler);
    } else { // no other error handler set
      set_error_handler('on_error_no_output');
    }
}

function showFlashPlugin($id, $twgpath) {
   global $prefix_path;

     $database = JFactory::getDBO();
	 $row = new joomla_flash_uploader($database);
	 $row->load($id);
	 if (!$row->resize_show) { // no profile found or no id!
	    return HTML_joomla_flash_uploader::wrongId($id, true);
	 } else {
	   $uploadfolder = $row->folder;
	   $uploadfolder_base = $uploadfolder;

	   $user = JFactory::getUser();
        // we check if we have a master profile!
       if ($row->master_profile == 'true') {
	      if ($user->id != 0 || $row->master_profile_mode == 'ip') {
               if ($row->master_profile_mode == 'id') {
                    $_SESSION["s_user"] = $user->id;
                    $uploadfolder = $uploadfolder . '/' . $user->id;
                } else if ($row->master_profile_mode == 'ip') {
                    $uploadfolder = $uploadfolder . '/' . $_SERVER['REMOTE_ADDR'];
                } else if ($row->master_profile_mode == 'group') {
                    $group = JFUHelper::getHighestGroupName($database, $user->groups);
                    
                    if ($row->master_profile_lowercase == 'true') {
                        $normalizeSpaces=true;
                        $group = normalizeFileNames($group);      
                    } 
                     $uploadfolder = $uploadfolder . '/' . $group;
                 } else {
                    if ($row->master_profile_mode == 'login') {
                        $uname = $user->username;
                    } else {
                        $uname = $user->name;
                    }
                    $_SESSION["s_user"] = $uname;
                    if ($row->master_profile_lowercase == 'true') {
                        $normalizeSpaces=true;
                        $uname = normalizeFileNames($uname);
                    }
                    $uploadfolder = $uploadfolder . '/' . $uname;  
                }
              // we check if the folder exists - if not it is created!
              if (!file_exists($uploadfolder) && $uploadfolder != "") {
                 $dir_chmod = JFUHelper::getVariable($database, 'dir_chmod'); 
                 $ftp_enable = $row->ftp_enable;  
                 if (isset($ftp_enable) && $ftp_enable == 'true') {
                        $ftp_host = $row->ftp_host; 
                        $ftp_port = $row->ftp_port; 
                        $ftp_user = $row->ftp_user; 
                        $ftp_pass = $row->ftp_pass; 
                        $ftp_root = $row->ftp_root;                                  
                        $ftp_createdir = $uploadfolder;
                        $conn_id = ftp_connect($ftp_host, $ftp_port); 
                        $login_result = ftp_login($conn_id, $ftp_user, $ftp_pass);                         
                        ftp_chdir($conn_id, $ftp_root); 
                        $result = ftp_mkdir ($conn_id , $ftp_createdir);
                        if ($result && $dir_chmod != 0) {
                          @ftp_chmod($conn_id, $dir_chmod, $ftp_createdir);
                        }
                        ftp_close($conn_id);
                  } else {
                      $result = mkdir($uploadfolder);  
                      if ($result && $dir_chmod != 0) {
                        @chmod($uploadfolder, $dir_chmod);
                      }
                 }
                // if the copy directory exists we copy everything!
                $extra_dir = "components/com_jfuploader/default";
                if (file_exists($extra_dir)) {
                  JFUHelper::dir_copy($extra_dir, $uploadfolder);
                } 
              }
          } else {
              return HTML_joomla_flash_uploader::noUser($id,true);          
          }
       }
       
       if (file_exists("components/com_jfuploader/tfu/tfu_helper.php")) {
         $prefix_path = '';
         $prefix_dir_path = '';
       } else {
         $prefix_path = 'administrator/';
         $prefix_dir_path = '../';
       }      
	     // we go back to the main folder!
       if ($uploadfolder == "") {
         $folder =  './'.$prefix_dir_path.'../../..';
       } else {
         $folder =  './'.$prefix_dir_path.'../../../' . $uploadfolder;
       }
       JFUHelper::setJFUSession($row, $folder, $database); 
       unset($_SESSION["IS_ADMIN"]);
       $_SESSION["IS_FRONTEND"] = "TRUE";
       if ($user->id != 0) {
         $_SESSION["TFU_USER"] = $user->username;
         $_SESSION["TFU_USER_ID"] = $user->id;
         $_SESSION["TFU_USER_NAME"] = $user->name;
         $_SESSION["TFU_USER_EMAIL"] = $user->email;
         JFUHelper::setContactDetailsToSession($user->id);
       } else {
         unset($_SESSION['TFU_USER']);
         unset($_SESSION['TFU_USER_ID']);
         unset($_SESSION['TFU_USER_NAME']);
         unset($_SESSION['TFU_USER_EMAIL']);
         unset($_SESSION['TFU_USER_CONTACT']);
       }
       
        // we check if the flash should be included with js oder the object tag
       $use_js_include = JFUHelper::check_js_include($database);
       $jfu_config['idn_url']= JFUHelper::getVariable($database, 'idn_url');     
             
       store_temp_session();
       JFUHelper::fixSession();
       
       $thumbnailflash = '';
       if ($twgpath != '') {
         $thumbnailflash = $this->getFlashContent($twgpath, $uploadfolder,$use_js_include);
       }
       return  HTML_joomla_flash_uploader::showFlash( $row, $uploadfolder, $use_js_include, $jfu_config, true ) . $thumbnailflash;
	 }	 
}

function getFlashContent($twgpath, $uploadfolder, $use_js_include) {
$basedir = 'pictures/';
// album = alles nach "pictures" of $uploadfolder
$album = substr(strstr($uploadfolder, $basedir),strlen($basedir));
$flash = '<p>';
$flash .= '<script type="text/javascript" src="'.$twgpath.'/js/swfobject.js"></script>';
$flash .= '
<script type="text/javascript">
   document.write(\'<div style="margin-left:120px;" id="flashcontent2"><div class="noflash">The TWG Flash Thumbnail requires at least Flash 8.<br>Please update your browser.</div></div>\');
// TODO: Please change the parameters below to you needs - Important are twg_install_root and twg_album
var twg_install_root = "'.$twgpath.'/";             // (String) The path to your TWG. A / at the end is needed. Can be relative or absolute. If TWF is NOT on the same domain a crossdomain.xml is needed!
var twg_external_album = "'.$album.'";    // (String) The album you want to show. I recommend only to use ansi characters!
var twg_target_url = "";               // (String) Normally the link on the thumbnail flash goes directly to the TWG installation. If you e.g. have TWG included in an iframe you can specify the full url here. The parameters twg_album and twg_show are added to this url with ?. Please parse this values and pass them to the iframe. 
var twg_simple_select="true";          // ("true","false") "true": The image has to be clicked to go to TWG, "false": Even selecting a different image with the scrollbar triggers TWG 
var twg_shadow="on";                   // ("on", "off") Turns the shadow on/off 
var twg_background="FFFFFF";           // (String) Shadow color in hex
var twg_current=3;                     // (Number) Start image
var twg_hide_scrollbar="false";        // ("true","false") Shows/hiddes the scrollbar
var twg_enable_autoscroll="false";     // ("true","false") Enable/disable autoscroll. set twg_simple_select to true if you use this
var twg_border_color="";                // (String) You can have a 1px boarder aroun your images. Define a color to ebable it

// For the big flash (120 px) use strip120.swf, 550 as width, 153 as height! The small flash: strip100.swf, 440 as width, 135 as height.
var f2 = new SWFObject(twg_install_root + "html/strip120.swf?twg_path=" + twg_install_root + "&twg_external_album=" + twg_external_album + "&twg_simple_select="+ twg_simple_select + "&twg_shadow=" + twg_shadow + "&twg_background=" + twg_background + "&twg_current=" + twg_current + "&twg_hide_scrollbar=" + twg_hide_scrollbar + "&twg_enable_autoscroll=" + twg_enable_autoscroll + "&twg_target_url="+twg_target_url+"&twg_border_color=" + twg_border_color , "myMovie", "550", "153", "8");
f2.addParam("wmode","transparent");
f2.addParam("allowScriptAccess","always");
f2.write("flashcontent2");
</script></p>
';
return $flash;
}

/**
 *  This functions checks if the security token belongs to the security token given in the administration 
 * 
 */ 
function securityTokenIsValid($selector, $id, $securitytoken)  {
  $jConfig = new JConfig();
  return md5($selector . $jConfig->secret .'_'. $id) == $securitytoken;
}

}
?>