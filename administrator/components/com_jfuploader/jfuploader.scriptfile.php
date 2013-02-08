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
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Script file of JFUploader component
 */

class com_jfuploaderInstallerScript
{ 
    private $cur_version = '2.15.1'; 
     /**
	 * method to install the component
	 *
	 * @return void
	 */
	function install($parent) 
	{
	  $this->jfu_install();
	}
	
	function jfu_install() {
  $database = &JFactory::getDBO();
  
  $error=false;
  echo JText::_('I_CHECK');
  
  // delete old menu entries if exist! Seems an error in 1.6.x which happens sometimes!
  $database->setQuery("delete from  #__menu where alias like ('comjfuploader%')");
  if(!$database->query()) echo $database->getErrorMsg().'<br />';
  
  // we check if we have a previous version
  $database->setQuery("CREATE TABLE IF NOT EXISTS  #__joomla_flash_uploader_conf (key_id TEXT, value TEXT)");
  if(!$database->query()) echo $database->getErrorMsg().'<br />';
  $database->setQuery("SELECT value FROM #__joomla_flash_uploader_conf where key_id = 'version'");
  $version = $database->loadObjectList();

  // end version check 
  if(!$version) { // tables do not exist
	echo  JText::_('I_CHECK_NONE');
    $this->install_db($this->cur_version); // we install basic version.
    $error = $this->update_db(true); // and then we update to current version.
 } else {
    $ver = $version[0]->value;
    if ($ver == '' || version_compare($ver,$this->cur_version, "<")) { 
      echo JText::_('I_CHECK_OLD_FOUND') . ' ' . $ver . JText::_('I_CHECK_OLD_FOUND_2');
      $error = $this->update_db(false);
    } else {
      echo JText::_('I_CHECK_FOUND');
      $error = $this->update_db(false);
    }
 }
 
  // remove after testing
  if (!$this->testEntry('check_image_magic')) {
      $database->setQuery("INSERT INTO #__joomla_flash_uploader_conf (key_id, value) values ('check_image_magic','true')");
	    if(!$database->query()) { $error=true; echo $database->getErrorMsg().'<br />'; }
  } 
  if (!$error) {
       // we update the version of the db table
	  $database->setQuery("UPDATE #__joomla_flash_uploader_conf SET value='".$this->cur_version."' WHERE key_id='version'");
	  if(!$database->query()) { $error=true; echo $database->getErrorMsg().'<br />'; }
  
      // we try to read the license now
       $database->setQuery("SELECT value FROM #__joomla_flash_uploader_conf where key_id = 'user'");
       $user = $database->loadObjectList();
       if (count($user) > 0) {
         $us = $user[0]->value;
         $database->setQuery("SELECT value FROM #__joomla_flash_uploader_conf where key_id = 'domain'");
         $domain = $database->loadObjectList();
         $do = $domain[0]->value;
         $database->setQuery("SELECT value FROM #__joomla_flash_uploader_conf where key_id = 'code'");
         $code = $database->loadObjectList();
         $co = $code[0]->value;
         // write twg.lic.twg !
         $filename = dirname(__FILE__) . "/tfu/twg.lic.php";
          $file = fopen($filename, 'w');
        	fputs($file, "<?php\n");
        	fputs($file, "\$l=\"".$us."\";\n");
        	fputs($file, "\$d=\"".$do."\";\n");
        	fputs($file, "\$s=\"".$co."\";\n");
        	fputs($file, "?>");	
          fclose($file);
       }
       // load a php.ini if one is stored in the db
       $filename = dirname(__FILE__) . "/tfu/php.ini";
       if (!file_exists($filename)) {
         $database->setQuery("SELECT value FROM #__joomla_flash_uploader_conf where key_id = 'file_php_ini'");
         $file_php_content = $database->loadObjectList();
         if (count($file_php_content) > 0) {
           $content = $file_php_content[0]->value;
           $file = fopen($filename, 'w');
           fputs($file, $content);
           fclose($file);
         }
       }
       // load a .htaccess if one is stored in the db
       $filename = dirname(__FILE__) . "/tfu/.htaccess";
       if (!file_exists($filename)) {
         $database->setQuery("SELECT value FROM #__joomla_flash_uploader_conf where key_id = 'file_htaccess'");
         $file_htaccess_content = $database->loadObjectList();
         if (count($file_htaccess_content) > 0) {
           $content = $file_htaccess_content[0]->value;
           $file = fopen($filename, 'w');
           fputs($file, $content);
           fclose($file);
         }
       }
      echo "&nbsp;<br />";
      echo "<div style='text-align:left;'>";
      echo JText::_('I_TEXT');
      echo JText::_('I_DESC'); 
      echo "</div>";
    }
	}

	/**
	 * method to uninstall the component
	 *
	 * @return void
	 */
	function uninstall($parent) 
	{
	   $database = &JFactory::getDBO();	
	  $database->setQuery("SELECT value FROM #__joomla_flash_uploader_conf where key_id = 'keep_tables'");
	  $settings = $database->loadObjectList();
 
	  if ( $settings[0]->value == 'false') {
	    $database->setQuery("DROP TABLE IF EXISTS #__joomla_flash_uploader");
	    $database->query();
	    $database->setQuery("DROP TABLE IF EXISTS #__joomla_flash_uploader_user");
	    $database->query();
	    $database->setQuery("DROP TABLE IF EXISTS #__joomla_flash_uploader_conf");
        $database->query();
        echo JText::_('R_TEXT_NOT_KEEP');
      } else { // we keep it and store the license data if still a license file exists.
        $database->setQuery("DELETE FROM #__joomla_flash_uploader_conf where key_id='user' OR key_id='domain' OR key_id='code' OR key_id='file_php_ini' OR key_id='file_htaccess' ");
        if(!$database->query()) { error_log($database->getErrorMsg()); }
        // If we have a license file we add this stuff to the db - we delete the old one first
	   $licexists = true;
        $file =  dirname(__FILE__) . '/tfu/twg.lic.php';
        $file2 = dirname(__FILE__) . '/../../../components/com_jfuploader/tfu/twg.lic.php';
        if (file_exists($file)) {
          include($file);
        } else if (file_exists($file2)) {
          include($file2);
        } else {
          $licexists = false;
        }
        if ($licexists) {
          $database->setQuery("INSERT INTO #__joomla_flash_uploader_conf (key_id, value) values ('user','".$l."')");
  	      if(!$database->query()) { error_log($database->getErrorMsg()); }
          $database->setQuery("INSERT INTO #__joomla_flash_uploader_conf (key_id, value) values ('domain','".$d."')");
  	      if(!$database->query()) { error_log($database->getErrorMsg()); }
          $database->setQuery("INSERT INTO #__joomla_flash_uploader_conf (key_id, value) values ('code','".$s."')");
  	      if(!$database->query()) { error_log($database->getErrorMsg()); }	  
        }
        
        // php.ini
        $file_php =  dirname(__FILE__) . '/tfu/php.ini';
        $file_php2 = dirname(__FILE__) . '/../../../components/com_jfuploader/tfu/php.ini';
        $phpexists = true;  
        if (file_exists($file_php)) {
          $content = file_get_contents($file_php);
        } else if (file_exists($file_php2)) {
          $content = file_get_contents($file_php2); 
        } else {
          $phpexists = false;  
        }  
        if ($phpexists) {  
          $database->setQuery("INSERT INTO #__joomla_flash_uploader_conf (key_id, value) values ('file_php_ini','".$content."')");
  	     if(!$database->query()) { error_log($database->getErrorMsg()); }	
        }
        
        // .htaccess
        $file_htaccess =  dirname(__FILE__) . '/tfu/.htaccess';
        $file_htaccess2 = dirname(__FILE__) . '/../../../components/com_jfuploader/tfu/.htaccess';
        $htexists = true;  
        if (file_exists($file_htaccess)) {
          $content = file_get_contents($file_htaccess);
        } else if (file_exists($file_htaccess2)) {
          $content = file_get_contents($file_htaccess2); 
        } else {
          $htexists = false;  
        }      
        if ($htexists) {     
          $database->setQuery("INSERT INTO #__joomla_flash_uploader_conf (key_id, value) values ('file_htaccess','".$content."')");
  	     if(!$database->query()) { error_log($database->getErrorMsg()); }	
        }
        echo JText::_('R_TEXT_KEEP');
      }
      echo JText::_('R_TEXT');
	}

	/**
	 * method to update the component
	 *
	 * @return void
	 */
	function update($parent) 
	{  	
	  $this->update_db(false);	  
	  // we update the version of the db table
	  $database = &JFactory::getDBO();	
      $database->setQuery("UPDATE #__joomla_flash_uploader_conf SET value='".$this->cur_version."' WHERE key_id='version'");
	  if(!$database->query()) { $error=true; echo $database->getErrorMsg().'<br />'; }
  
	  
       echo '<br>' . JText::_('I_UPDATE_JFU_OK');
       echo '<p>';	  
	  echo JText::_('I_TEXT');
	  echo '</p>';
	}

	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @return void
	 */
	function preflight($type, $parent) 
	{		
	}

	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	function postflight($type, $parent) 
	{
	}
	
  function install_db($cur_version){
       $database = &JFactory::getDBO();   
	  $database->setQuery("DROP TABLE IF EXISTS #__joomla_flash_uploader");
	  $database->query();
	  $database->setQuery("DROP TABLE IF EXISTS #__joomla_flash_uploader_user");
	  $database->query();
	  $database->setQuery("DROP TABLE IF EXISTS #__joomla_flash_uploader_conf");
	  $database->query();
	  
	  $database->setQuery("CREATE TABLE #__joomla_flash_uploader_user ( id INT NOT NULL AUTO_INCREMENT, 
	  profile INT,
	  user INT,
	  jgroup INT,
      location VARCHAR(30),
	  PRIMARY KEY (id),
	  UNIQUE profile_user_group_index (profile,user,jgroup,location)
	  )");
	  if(!$database->query()) echo $database->getErrorMsg().'<br />';
	   
	  $database->setQuery("CREATE TABLE #__joomla_flash_uploader (
	              id INT NOT NULL AUTO_INCREMENT,gid TEXT NOT NULL,
	              config_name TEXT NOT NULL,folder TEXT NOT NULL DEFAULT '',
	              description TEXT NOT NULL DEFAULT '',text_title TEXT DEFAULT '',
	              text_title_lang TEXT DEFAULT '',text_top TEXT DEFAULT '',
	  			  text_top_lang TEXT DEFAULT '',text_bottom TEXT DEFAULT '',
	              text_bottom_lang TEXT DEFAULT '',maxfilesize TEXT NOT NULL,
	              resize_show TEXT NOT NULL,resize_data TEXT ,
	              resize_label TEXT ,resize_default TEXT ,
	              allowed_file_extensions TEXT,forbidden_file_extensions TEXT,
	              hide_remote_view TEXT DEFAULT '',show_delete TEXT NOT NULL,
	              enable_folder_browsing TEXT NOT NULL,enable_folder_creation TEXT NOT NULL,
	              enable_folder_deletion TEXT NOT NULL,enable_folder_rename TEXT NOT NULL,
	              enable_file_rename TEXT NOT NULL,keep_file_extension TEXT NOT NULL,
	              enable_file_download TEXT NOT NULL,sort_files_by_date TEXT NOT NULL,
	              warning_setting TEXT NOT NULL,show_size TEXT DEFAULT '',
	              enable_setting TEXT NOT NULL,creation_date DATE NOT NULL,
	              last_modified_date DATE NOT NULL,fix_overlay TEXT NOT NULL,
	              flash_title TEXT NOT NULL, hide_directory_in_title TEXT NOT NULL, 
	              swf_text TEXT, split_extension TEXT,
	              upload_notification_email TEXT,upload_notification_email_from TEXT,
	              upload_notification_email_subject TEXT,upload_notification_email_text TEXT,
	              upload_finished_js_url TEXT,preview_select_js_url TEXT,
	              delete_js_url TEXT,js_change_folder TEXT,
	              directory_file_limit TEXT,queue_file_limit TEXT,
	              queue_file_limit_size TEXT,display_width TEXT,
	              enable_folder_movecopy TEXT, enable_file_movecopy TEXT,
	              preview_textfile_extensions TEXT,edit_textfile_extensions TEXT,
	              PRIMARY KEY (id))");
	  if(!$database->query()) echo $database->getErrorMsg().'<br />';
	 
	  $database->setQuery("CREATE TABLE #__joomla_flash_uploader_conf (key_id TEXT, value TEXT)");
	  if(!$database->query()) echo $database->getErrorMsg().'<br />';
	 
      // we set the version
      $database->setQuery("INSERT INTO #__joomla_flash_uploader_conf (key_id, value) values ('version','".$cur_version."')");
      if(!$database->query()) echo $database->getErrorMsg().'<br />';
	  
	  $database->setQuery("INSERT INTO #__joomla_flash_uploader_conf (key_id, value) values ('keep_tables','true')");
	  if(!$database->query()) echo $database->getErrorMsg().'<br />';
	  
	  $database->setQuery("INSERT INTO #__joomla_flash_uploader_conf (key_id, value) values ('use_js_include','true')");
	  if(!$database->query()) echo $database->getErrorMsg().'<br />';
	   
      // admin
	  $database->setQuery("INSERT INTO #__joomla_flash_uploader(
       config_name, folder, description, text_title,
	   text_top, text_bottom, text_title_lang, text_top_lang,
	   text_bottom_lang, maxfilesize, resize_show, resize_data,
	   resize_label, resize_default, allowed_file_extensions, forbidden_file_extensions,
	   hide_remote_view, show_delete, enable_folder_browsing, enable_folder_creation,
	   enable_folder_deletion, enable_folder_rename, enable_file_rename, keep_file_extension,
	   enable_file_download, sort_files_by_date, warning_setting, show_size,
	   enable_setting, creation_date, last_modified_date, fix_overlay,
	   flash_title, hide_directory_in_title, upload_notification_email, upload_notification_email_from,
	   upload_notification_email_subject, upload_notification_email_text, upload_finished_js_url, preview_select_js_url,
	   delete_js_url, js_change_folder, directory_file_limit, queue_file_limit, 
       queue_file_limit_size, display_width, enable_folder_movecopy, enable_file_movecopy,
       preview_textfile_extensions, edit_textfile_extensions
     ) 
	   values 
	   ('admin', '','Administration profile','Title',
	   'Text before flash',  'Text after flash',  'true',  'true',
	   'true',  '',  'true',  '100000,1000',
	   'Original,1000',  '0',  'all',  '',
	   'false',  'true',  'true',  'true',
	   'true',  'true',  'true',  'true',
	   'true',  'false',  'once',  '',
	   'true',  NOW(),  NOW(),  'false',
	   'JFUploader',  'false',  '',  '',
	   'Files were uploaded by the JFUploader',  'The following files where uploaded by %s: %s',  '',  '',
	   '',  '',  '100000',  '100000',
	   '100000',  '650',  'true', 'true',
       'txt,log,php,css,htm,html,js,bak','txt,log,php,css,htm,html,js,bak' );
	   ");
	 if(!$database->query()) echo $database->getErrorMsg().'<br />';
	 
     // frontend user
     $database->setQuery("INSERT INTO #__joomla_flash_uploader(
       config_name, folder, description, text_title,
	   text_top, text_bottom, text_title_lang, text_top_lang,
	   text_bottom_lang, maxfilesize, resize_show, resize_data,
	   resize_label, resize_default, allowed_file_extensions, forbidden_file_extensions,
	   hide_remote_view, show_delete, enable_folder_browsing, enable_folder_creation,
	   enable_folder_deletion, enable_folder_rename, enable_file_rename, keep_file_extension,
	   enable_file_download, sort_files_by_date, warning_setting, show_size,
	   enable_setting, creation_date, last_modified_date, fix_overlay,
	   flash_title, hide_directory_in_title, upload_notification_email, upload_notification_email_from,
	   upload_notification_email_subject, upload_notification_email_text, upload_finished_js_url, preview_select_js_url,
	   delete_js_url, js_change_folder, directory_file_limit, queue_file_limit, 
       queue_file_limit_size, display_width, enable_folder_movecopy, enable_file_movecopy,
       preview_textfile_extensions, edit_textfile_extensions
       ) 
	    values 
	    ('frontend','images','Example frontend profile','Title',
	    'Text before flash','Text below flash','false','false',
	    'false', '1000','true','100000,1000',
        'Original,1000', '0','jpg,jpeg,gif,png' ,'' ,
        'false','true','true','false',
        'false','false', 'false','true',
        'false', 'false','all','',
        'true', NOW(), NOW(), 'false',
        'JFUploader','false', '','',
	    'Files were uploaded by the JFUploader','The following files where uploaded by %s: %s','','',
		'','','100000','100000',
	    '100000','650','false','false',
        'txt,log', '' );
     ");
  if(!$database->query()) echo $database->getErrorMsg().'<br />';
  }
  
  
  /*
    This function checks if a column does already exists. If not it is created.
    This makes it easy to do a update from older versions 
  */
  function update_db($newinstall){
   $database = &JFactory::getDBO();
    $error = false;
      // we create the new columns for 2.7
      $error = $this->addColumn($database, 'preview_textfile_extensions', '', $error);
      $error = $this->addColumn($database, 'edit_textfile_extensions', '', $error);
      $error = $this->addColumn($database, 'js_create_folder', '', $error);
      $error = $this->addColumn($database, 'js_rename_folder', '', $error);
      $error = $this->addColumn($database, 'js_delete_folder', '', $error);
      $error = $this->addColumn($database, 'js_copymove', '', $error);      
	  // new 2.8
	  $error = $this->addColumn($database, 'language_dropdown', 'de,en,es,cn,da,fr,it,jp,nl,no,pl,pt,ru,se', $error);
	  $error = $this->addColumn($database, 'use_image_magic', 'false', $error);
	  $error = $this->addColumn($database, 'image_magic_path', 'convert', $error);
	  $error = $this->addColumn($database, 'exclude_directories', 'data.pxp,_vti_cnf,.svn,CVS,thumbs', $error);
	  $error = $this->addColumn($database, 'normalise_file_names', 'false', $error);
	  $error = $this->addColumn($database, 'download_multiple_files_as_zip', 'false', $error);
	  $error = $this->addColumn($database, 'allowed_view_file_extensions', 'all', $error);
	  $error = $this->addColumn($database, 'forbidden_view_file_extensions', '', $error);
	  $error = $this->addColumn($database, 'description_mode', 'false', $error);
	  $error = $this->addColumn($database, 'description_mode_show_default', 'true', $error);
	  $error = $this->addColumn($database, 'description_mode_store', 'email', $error);
	  $error = $this->addColumn($database, 'master_profile', 'false', $error);
	  $error = $this->addColumn($database, 'master_profile_mode', 'login', $error);
	  $error = $this->addColumn($database, 'master_profile_lowercase', 'true', $error);
	  // new 2.8.3
	  $error = $this->addColumn($database, 'normalise_directory_names', 'false', $error);
	  $error = $this->addColumn($database, 'direct_download', 'false', $error);
	  $error = $this->addColumn($database, 'fix_utf8', '', $error);
	  
	  // new 2.9
	  if (!$this->testEntry('use_js_include')) {
      $database->setQuery("INSERT INTO #__joomla_flash_uploader_conf (key_id, value) values ('use_js_include','true')");
	    if(!$database->query()) { $error=true; echo $database->getErrorMsg().'<br />'; }
	  }
	  if (!$this->testEntry('backend_access_upload')) {
      $database->setQuery("INSERT INTO #__joomla_flash_uploader_conf (key_id, value) values ('backend_access_upload','Manager')");
	    if(!$database->query()) { $error=true; echo $database->getErrorMsg().'<br />'; }
	  }
	  if (!$this->testEntry('backend_access_config')) {
      $database->setQuery("INSERT INTO #__joomla_flash_uploader_conf (key_id, value) values ('backend_access_config','Manager')");
	    if(!$database->query()) { $error=true; echo $database->getErrorMsg().'<br />'; }
	  }
	  if (!$this->testEntry('file_chmod')) {
      $database->setQuery("INSERT INTO #__joomla_flash_uploader_conf (key_id, value) values ('file_chmod','')");
	    if(!$database->query()) { $error=true; echo $database->getErrorMsg().'<br />'; }
	  }
      $error = $this->addColumn($database, 'overwrite_files', 'true', $error);
	    $error = $this->addColumn($database, 'description_mode_mandatory', 'false', $error); 
      $error = $this->addColumn($database, 'show_full_url_for_selected_file', 'false', $error);
      $error = $this->addColumn($database, 'normalize_spaces', 'false', $error);
      
    if (!$this->testEntry('dir_chmod')) {
      $database->setQuery("INSERT INTO #__joomla_flash_uploader_conf (key_id, value) values ('dir_chmod','')");
	    if(!$database->query()) { $error=true; echo $database->getErrorMsg().'<br />'; }
	  }
	  // new 2.10.3
	  if (!$this->testEntry('enable_upload_debug')) {
      $database->setQuery("INSERT INTO #__joomla_flash_uploader_conf (key_id, value) values ('enable_upload_debug','false')");
	    if(!$database->query()) { $error=true; echo $database->getErrorMsg().'<br />'; }
	  } // will be part of 2.10.4
	  if (!$this->testEntry('sa_profil')) {
      $database->setQuery("INSERT INTO #__joomla_flash_uploader_conf (key_id, value) values ('sa_profil','1')");
	    if(!$database->query()) { $error=true; echo $database->getErrorMsg().'<br />'; }
	  }
	  if (!$this->testEntry('a_profil')) {
      $database->setQuery("INSERT INTO #__joomla_flash_uploader_conf (key_id, value) values ('a_profil','1')");
	    if(!$database->query()) { $error=true; echo $database->getErrorMsg().'<br />'; }
	  }
	  if (!$this->testEntry('m_profil')) {
      $database->setQuery("INSERT INTO #__joomla_flash_uploader_conf (key_id, value) values ('m_profil','1')");
	    if(!$database->query()) { $error=true; echo $database->getErrorMsg().'<br />'; }
	  }
	  if (!$this->testEntry('enhanced_debug')) {
      $database->setQuery("INSERT INTO #__joomla_flash_uploader_conf (key_id, value) values ('enhanced_debug','false')");
	    if(!$database->query()) { $error=true; echo $database->getErrorMsg().'<br />'; }
	  }
    $error = $this->addColumn($database, 'upload_notification_use_full_path', 'false', $error); 
    
    // new 2.10.6
    $error = $this->addColumn($database, 'hide_hidden_files', 'false', $error); 
    $error = $this->addColumn($database, 'truncate_dir_in_title', 'false', $error);
      
    if (!$this->testEntry('check_image_magic')) {
      $database->setQuery("INSERT INTO #__joomla_flash_uploader_conf (key_id, value) values ('check_image_magic','true')");
	    if(!$database->query()) { $error=true; echo $database->getErrorMsg().'<br />'; }
	  }  
	  
    // new 2.11
    $error = $this->addColumn($database, 'form_fields', '', $error); 
    $error = $this->addColumn($database, 'hide_hidden_files', 'false', $error); 
    $error = $this->addColumn($database, 'big_progressbar', 'false', $error); 
    $error = $this->addColumn($database, 'img_progressbar', 'progressbar.png', $error); 
    $error = $this->addColumn($database, 'img_progressbar_back', 'progressbar_back.png', $error); 
    $error = $this->addColumn($database, 'img_progressbar_anim', 'progressbar_anim.swf', $error); 
    $error = $this->addColumn($database, 'enable_dir_create_detection', 'true', $error); 
    $error = $this->addColumn($database, 'ftp_enable', 'false', $error); 
    $error = $this->addColumn($database, 'ftp_host', 'host', $error); 
    $error = $this->addColumn($database, 'ftp_port', 21, $error); 
    $error = $this->addColumn($database, 'ftp_user', 'user', $error); 
    $error = $this->addColumn($database, 'ftp_pass', 'pass', $error); 
    $error = $this->addColumn($database, 'ftp_root', 'full root directory', $error); 
    $error = $this->addColumn($database, 'big_server_view', 'false', $error); 
    // new 2.12
    $error = $this->addColumn($database, 'compression', 80, $error); 
    $error = $this->addColumn($database, 'remove_multiple_php_extension', 'true', $error); 
    $error = $this->addColumn($database, 'scan_images', 'true', $error);  
    // new 2.12.1 
    $error = $this->addColumn($database, 'forbidden_view_file_filter', '', $error); 
    $error = $this->addColumn($database, 'zip_file_pattern', 'download-{number}-files_{date}.zip', $error); 
         
         
     // new 2.13 - Joomla 1.6 stuff!
     if (!$this->testField('jgroup', "#__joomla_flash_uploader_user")) {
	    $database->setQuery("ALTER TABLE #__joomla_flash_uploader_user ADD jgroup int");
	    if(!$database->query()) { $error=true; echo $database->getErrorMsg().'<br />'; }
	}  	
    if (!$this->testField('location', "#__joomla_flash_uploader_user")) {
	    $database->setQuery("ALTER TABLE #__joomla_flash_uploader_user ADD location VARCHAR(30)");
	    if(!$database->query()) { $error=true; echo $database->getErrorMsg().'<br />'; }
	    // now we have to set the default value because before mysql 5.1.2 this is not possible
         $database->setQuery( "UPDATE #__joomla_flash_uploader_user SET location='site'");
	    if(!$database->query()) { $error=true; echo $database->getErrorMsg().'<br />'; }   
	}  
	// drop the old index
	
	 // this does exist in 2.13
	 if ($this->hasIndex($database,'profile')) {
        $database->setQuery( "drop index profile on #__joomla_flash_uploader_user");
	    if(!$database->query()) { $error=true; echo $database->getErrorMsg().'<br />'; }   
     }
	
	 // is replaced by this in 2.14 and default with new installations.
     if (!$this->hasIndex($database,'profile_user_group_index')) {
       // we add a new constraint which has all entries
       $database->setQuery( "ALTER TABLE #__joomla_flash_uploader_user ADD UNIQUE profile_user_group_index (profile,user,jgroup,location)");
	  if(!$database->query()) { $error=true; echo $database->getErrorMsg().'<br />'; }  
     }
     // enter the default settings - all core.admin.login users get access to the admin login. 
     // Existing mappings are not touched
/*
     $database->setQuery("SELECT u.id as myid, config_name, us.username, ug.title, u.location, u.jgroup FROM #__joomla_flash_uploader f, #__joomla_flash_uploader_user u left outer join #__users us on u.user=us.id left outer join #__usergroups ug on u.jgroup=ug.id  where  u.profile=f.id ORDER BY u.profile,username,ug.title");	
     $rows = $database->loadObjectList();
     $backendgroups = JFUHelper::getBackendGroups($rows, JFUHelper::getUserGroups()); 

     // now we insert an admin profile for all backend groups
     foreach ($backendgroups AS $groups) {
       $rowuser = new joomla_flash_uploader_user($database);
       $rowuser->profile = 1;   
       $rowuser->jgroup  = $groups->value;
       $rowuser->location = 'admin';
       $rowuser->store();
     } 
*/

    // 2.13
    $error = $this->addColumn($database, 'info_text', '{dimension} | {size} | {date}', $error); 
    $error = $this->addColumn($database, 'info_textcolor_R', 255, $error); 
    $error = $this->addColumn($database, 'info_textcolor_G', 60, $error); 
    $error = $this->addColumn($database, 'info_textcolor_B', 60, $error); 
    $error = $this->addColumn($database, 'info_font', 'verdana.ttf', $error); 
    $error = $this->addColumn($database, 'info_fontsize', 8, $error);   
    // new 2.14
    $error = $this->addColumn($database, 'directory_file_limit_size', -1, $error);   
    $error = $this->addColumn($database, 'directory_file_limit_size_system', 'true', $error);   
    $error = $this->addColumn($database, 'sort_directores_by_date', 'false', $error);   
    $error = $this->addColumn($database, 'show_server_date_instead_size', 'false', $error);   
    $error = $this->addColumn($database, 'enable_file_creation', 'false', $error);   
    $error = $this->addColumn($database, 'enable_file_creation_extensions', 'txt', $error);   
    $error = $this->addColumn($database, 'zip_folder', '', $error);   
    // new 2.15
    if (!$this->testEntry('idn_url')) {
      $database->setQuery("INSERT INTO #__joomla_flash_uploader_conf (key_id, value) values ('idn_url','')");
	    if(!$database->query()) { $error=true; echo $database->getErrorMsg().'<br />'; }
	  }  
    $error = $this->addColumn($database, 'switch_sides', 'false', $error);    
    if (!$this->testEntry('use_index_for_files')) {
      $database->setQuery("INSERT INTO #__joomla_flash_uploader_conf (key_id, value) values ('use_index_for_files','true')");
	    if(!$database->query()) { $error=true; echo $database->getErrorMsg().'<br />'; }
	  }
	  $error = $this->addColumn($database, 'date_format', 'd.m.y', $error); 
        	         
    if ($error) {
      echo "<p class='error'>";
      echo JText::_('I_UPDATE_ERROR');
      return true;
      echo "</p>";
    } else {
      if (!$newinstall) {
        echo JText::_('I_UPDATE_OK');
      }
      return false;
    }            
  }
  
  /*
    Checks if a field is a table exists - true: exist; false: does not exist
  */
  function testField($field, $table) {
     $database = &JFactory::getDBO();
     $database->setQuery("show columns from $table like '$field'");
     return (count ($database->loadObjectList()) > 0) ? true : false;
  }
  /*
    Checks if a entry in a table exists - true: exist; false: does not exist
  */
  function testEntry($entry) {
     $database = &JFactory::getDBO();
     $database->setQuery("SELECT value FROM #__joomla_flash_uploader_conf where key_id = '".$entry."'");
     return (count ($database->loadObjectList()) > 0) ? true : false;
  }
  
  /*
    Adds a new column - first it checks is the table exists and if not it is added + the default value is set.
  */
  function addColumn($database, $field, $defaultValue, $error) {
    if (!$this->testField($field, "#__joomla_flash_uploader")) {
	    $database->setQuery("ALTER TABLE #__joomla_flash_uploader ADD ".$field." TEXT DEFAULT ''");
	    if(!$database->query()) { $error=true; echo $database->getErrorMsg().'<br />'; }
	    // now we have to set the default value because before mysql 5.1.2 this is not possible
        $database->setQuery( "UPDATE #__joomla_flash_uploader SET ".$field."='".$defaultValue."'");
        if(!$database->query()) { $error=true; echo $database->getErrorMsg().'<br />'; }
	  }
	 return $error; 
  }	
  
  function hasIndex($database, $index_name) {
     $hasindex = false;
     $database->setQuery("SHOW INDEX FROM #__joomla_flash_uploader_user");
	if(!$database->query()) { $error=true; echo $database->getErrorMsg().'<br />'; }
     $results = $database->loadObjectList();
     foreach($results as $result) {
       if ($result->Key_name == $index_name) {
          return true;
       }
     }
     return $hasindex;
  
  }
  
  
  
}