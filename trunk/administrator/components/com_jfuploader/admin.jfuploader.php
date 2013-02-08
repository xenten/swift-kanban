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
define('_VALID_TWG', '42');
error_reporting(E_ALL ^ E_NOTICE);

$id = JRequest::getVar('cid', array(0) );
  if (!is_array( $id )) {
    $id = array(0);
  }
  
global $mybasedir, $otherdir; 
$mybasedir = '../../../components/com_jfuploader/';
$otherdir = '';
if (!file_exists(dirname(__FILE__) . "/" . $mybasedir . 'tfu/tfu_helper.php')) {  // we are in the backend.
  $otherdir = $mybasedir; 
  $mybasedir = '';
}

require_once(JApplicationHelper::getPath('class'));
require_once(JApplicationHelper::getPath('admin_html'));

if  ( JRequest::getVar('no_html','') != 1) {
  JFUHelper::printCss(''); // no extra path needed.
}

$skip_error_handling = "true"; // avoids that the jfu logfile is used for everything!		
$debug_file = '';
global $m;
@ob_start();
include_once(dirname(__FILE__) . "/".$mybasedir."tfu/tfu_helper.php");
@ob_end_clean();

$act = JRequest::getVar('act');
$task = JRequest::getVar('task');

JSubMenuHelper::addEntry(JText::_('JFU_M_1'), 'index.php?option=com_jfuploader&act=upload');
JSubMenuHelper::addEntry(JText::_('JFU_M_2'), 'index.php?option=com_jfuploader&act=config');
JSubMenuHelper::addEntry(JText::_('JFU_M_3'), 'index.php?option=com_jfuploader&act=user');
JSubMenuHelper::addEntry(JText::_('JFU_M_5'), 'index.php?option=com_jfuploader&act=plugins');
JSubMenuHelper::addEntry(JText::_('JFU_M_4'), 'index.php?option=com_jfuploader&act=help');


  if ($task) {
    $act = $task;
  }
  
  //  echo "a:" . $act . " ID " . $id[0];
  switch ($act) {
    case "upload": showUpload(); break;
    case "config": showConfig(); break;
    case "plugins": showPlugins(); break;
    case "edit": showConfigUser($id[0]); break;
    case "edituser": showConfigUser($id[0], true); break;
    case "deleteConfig": deleteConfig($id); break;
    case "newConfig": newConfig(); ; break;
    case "saveConfig": saveConfig(); break;
    case "saveMainConfig": saveMainConfig(); break;
    case "copyConfig": copyConfig($id); break;
    case "addUser": addUser(); break;
    case "deleteUser": deleteUser($id); break;
    case "cancel": cancel();; break; 
    case "register": register(); break; 
    case "dellic": deleteLicense(); break; 
    case "help": showHelpRegister() ; break;
    case "user": showUser($id); break;  
    case "createhtaccess" : createHtaccess(); break;
    case "deletehtaccess" : deleteHtaccess(); break;
    case "deletelog" : deleteLog(); break;
    case "changeProfile" : changeProfile(); break;
    case "changeMaster" : changeMaster(); break;
    case "testFolder" : testFolder(); break;
    case "chmod777" : chmod_tfu(0777); break;
    case "chmod666" : chmod_tfu(0666); break;
    case "chmod755" : chmod_tfu(0755); break;
    case "chmod644" : chmod_tfu(0644); break;
    case "movetfudir" : jfu_move_tfu_dir(); break;
    case "addGroup": addUserGroup('site', 'jgroup', 'profilegroup'); break;
    case "addBackGroup": addUserGroup('admin', 'jgroupback', 'allprofile'); break;
    
    default: showUpload(); break;
  }
  
// we remove the JFU error handler
if ($old_error_handler) {
  set_error_handler($old_error_handler);
} else { // no other error handler set
  set_error_handler('on_error_no_output');
}

  
function checkAccess($database, $needed_right) {
  $canDo = JFUHelper::getActions();
  return ($canDo->get($needed_right));
}

function selectBackendProfile($database, $current_right) {
  $list_groups = implode("', '", $current_right);
  $query = "SELECT u.profile, u.jgroup, ug.title, ug.id FROM #__joomla_flash_uploader f, #__joomla_flash_uploader_user u left outer join #__usergroups ug on u.jgroup=ug.id  where location='admin' and u.profile=f.id and ug.id in ('".$list_groups."') ORDER BY u.jgroup";
  $database->setQuery($query);	
  $rows = $database->loadObjectList();
  if (count($rows) > 0) {
    return $rows[0]->profile;
  } else {
    // default value if no profile is found
    return 1;
  }  
}

function showUpload() {
  global $mybasedir;
  $mainframe = JFactory::getApplication(); 
  $database = JFactory::getDBO();
  $my =JFactory::getUser();
  

  // print_r(JAccess::getGroups());
  if (checkAccess($database, 'core.manage' )) {
      $jfu_config['idn_url']= JFUHelper::getVariable($database, 'idn_url');     
      $row = new joomla_flash_uploader($database);
      $b_prof = selectBackendProfile($database, $my->groups);
      $row->load($b_prof);
      $uploadfolder = $row->folder;
      
      $pathfix='';
      if ($mybasedir == '') {
      $pathfix='../';
      }
      // we go back to the main folder!
      if ($uploadfolder == "") {
        $folder =  "./".$pathfix."../../..";
        $filefolder = ''; // this setting make the folder check always true 
      } else {
        $folder =  "./".$pathfix."../../../" . $uploadfolder;
        $filefolder =  "./../" . $uploadfolder;
      } 
      // settings for the flash
      JFUHelper::setJFUSession($row, $folder, $database);
      $_SESSION["IS_ADMIN"] = "TRUE"; 
      unset($_SESSION["IS_FRONTEND"]); 
      $my =JFactory::getUser();
      $_SESSION["TFU_USER"] = $my->username . " (backend)";
      $_SESSION["TFU_USER_ID"] = $my->id;
      $_SESSION["TFU_USER_NAME"] = $my->name;
      $_SESSION["TFU_USER_EMAIL"] = $my->email;
      JFUHelper::setContactDetailsToSession($my->id);  
      JFUHelper::fixSession();
      store_temp_session();
      HTML_joomla_flash_uploader::showUpload($row, $uploadfolder, $filefolder, $jfu_config);
  } else {
      HTML_joomla_flash_uploader::errorRights();
  }
}
  
  
/*
  Creates a new default profile
*/
function newConfig() {
	$database = JFactory::getDBO();
		$my = JFactory::getUser();
    if (checkAccess($database, 'core.create' )) {
	    $row = new joomla_flash_uploader($database);	
	    
	    $row->creation_date = date("Y-m-d");
      $row->last_modified_date = date("Y-m-d");
	    $a_user = getFreeUsers($database);
      $p_user = getAssingedUsers($database);
      $cim = JFUHelper::getVariable($database, 'check_image_magic');
      $f_group = null; // no user groups can be assigned here because no group is assigned to a new profile
      HTML_joomla_flash_uploader::showConfig($row, $a_user, $p_user, $f_group, $cim);
	 } else {
      HTML_joomla_flash_uploader::errorRights();
  }
}
  
function deleteConfig($cid) {
   $mainframe = JFactory::getApplication(); 
   $database = JFactory::getDBO();
   	$my =JFactory::getUser();
    if (checkAccess($database, 'core.delete' )) {
  
   $cids = implode( ',', $cid );
   $database->setQuery( "DELETE FROM #__joomla_flash_uploader WHERE id IN ($cids) AND id != 1" );
   $database->query();
   $mainframe->redirect( "index.php?option=com_jfuploader&act=config" );
    } else {
     HTML_joomla_flash_uploader::errorRights();
  }
} 

function saveMainConfig() {
   $mainframe = JFactory::getApplication(); 
  $database = JFactory::getDBO();
  	$my =JFactory::getUser();
    if (checkAccess($database, 'core.edit' )) {
  
  $kt = JRequest::getVar('keep_tables', 'true' );
  $uj = JRequest::getVar('use_js_include', 'true' );
  $ac = JRequest::getVar('backend_access_config', 'Manager' );
  $au = JRequest::getVar('backend_access_upload', 'Manager' );
  $mo = JRequest::getVar('file_chmod', '' );
  $do = JRequest::getVar('dir_chmod', '' );
  $up = JRequest::getVar('enable_upload_debug', 'false' );
  $sp = JRequest::getVar('sa_profil', '1' );
  $ap = JRequest::getVar('a_profil', '1' );
  $mp = JRequest::getVar('m_profil', '1' );
  $ed = JRequest::getVar('enhanced_debug', 'false' );
  $im = JRequest::getVar('check_image_magic', 'true' );
  $id = JRequest::getVar('idn_url', '' );
  $ui = JRequest::getVar('use_index_for_files', '' );
    
  $database->setQuery( "UPDATE #__joomla_flash_uploader_conf SET value='".$kt."' WHERE key_id='keep_tables'");
  $database->query();
  $database->setQuery( "UPDATE #__joomla_flash_uploader_conf SET value='".$uj."' WHERE key_id='use_js_include'");
  $database->query();
  $database->setQuery( "UPDATE #__joomla_flash_uploader_conf SET value='".$ac."' WHERE key_id='backend_access_config'");
  $database->query();
  $database->setQuery( "UPDATE #__joomla_flash_uploader_conf SET value='".$au."' WHERE key_id='backend_access_upload'");
  $database->query();
  $database->setQuery( "UPDATE #__joomla_flash_uploader_conf SET value='".$mo."' WHERE key_id='file_chmod'");
  $database->query();
  $database->setQuery( "UPDATE #__joomla_flash_uploader_conf SET value='".$do."' WHERE key_id='dir_chmod'");
  $database->query();
  $database->setQuery( "UPDATE #__joomla_flash_uploader_conf SET value='".$up."' WHERE key_id='enable_upload_debug'");
  $database->query();
  $database->setQuery( "UPDATE #__joomla_flash_uploader_conf SET value='".$sp."' WHERE key_id='sa_profil'");
  $database->query();
  $database->setQuery( "UPDATE #__joomla_flash_uploader_conf SET value='".$ap."' WHERE key_id='a_profil'");
  $database->query();
  $database->setQuery( "UPDATE #__joomla_flash_uploader_conf SET value='".$mp."' WHERE key_id='m_profil'");
  $database->query();
  $database->setQuery( "UPDATE #__joomla_flash_uploader_conf SET value='".$ed."' WHERE key_id='enhanced_debug'");
  $database->query();
  $database->setQuery( "UPDATE #__joomla_flash_uploader_conf SET value='".$im."' WHERE key_id='check_image_magic'");
  $database->query();
  $database->setQuery( "UPDATE #__joomla_flash_uploader_conf SET value='".$id."' WHERE key_id='idn_url'");
  $database->query();
  $database->setQuery( "UPDATE #__joomla_flash_uploader_conf SET value='".$ui."' WHERE key_id='use_index_for_files'");
  $database->query();
  cleanMessageQueue();
  $mainframe->redirect( "index.php?option=com_jfuploader&act=config", JText::_('MES_SAVED'));
   } else {
     HTML_joomla_flash_uploader::errorRights();
  }
}

function copyConfig($cid) {
$mainframe = JFactory::getApplication(); 
  	$database = JFactory::getDBO();
	$my =JFactory::getUser();
    if (checkAccess($database, 'core.create' )) {
  	
  	if (count($cid) == 0) {
  	      cleanMessageQueue();
	  	  $mainframe->redirect("index.php?option=com_jfuploader&act=config", JText::_('MES_COPY_NONE'));
	  	  return;
  	}
  	if (count($cid) > 1) {
  	  cleanMessageQueue();
  	  $mainframe->redirect("index.php?option=com_jfuploader&act=config", JText::_('MES_COPY_ONE'));
  	  return;
  	}
  	$row = new joomla_flash_uploader($database);
    $row->load($cid[0]);
    $row->id=null;
    $row->description = "Copy of " . $row->description;
    $row->text_title_lang="false";
    $row->text_top_lang="false";
    $row->text_bottom_lang="false";
    $row->last_modified_date=date("Y-m-d");
    $row->creation_date=date("Y-m-d");
    $row->store();
    cleanMessageQueue();
    $mainframe->redirect("index.php?option=com_jfuploader&act=config", JText::_('MES_COPY_OK'));
   } else {
     HTML_joomla_flash_uploader::errorRights();
  }
}

function changeProfile() {
$mainframe = JFactory::getApplication(); 
    $database = JFactory::getDBO();    
    $type =  JRequest::getVar('type','' );
    $profile =  JRequest::getVar( 'profile','' );
   	$row = new joomla_flash_uploader($database);
    $row->load($profile);
    if ($type == "enable") {
      $row->enable_setting = "true";
    } else if ($type == "disable") { // we do 2 checks to make sure that only the given values work
      $row->enable_setting = "false";
    }
    // and than we save it again.
    $row->store();
    echo "JFU:OUTPUT:1";
}

function changeMaster() {
$mainframe = JFactory::getApplication(); 
    $database = JFactory::getDBO();    
    $type =  JRequest::getVar('type','' );
    $profile =  JRequest::getVar( 'profile','' );
   	$row = new joomla_flash_uploader($database);
    $row->load($profile);
    if ($type == "enable") {
      $row->master_profile = "true";
    } else if ($type == "disable") { // we do 2 checks to make sure that only the given values work
      $row->master_profile = "false";
    }
    // and than we save it again.
    $row->store();
    echo "JFU:OUTPUT:1";
}

function testFolder() {
    $folder =  JRequest::getVar('folder','xxx' );
    if (endswith($folder, '/') || endswith($folder, '\\') || startswith($folder, '/') || startswith($folder, '\\')) {
       echo 'JFU:OUTPUT:0';
       return;
    }
    if (file_exists("../" . $folder)) {
      if (is_writable("../" . $folder)) {
        // We create a file - check if it exists and delete it again. Possible safemode problems can be detected!
        $testfile = "../" . $folder . '/xxx_jfu_testfile.test';
        $fh = fopen($testfile , 'w');
        fclose($fh);
        clearstatcache();
        if (file_exists($testfile)) {
          echo 'JFU:OUTPUT:1';
          @unlink($testfile);
        } else { // file could not be created
          echo 'JFU:OUTPUT:2';
        }
      } else {
        echo 'JFU:OUTPUT:2';
      }
    } else {
      echo 'JFU:OUTPUT:0';
    }   
}

function endswith( $str, $sub ) {
  return ( substr( $str, strlen( $str ) - strlen( $sub ) ) == $sub );
}

function startswith($Haystack, $Needle){
    // Recommended version, using strpos
    return strpos($Haystack, $Needle) === 0;
}


function cancel() {
$mainframe = JFactory::getApplication(); 
   $mainframe->redirect( "index.php" );
}

  
function showConfig() {
  $warning = false;
  $database = JFactory::getDBO();
	$my =JFactory::getUser();
    if (checkAccess($database, 'core.admin' )) {
	$i = 0;
	$database->setQuery("SELECT * FROM #__joomla_flash_uploader where id > 0 ORDER BY id ");
     $rows = $database->loadObjectList();
	if (count($rows) > 0) {
	   // we check for rows where the gid does only appear once	   
        $database->setQuery("SELECT gid FROM #__joomla_flash_uploader where id > 1 group by gid having count(gid) = 1");
        $gids = $database->loadObjectList();
        $gids_array = array();
        foreach ($gids as $g) {
          $gids_array[] = $g->gid; 
        }
	   foreach ($rows as $row) {
	     if ($row->gid != '' && in_array($row->gid,$gids_array) ) {
	       $rows[$i]->resize_data = "<img alt='".JText::_('C_ONE_PROFILE_GROUP')."' title='".JText::_('C_ONE_PROFILE_GROUP')."' src='components/com_jfuploader/images/warning.png' style='vertical-align:middle;width:16px; margin-top:0px;' />&nbsp;"; 
	       $warning = true;
          } else {
            $rows[$i]->resize_data = "";
          }
          if ($row->id ==1) {
		        $rows[$i]->resize_label = JText::_('C_ADMINS_ONLY');
		     } else {	         
		        $database->setQuery("SELECT username FROM #__users u, #__joomla_flash_uploader_user f WHERE u.id = f.user AND  f.location='site' AND f.profile =" . $row->id ." order by username");
		        $users = $database->loadObjectList("username");
		        
		          $ret = array();
		         foreach ($users as $user) {
		             array_push($ret,$user->username); 
		         }    
		        
		        $database->setQuery("SELECT u.title as username FROM #__usergroups u, #__joomla_flash_uploader_user f WHERE u.id = f.jgroup AND  f.location='site' AND f.profile =" . $row->id ." order by title");
		        $groups = $database->loadObjectList("username");
		        
		         foreach ($groups as $user) {
		             array_push($ret,$user->username . " (G)"); 
		        }
		        
                  $users = array_merge($users, $groups);
		        $open_tag = "<a href=\"#edituser\" onclick=\"return listItemTask('cb$row->id','edituser')\">";
		        $rows[$i]->resize_label = $open_tag;  
		        
		        if (count($ret) == 0) {
		           if ($rows[$i]->gid != "") {
		             $rows[$i]->resize_label .= JText::_('C_DEFAULT_PROFILE') . '</a>';  
		           } else {
                       $rows[$i]->resize_label = JText::_('C_NO_GROUP');  
		           }
		        } else {
		            if ($row->gid == '') {
                       $rows[$i]->resize_label = "<img alt='".JText::_('C_NO_GROUP_USER')."' title='".JText::_('C_NO_GROUP_USER')."' src='components/com_jfuploader/images/warning.png' style='vertical-align:middle;width:16px; margin-top:0px;' /> " . $open_tag; 
                        $warning = true;
                      }
		            
		           $rows[$i]->resize_label .= implode (", ", $ret);
		           $rows[$i]->resize_label .= "</a>"; 
		        }
         }
	     $i++;
	     }     
	}
	
  $jfu_config= array();
  $jfu_config['keep_tables']= JFUHelper::getVariable($database, 'keep_tables');
  $jfu_config['use_js_include']= JFUHelper::getVariable($database, 'use_js_include');
  $jfu_config['backend_access_upload']= JFUHelper::getVariable($database, 'backend_access_upload');
  $jfu_config['backend_access_config']= JFUHelper::getVariable($database, 'backend_access_config');
  $jfu_config['version']= JFUHelper::getVariable($database, 'version');
  $jfu_config['file_chmod']= JFUHelper::getVariable($database, 'file_chmod');
  $jfu_config['dir_chmod']= JFUHelper::getVariable($database, 'dir_chmod');
  $jfu_config['enable_upload_debug']= JFUHelper::getVariable($database, 'enable_upload_debug');
  $jfu_config['sa_profil']= JFUHelper::getVariable($database, 'sa_profil');
  $jfu_config['a_profil']= JFUHelper::getVariable($database, 'a_profil');
  $jfu_config['m_profil']= JFUHelper::getVariable($database, 'm_profil');
  $jfu_config['enhanced_debug']= JFUHelper::getVariable($database, 'enhanced_debug');
  $jfu_config['check_image_magic']= JFUHelper::getVariable($database, 'check_image_magic');
  $jfu_config['idn_url']= JFUHelper::getVariable($database, 'idn_url');
  $jfu_config['use_index_for_files']= JFUHelper::getVariable($database, 'use_index_for_files');
      
  if ($warning) {  
    $jfu_config['warning']= "<br><div class='message'><img src='components/com_jfuploader/images/warning.png' /> ".JText::_('C_GROUP_WARNING')."</div>";
  } else {
    $jfu_config['warning']= '';
  }
      
  HTML_joomla_flash_uploader::listConfig($rows, $jfu_config);
  } else {
     HTML_joomla_flash_uploader::errorRights();
  }
}

function showConfigUser($uid, $showUserPage = false) {
  $database = JFactory::getDBO();
  $row = new joomla_flash_uploader($database);
  $row->load($uid);
  $a_user = getFreeUsers($database, $uid, $row->gid);
  $p_user = getAssingedUsers($database, $uid);
  $cim = JFUHelper::getVariable($database, 'check_image_magic');
  $f_group = JFUHelper::getAvailableGroups($uid);
  HTML_joomla_flash_uploader::showConfig($row, $a_user, $p_user, $f_group, $cim, $showUserPage);
}

function getAssingedUsers($database, $uid = '0') {
$database->setQuery('SELECT u.user, us.username FROM #__joomla_flash_uploader_user u, #__users us where u.user=us.id and  u.location=\'site\' AND u.profile='.$uid.' ORDER BY us.username');	
	  $users = $database->loadObjectList();
    $p_user = '';
     if (isset($users) && (count($users) > 0)) {
       foreach ($users as $user) { //  <li id="63">michi</li>
         $p_user .= '<li id="'.$user->user.'">' . $user->username . '</li>';
		   }
		 }
		 return $p_user;
}

function getFreeUsers($database, $uid='0', $gid='') {
	 if ($gid !='') {
       $profiles = 'select id from #__joomla_flash_uploader where gid=\'' . $gid . '\'';
     } else {
	  $profiles = $uid;
	}		 
	 // wir brauchen ein statement welches alle verf++gbaren benutzer einer Gruppe anzeigt!
   $database->setQuery('SELECT * FROM #__users u  where username NOT IN (SELECT us.username FROM #__joomla_flash_uploader_user u, #__users us where u.user=us.id AND u.location=\'site\' AND u.profile in ('.$profiles.')) order by username ');
	 $users = $database->loadObjectList();
   $a_user = '';
   if (isset($users) && (count($users) > 0)) {
     foreach ($users as $user) { //  <li id="63">michi</li>
        $a_user .= '<li id="'.$user->id.'">' . $user->username . '</li>';
		 }
	 }	 
	 return $a_user;
}




function showUser($id) {
$database = JFactory::getDBO();

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
  if (checkAccess($database, 'core.edit.state' ) && checkAccess($database, 'core.admin' )) {	
	
     // all mappings	
	$query = "SELECT u.id as myid, concat(config_name, ' (id:', f.id ,')') as config_name, us.username, ug.title, u.location, u.jgroup FROM #__joomla_flash_uploader f, #__joomla_flash_uploader_user u left outer join #__users us on u.user=us.id left outer join #__usergroups ug on u.jgroup=ug.id  where  u.profile=f.id ORDER BY u.location,u.profile,username,ug.title";
     $database->setQuery($query);	
	$rows = $database->loadObjectList();
	
	// check if mappings are still vaild and if not they are removed.
	foreach ($rows as $row) {
       if ($row->username == null && $row->title == null) {
          $database->setQuery( "DELETE FROM #__joomla_flash_uploader_user WHERE id IN ($row->myid)" );
          $database->query();  
       }
     }
	
	// reload
	$database->setQuery($query);	
	$rows = $database->loadObjectList();
     	
	  // now I create the dropdowns for users and for profiles!
	  $database->setQuery("SELECT * FROM #__users u order by username");
	  $users = $database->loadObjectList();
	  $num_users = count($users);
	  if ($num_users > 10) { $num_users = 10; }
	  $data['users'] = JHTML::_('select.genericlist', $users, 'user[]', 'size="'.$num_users.'" multiple="multiple" ', 'id', 'username', 0 );  
	       
       // $database->setQuery("SELECT * FROM #__joomla_flash_uploader WHERE id != 1 AND gid!='' ");
       $database->setQuery("SELECT id, concat(config_name, ' (id:', id ,')') as config_name, gid FROM #__joomla_flash_uploader WHERE id != 1 AND gid!='' ");
	 
	  $profiles = $database->loadObjectList();	  
	  $last_profile = 0;
	  if (isset($_SESSION['LAST_PROFILE'])) {
	    $last_profile = $_SESSION['LAST_PROFILE'];
	  }	  
	  if (count($profiles) != 0) {
	    $data['profiles'] = JHTML::_('select.genericlist', $profiles, 'profile', 'size="1"', 'id', 'config_name', $last_profile) . '<div style="clear:both"></div><p>'.JText::_('U_AVAILABLE_LIST').'.</p>';
	    $data['profilesgroup'] = JHTML::_('select.genericlist', $profiles, 'profilegroup', 'size="1"', 'id', 'config_name', $last_profile) . '<div style="clear:both"></div><p>'.JText::_('U_AVAILABLE_LIST').'.</p>';
         $data['showAdd'] = true;
	  } else {
         $data['profiles'] = $data['profilesgroup'] = '<div class="message fade">'.JText::_('U_NO_PROFILE').'.</div>';
         $data['showAdd'] = false;
       }
       
       $database->setQuery("SELECT id, concat(config_name, ' (id:', id ,')') as config_name, gid FROM #__joomla_flash_uploader");
	  $profiles = $database->loadObjectList();	  
	  $data['allprofiles'] = JHTML::_('select.genericlist', $profiles, 'allprofile', 'size="1"', 'id', 'config_name', $last_profile) . '<div style="clear:both"></div><p>'.JText::_('U_AVAILABLE_LIST_ALL_PROFILES').'.</p>';
 
       $data['groups'] = JFUHelper::getUserGroups();        
       $data['backendgroups'] = JFUHelper::getBackendGroups($rows, $data['groups']); 
       $data['showAddAdmin'] = count($data['backendgroups']) > 0;     
              
	  HTML_joomla_flash_uploader::listUsers($rows, $data);
	  } else {
         HTML_joomla_flash_uploader::errorRights();
      }
}

function saveConfig() {
$mainframe = JFactory::getApplication(); 
$database = JFactory::getDBO();
$row = new joomla_flash_uploader($database);
// if magic quotes is on we remove slashes forst because store does quote automatically!
if(get_magic_quotes_gpc())
{
  $row->bind(array_map("stripslashes",$_POST));
} else {
  $row->bind($_POST);
}
$row->last_modified_date=date("Y-m-d");
$row->store();

// now we update the users
$userstring = JRequest::getVar('list_2_sent','');
$userchanged = JRequest::getVar('list_2_changed','');



  // first we remove all user mappings and then we insert all the new ones.
  $database->setQuery( "DELETE FROM #__joomla_flash_uploader_user WHERE location='site' and profile = ($row->id)" );
  $database->query(); 
  
  if ($userstring != '') {
    $userstringarray = explode (",", trim($userstring, " ,"));
    foreach ($userstringarray AS $singleuser) {
       $rowuser = new joomla_flash_uploader_user($database);
       $rowuser->profile = $row->id;
       $rowuser->user = trim($singleuser);  
       $rowuser->location = 'site';
       $rowuser->store();
    }
  }
  addUserGroup('site','jgroupfront','id', false);
  
cleanMessageQueue();
unset($_SESSION['IM_CHECK']);
$mainframe->redirect("index.php?option=com_jfuploader&act=config", JText::_('MES_SAVED'));
}

function addUser() {
$mainframe = JFactory::getApplication(); 
$database = JFactory::getDBO();
$error_num = 0;
cleanMessageQueue();
if (!isset($_POST['user']) || !isset($_POST['profile'])) {
  $mainframe->redirect("index.php?option=com_jfuploader&act=user", JText::_('MES_MAP_NOSEL'));
}

foreach ($_POST['user'] AS $singleuser) {
   $database->setQuery('SELECT * FROM #__joomla_flash_uploader_user u where u.user='.$singleuser.' and u.profile='.$_POST['profile']);	
   if (count ($database->loadObjectList()) == 0) {
     $row = new joomla_flash_uploader_user($database);
     $row->profile = $_POST['profile'];
     $row->user = $singleuser;  
     $row->location = 'site';
     if (!$row->store()) {
       $error_num++;
     }
   } else {
     $error_num++;
   }
}
// $row->bind($_POST);

$_SESSION['LAST_PROFILE'] = $row->profile;
if ($error_num > 0) {
  $mainframe->redirect("index.php?option=com_jfuploader&act=user", $error_num . JText::_('MES_EXISTS'));
} else {
  $mainframe->redirect("index.php?option=com_jfuploader&act=user", JText::_('MES_MAP_SAVED'));
}
}

function addUserGroup($location, $type, $profile, $redirect = true) {
$mainframe = JFactory::getApplication(); 
$database = JFactory::getDBO();
$error_num = 0;
$count = 0;
cleanMessageQueue();
if (!isset($_POST[$type]) || !isset($_POST[$profile])) {
  if ($redirect) {
    $mainframe->redirect("index.php?option=com_jfuploader&act=user", JText::_('MES_MAP_NOSEL'));
  } else {
    return;
  }
}

foreach ($_POST[$type] AS $jgroup) {
   $count++;
   $database->setQuery('SELECT * FROM #__joomla_flash_uploader_user u where u.jgroup='.$jgroup.' and u.profile='.$_POST[$profile]);	
   if (count ($database->loadObjectList()) == 0) {
     $row = new joomla_flash_uploader_user($database);
     $row->profile = $_POST[$profile];
     $row->jgroup = $jgroup;  
     $row->location = $location;
     if (!$row->store()) {
       $error_num++;
     }
   } else {
     $error_num++;
   }
} 

// $row->bind($_POST);

$_SESSION['LAST_PROFILE'] = $row->profile;

if ($redirect) {
     if ($error_num > 0) {
       $mainframe->redirect("index.php?option=com_jfuploader&act=user", $error_num . JText::_('MES_EXISTS'));
     } else {
       $mainframe->redirect("index.php?option=com_jfuploader&act=user", JText::_('MES_MAP_SAVED'));
     }
  }
}

function deleteUser($cid) {
$mainframe = JFactory::getApplication(); 
   $database = JFactory::getDBO();
   $cids = implode( ',', $cid );
   $database->setQuery( "DELETE FROM #__joomla_flash_uploader_user WHERE id IN ($cids)" );
   $database->query(); 
   cleanMessageQueue();
   $mainframe->redirect( "index.php?option=com_jfuploader&act=user", JText::_('MES_MAP_REM'));
} 

function createHtaccess() {
global $mybasedir;
$mainframe = JFactory::getApplication(); 
  $filename = dirname(__FILE__) . "/".$mybasedir."tfu/.htaccess";
  $file = fopen($filename, 'w');
  fputs($file, "SecFilterEngine Off\nSecFilterScanPOST Off");
  fclose($file);
  cleanMessageQueue();
  if (file_exists($filename)) {
     $mainframe->redirect( "index.php?option=com_jfuploader&act=upload", JText::_('MES_HTACCESS_CREATED') ); 
  }  else {
     $mainframe->redirect( "index.php?option=com_jfuploader&act=upload", JText::_('MES_HTACCESS_NOT_CREATED') );
  }
}

function deleteHtaccess() {
global $mybasedir;
$mainframe = JFactory::getApplication(); 
  $file = dirname(__FILE__) . "/".$mybasedir."tfu/.htaccess";
  @unlink($file);
  cleanMessageQueue();
  $mainframe->redirect( "index.php?option=com_jfuploader&act=upload", JText::_('MES_HTACCESS_DELETED') );
}

function deleteLog() {
global $mybasedir;
$mainframe = JFactory::getApplication(); 
  $file = dirname(__FILE__) . "/".$mybasedir."tfu/tfu.log";
  @unlink($file);
  cleanMessageQueue();
  $mainframe->redirect( "index.php?option=com_jfuploader&act=help", JText::_('MES_LOG_DELETED') );
}

function showHelpRegister() {
$database = JFactory::getDBO();
$my =JFactory::getUser();
  if (checkAccess($database,'core.admin') || checkAccess($database,'core.manage')) {
    HTML_joomla_flash_uploader::showHelpRegister();
  } else {
    HTML_joomla_flash_uploader::errorRights();
  }
}

function deleteLicense() {
 global $mybasedir;
 $mainframe = JFactory::getApplication(); 
  $file = dirname(__FILE__) . "/".$mybasedir."tfu/twg.lic.php";
  @unlink($file);
  cleanMessageQueue();
  $mainframe->redirect( "index.php?option=com_jfuploader&act=help", JText::_('MES_LICENSE_DELETED') );
}

function chmod_tfu($mode) {
  global $mybasedir;
  $mainframe = JFactory::getApplication(); 
  $database = JFactory::getDBO();
  $my =JFactory::getUser();
  if (checkAccess($database,'core.admin')) {
    chmod(dirname(__FILE__) . "/".$mybasedir."tfu/tfu_config.php",$mode);
    chmod(dirname(__FILE__) . "/".$mybasedir."tfu/tfu_login.php", $mode);
    chmod(dirname(__FILE__) . "/".$mybasedir."tfu/tfu_file.php",  $mode);
    chmod(dirname(__FILE__) . "/".$mybasedir."tfu/tfu_upload.php",$mode);
    cleanMessageQueue();
    $mainframe->redirect( "index.php?option=com_jfuploader&act=help", JText::_('H_L_CHMOD_MES') );
  } else {
    HTML_joomla_flash_uploader::errorRights();
  }  
}

function register() {
global $mybasedir;
$mainframe = JFactory::getApplication(); 
 $l =  trim(JRequest::getVar( 'l','' ));
 $d =  trim(JRequest::getVar( 'd','' ));
 $s =  trim(JRequest::getVar( 's','' ));
 
  // we remove invalid input
  $l = str_replace('$l="','',$l );
  $d = str_replace('$d="','',$d );
  $s = str_replace('$s="','',$s );
  $l = str_replace('";','',$l );
  $d = str_replace('";','',$d );
  $s = str_replace('";','',$s );
  $l = str_replace('"','',$l );
  $d = str_replace('"','',$d );
  $s = str_replace('"','',$s );
 
  $filename = dirname(__FILE__) . "/".$mybasedir."tfu/twg.lic.php";
  $file = fopen($filename, 'w');
  fputs($file, "<?php\n");
  fputs($file, "\$l=\"".$l."\";\n");
  fputs($file, "\$d=\"".$d."\";\n");
  fputs($file, "\$s=\"".$s."\";\n");
  fputs($file, "?>");	
  fclose($file);
  
  if (!file_exists($filename)) {
      $text = JText::_('MES_LICENSE_NOT_CREATED');
  } else {
      // we now check if the file can be renamed.
      $m = is_renameable();
      if ($m == "s" || $m =="w" ) {
        $text = JText::_('MES_LICENSE_WRONG');
        @unlink($filename);
      } else {
        $text = JText::_('MES_LICENSE_OK');
      } 
  } 
  cleanMessageQueue();
  $mainframe->redirect( "index.php?option=com_jfuploader&act=help", $text );
}

// I delete the message queue because it seems to be buggy in some versions !
function cleanMessageQueue() {
  $session =JFactory::getSession();
  $sessionQueue = $session->get('application.queue');
  if (count($sessionQueue)) {
    $session->set('application.queue', null);
  }
}

/* This function moves the tfu directory from the frontend to the backend or the other ways around. */
function jfu_move_tfu_dir() {
global $mybasedir, $otherdir;
$mainframe = JFactory::getApplication(); 
  clearstatcache();
  $filename = str_replace("//","/",dirname(__FILE__) . "/".$mybasedir."/tfu");
  $otherfilename =  str_replace("//","/",dirname(__FILE__) . "/".$otherdir."/tfu");
  if (rename($filename, $otherfilename )) {
     clearstatcache();
     $mainframe->redirect( 'index.php?option=com_jfuploader&act=config', JText::_('MES_TFU_MOVED') ); 
  }  else {
     $mainframe->redirect( 'index.php?option=com_jfuploader&act=config', JText::_('MES_TFU_NOT_MOVED') );
  }
  
}

function showPlugins() {
	$database = JFactory::getDBO();
	$my =JFactory::getUser();
    if (checkAccess($database,'core.admin')) {    
     $plugins = array();
     $show_hint = false;
     foreach (glob(dirname(__FILE__) . '/tfu/*_plugin.php') as $filename) {
       $name = 'Not set';
       $description = 'Not set';
       $version_plugin = 'Not set';
       $version_tfu = 'Not set';
              
       $content = file_get_contents($filename);
       $hits = preg_match('/(Name:)([^\n]*)(\n)/i',$content, $treffer);
       if ($hits != 0) {
         $name = trim($treffer[2]);
       } 
        $hits = preg_match('/(Description:)([^\n]*)(\n)/i',$content, $treffer);
       if ($hits != 0) {
         $description = trim($treffer[2]);
       } 
       $hits = preg_match('/(Version Plugin:)([^\n]*)(\n)/i',$content, $treffer);
       if ($hits != 0) {
         $version_plugin = trim($treffer[2]);
       }  
        $hits = preg_match('/(Needed flash version:)([^\n]*)(\n)/i',$content, $treffer);
       if ($hits != 0) {
         $version_tfu = trim($treffer[2]);    
         if (version_compare ($version_tfu,JFUHelper::getVariable($database, 'version')) == 1) {
           $version_tfu = 'style="color: #ff0000;"'; 
           $show_hint = true;      
         } else {
           $version_tfu = '';  
         }
       }  
            
       $plugins[] = array(basename($filename), htmlentities ($name), htmlentities ($description), $version_plugin, $version_tfu);
     }
     
     $available = array("a_plugin","move_plugin"); 
      HTML_joomla_flash_uploader::showPlugins($plugins, $show_hint);
	 } else {
      HTML_joomla_flash_uploader::errorRights();
  }
}



?>