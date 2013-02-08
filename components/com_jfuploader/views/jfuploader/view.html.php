<?php
/**
 * JFUploader 2.15.x Freeware - for Joomla 1.6.x
 *
 * HTML View class for the JFUploader Component
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
if (!defined('_VALID_TWG')) {
    define('_VALID_TWG', '42');
}
// import Joomla view library
jimport('joomla.application.component.view');
jimport('joomla.html.parameter');

class JFUploaderViewJFUploader extends JView {
    // Overwriting JView display method
    function display($tpl = null) {
        global $Itemid, $m, $prefix_dir_path, $prefix_path;

        require_once(JApplicationHelper::getPath('class'));
        require_once(JApplicationHelper::getPath('front_html'));

        $public_profile = false;
        $user =& JFactory::getUser();
        $type = JRequest::getVar('type');
        $editor_plugin = ($type == 'jfuploader_editor');
        $block_type = 'span';

        // Parameters
        $id = JRequest::getString('tfu_id');
        $sel_id = JRequest::getString('tfu_sel_id');
        $jfu_params = array();

        // we check if we where called by the editor plugin
        if ($editor_plugin) {
            $error_text = "<center>Username and security token do not match. Most likely your session or the timestamp (30 min) has expired or the request was modified. If this happens all the time please get help at <a href=\"http://jfu.tinywebgallery.com\" target=\"_blank\">jfu.tinywebgallery.com.</center>";
            $plugin =& JPluginHelper::getPlugin('editors-xtd', 'jfuploader_editor');
            $pluginParams = new JParameter($plugin->params);
            $id = $pluginParams->get('tfu_id', 'not set');
            $sel_id = $pluginParams->get('tfu_sel_id', 'not set');
            $block_type = $pluginParams->get('block_type', 'span');
            $jfu_params['tfu_show_resize'] = $pluginParams->get('tfu_show_resize', 1);
            $jfu_params['tfu_enable_resize'] = $pluginParams->get('tfu_enable_resize', 1);
            $jfu_params['tfu_insert_resize'] = $pluginParams->get('tfu_insert_resize', 1);
            $jfu_params['tfu_show_border'] = $pluginParams->get('tfu_show_border', 1);
            $jfu_params['tfu_show_alignment'] = $pluginParams->get('tfu_show_alignment', 1);
            $jfu_params['tfu_show_spacing'] = $pluginParams->get('tfu_show_spacing', 1);
            $jfu_params['tfu_show_thumbnail_create'] = $pluginParams->get('tfu_show_thumbnail_create', 1);
            $jfu_params['tfu_show_help'] = $pluginParams->get('tfu_show_help', 1);
            $jfu_params['tfu_show_image_extra'] = $pluginParams->get('tfu_show_image_extra', 1);
            $jfu_params['tfu_show_caption'] = $pluginParams->get('tfu_show_caption', 1);
            $jfu_params['tfu_show_ruler'] = $pluginParams->get('tfu_show_ruler', 1);
            $jfu_params['tfu_show_google_doc'] = $pluginParams->get('tfu_show_google_doc', 1);
            $jfu_params['e_name'] = JRequest::getVar('e_name', 'text');
            $param_id = JRequest::getVar('myid', 'not set');
            if ($param_id != 'not set') {
                // the token has to be validated again the database with the current user name!
                $my_first_daughters_name = "Anna";
                $mytoken = JRequest::getVar('mytoken', 'not set');
                $ts = JRequest::getVar('ts', time()-100);
                // time is valid for only 30 m
                if ((time() - $ts) > 1800) {
                    die($error_text);
                }
                $jConfig = new JConfig();
                $secret = $jConfig->secret;
                $usertoken = md5($param_id . $my_first_daughters_name . $secret . $ts);
                if ($usertoken == $mytoken) {
                    $user =& JFactory::getUser($param_id);
                } else {
                    die($error_text);
                }
            } else {
                // check if public access is allowed
                $public_profiles = $pluginParams->get('tfu_public_profiles', '0');  
                if ($sel_id == '0' && $public_profiles == '1') {
                    $public_profile = true;
                }
            }
            if (!$public_profile && !$user->id) {
                die ($error_text);
            }
        }

        $skip_error_handling = "true"; // avoids that the jfu logfile is used for everything!
        $debug_file = '';

        @ob_start();
        if (file_exists('components/com_jfuploader/tfu/tfu_helper.php')) { // frontend!
            $prefix_path = '';
            $prefix_dir_path = '';
            include_once('components/com_jfuploader/tfu/tfu_helper.php');
        } else {
            $prefix_path = 'administrator/';
            $prefix_dir_path = '../';
            include_once($prefix_path . 'components/com_jfuploader/tfu/tfu_helper.php');
        }
        @ob_end_clean();

        JFUHelper::printCss();
        // we include the js
        echo '<script type="text/javascript" src="components/com_jfuploader/jfuploader.js"></script>';

        if ($id == 'not set' || $sel_id == 'not set') {
            HTML_joomla_flash_uploader::wrongId('ERR_ID_NO_XTD');
            return;
        }

        // The administrator profile was selected and because of security issue it is not allowed to use this profile in the frontend. If you really like to use a profile that has access to the full installation please create a new profile and set the folder like in the administration profile.
        if ($user) {
            $_SESSION["TFU_USER"] = $user->username;
        }

        echo '<!-- JFU sel_id: \'' . $sel_id . '\' id: \'' . $id . '\' -->';

        if (($sel_id == '0' && $id == '1') || $sel_id == '' || $id == '') { // admin profile or no id!
            HTML_joomla_flash_uploader::wrongId($id);
        } else {
            $myId = JFUHelper::getProfileId($sel_id, $id, $user);
            if ($myId > 1) { // admin profile  is not allowed in the frontend
                JFUploaderViewJFUploader::showFlashComponent($myId, $user, $editor_plugin, $block_type, $jfu_params);
            } else {
                HTML_joomla_flash_uploader::wrongId($myId);
            }
        }

        // we remove the JFU error handler
        if ($old_error_handler) {
            set_error_handler($old_error_handler);
        } else { // no other error handler set
            set_error_handler('on_error_no_output');
        }
    }

    function showFlashComponent($id, $user, $editor_plugin, $block_type, $jfu_params) {
        global $prefix_dir_path, $prefix_path;
        $database = &JFactory::getDBO();
        $row = new joomla_flash_uploader($database);
        $row->load($id);
        if (!$row->resize_show) { // no profile found or no id!
            HTML_joomla_flash_uploader::wrongId($id);
        } else {
            $uploadfolder = $row->folder;
            $uploadfolder_base = $uploadfolder;
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
                            $result = ftp_mkdir($conn_id, $ftp_createdir);
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
                    HTML_joomla_flash_uploader::noUser($id);
                    return;
                }
            }
            // we go back to the main folder! path has to be relativ to the tfu upload folder!
            if ($uploadfolder == "") {
                $folder = './' . $prefix_dir_path . '../../..';
            } else {
                $folder = './' . $prefix_dir_path . '../../../' . $uploadfolder;
            }
            JFUHelper::setJFUSession($row, $folder, $database);
            unset($_SESSION["IS_ADMIN"]);
            $_SESSION["IS_FRONTEND"] = "TRUE";
            if ($user->id != 0) {
                $_SESSION["TFU_USER"] = $user->name;
                $_SESSION["TFU_USER_ID"] = $user->id;
                $_SESSION["TFU_USER_NAME"] = $user->username;
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
      
            if (!$editor_plugin) {
                JFUHelper::fixSession();
                store_temp_session();
                HTML_joomla_flash_uploader::showFlash($row, $uploadfolder, $use_js_include, $jfu_config,  false);
            } else {
                $_SESSION['TFU']['IS_JFU_PLUGIN'] = true;
                JFUHelper::fixSession();
                store_temp_session();
                // I have to set the javascript setting to update the data!
                $flash = HTML_joomla_flash_uploader::showFlash($row, $uploadfolder, $use_js_include, $jfu_config, true);
                echo '
         <style type="text/css">
           body.contentpane { background-color:#ffffff; margin-top:15px; margin-left:15px; margin-bottom:0px; overflow-y: hidden; overflow-x: hidden; line-height: 1.4;} 
           #main { padding: 0px;} 
         </style>';
                echo $flash;
                $base_path = JURI::base();
                HTML_joomla_flash_uploader::showImageSelector($base_path, $block_type, $jfu_params);
            }
        }
    }
}

?>