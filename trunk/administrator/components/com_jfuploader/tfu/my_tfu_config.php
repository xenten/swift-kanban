<?php
/**
 * JFUploader 2.15.x Freeware - for Joomla 1.0.x and Joomla 1.5.x
 *
 * Copyright (c) 2004-2011 TinyWebGallery
 * written by Michael Dempfle
 * 
 *  This is the config file where sll the JFU stuff from the wrapper is set.
 *  Since 2.8 99% of the TFU addoptions needed to use TFU for JFU are in this file! 
 *  
 *  The commented settings cannot be set by the backend - if you want to set them you 
 *  have to uncomment it an set it    
 * 
 *   Have fun using JFU
 */
/** ensure this file is being included by a parent file */
defined( '_VALID_TWG' ) or die( 'Direct Access to this location is not allowed.' );

/*
    Joomla related settings
*/
set_error_handler('on_error_no_output'); // base dir restrictions can make an output here - we don't want this not matter what!
ob_start();

  // we check if the tfu directory is in the frontend or the backend.
  if(stristr(dirname(__FILE__), 'administrator') === FALSE) { // if in the frontend
    $path_c = '';
    $path_1 = '../';
    $path_2 = '';
  } else {
    $path_c = '../';
    $path_1 = '';
    $path_2 = 'administrator/';
  }

$joomla_config =  dirname(__FILE__) . "/".$path_c."../../../configuration.php";
if (file_exists($joomla_config)) {
  include $joomla_config; 
}
@ob_end_clean();
set_error_handler('on_error');

$goon = true;

if (isset($_SESSION["IS_ADMIN"])) {
  $joomla_path = $path_1 . 'components/com_jfuploader/tfu/';
  $path_fix = "../"; 
} else if (isset($_SESSION["IS_FRONTEND"])) {
  $joomla_path = $path_2 . 'components/com_jfuploader/tfu/';
  $path_fix = "../";
} else {
  tfu_debug("Config call, illegal direct access or missing session settings - your browser has to be closed to get a new session. Please check your session_save_path if you get this error all the time or create the folder session_cache in the tfu folder to activate the session workaround.");
  echo '
  <style type="text/css">
  body { 	font-family : Arial, Helvetica, sans-serif; font-size: 12px; background-color:#ffffff; }
  td { vertical-align: top; font-size: 12px; }
  .install { text-align:center; margin-left: auto;  margin-right: auto;  margin-top: 3em;  margin-bottom: 3em; padding: 10px; border: 1px solid #cccccc;  width: 450px; background: #F1F1F1; }
  </style>';
  echo '<div class="install">';
  echo 'You server is configured properly to access the needed files of JFU.<br>Please go to the Joomla Administration of JFU to see your server limits.';
  echo '</div>';
  // maybe the session is lost - we try to do the workaround if the file was called by a parameter!
  if (strlen($_SERVER['QUERY_STRING']) > 5) {
    checkSessionTempDir();
  }
  die();
  $goon = false;
}

/*
    TFU CONFIGURATION
*/
if ($goon) {
// I set all variables that match excalty with the name!
// string and booleans only - everything else is handled manually.
foreach ($_SESSION['TFU'] as $tfu_key => $tfu_setting ) {
  $tfu_key_low = strtolower($tfu_key);
  
  if (eval ('if (isset($'.$tfu_key_low.')) return true;')) {
     if (eval('if (is_bool($' .$tfu_key_low.')) return true; ')) {
        eval ('$'.$tfu_key_low.'= ($_SESSION[\'TFU\'][\''.$tfu_key.'\']  == \'true\');');        
     }
     if (eval('if (is_string($' .$tfu_key_low.')) return true; ')) {
        eval ('$'.$tfu_key_low.'= $_SESSION[\'TFU\'][\''.$tfu_key.'\'];');        
     }
  }
} 

// Manually mappings!
$login = "true"; // The login flag - has to set by yourself below "true" is logged in, "auth" shows the login form, "reauth" should be set if the authentification has failed. "false" if the flash should be disabled.  
$folder = $_SESSION['TFU']["TFU_FOLDER"]; // this is the root upload folder. 
$zip_folder = $folder; // has to be set again!
$maxfilesize = ($_SESSION['TFU']["MAXFILESIZE"] !="") ?  ((getMaximumUploadSize() > $_SESSION['TFU']["MAXFILESIZE"]) ? $_SESSION['TFU']["MAXFILESIZE"] : getMaximumUploadSize()) : getMaximumUploadSize();
$resize_show = ($_SESSION['TFU']["RESIZE_SHOW"] =="true") ? is_gd_version_min_20() : "false";
// Enhanced features - this are only defaults! if TFU detects that this is not possible this functions are disabled! 
$hide_remote_view = ($_SESSION['TFU']["HIDE_REMOTE_VIEW"] == "true") ? 'true' : '';   
// some optional things
$base_dir = $joomla_path;              // this is the base dir of the other files - tfu_read_Dir, tfu_file and the lang folder. since 2.6 there are no seperate settings for tfu_readDir and tfu_file anymore because it's actually not needed.
$warning_setting = $_SESSION['TFU']["WARNING_SETTING"]; // the warning is shown if remote files do already exist - can be set to all,once,none
$show_size = ($_SESSION['TFU']["SHOW_SIZE"] == 'true') ? 'true' : '';
// the text of the email is stored in the tfu_upload.php if you like to change it :)
$upload_notification_email = $_SESSION['TFU']["NOT_EMAIL"];
$upload_notification_email_from = $_SESSION['TFU']["NOT_EMAIL_FROM"];
$upload_notification_email_subject = $_SESSION['TFU']["NOT_EMAIL_SUBJECT"];
$upload_notification_email_text = $_SESSION['TFU']["NOT_EMAIL_TEXT"];
$normalizeSpaces = $_SESSION['TFU']["NORMALIZE_SPACES"];  

$titel = $_SESSION['TFU']["FLASH_TITLE"];
// new 2.8
$exclude_directories = array_map("trim", explode(",", $_SESSION['TFU']["EXCLUDE_DIRECTORIES"])); // we need an array here and trim spaces too.
$file_chmod=($_SESSION['TFU']["FILE_CHMOD"] == '') ? 0 : octdec($_SESSION['TFU']["FILE_CHMOD"]);
$dir_chmod=($_SESSION['TFU']["DIR_CHMOD"] == '') ? 0 : octdec($_SESSION['TFU']["DIR_CHMOD"]);
// new 2.10.x
$enable_enhanced_debug=($_SESSION['TFU']["ENHANCED_DEBUG"]  == "true");
// new 2.11.x
$ftp_port   = intval($_SESSION['TFU']['FTP_PORT']) ;
// new 2.12.x
$compression= intval($_SESSION['TFU']['COMPRESSION']);
 // 2.13
$info_textcolor_R   = intval($_SESSION['TFU']['INFO_TEXTCOLOR_R']) ;
$info_textcolor_G   = intval($_SESSION['TFU']['INFO_TEXTCOLOR_G']) ;
$info_textcolor_B   = intval($_SESSION['TFU']['INFO_TEXTCOLOR_B']) ;
$info_fontsize   =    intval($_SESSION['TFU']['INFO_FONTSIZE']) ;
// new 2.14
$directory_file_limit_size = intval($_SESSION['TFU']['DIRECTORY_FILE_LIMIT_SIZE']) ; 
$zip_folder   = $_SESSION['TFU']["ZIP_FOLDER"];
if ($zip_folder=='') {
   $zip_folder = $folder;
}

// Extension for the post-processing window.
// we check $_SESSION["TFU_USER_NAME"], $_SESSION["TFU_USER_EMAIL"] and TFU_USER_CONTACT
$post_upload_panel='false';
$post_upload_panel.='&post_name='  . urlencode((isset($_SESSION['TFU_USER_NAME']))? $_SESSION['TFU_USER_NAME']: '');
$post_upload_panel.='&post_email=' . urlencode((isset($_SESSION['TFU_USER_EMAIL']))? $_SESSION['TFU_USER_EMAIL']: '');

if (isset($_SESSION['TFU_USER_CONTACT'])) {
 $contact = $_SESSION['TFU_USER_CONTACT'];
 $post_upload_panel.='&post_address=' . urlencode((isset($contact->address) && $contact->address!='')      ? $contact->address: ' ');
 $post_upload_panel.='&post_postcode=' .urlencode((isset($contact->postcode)&& $contact->postcode != '')   ? $contact->postcode: ' ');
 $post_upload_panel.='&post_city=' .    urlencode((isset($contact->city)&& $contact->city != '')           ? $contact->city: ' ');
 $post_upload_panel.='&post_country=' . urlencode((isset($contact->country)&& $contact->country != '')     ? $contact->country: ' ');
 $post_upload_panel.='&post_telephone='.urlencode((isset($contact->telephone)&& $contact->telephone != '') ? $contact->telephone: ' ');
 $post_upload_panel.='&post_fax=' .     urlencode((isset($contact->fax) && $contact->fax != '')            ? $contact->fax: ' ');
 $post_upload_panel.='&post_company=' . urlencode((isset($contact->company) && $contact->company != '')    ? $contact->company: ' ');
}

if (isset($_SESSION['TFU']['IS_JFU_PLUGIN'])) { 
 $is_jfu_plugin = 'true';
}
} // goon
?>