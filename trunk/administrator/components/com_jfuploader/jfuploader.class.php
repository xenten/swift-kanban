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

class joomla_flash_uploader extends JTable {
    var $id = null;
    var $gid = null;
    var $config_name = 'New';
    var $description = '';
    var $folder = '';
    var $text_title = 'Titel';
    var $text_top = 'Text before flash';
    var $text_bottom = 'Text after flash';
    var $text_title_lang = 'false';
    var $text_top_lang = 'false';
    var $text_bottom_lang = 'false';
    var $maxfilesize = '';
    var $resize_show = 'true';
    var $resize_data = '10000,1024';
    var $resize_label = 'Original,1024';
    var $resize_default = '0';
    var $allowed_file_extensions = 'jpg,gif,png';
    var $forbidden_file_extensions = 'none';
    var $hide_remote_view = 'false';
    var $show_delete = 'true';
    var $enable_folder_browsing = 'true';
    var $enable_folder_creation = 'true';
    var $enable_folder_deletion = 'true';
    var $enable_folder_rename = 'true';
    var $enable_file_rename = 'true';
    var $keep_file_extension = 'true';
    var $enable_file_download = 'true';
    var $sort_files_by_date = 'false';
    var $warning_setting = 'all';
    var $show_size = 'false';
    var $enable_setting = 'true';
    var $creation_date = null;
    var $last_modified_date = null;
    var $fix_overlay = 'false';
    var $flash_title = 'JFUploader';
    var $hide_directory_in_title = 'false';
    var $swf_text = null;
    var $split_extension = null;

    var $upload_notification_email = '';
    var $upload_notification_email_from = '';
    var $upload_notification_email_subject = 'A file was uploaded by the JFUploader';
    var $upload_notification_email_text = 'A file was uploaded by the JFUploader';
    var $upload_finished_js_url = '';
    var $preview_select_js_url = '';
    var $delete_js_url = '';
    var $js_change_folder = '';
    var $directory_file_limit = '100000';
    var $queue_file_limit = '100000';
    var $queue_file_limit_size = '100000';
    var $display_width = '650';
    var $enable_folder_movecopy = 'false';
    var $enable_file_movecopy = 'false';
    var $preview_textfile_extensions = 'txt,log';
    var $edit_textfile_extensions = '';
    var $js_create_folder= '';
    var $js_rename_folder = '';
    var $js_delete_folder = '';
    var $js_copymove = '';
    // new 2.8
    var $language_dropdown = 'de,en,es,br,cn,ct,da,fr,it,jp,nl,no,pl,pt,ru,se,sk,tw';
    var $use_image_magic = 'false';
    var $image_magic_path = 'convert';
    var $exclude_directories = 'data.pxp,_vti_cnf,.svn,CVS,thumbs';
    var $normalise_file_names  = 'true';
    var $download_multiple_files_as_zip = 'false';
    var $allowed_view_file_extensions  = 'all';
    var $forbidden_view_file_extensions  = '';
    var $description_mode = 'false';
    var $description_mode_show_default = 'true';
    var $description_mode_store  = 'email';
    var $master_profile  = 'false';
    var $master_profile_mode  = 'login';
    var $master_profile_lowercase = 'true';
    // new 2.8.3
    var $normalise_directory_names = 'false';
    var $direct_download = 'false';
    var $fix_utf8='';
    // new 2.9
    var $overwrite_files = 'false';
    var $description_mode_mandatory = 'false';
    var $show_full_url_for_selected_file = 'false';
    var $normalize_spaces = 'false';
    // new 2.10.4
    var $upload_notification_use_full_path = 'false';
    // new 2.10.6
    var $hide_hidden_files = 'false';
    var $truncate_dir_in_title = 'false';

    // new 2.11
    var $form_fields = '';
    var $big_progressbar = 'true';
    var $img_progressbar = 'progressbar.png';
    var $img_progressbar_back = 'progressbar_back.png';
    var $img_progressbar_anim = 'progressbar_anim.swf';
    var $enable_dir_create_detection = 'true';
    var $ftp_enable = 'false';
    var $ftp_host = 'host';
    var $ftp_port = 21;
    var $ftp_user = 'user';
    var $ftp_pass = 'pass';
    var $ftp_root = 'full root directory';
    var $big_server_view = 'false';
    // new 2.12
    var $compression= 80;
    var $remove_multiple_php_extension= 'true';
    var $scan_images= 'true';
    // new 2.12.1
    var $forbidden_view_file_filter= '';
    var $zip_file_pattern= 'download-{number}-files_{date}.zip';
    // new 2.13
    var $info_text= '{dimension} | {size} | {date}';
    var $info_textcolor_R=255;
    var $info_textcolor_G=60;
    var $info_textcolor_B=60;
    var $info_font='verdana.ttf';
    var $info_fontsize=8;
    // new 2.14
    var $directory_file_limit_size = -1;
    var $directory_file_limit_size_system = 'true';
    var $sort_directores_by_date = 'false';
    var $show_server_date_instead_size = 'false';
    var $enable_file_creation = 'false';
    var $enable_file_creation_extensions = 'txt';
    var $zip_folder = '';
    // new 2.15
    var $switch_sides = 'false';
    var $date_format = 'd.m.y';

    function __construct(&$db)
    {
        parent::__construct('#__joomla_flash_uploader', 'id', $db);
    }
}
class joomla_flash_uploader_user extends JTable {
    var $id = null;
    var $profile = null;
    var $user = null;
    var $jgroup = null;
    var $location = null;

    function __construct(&$db)
    {
        parent::__construct('#__joomla_flash_uploader_user', 'id', $db);
    }
}

class tfuHTML extends JHTML {

    function truefalseRadioList( $tag_name, $tag_attribs, $selected, $yes = false, $no = false) {
        if (!$yes) {            $yes = JText::_('C_W_YES');        }
        if (!$no)  {            $no =  JText::_('C_W_NO');        }
        $arr[] = JHTML::_('select.option', 'true', $yes );
        $arr[] = JHTML::_('select.option', 'false', $no );
        return JHTML::_('select.radiolist', $arr, $tag_name, $tag_attribs,'value', 'text',$selected);
    }
    function modeRadioList( $tag_name, $tag_attribs, $selected) {
        $arr[] = JHTML::_('select.option', 'email', JText::_('C_W_EMAIL') );
        $arr[] = JHTML::_('select.option', 'txt',  JText::_('C_W_TEXT'));
        return JHTML::_('select.radiolist', $arr, $tag_name, $tag_attribs,'value', 'text',$selected);
    }
    function selectModeRadioList( $tag_name, $tag_attribs, $selected) {
        $arr[] = JHTML::_('select.option', 'true', JText::_('C_W_INDEX') );
        $arr[] = JHTML::_('select.option', 'false',  JText::_('C_W_FILENAME'));
        return JHTML::_('select.radiolist', $arr, $tag_name, $tag_attribs,'value', 'text',$selected);
    }
    function mastermodeRadioList( $tag_name, $tag_attribs, $selected) {
        $arr[] = JHTML::_('select.option', 'id', JText::_('C_W_ID') );
        $arr[] = JHTML::_('select.option', 'login',  JText::_('C_W_LOGIN') );
        $arr[] = JHTML::_('select.option', 'name',  JText::_('C_W_NAME') );
        $arr[] = JHTML::_('select.option', 'ip',  JText::_('C_W_IP') );
        $arr[] = JHTML::_('select.option', 'group',  JText::_('C_W_GROUP') );
        return JHTML::_('select.radiolist', $arr, $tag_name, $tag_attribs,'value', 'text',$selected);
    }

    function warningRadioList( $tag_name, $tag_attribs, $selected) {
        $arr = array(
                   JHTML::_('select.option', 'all', JText::_('C_W_ALL') ),
                   JHTML::_('select.option', 'once', JText::_('C_W_ONCE')),
                   JHTML::_('select.option', 'none', JText::_('C_W_NONE')),
               );
        return JHTML::_('select.radiolist', $arr, $tag_name, $tag_attribs,'value', 'text', $selected );
    }

    function downloadRadioList( $tag_name, $tag_attribs, $selected) {
        $arr = array(
                   JHTML::_('select.option', 'true', JText::_('C_W_YES') ),
                   JHTML::_('select.option', 'false', JText::_('C_W_NO') ),
                   JHTML::_('select.option', 'button1', JText::_('C_Button_1')),
                   JHTML::_('select.option', 'button',  JText::_('C_Button_2')),
               );
        return JHTML::_('select.radiolist', $arr, $tag_name, $tag_attribs,'value', 'text', $selected );
    }

    function showSizeRadioList( $tag_name, $tag_attribs, $selected) {
        $arr = array(
                   JHTML::_('select.option', 'true', JText::_('C_W_YES') ),
                   JHTML::_('select.option', 'false', JText::_('C_W_NO') )
               );
        if (!$selected) { // needed for backward compability for JFU 2.5.x
            $selected = "false";
        }
        return JHTML::_('select.radiolist', $arr, $tag_name, $tag_attribs,'value', 'text', $selected );
    }


    function showFileCreateSelectBox( $tag_name, $tag_attribs, $selected) {
        $arr = array(
                   JHTML::_('select.option', 'txt', JText::_('C_W_TXT') ),
                   JHTML::_('select.option', 'edit', JText::_('C_W_EDIT') ),
                   JHTML::_('select.option', 'all', JText::_('C_W_ALL') )
               );
        return JHTML::_('Select.genericlist', $arr, $tag_name, $tag_attribs,'value', 'text', $selected );
    }

    function showProfileList( $tag_name, $tag_attribs, $selected) {
        $database = JFactory::getDBO();
        $database->setQuery("SELECT id, config_name FROM #__joomla_flash_uploader order by id");
        $profiles = $database->loadObjectList();
        $arr = array();
        foreach ($profiles as $profile) {
            $arr[] = JHTML::_('select.option', $profile->id, $profile->id . " - " . substr($profile->config_name,0,20));
        }
        return JHTML::_('Select.genericlist', $arr, $tag_name, $tag_attribs,'value', 'text', $selected );
    }

    function showTockenList( $tag_name, $tag_attribs, $selected) {
        $jConfig = new JConfig();
        $database = JFactory::getDBO();
        $arr = array();

        $database->setQuery("SELECT distinct gid, gid FROM #__joomla_flash_uploader where gid != '' order by id");
        $groups = $database->loadObjectList();
        foreach ($groups as $group) {
            $arr[] = JHTML::_('select.option',  '1' . $jConfig->secret .'_' . $group->gid, ' Group: ' . $group->gid);
        }
        $database->setQuery("SELECT id, config_name FROM #__joomla_flash_uploader where id > 1 order by id");
        $profiles = $database->loadObjectList();
        foreach ($profiles as $profile) {
            $arr[] = JHTML::_('select.option',  '0' . $jConfig->secret . '_' . $profile->id, ' Profile: ' . $profile->id . " - " . substr($profile->config_name,0,20));
        }
        return JHTML::_('Select.genericlist', $arr, $tag_name, $tag_attribs,'value', 'text', $selected );
    }


}

class JFULanguage {
    function mapLangJoomlatoTFU($joomla) {
        $lang_arr = array (
                        "en-GB" => "en",
                        "en-EN" => "en",
                        "en-US" => "en",
                        "de-DE" => "de",
                        "de-AT" => "de",
                        "de-CH" => "de",
                        "es-ES" => "es",
                        "nl-NL" => "nl",
                        "fr-FR" => "fr",
                        "it-IT" => "it",
                        "no-NO" => "no",
                        "pt-PT" => "pt",
                        "pt-BR" => "br",
                        "zh-TW" => "tw",
                        "zh-CN" => "cn",
                        "da-DK" => "da",
                        "pl-PL" => "pl",
                        "sk-SK" => "sk",
                        "ja-JP" => "jp",
                        "sv-SE" => "se",
                        "ru-RU" => "ru",
                        "bg-BG" => "bg",
                        "ro-RO" => "ro",
                        "hu-HU" => "hu",
                        "lt-LT" => "lt",
                        "el-GR" => "el",
                        "sr-RS" => "rs",
                        "sr-YU" => "rs", 
                        // the following are only if someone provide the proper xml ;).
                        "tr-TR" => "tr",
                        "fi-FI" => "fi",
                        "cs-CZ" => "cz");
        if (isset($lang_arr[$joomla])) { // check if lang exists
            return $lang_arr[$joomla];
        } else {
            return "en"; // default language if an unknow lang was choosen
        }
    }

    function getLanguage($id, $text, $prefix, $nr) {
        if ($id == "true") {
            $v = "JFU_" . $prefix . "_" . $nr;
            if (JText::_($v) != $v) {
                return JText::_($v);
            } else {
                return "Value ".$v." is not set.";
            }
        } else {
            return $text;
        }
    }
}

class JFUHelper {
    function setJFUSession($row,$folder, $database) {
        // new 2.11.x - I will move all tfu settings to one session list!
        $tfu_param = array();
        // we put ALL parameters to the session that it is available in TFU

        foreach ( get_object_vars($row) as $key => $val  ) {
            $tfu_param[strtoupper($key)] = $val;
        }
        $_SESSION["TFU"] = $tfu_param;

        $_SESSION['TFU']["TFU_FOLDER"] = $folder;

        $_SESSION['TFU']["FIX_UTF8"] = trim($row->fix_utf8);
        $_SESSION['TFU']["NOT_EMAIL"]	= $row->upload_notification_email;
        $_SESSION['TFU']["NOT_EMAIL_FROM"]	= $row->upload_notification_email_from;
        $_SESSION['TFU']["NOT_EMAIL_SUBJECT"]	= $row->upload_notification_email_subject;
        $_SESSION['TFU']["NOT_EMAIL_TEXT"]	= $row->upload_notification_email_text;
        $_SESSION['TFU']["ENABLE_SETTING"]	= $row->enable_setting;

        // Global settings!
        $_SESSION['TFU']["FILE_CHMOD"] = JFUHelper::getVariable($database, 'file_chmod');
        $_SESSION['TFU']["DIR_CHMOD"] = JFUHelper::getVariable($database, 'dir_chmod');
        $_SESSION['TFU']["ENABLE_UPLOAD_DEBUG"] = JFUHelper::getVariable($database, 'enable_upload_debug');
        $_SESSION['TFU']["ENHANCED_DEBUG"] = JFUHelper::getVariable($database, 'enhanced_debug');
        $_SESSION['TFU']["CHECK_IMAGE_MAGIC"] = JFUHelper::getVariable($database, 'check_image_magic');
    }

    function getProfileId($sel_id, $id, $my) {
        $database = JFactory::getDBO();
        if ($sel_id == 1) { // we have to find the right profile!
            // we check if we have a user with a profile mapping
            $uid = $my->id;

            $database->setQuery("SELECT j.id FROM #__joomla_flash_uploader j, #__joomla_flash_uploader_user u WHERE j.gid='" . $id . "' AND u.profile=j.id AND  u.location='site' AND u.user=" . $uid . " and u.jgroup IS NULL order by j.id");
            $userprofile = $database->loadObjectList();

            if (count($userprofile) > 0) {
                // found
                $id = $userprofile[0]->id;
            } else {
                $group_match = false;
                $user_groups = $my->groups;

                // we check if we have groups at all and serach for a match
                if (count($user_groups) > 0) {
                    $gr_list = implode ("' ,'", $user_groups );

                    // now we have to look if we have a user group match. I select the one with the smallest id.
                    // if we have more matches - the profile with the lowest user profile id is picked
                    // The selection is done through the sorting.
                    $query = "SELECT j.id FROM #__joomla_flash_uploader j, #__joomla_flash_uploader_user u, #__usergroups ug WHERE j.gid='" . $id . "' AND u.profile=j.id AND  u.location='site' AND u.jgroup = ug.id and u.user IS NULL and (ug.id in ('" . $gr_list . "') ) order by u.jgroup, j.id";

                    $database->setQuery($query);
                    $groupprofiles = $database->loadObjectList();
                    if (count($groupprofiles) > 0) {
                        $group_match = true;
                    }
                }
                if ($group_match) {
                    // we have at least one match. If more than one user group matches
                    $id = $groupprofiles[0]->id;
                } else {
                    // we look for the default
                    $database->setQuery("SELECT id FROM #__joomla_flash_uploader  WHERE gid = '" . $id . "' and id NOT IN (select profile from #__joomla_flash_uploader_user f where  f.location='site') ORDER BY id");
                    $profiles = $database->loadObjectList();
                    if (count($profiles) == 0) {
                        return -1;
                    } else {
                        $id = $profiles[0]->id;
                    }
                }
            }
        }
        return $id;
    }

    /**
     *  The contact details are set to the session into the object TFU_USER_CONTACT
     *
     *  We read when available from either __vm_user_info or __contact_details:
     *  first_name
     *  name (last name if there is a first name)
     *  company
     *  address
     *  postcode
     *  city
     *  country
     *  telephone
     *  fax
     *  email
     */
    function setContactDetailsToSession($id) {
        $database = JFactory::getDBO();
        // first we check from which table we get it
        $database->setQuery("show tables like '%vm_user_info'");
        $isvm = $database->loadObjectList();
        if (count($isvm) > 0) {
            $database->setQuery("SELECT first_name, last_name as name, company, address_1 as address, zip as postcode, city, country, phone_1 as telephone, fax, user_email as email FROM #__vm_user_info c WHERE address_type='BT' AND user_id='" . $id . "'");
        } else {
            $database->setQuery("SELECT '' as first_name, Name as name, '' as company, address ,postcode, suburb as city , country, telephone, fax, email_to as email  FROM #__contact_details WHERE user_id='" . $id . "'");
        }
        $userprofile = $database->loadObjectList();
        if (count($userprofile) > 0) {
            $_SESSION['TFU_USER_CONTACT'] = $userprofile[0];
        } else {
            unset($_SESSION['TFU_USER_CONTACT']);
        }

    }

    function table_exists ($table) {
// open db connection
        $result = mysql_query("show tables like '$table'",$yourDB) or die ('error reading database');
        if (mysql_num_rows ($result)>0)
            return true;
        else
            return false;
    }


    function fixSession() {
        ob_start();
        // this is a fix if session are not saved and passed to the config.php
        $HTTP_SESSION_VARS = $_SESSION;
        session_write_close();
        ini_set('session.save_handler', 'files');
        session_start();
        $_SESSION = $HTTP_SESSION_VARS;
        session_write_close();
        ob_end_clean();
        // end fix ;).
    }

    function printCss($frontend = "administrator/") {
        // needed to fix path with seo
        $relative_dir = parse_url(JURI::base());
        $relative_dir = rtrim($relative_dir['path'],"\\/."); // we replace to get a consistent output with different php versions!
        if ($frontend == '') { // we are NOT w3c conform but this works all the time - and for the backend it has to work!
            echo "<link href=\"".$relative_dir."/components/com_jfuploader/jfuploader.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
        } else { // w3c conform
            echo '<script type = "text/javascript">
            <!--
            var link = document.createElement("link");
            link.setAttribute("href", "'.$relative_dir.'/components/com_jfuploader/jfuploader.css");
            link.setAttribute("rel", "stylesheet");
            link.setAttribute("type", "text/css");
            var head = document.getElementsByTagName("head").item(0);
            head.appendChild(link);
            //-->
            </script>
            ';
        }
    }

    function check_js_include($database) {
        $database->setQuery("SELECT value FROM #__joomla_flash_uploader_conf where key_id = 'use_js_include'");
        $settings = $database->loadObjectList();
        return $settings[0]->value == 'true';
    }

    function dir_copy( $source, $target ) {
        if ( is_dir( $source ) ) {
            @mkdir( $target );
            $d = dir( $source );
            while ( FALSE !== ( $entry = $d->read() ) )
            {
                if ( $entry == '.' || $entry == '..' ) {                    continue;                }
                $Entry = $source . '/' . $entry;
                if ( is_dir( $Entry ) ) {
                    JFUHelper::dir_copy( $Entry, $target . '/' . $entry );
                    continue;
                }
                copy( $Entry, $target . '/' . $entry );
            }
            $d->close();
        } else {
            copy( $source, $target );
        }
    }

    function getVariable($database, $variable) {
        $database->setQuery("SELECT value FROM #__joomla_flash_uploader_conf where key_id = '".$variable."'");
        $result = $database->loadObjectList();
        return $result[0]->value;
    }

    function getlatestVersion() {
        if (isset($_SESSION['JFU_LATEST_VERSION'])) {
            return $_SESSION['JFU_LATEST_VERSION'];
        } else if ($fsock = @fsockopen('www.tinywebgallery.com', 80, $errno, $errstr, 10)) {
            $version_info = '';
            @fputs($fsock, "GET /updatecheck/jfu16.txt HTTP/1.1\r\n");
            @fputs($fsock, "HOST: www.tinywebgallery.com\r\n");
            @fputs($fsock, "Connection: close\r\n\r\n");
            $get_info = false;
            while (!@feof($fsock))
            {
                if ($get_info)
                {
                    $version_info .= @fread($fsock, 1024);
                }
                else
                {
                    if (@fgets($fsock, 1024) == "\r\n")
                    {
                        $get_info = true;
                    }
                }
            }
            @fclose($fsock);
            if (!is_numeric(substr( $version_info,0,1))) {
                $version_info = -1;
            }
        } else {
            $version_info = -1;
        }
        $_SESSION['JFU_LATEST_VERSION'] = $version_info;
        return $version_info;
    }
    function getLanguageList() {
        $d = opendir(dirname(__FILE__) . '/tfu/lang');
        while (false !== ($entry = readdir($d))) {
            if ($entry != '.' && $entry != ".." && !is_dir($entry) && preg_match("/.[a-z]{2}\.xml$/", $entry)) {
                $list[$i++] = urlencode($entry);
            }
        }
        closedir($d);
        sort($list);
        reset($list);
        return ($list);
    }
    public static function getActions()
    {
        $user  = JFactory::getUser();
        $result        = new JObject;
        $assetName = 'com_jfuploader';

        $actions = array(
                       'core.login.admin', 'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.delete', 'core.edit.state'
                   );
        foreach ($actions as $action) {
            $result->set($action, $user->authorise($action, $assetName));
        }

        return $result;
    }
    /**
     * Get a list of the user groups. Copied from Joomla rules.php.
     *
     * @return array
     * @since	1.6
     */
    function getUserGroups($filter = '1=1', $subsel = "'true'")
    {
        // Initialise variables.
        $db = JFactory::getDBO();
        $query = $db->getQuery(true)
                 ->select('a.id AS value, a.title AS text, COUNT(DISTINCT b.id) AS level, a.parent_id, '.$subsel.' as jselected ')
                 ->from('#__usergroups AS a')
                 ->leftJoin('`#__usergroups` AS b ON a.lft > b.lft AND a.rgt < b.rgt')
                 ->where($filter)
                 ->group('a.id')
                 ->order('a.lft ASC');
        $db->setQuery($query);
        $options = $db->loadObjectList();
        return $options;
    }

    function getAdminLoginGroups()
    {
        // Initialise variables.
        $db		= JFactory::getDBO();
        $query	= 'SELECT b.rules FROM #__assets AS a  LEFT JOIN #__assets AS b ON b.lft <= a.lft AND b.rgt >= a.rgt ' .
                 ' WHERE (a.name = \'root.1\' OR a.parent_id=0) GROUP BY b.id ORDER BY b.lft';
        $db->setQuery($query);
        $options = $db->loadObjectList();
        $values = json_decode($options[0]->rules, true);
        return array_merge(array_keys($values['core.login.admin']), array_keys($values['core.admin'])) ;
    }
    function getBackendGroups($rows, $allgroups) {
        $adminmappings = array();
        if ($rows) {
            foreach ($rows as $row) {
                if ($row->location == 'admin') {
                    $adminmappings[] = $row->jgroup;
                }
            }
        }

        // filter backend roles
        $keys = JFUHelper::getAdminLoginGroups();
        $backendgroups = array();
        foreach ($allgroups as $group) {
            if (in_array ( $group->value ,$keys)) {
                if (!in_array($group->value, $adminmappings)) {
                    $backendgroups[] = $group;
                }
            } else {
                $group_temp = $group;
                // we check if one of the parents have the needed rights!
                while ($group->parent_id != 0) {
                    $parent = $group->parent_id;
                    foreach ($allgroups as $group_parent) {
                        if ($parent == $group_parent->value) {
                            if (in_array ( $group_parent->value ,$keys)) {
                                if (!in_array($group_temp->value, $adminmappings)) {
                                    $backendgroups[] = $group_temp;
                                }
                                break 2;
                            }
                            $group = $group_parent;
                        }
                    }
                }
            }
        }
        return $backendgroups;
    }
    /*
      get the available groups and the current selections.
    */
    function getAvailableGroups($id) {
        // get the group of this profile
        $db = JFactory::getDBO();
        $query	= 'select gid from #__joomla_flash_uploader where id=' . $id;
        $db->setQuery($query);
        $options = $db->loadObjectList();
        $group_id =  $options[0]->gid;

        // select the joomla groups that are assigned to a different jfu group
        $db->setQuery('select jgroup from #__joomla_flash_uploader_user where location=\'site\' and profile in (SELECT id FROM #__joomla_flash_uploader where id <> ' . $id . ' and jgroup IS NOT NULL and gid = \'' . $group_id . '\')');
        $jgroups = $db->loadObjectList();
        if (count($jgroups) > 0) {
            $assigned_groups = array();
            foreach($jgroups as $jg) {
                $assigned_groups[] = $jg->jgroup;
            }
            $filter = "a.id NOT in (". implode(",", $assigned_groups)  .")";
        } else {
            $filter = ' 1=1 ';
        }
        $av_groups = JFUHelper::getUserGroups($filter);
        // echo '<br>'print_r(mixed expression, [bool return])print_r($av_groups);
        // select the ones which are assigned to this jfu group to mark them
        $db->setQuery('select jgroup from #__joomla_flash_uploader_user where location=\'site\' and profile in (SELECT id FROM #__joomla_flash_uploader where id = ' . $id . ' and gid = \'' . $group_id . '\')');
        $jgroups_res = $db->loadObjectList();

        $jgroups = array();
        // extract the hits
        foreach($jgroups_res as $key => $value) {
            $jgroups[] = $value->jgroup;
        }
        // set the hits in the available joomla groups
        foreach($av_groups as $av_group) {
            if ( in_array($av_group->value, $jgroups) ) {
                $av_group->jselected='true';
            } else {
                $av_group->jselected='false';
            }
        }
        return $av_groups;
    }
    
    function getHighestGroupName($db, $groups) {
        natcasesort($groups) ;
        $group_keys = array_flip($groups);
        return array_pop($group_keys);
    }
} // class

?>