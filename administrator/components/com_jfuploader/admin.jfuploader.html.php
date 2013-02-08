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

var $currentrow = 0;
function getRowClass() {
  global $currentrow;
  return (($currentrow++ %2 ) ==0 ) ? ' class="row0"' : ' class="row1"'; 
}


function getDefaultInputBox($param, $value, $class) {
 return '
		 <tr '. HTML_joomla_flash_uploader::getRowClass() .'>
			<td '.$class.'>'.JText::_('E_S_'.strtoupper($param)).'</td>
			<td><input onBlur="javascript:removeSpaces(this);" type="text" class="w250" maxsize="100"
				name="'.$param.'" value="'.$value.'" /></td>
			<td>'.JText::_('E_D_'.strtoupper($param)).'</td>
		</tr>
		';
}

function getDefaultRadioBox($param, $value, $class) {
return '
		 <tr '. HTML_joomla_flash_uploader::getRowClass() .'>
			<td '.$class.'>'.JText::_('E_S_'.strtoupper($param)).'</td>
			<td>'.tfuHTML::truefalseRadioList($param,
			'class="inputbox"', $value).'</td>
			<td>'.JText::_('E_D_'.strtoupper($param)).'</td>
		</tr>
		';
}

function errorRights () { 
  echo '<form action="index.php" method="post" name="adminForm" id="adminForm">
      <input type="hidden" name="option" value="com_jfuploader"/>
      <input type="hidden" name="task" value="config"/>
      </form>';
  echo '<center><div class="errordiv">';
  echo JText::_('MES_RIGHTS');
  echo '</div></center>';
}

function showUpload ($row, $realfolder, $folder, $jfu_config) { 
global $mybasedir;
$pathfix= (strlen($mybasedir) > 0) ? '../' : '';

$language = JFactory::getLanguage();
$Cfg_lang = $language->getTag();

$relative_dir = dirname($_SERVER['PHP_SELF']);
$relative_dir = rtrim($relative_dir,"\\/.") . '/'; // we replace to get a consistent output with different php versions!

$width=$row->display_width;
$height=floor($width*(340/650));
if ($height > 390) $height = floor($height * 0.95);
// $height=375;

echo JHTML::_('behavior.keepalive');
// now I overwrite the existing function.
echo '
<script type="text/javascript">
function debugError(errorString) { }

function getIDN() {
  return "'.$jfu_config['idn_url'].'/administrator/";
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
	
	
echo '<center><form action="index.php" method="post" name="adminForm" id="adminForm">
<input type="hidden" name="option" value="com_jfuploader"/>
<input type="hidden" name="task" value="config"/>
</form>';

if ($row->enable_setting=="false") { // no flash only text!
  echo JFULanguage::getLanguage($row->text_top_lang,$row->text_top, "TEXT_TOP" , $row->id);
  return;
}

if (!file_exists($folder) && $realfolder != "") {
  echo JText::_('ERR_FOLDER') . " : " . $realfolder;
  return;
}

echo "<h3>";
echo JFULanguage::getLanguage($row->text_title_lang,$row->text_title, "TEXT_TITLE" ,$row->id);
echo "</h3>";
echo JFULanguage::getLanguage($row->text_top_lang,$row->text_top, "TEXT_TOP" , $row->id);
echo "<p>";

$lang = JFULanguage::mapLangJoomlatoTFU($Cfg_lang);
$base_dir = $pathfix."components/com_jfuploader/tfu";
$extra_settings = '';
if ($row->description_mode == "true") {
  $extra_settings .= '&tfu_description_mode=true';
}
if ($row->hide_remote_view == "true") {
  $extra_settings .= '&hide_remote_view=true';
}
if ($pathfix == '../') { // we tell the flash that tfu is in the frontend directory!
   $extra_settings .= '&loc=FE';
}

echo '
  <div style="z-index:1">
    <div id="flashcontent"><div class="noflash">' . sprintf(JText::_('MES_NO_FLASH'),'<a href="http://www.tinywebgallery.com/en/tfu/web_jfu.php">', '</a>') . '</p></div></div>
	<script type="text/javascript" src="'.$pathfix.'components/com_jfuploader/tfu/swfobject.js"></script>
	<script type="text/javascript">
var flashvars = {};
var params = {};
var attributes = {};
params.allowfullscreen = "true";
';
if ($row->fix_overlay == "true") {
  echo 'params.wmode ="transparent";'; 
}
if ($width == '650') {
  echo 'params.scale = "noScale";';
}
if  ($jfu_config['idn_url'] != '') {
  echo 'flashvars.enable_absolut_path = "true";';
  $extra_settings.="&enable_absolut_path=true";
}
if ($row->big_server_view == "true") {
  echo 'flashvars.big_server_view = "true";';
  $extra_settings .= '&big_server_view=true';
}
if ($row->switch_sides == "true") {
  echo 'flashvars.switch_sides = "true";';
  $extra_settings .= '&switch_sides=true';
}

echo '
   swfobject.embedSWF("'.$pathfix.'components/com_jfuploader/tfu/tfu_215.swf?joomla=true&lang='.$lang.'&session_id='.session_id().'&base='.$base_dir.'&relative_dir='.$relative_dir.$extra_settings.'&'.$row->swf_text.'", "flashcontent", "'.$width.'", "'.$height.'", "8.0.0", "", flashvars, params, attributes);
';
echo <<< HTML
	</script>
	<!-- end include with Javascript -->
	<!-- static html include -->
	<noscript>
	Please enable Javascript
	</noscript> 
</div>
HTML;
echo "</p>";
echo "<br/>";
echo JFULanguage::getLanguage($row->text_bottom_lang, $row->text_bottom, "TEXT_BOTTOM" ,$row->id);
echo "<br/>";
if (!file_exists(dirname(__FILE__) . "/".$mybasedir."tfu/.htaccess")) {
  printf(JText::_('C_HTACCESS_CREATE'),'<a href="#createhtaccess" onclick="return submitform(\'createhtaccess\')"><b>','</b></a>');
} else {
printf(JText::_('C_HTACCESS_DELETE'),'<a href="#deletehtaccess" onclick="return submitform(\'deletehtaccess\')"><b>','</b></a>');
}
echo "</center>";
}

function listConfig($rows, $jfu_config) {
global $mybasedir;
$count = count($rows) + 1; // because id starts at 1
$config = new JConfig();
$infrontend= (strlen($mybasedir) > 0);

$vers = $jfu_config['version'];

// we build the version string!
$latest_version = JFUHelper::getlatestVersion();
$version_description = $latest_version;
if ($latest_version == -1) {
$version_description = '<span class="jfu_nocheck">' . JText::_('C_VERSION_NO') . ' <a href="http://jfu.tinywebgallery.com" target="_blank">http://jfu.tinywebgallery.com</a> ' . JText::_('C_VERSION_NO2') . '</span>';
} else if (version_compare ($latest_version,$vers) == 1) {
$version_description = '<span class="jfu_old">' . JText::_('C_VERSION_OLD1') .  ' <a href="http://jfu.tinywebgallery.com" target="_blank">http://jfu.tinywebgallery.com</a> ' . JText::_('C_VERSION_OLD2') . JText::_('C_VERSION_OLD3') . ' <b>'.$latest_version.'</b>. '.JText::_('C_VERSION_OLD4').' <b>' . $vers . '.</b><p>
'.JText::_('C_VERSION_OLD5').' <a href="http://blog.tinywebgallery.com" target="_blank">'.JText::_('C_VERSION_OLD6').'</a>.' . '</p></span>';
} else {
$version_description = '<span class="jfu_current">' . JText::_('C_VERSION_OK') . '</span>';
}

echo '
<script type="text/javascript" src="components/com_jfuploader/js/jfu.js"></script>
<form action="index.php" method="post" name="adminForm" id="adminForm">
  <h2>'.JText::_('C_TITLE').'</h2>		
	'.JText::_('C_TEXT').'

<fieldset class="batch">
<legend>'.JText::_('E_H3_PROFILES').'</legend>
 <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist" >
       <thead>
       <tr>
         <th width="20">
          <input type="checkbox" name="toggle"
                 value="" onclick="checkAll('.$count.');"/>
          </th>
          <th align="left" width="8%">'.JText::_('C_GROUP').'</th>
          <th align="left" width="5%">'.JText::_('C_ID').'</th>
          <th align="left" width="8%">'.JText::_('C_PROFILE').'</th>
          <th align="left" width="17%">'.JText::_('C_DESCRIPTION').'</th>
          <th align="left" width="15%">'.JText::_('C_FOLDER').'</th>
          <th align="left" width="10%">'.JText::_('C_UPLOAD_LIMIT').'</th>
          <th align="left" width="20%">'.JText::_('C_USERS').'</th>
          <th align="left" width="5%">'.JText::_('C_MASTER_PROFILE').'</th>
          <th width="5%">'.JText::_('C_ENABLED').'</th>
          <th width="5%">'.JText::_('C_DATE').'</th>          
         </tr>
         </thead>
';
        $i = 0;
        foreach ($rows as $row) {
           $evenodd = $i % 2;
           if ($row->maxfilesize == "") {
             $row->maxfilesize=JText::_('C_AUTO') . getMaximumUploadSize(); 
           }
echo <<< HTML
      <tr class="row$evenodd">
       <td>      
        <input type="checkbox" id="cb$row->id" name="cid[]"
               value="$row->id"
               onclick="isChecked(this.checked);" />
       </td>
       
        <td class="middle_ti nobreak"><a href="#edit" onclick="return listItemTask('cb$row->id','edit')">$row->resize_data$row->gid</a>&nbsp;</td>
        <td>
          <a href="#edit" onclick="return listItemTask('cb$row->id','edit')">$row->id</a>
        </td>
        <td>
				 <a href="#edit"
				             onclick="return listItemTask('cb$row->id','edit')">
				            $row->config_name</a>
        </td>
        <td>$row->description&nbsp;</td>
        <td>$row->folder&nbsp;</td>
        <td>$row->maxfilesize KB</td>
        <td class="middle_ti">$row->resize_label</td>
HTML;
           // master
           echo "<td align='center'>";
           if ($row->id != 1) {
           echo "<span style='cursor:pointer;' id='enableM".$row->id."'>";
           if ($row->master_profile == "true") {
              echo "<img onClick='disableMaster(".$row->id.")' src='components/com_jfuploader/images/tick.png' border='0' />";
           } else {
              echo "<img onClick='enableMaster(".$row->id.")' src='components/com_jfuploader/images/publish_x.png' border='0' />";
           }
           } else {
           echo '&nbsp;';
           }
           echo "</span>";
           echo "</td>";
           // enable
           echo "<td align='center'>";
           echo "<span style='cursor:pointer;' id='enableP".$row->id."'>";
           if ($row->enable_setting == "true") {
              echo "<img onClick='disableProfile(".$row->id.")' src='components/com_jfuploader/images/tick.png' border='0' />";
           } else {
              echo "<img onClick='enableProfile(".$row->id.")' src='components/com_jfuploader/images/publish_x.png' border='0' />";
           }
           echo "</span>";
           echo "</td>";
          
           echo "<td align='center'>".$row->last_modified_date."</td></tr>";
           $i++;
        }      
echo "</table>";
echo $jfu_config['warning'];

echo "<br>";

echo "
<script type=\"text/javascript\">
function show_md5() {
  var dropdown = document.getElementById('secutity_token');
  var index = dropdown.selectedIndex;
  var value = dropdown.options[index].value;
  var text = '<div style=\"clear:both;\"></div>' + escapeHTML('" . JText::_('P_INST') . "') + '<br>&nbsp;<br> {jfuploader type='+value.substr(0,1)+' id='+value.substr(value.indexOf(\"_\", 0)+1)+' securitytoken=' + MD5(value) + '} <br>&nbsp;'; 
  var out = document.getElementById('token_output').innerHTML=text; 
  return false;
}
function clearCode() {
  document.getElementById('token_output').innerHTML='';
}
</script>
";

echo '<div style="float:left;width:40%;white-space: nowrap;">';
echo JText::_('P_GENERATE') . ' '. tfuHTML::showTockenList('secutity_token','onchange="clearCode();" class="inputbox"','1'). '&nbsp;&nbsp;<a class="jfu_button" href="#generate" onclick="return show_md5(); ;"><b>'.JText::_('P_GENERATE_BUTTON').'</b></a><p><span id="token_output"></span></p>';
echo '</div>';
echo '<div style="float:left;width:55%;">' . JText::_('P_GENERATE_INST') . '</div>';
echo '<div style="clear:both;" ></div>';
echo '</fieldset>';
echo '
<fieldset class="batch">
<legend>'.JText::_('E_H3_GLOB').'</legend>
<table class="admintable">
     <thead> 
		<tr>
			<th width="20%">'.JText::_('E_H_SETTING').'</th>
			<th width="20%">'.JText::_('E_H_VALUE').'</th>
			<th width="60%">'.JText::_('E_H_DESCRIPTION').'</th>
		</tr>
     </thead> 
	 <tbody>
 	    <tr>
			<td class="key">'.JText::_('E_S_JFU_VERSION').'</td>
			<td>'.$vers.'</td>
			<td>'.$version_description.' (<a href="components/com_jfuploader/history.htm" onclick="openHistory(); return false;">History</a>)</td>
		</tr>
 	    <tr>
			<td class="key">'.JText::_('E_S_JFU_SESSION').'</td>
			<td>'.$config->session_handler . (($config->session_handler == 'database') ? "&nbsp;<img src='components/com_jfuploader/images/warning.png' style='vertical-align:middle;width:16px; margin-top:0px;' />" : "&nbsp;<img src='components/com_jfuploader/images/tick.png' style='vertical-align:middle;margin-top:0px;' />") .'</td>
			<td>'. (($config->session_handler == 'database') ? ('<span class="jfu_nocheck">' . JText::_('E_D_JFU_SESSION_DB') . '</span>') :  JText::_('E_D_JFU_SESSION_NONE')) .'</td>
		</tr>
    	<tr>
			<td class="key">'.JText::_('E_S_JFU_KEEP').'</td>
			<td>'.tfuHTML::truefalseRadioList('keep_tables',
			'class="inputbox"',$jfu_config['keep_tables']) . '</td>
			<td>'.JText::_('E_D_JFU_KEEP').'</td>
		</tr>
			<tr>
			<td class="key">'.JText::_('E_S_JFU_USE_JS_INCLUDE').'</td>
			<td>'.tfuHTML::truefalseRadioList('use_js_include',
			'class="inputbox"',$jfu_config['use_js_include']) . '</td>
			<td>'.JText::_('E_D_JFU_USE_JS_INCLUDE').'</td>
		</tr>
		<tr>
			<td class="key">'.JText::_('E_S_JFU_BACKEND_ACCESS_UPLOAD').'</td>
			<td><input type="hidden" name="backend_access_upload" value="Manager">'.JText::_('E_D_PROFIL_SEE').'</td>
			<td>'.JText::_('E_D_JFU_BACKEND_ACCESS_UPLOAD').'</td>
		</tr>
			<tr>
			<td class="key">'.JText::_('E_S_JFU_BACKEND_ACCESS_CONFIG').'</td>
			<td><input type="hidden" name="backend_access_config" value="Super Administrator">'.JText::_('E_D_PROFIL_SEE').'</td>
			<td>'.JText::_('E_D_JFU_BACKEND_ACCESS_CONFIG').'</td>
		</tr>
		<tr>
			<td class="key">'.JText::_('E_S_PROFIL_SA').'</td>
			<td><input type="hidden" name="sa_profil" value="1">'.JText::_('E_D_ACCESS_MIN_SEE').'</td>
			<td>'.JText::_('E_D_PROFIL_SA').'</td>
		</tr>
		<tr>
			<td class="key">'.JText::_('E_S_PROFIL_A').'</td>
			<td><input type="hidden" name="a_profil" value="1">'.JText::_('E_D_ACCESS_MIN_SEE').'</td>
			<td>'.JText::_('E_D_PROFIL_SA').'</td>
		</tr>
		<tr>
			<td class="key">'.JText::_('E_S_PROFIL_M').'</td>
			<td><input type="hidden" name="m_profil" value="1">'.JText::_('E_D_ACCESS_MIN_SEE').'</td>
			<td>'.JText::_('E_D_PROFIL_SA').'</td>
		</tr>   	
		<tr>
			<td class="key">'.JText::_('E_S_JFU_FILE_CHMOD').'</td>
			<td><input type="text" class="w50" maxsize="100"
				name="file_chmod" value="'.$jfu_config['file_chmod'].'" /></td>
			<td>'.JText::_('E_D_JFU_FILE_CHMOD').'</td>
		</tr>
			<tr>
			<td class="key">'.JText::_('E_S_JFU_DIR_CHMOD').'</td>
			<td><input type="text" class="w50" maxsize="100"
				name="dir_chmod" value="'.$jfu_config['dir_chmod'].'" /></td>
			<td>'.JText::_('E_D_JFU_DIR_CHMOD').'</td>
		</tr>
		<tr>
			<td class="key">'.JText::_('E_S_ENABLE_UPLOAD_DEBUG').'</td>
			<td>'.tfuHTML::truefalseRadioList('enable_upload_debug',
			'class="inputbox"',$jfu_config['enable_upload_debug']) . '</td>
			<td>'.JText::_('E_D_ENABLE_UPLOAD_DEBUG').'</td>
		</tr>
		<tr>
			<td class="key">'.JText::_('E_S_ENHANCED_DEBUG').'</td>
			<td>'.tfuHTML::truefalseRadioList('enhanced_debug',
			'class="inputbox"',$jfu_config['enhanced_debug']) . '</td>
			<td>'.JText::_('E_D_ENHANCED_DEBUG').'</td>
		</tr>
		<tr>
			<td class="key">'.JText::_('E_S_CHECK_IMAGE_MAGIC').'</td>
			<td>'.tfuHTML::truefalseRadioList('check_image_magic',
			'class="inputbox"',$jfu_config['check_image_magic']) . '</td>
			<td>'.JText::_('E_D_CHECK_IMAGE_MAGIC').'</td>
		</tr>
		<tr>
';
// we check where the tfu folder is
echo '<td class="key">'.JText::_('E_S_MOVE_TFU_FOLDER').'</td>';
if ($infrontend) {
echo '<td><br>'.'<a class="jfu_button" href="#movetfudir" onclick="return submitform(\'movetfudir\')"><b>'.JText::_('E_D_MOVE_TFU_FOLDER_B_B').'</b></a>'.'</td>
			<td>'.JText::_('E_D_MOVE_TFU_FOLDER'). '<br><b>' . JText::_('E_D_MOVE_TFU_FOLDER_F') .'</b></td>
		';
} else {
echo '<td><br>'.'<a class="jfu_button" href="#movetfudir" onclick="return submitform(\'movetfudir\')"><b>'.JText::_('E_D_MOVE_TFU_FOLDER_F_B').'</b></a>'.'</td>
			<td>'.JText::_('E_D_MOVE_TFU_FOLDER'). '<br><b>' . JText::_('E_D_MOVE_TFU_FOLDER_B') .'</b></td>
		';   
}
echo '
    </tr>
    <tr>
			<td class="key">'.JText::_('E_S_IDN_URL').'</td>
			<td><input type="text" class="w250" maxsize="120"
				name="idn_url" value="'.$jfu_config['idn_url'].'" /></td>
			<td>'.JText::_('E_D_IDN_URL').'</td>
		</tr>
			<tr>
			<td class="key">'.JText::_('E_S_USE_INDEX_FOR_FILES').'</td>
			<td>'.tfuHTML::selectModeRadioList('use_index_for_files',
			'class="inputbox"',$jfu_config['use_index_for_files']) . '</td>
			<td>'.JText::_('E_D_USE_INDEX_FOR_FILES').'</td>
		</tr>
    
		</tbody>
</table>		
</fieldset>
';
echo <<< HTML
      <input type="hidden" name="task" value="" />
      <input type="hidden" name="option" value="com_jfuploader"/>
      <input type="hidden" name="boxchecked" value="0" />
  </form>
HTML;
}


function showConfig($row, $a_user, $p_user, $f_groups, $do_check_image_magic, $showUserPage = false) {    
jimport('joomla.filter.output'); 
global $m;
JFilterOutput::objectHTMLSafe($row);
$folder_check_image = "tick";

// The image magick status
$im_check = check_image_magic($row->image_magic_path, $do_check_image_magic == 'true');
$im_status = (($im_check) == '1') ? '<p><img src="components/com_jfuploader/images/tick.png" style="vertical-align:middle;"" /> &nbsp;' .JText::_('E_D_USE_IMAGE_MAGIC_OK') . '</p>' : ((($im_check) == '0') ? ' <p><img src="components/com_jfuploader/images/publish_x.png" style="vertical-align:middle; margin-top:0px;" /> &nbsp;' .JText::_('E_D_USE_IMAGE_MAGIC_FAIL') . '</p>':' <p><img src="images/publish_x.png" style="vertical-align:middle; margin-top:0px;" /> &nbsp;' .JText::_('E_D_CHECK_IMAGE_MAGIC_DISABLED') . '</p>');
echo '
<script type="text/javascript" src="components/com_jfuploader/js/jfu.js"></script>

<script type="text/javascript">
function checkValue(element, min, req) {
  var val = element.value;
  if (val == "" && req==1) {
     alert("'.JText::_('C_W_REQUIRED').'");
  } else if (!isNumeral(val)) {
     alert("'.JText::_('C_W_NUMBER').'");
  } else if (val < min) {
    alert("'.JText::_('C_W_SMALL').' " + min + ".");
  }
}

function checkUploadMaxValue(element) {
  var val = element.value;
  var max = '.getMaximumUploadSize().';
  if (val > max) {
    alert("'.JText::_('C_W_MAX').'");
  }
}
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="config" >
<h2>'.JText::_('E_HEADER_PROFILE').'</h2>
<p>
<div class="block-menu" >
<div id="form1h" class="form-block-menu-sel" onClick="showform(\'form1\')">
&nbsp;'.JText::_('E_HEADER_1').'&nbsp;</div>&nbsp;
<div  id="form2h" class="form-block-menu" onClick="showform(\'form2\')">
&nbsp;'.JText::_('E_HEADER_2').'&nbsp;</div>&nbsp;
<div  id="form3h" class="form-block-menu" onClick="showform(\'form3\')">
&nbsp;'.JText::_('E_HEADER_3').'&nbsp;</div>&nbsp;
';
if ($row->id != '1') {
echo '
<div  id="form4h" class="form-block-menu" onClick="showform(\'form4\')">
&nbsp;'.JText::_('E_HEADER_4').'&nbsp;</div>&nbsp;
';
}
echo '
</div>
</p>
<div id="form1">
<br>
<table class="adminlist">
	
	 <thead>
		<tr '. HTML_joomla_flash_uploader::getRowClass() .'>
			<th width="20%">'.JText::_('E_H_SETTING').'</th>
			<th width="20%">'.JText::_('E_H_VALUE').'</th>
			<th width="60%">'.JText::_('E_H_DESCRIPTION').'</th>
		</tr>
		 </thead>
		 <tbody>
		<tr '. HTML_joomla_flash_uploader::getRowClass() .'>
			<td>'.JText::_('E_S_ENABLE_SETTING').'</td>
			<td>'.tfuHTML::truefalseRadioList('enable_setting',
			'class="inputbox"', $row->enable_setting).'</td>
			<td>'.JText::_('E_D_ENABLE_SETTING').'</td>
		</tr>
		<tr '. HTML_joomla_flash_uploader::getRowClass() .'>
			<td>'.JText::_('E_S_ID').'</td>
			<td>'.$row->id.'</td>
			<td>'.JText::_('E_D_ID').'</td>
		</tr>
		<tr '. HTML_joomla_flash_uploader::getRowClass() .'>
			<td>'.JText::_('E_S_GID').'</td>
			<td><input  onBlur="javascript:removeSpaces(this);" type="text" class="w250" maxsize="100"
				name="gid" value="'.$row->gid.'" '. (($row->id == '1')? ' readonly="readonly" ':'') .' /></td>
			<td>'.JText::_('E_D_GID').'</td>
		</tr>
		<tr '. HTML_joomla_flash_uploader::getRowClass() .'>
			<td>'.JText::_('E_S_CONFIG_NAME').'</td>
			<td><input type="text" class="w250" maxsize="100"
				name="config_name" value="'.$row->config_name.'" /></td>
			<td>'.JText::_('E_D_CONFIG_NAME').'</td>
		</tr>
		<tr '. HTML_joomla_flash_uploader::getRowClass() .'>
			<td>'.JText::_('E_S_CONFIG_DESCRIPTION').'</td>
			<td><input type="text" class="w250" maxsize="500"
				name="description" value="'.$row->description.'" /></td>
			<td>'.JText::_('E_D_CONFIG_DESCRIPTION').'</td>
		</tr>
		<tr '. HTML_joomla_flash_uploader::getRowClass() .'>
			<td>'.JText::_('E_S_TEXT_TITLE') . '</td>
			<td>'.tfuHTML::truefalseRadioList('text_title_lang',
			'class="inputbox"', $row->text_title_lang, JText::_('E_S_USE_FILE'),
			JText::_('E_S_USE_TEXT')).'<br />
			<input type="text" class="w250" maxsize="100"
				name="text_title" value="'.$row->text_title.'" /></td>
			<td>'.JText::_('E_D_TEXT_TITLE') . " " . JText::_('E_D_TEXT') .'</td>
		</tr>
		<tr '. HTML_joomla_flash_uploader::getRowClass() .'>
			<td>'.JText::_('E_S_TEXT_BEFORE').'</td>
			<td>'.tfuHTML::truefalseRadioList('text_top_lang',
			'class="inputbox"', $row->text_top_lang, JText::_('E_S_USE_FILE'),
			JText::_('E_S_USE_TEXT')).'<br />
			<textarea rows="4" name="text_top" id="text_top" class="w250">'. $row->text_top .'</textarea></td>
			<td>'.JText::_('E_D_TEXT_BEFORE'). " " . JText::_('E_D_TEXT') .'</td>
		</tr>
		<tr '. HTML_joomla_flash_uploader::getRowClass() .'>
			<td>'.JText::_('E_S_TEXT_AFTER').'</td>
			<td>'.tfuHTML::truefalseRadioList('text_bottom_lang',
			'class="inputbox"', $row->text_bottom_lang, JText::_('E_S_USE_FILE'), JText::_('E_S_USE_TEXT') ).'<br />
			<textarea rows="4" name="text_bottom" id="text_bottom"
				class="w250">' . $row->text_bottom .'</textarea></td>
			<td>'.JText::_('E_D_TEXT_AFTER'). " " . JText::_('E_D_TEXT') .'</td>
		</tr>
		<tr'. HTML_joomla_flash_uploader::getRowClass() .'>
			<td>'.JText::_('E_S_FIXOVERLAY').'</td>
			<td>'.tfuHTML::truefalseRadioList('fix_overlay',
			'class="inputbox"', $row->fix_overlay).'</td>
			<td>'.JText::_('E_D_FIXOVERLAY').'</td>
		</tr>
	</tbody>
</table>
</div>
<div id="form2">
<br>
<table class="adminlist">
	<thead>
		<tr>
			<th width="20%">'.JText::_('E_H_SETTING').'</th>
			<th width="20%">'.JText::_('E_H_VALUE').'</th>
			<th width="60%">'.JText::_('E_H_DESCRIPTION').'</th>
		</tr>
		</thead>
		<tbody>
		<tr '. HTML_joomla_flash_uploader::getRowClass() .'>
			<td><strong>'.JText::_('E_S_FOLDER').'</strong></td>
				<td><input onBlur="javascript:removeSpaces(this);" onKeyUp="javascript:testFolder();" type="text" class="w230" maxsize="100"
				name="folder" id="folder" value="'.$row->folder.'" />&nbsp;<img id="foldertestimage" height="16" src="components/com_jfuploader/images/'.$folder_check_image.'.png" border="0 "/></td>
			<td>'.JText::_('E_D_FOLDER').'</td>
		</tr>
';		
if 	($row->id != 1) {
  echo HTML_joomla_flash_uploader::getDefaultRadioBox('master_profile', $row->master_profile, '');	
  echo '
			<tr '. HTML_joomla_flash_uploader::getRowClass() .'>
			<td>'.JText::_('E_S_MASTER_PROFILE_MODE').'</td>
			<td>'.tfuHTML::mastermodeRadioList('master_profile_mode',
			'class="inputbox"', $row->master_profile_mode).'</td>	
			<td>'.JText::_('E_D_MASTER_PROFILE_MODE').'</td>
		</tr>
  ';
  echo HTML_joomla_flash_uploader::getDefaultRadioBox('master_profile_lowercase', $row->master_profile_lowercase, '');	
}
echo '
		<tr '. HTML_joomla_flash_uploader::getRowClass() .'>
			<td>'.JText::_('E_S_MAX_FILE_SIZE').'</td>
			<td><input  onBlur="javascript:removeSpaces(this);checkValue(this, 0,0);checkUploadMaxValue(this);" type="text" class="w250" maxsize="100"
				name="maxfilesize" value="'.$row->maxfilesize.'" /></td>
			<td>'.JText::_('E_D_MAX_FILE_SIZE').'</td>
		</tr>
		<tr '. HTML_joomla_flash_uploader::getRowClass() .'>
			<td>'.JText::_('E_S_WIDTH').'</td>
			<td><input onBlur="javascript:removeSpaces(this);checkValue(this, 100,1);" type="text" class="w250" maxsize="100"
				name="display_width" value="'.$row->display_width.'" /></td>
			<td>'.JText::_('E_D_WIDTH').'</td>
		</tr>
';		
echo HTML_joomla_flash_uploader::getDefaultRadioBox('resize_show', $row->resize_show, '');	
echo HTML_joomla_flash_uploader::getDefaultInputBox('resize_data', $row->resize_data, '');	
echo HTML_joomla_flash_uploader::getDefaultInputBox('resize_label', $row->resize_label, '');	
echo HTML_joomla_flash_uploader::getDefaultInputBox('resize_default', $row->resize_default, '');
echo HTML_joomla_flash_uploader::getDefaultInputBox('compression', $row->compression, '');	
echo HTML_joomla_flash_uploader::getDefaultInputBox('allowed_file_extensions', $row->allowed_file_extensions, '');	
echo HTML_joomla_flash_uploader::getDefaultInputBox('forbidden_file_extensions', $row->forbidden_file_extensions, '');	
if (function_exists('fnmatch')) {
  echo HTML_joomla_flash_uploader::getDefaultInputBox('forbidden_view_file_filter', $row->forbidden_view_file_filter, '');
}
echo HTML_joomla_flash_uploader::getDefaultRadioBox('show_size', $row->show_size, '');
echo HTML_joomla_flash_uploader::getDefaultInputBox('date_format', $row->date_format, '');	
echo HTML_joomla_flash_uploader::getDefaultRadioBox('show_server_date_instead_size', $row->show_server_date_instead_size, 'class="jfu_indent"');
echo HTML_joomla_flash_uploader::getDefaultRadioBox('big_server_view', $row->big_server_view, '');
echo HTML_joomla_flash_uploader::getDefaultRadioBox('switch_sides', $row->switch_sides, '');
echo HTML_joomla_flash_uploader::getDefaultRadioBox('hide_remote_view', $row->hide_remote_view, '');	
echo HTML_joomla_flash_uploader::getDefaultRadioBox('show_delete', $row->show_delete, '');	
echo HTML_joomla_flash_uploader::getDefaultRadioBox('enable_folder_browsing', $row->enable_folder_browsing, '');	
echo HTML_joomla_flash_uploader::getDefaultRadioBox('enable_folder_creation', $row->enable_folder_creation, '');	
echo HTML_joomla_flash_uploader::getDefaultRadioBox('enable_dir_create_detection', $row->enable_dir_create_detection, 'class="jfu_indent"');	
echo HTML_joomla_flash_uploader::getDefaultRadioBox('ftp_enable', $row->ftp_enable, 'class="jfu_indent"');	
echo HTML_joomla_flash_uploader::getDefaultInputBox('ftp_host', $row->ftp_host, 'class="jfu_indent"');	
echo HTML_joomla_flash_uploader::getDefaultInputBox('ftp_port', $row->ftp_port, 'class="jfu_indent"');	
echo HTML_joomla_flash_uploader::getDefaultInputBox('ftp_user', $row->ftp_user, 'class="jfu_indent"');	
echo HTML_joomla_flash_uploader::getDefaultInputBox('ftp_pass', $row->ftp_pass, 'class="jfu_indent"');	
echo HTML_joomla_flash_uploader::getDefaultInputBox('ftp_root', $row->ftp_root, 'class="jfu_indent"');	
echo HTML_joomla_flash_uploader::getDefaultRadioBox('enable_folder_deletion', $row->enable_folder_deletion, '');	
echo HTML_joomla_flash_uploader::getDefaultRadioBox('enable_folder_rename', $row->enable_folder_rename, '');	
echo HTML_joomla_flash_uploader::getDefaultRadioBox('enable_file_rename', $row->enable_file_rename, '');	
echo HTML_joomla_flash_uploader::getDefaultRadioBox('keep_file_extension', $row->keep_file_extension, '');
echo HTML_joomla_flash_uploader::getDefaultRadioBox('remove_multiple_php_extension', $row->remove_multiple_php_extension, '');
echo HTML_joomla_flash_uploader::getDefaultRadioBox('scan_images', $row->scan_images, '');	
echo HTML_joomla_flash_uploader::getDefaultRadioBox('sort_files_by_date', $row->sort_files_by_date, '');	
echo HTML_joomla_flash_uploader::getDefaultRadioBox('sort_directores_by_date', $row->sort_directores_by_date, '');
echo HTML_joomla_flash_uploader::getDefaultRadioBox('overwrite_files', $row->overwrite_files, '');	
echo '		
		<tr '. HTML_joomla_flash_uploader::getRowClass() .'>
			<td>'.JText::_('E_S_WARNING_SETTING').'</td>
			<td>'.tfuHTML::warningRadioList('warning_setting',
			'class="inputbox"', $row->warning_setting).'</td>
			<td>'.JText::_('E_D_WARNING_SETTING').'</td>
		</tr>
		';
	
echo HTML_joomla_flash_uploader::getDefaultRadioBox('hide_directory_in_title', $row->hide_directory_in_title, '');	
echo HTML_joomla_flash_uploader::getDefaultRadioBox('truncate_dir_in_title', $row->truncate_dir_in_title, '');	
echo HTML_joomla_flash_uploader::getDefaultRadioBox('normalise_file_names', $row->normalise_file_names, '');	
echo HTML_joomla_flash_uploader::getDefaultRadioBox('normalise_directory_names', $row->normalise_directory_names, '');	
echo HTML_joomla_flash_uploader::getDefaultRadioBox('normalize_spaces', $row->normalize_spaces, '');	
echo HTML_joomla_flash_uploader::getDefaultInputBox('upload_notification_email', $row->upload_notification_email, '');	
echo HTML_joomla_flash_uploader::getDefaultInputBox('upload_notification_email_from', $row->upload_notification_email_from, '');	
echo HTML_joomla_flash_uploader::getDefaultInputBox('upload_notification_email_subject', $row->upload_notification_email_subject, '');	
echo HTML_joomla_flash_uploader::getDefaultInputBox('upload_notification_email_text', $row->upload_notification_email_text, '');	
echo HTML_joomla_flash_uploader::getDefaultRadioBox('upload_notification_use_full_path', $row->upload_notification_use_full_path, '');	
echo HTML_joomla_flash_uploader::getDefaultInputBox('language_dropdown', $row->language_dropdown, '');	

$disabled = '';
if (($im_check) != '1') {
  $row->use_image_magic = 'false';
  $disabled = ' disabled="disabled" '; 
  echo '<input type="hidden" name="use_image_magic" value="false" />';
}
echo '
	     <tr '. HTML_joomla_flash_uploader::getRowClass() .'>
			<td>'.JText::_('E_S_USE_IMAGE_MAGIC').'</td>
			<td>'.tfuHTML::truefalseRadioList('use_image_magic',
			'class="inputbox"' . $disabled, $row->use_image_magic).'</td>
			<td>'.JText::_('E_D_USE_IMAGE_MAGIC'). $im_status . '</td>
		</tr>
';

echo HTML_joomla_flash_uploader::getDefaultInputBox('image_magic_path', $row->image_magic_path, '');	
echo HTML_joomla_flash_uploader::getDefaultRadioBox('hide_hidden_files', $row->hide_hidden_files, '');	
echo HTML_joomla_flash_uploader::getDefaultInputBox('exclude_directories', $row->exclude_directories, '');	
echo HTML_joomla_flash_uploader::getDefaultInputBox('fix_utf8', $row->fix_utf8, '');
// new 2.13
echo HTML_joomla_flash_uploader::getDefaultInputBox('info_text', $row->info_text, '');
echo HTML_joomla_flash_uploader::getDefaultInputBox('info_textcolor_R', $row->info_textcolor_R, '');
echo HTML_joomla_flash_uploader::getDefaultInputBox('info_textcolor_G', $row->info_textcolor_G, '');
echo HTML_joomla_flash_uploader::getDefaultInputBox('info_textcolor_B', $row->info_textcolor_B, '');
echo HTML_joomla_flash_uploader::getDefaultInputBox('info_font', $row->info_font, '');
echo HTML_joomla_flash_uploader::getDefaultInputBox('info_fontsize', $row->info_fontsize, '');

echo '
		<tr '. HTML_joomla_flash_uploader::getRowClass() .'>
			<td>'.JText::_('E_S_CREATION_DATE').'</td>
			<td>'.$row->creation_date.'</td>
			<td>'.JText::_('E_D_CREATION_DATE').'</td>
		</tr>
		<tr '. HTML_joomla_flash_uploader::getRowClass() .'>
			<td>'.JText::_('E_S_LAST_MODIFIED_DATE').'</td>
			<td>'.$row->last_modified_date.'</td>
			<td>'.JText::_('E_D_LAST_MODIFIED_DATE').'</td>
		</tr>
</table>
</div>
'; 

echo '
<div id="form3">
';
if (!($m != "" && $m != "s" && $m !="w")) { 
  echo '<p><div class="redreg">'.JText::_('E_H_NOT_REG').'</div></p>';
} else {
  echo '<br>';
}
echo '
<table class="adminlist">
	    <thead>
		<tr>
			<th width="20%">'.JText::_('E_H_SETTING').'</th>
			<th width="20%">'.JText::_('E_H_VALUE').'</th>
			<th width="60%">'.JText::_('E_H_DESCRIPTION').'</th>
		</tr>
		</thead>
		<tbody>';
echo HTML_joomla_flash_uploader::getDefaultInputBox('flash_title', $row->flash_title, '');	


echo '
	<tr '. HTML_joomla_flash_uploader::getRowClass() .'>
     	<td>'.JText::_('E_S_ENABLE_FILE_DOWNLOAD').'</td>
     	<td>'.tfuHTML::downloadRadioList('enable_file_download',
     	'class="inputbox"', $row->enable_file_download).'</td>
     	<td>'.JText::_('E_D_ENABLE_FILE_DOWNLOAD').'</td>
	</tr>
';


echo HTML_joomla_flash_uploader::getDefaultRadioBox('direct_download', $row->direct_download, '');	
echo HTML_joomla_flash_uploader::getDefaultRadioBox('download_multiple_files_as_zip', $row->download_multiple_files_as_zip, '');	
echo HTML_joomla_flash_uploader::getDefaultInputBox('zip_folder', $row->zip_folder, 'class="jfu_indent"');	
echo HTML_joomla_flash_uploader::getDefaultInputBox('zip_file_pattern', $row->zip_file_pattern, 'class="jfu_indent"');	

		echo '  	
    	<!-- /new 2.7 -->
		<tr '. HTML_joomla_flash_uploader::getRowClass() .'>
			<td>'.JText::_('E_S_DIRECTORY_FILE_LIMIT').'</td>
    	<td><input  onBlur="javascript:removeSpaces(this);checkValue(this, 0,0);" type="text" class="w250" maxsize="100"
				name="directory_file_limit" value="'.$row->directory_file_limit.'" /></td>
			<td>'.JText::_('E_D_DIRECTORY_FILE_LIMIT').'</td>
		</tr>
				<tr '. HTML_joomla_flash_uploader::getRowClass() .'>
			<td>'.JText::_('E_S_DIRECTORY_FILE_LIMIT_SIZE').'</td>
    	<td><input  onBlur="javascript:removeSpaces(this);checkValue(this, -1,0);" type="text" class="w250" maxsize="100"
				name="directory_file_limit_size" value="'.$row->directory_file_limit_size.'" /></td>
			<td>'.JText::_('E_D_DIRECTORY_FILE_LIMIT_SIZE').'</td>
		</tr>';
echo HTML_joomla_flash_uploader::getDefaultRadioBox('directory_file_limit_size_system', $row->directory_file_limit_size_system, 'class="jfu_indent"');
echo '
		<tr '. HTML_joomla_flash_uploader::getRowClass() .'>
			<td>'.JText::_('E_S_QUEUE_FILE_LIMIT').'</td>
			<td><input  onBlur="javascript:removeSpaces(this);checkValue(this, 0,0);" type="text" class="w250" maxsize="100"
				name="queue_file_limit" value="'.$row->queue_file_limit.'" /></td>
			<td>'.JText::_('E_D_QUEUE_FILE_LIMIT').'</td>
		</tr>
		<tr '. HTML_joomla_flash_uploader::getRowClass() .'>
			<td>'.JText::_('E_S_QUEUE_FILE_LIMIT_SIZE').'</td>
			<td><input  onBlur="javascript:removeSpaces(this);checkValue(this, 0,0);" type="text" class="w250" maxsize="100"
				name="queue_file_limit_size" value="'.$row->queue_file_limit_size.'" /></td>
			<td>'.JText::_('E_D_QUEUE_FILE_LIMIT_SIZE').'</td>
		</tr>
';
echo HTML_joomla_flash_uploader::getDefaultInputBox('preview_textfile_extensions', $row->preview_textfile_extensions, '');	
echo HTML_joomla_flash_uploader::getDefaultInputBox('edit_textfile_extensions', $row->edit_textfile_extensions, '');	
// # 2.14
echo HTML_joomla_flash_uploader::getDefaultRadioBox('enable_file_creation', $row->enable_file_creation, '');
echo '<tr>
		<td class="key jfu_indent">'.JText::_('E_S_ENABLE_FILE_CREATION_EXTENSIONS').'</td>
		<td>'.tfuHTML::showFileCreateSelectBox('enable_file_creation_extensions',
			'class="inputbox"',$row->enable_file_creation_extensions) . '</td>
		<td>'.JText::_('E_D_ENABLE_FILE_CREATION_EXTENSIONS').'</td>
	 </tr>';
// end 2.14
echo HTML_joomla_flash_uploader::getDefaultInputBox('allowed_view_file_extensions', $row->allowed_view_file_extensions, '');	
echo HTML_joomla_flash_uploader::getDefaultInputBox('forbidden_view_file_extensions', $row->forbidden_view_file_extensions, '');	
echo HTML_joomla_flash_uploader::getDefaultRadioBox('show_full_url_for_selected_file', $row->show_full_url_for_selected_file, '');	

echo '	
			<tr '. HTML_joomla_flash_uploader::getRowClass() .'>
			<td>'.JText::_('E_S_UPLOAD_FINISHED_JS_URL').'</td>
			<td><input  onBlur="javascript:removeSpaces(this);" type="text" class="w250" maxsize="100"
				name="upload_finished_js_url" value="'.$row->upload_finished_js_url.'" /></td>
			<td>'.JText::_('E_D_UPLOAD_FINISHED_JS_URL').' ' . JText::_("E_D_JS_TEXT").  '</td>
		</tr>
		<tr '. HTML_joomla_flash_uploader::getRowClass() .'>
			<td>'.JText::_('E_S_PREVIEW_SELECT_JS_URL').'</td>
			<td><input  onBlur="javascript:removeSpaces(this);" type="text" class="w250" maxsize="100"
				name="preview_select_js_url" value="'.$row->preview_select_js_url.'" /></td>
			<td>'.JText::_('E_D_PREVIEW_SELECT_JS_URL').' ' . JText::_("E_D_JS_TEXT"). '</td>
		</tr>
		<tr '. HTML_joomla_flash_uploader::getRowClass() .'>
			<td>'.JText::_('E_S_DELETE_JS_URL').'</td>
			<td><input  onBlur="javascript:removeSpaces(this);" type="text" class="w250" maxsize="100"
				name="delete_js_url" value="'.$row->delete_js_url.'" /></td>
			<td>'.JText::_('E_D_DELETE_JS_URL').' ' . JText::_("E_D_JS_TEXT"). '</td>
		</tr>
		<tr '. HTML_joomla_flash_uploader::getRowClass() .'>
			<td>'.JText::_('E_S_JS_CHANGE_FOLDER').'</td>
			<td><input  onBlur="javascript:removeSpaces(this);" type="text" class="w250" maxsize="100"
				name="js_change_folder" value="'.$row->js_change_folder.'" /></td>
			<td>'.JText::_('E_D_JS_CHANGE_FOLDER').' ' . JText::_("E_D_JS_TEXT"). '</td>
		</tr>
		<!-- new 2.7 -->
			<tr '. HTML_joomla_flash_uploader::getRowClass() .'>
			<td>'.JText::_('E_S_JS_CREATE_FOLDER').'</td>
			<td><input  onBlur="javascript:removeSpaces(this);" type="text" class="w250" maxsize="100"
				name="js_create_folder" value="'.$row->js_create_folder.'" /></td>
			<td>'.JText::_('E_D_JS_CREATE_FOLDER').' ' . JText::_("E_D_JS_TEXT"). '</td>
		</tr>
			<tr '. HTML_joomla_flash_uploader::getRowClass() .'>
			<td>'.JText::_('E_S_JS_RENAME_FOLDER').'</td>
			<td><input  onBlur="javascript:removeSpaces(this);" type="text" class="w250" maxsize="100"
				name="js_rename_folder" value="'.$row->js_rename_folder.'" /></td>
			<td>'.JText::_('E_D_JS_RENAME_FOLDER').' ' . JText::_("E_D_JS_TEXT"). '</td>
		</tr>
			<tr '. HTML_joomla_flash_uploader::getRowClass() .'>
			<td>'.JText::_('E_S_JS_DELETE_FOLDER').'</td>
			<td><input  onBlur="javascript:removeSpaces(this);" type="text" class="w250" maxsize="100"
				name="js_delete_folder" value="'.$row->js_delete_folder.'" /></td>
			<td>'.JText::_('E_D_JS_DELETE_FOLDER').' ' . JText::_("E_D_JS_TEXT"). '</td>
		</tr>
			<tr '. HTML_joomla_flash_uploader::getRowClass() .'>
			<td>'.JText::_('E_S_JS_COPYMOVE').'</td>
			<td><input  onBlur="javascript:removeSpaces(this);" type="text" class="w250" maxsize="100"
				name="js_copymove" value="'.$row->js_copymove.'" /></td>
			<td>'.JText::_('E_D_JS_COPYMOVE').' ' . JText::_("E_D_JS_TEXT").'</td>
		</tr>
 </tbody>
 </table>		
	 <h3 style="text-align:left;">'.JText::_('E_H3_REG_PROF').'</h3>
      <table class="adminlist">
        <thead>
	      <tr>
			<th width="20%">'.JText::_('E_H_SETTING').'</th>
			<th width="20%">'.JText::_('E_H_VALUE').'</th>
			<th width="60%">'.JText::_('E_H_DESCRIPTION').'</th>
		  </tr>	
	    </thead>
	    <tbody>
    <tr '. HTML_joomla_flash_uploader::getRowClass() .'>
			<td>'.JText::_('E_S_BIG_PROGRESSBAR').'</td>
			<td>'.tfuHTML::truefalseRadioList('big_progressbar',
			'class="inputbox"', $row->big_progressbar).'</td>
			<td>'.JText::_('E_D_BIG_PROGRESSBAR').'</td>
		</tr>
			<tr '. HTML_joomla_flash_uploader::getRowClass() .'>
			<td>'.JText::_('E_S_IMG_PROGRESSBAR').'</td>
			<td><input onBlur="javascript:removeSpaces(this);" type="text" class="w250" maxsize="100"
				name="img_progressbar" value="'.$row->img_progressbar.'" /></td>
			<td>'.JText::_('E_D_IMG_PROGRESSBAR').' '.JText::_('E_D_PROGRESSBAR_ADD').'</td>
		</tr> 
		<tr '. HTML_joomla_flash_uploader::getRowClass() .'>
			<td>'.JText::_('E_S_IMG_PROGRESSBAR_BACK').'</td>
			<td><input onBlur="javascript:removeSpaces(this);" type="text" class="w250" maxsize="100"
				name="img_progressbar_back" value="'.$row->img_progressbar_back.'" /></td>
			<td>'.JText::_('E_D_IMG_PROGRESSBAR_BACK').' '.JText::_('E_D_PROGRESSBAR_ADD').'</td>
		</tr> 
		<tr '. HTML_joomla_flash_uploader::getRowClass() .'>
			<td>'.JText::_('E_S_IMG_PROGRESSBAR_ANIM').'</td>
			<td><input onBlur="javascript:removeSpaces(this);" type="text" class="w250" maxsize="100"
				name="img_progressbar_anim" value="'.$row->img_progressbar_anim.'" /></td>
			<td>'.JText::_('E_D_IMG_PROGRESSBAR_ANIM').' '.JText::_('E_D_PROGRESSBAR_ADD').'</td>
		</tr> 

    <tr '. HTML_joomla_flash_uploader::getRowClass() .'>
			<td>'.JText::_('E_S_ENABLE_FOLDER_MOVECOPY').'</td>
			<td>'.tfuHTML::truefalseRadioList('enable_folder_movecopy',
			'class="inputbox"', $row->enable_folder_movecopy).'</td>
			<td>'.JText::_('E_D_ENABLE_FOLDER_MOVECOPY').'</td>
		</tr>
		<tr '. HTML_joomla_flash_uploader::getRowClass() .'>
			<td>'.JText::_('E_S_ENABLE_FILE_MOVECOPY').'</td>
			<td>'.tfuHTML::truefalseRadioList('enable_file_movecopy',
			'class="inputbox"', $row->enable_file_movecopy).'</td>
			<td>'.JText::_('E_D_ENABLE_FILE_MOVECOPY').'</td>
		</tr>
        <tr '. HTML_joomla_flash_uploader::getRowClass() .'>
			<td>'.JText::_('E_S_DESCRIPTION_MODE').'</td>
			<td>'.tfuHTML::truefalseRadioList('description_mode',
			'class="inputbox"', $row->description_mode).'</td>
			<td>'.JText::_('E_D_DESCRIPTION_MODE').'</td>
		</tr> 
         <tr '. HTML_joomla_flash_uploader::getRowClass() .'>
			<td>'.JText::_('E_S_DESCRIPTION_MODE_SHOW_DEFAULT').'</td>
			<td>'.tfuHTML::truefalseRadioList('description_mode_show_default',
			'class="inputbox"', $row->description_mode_show_default).'</td>
			<td>'.JText::_('E_D_DESCRIPTION_MODE_SHOW_DEFAULT').'</td>
		</tr>   
         <tr '. HTML_joomla_flash_uploader::getRowClass() .'>
			<td>'.JText::_('E_S_DESCRIPTION_MODE_MANDATORY').'</td>
			<td>'.tfuHTML::truefalseRadioList('description_mode_mandatory',
			'class="inputbox"', $row->description_mode_mandatory).'</td>
			<td>'.JText::_('E_D_DESCRIPTION_MODE_MANDATORY').'</td>
		</tr>   	
         <tr '. HTML_joomla_flash_uploader::getRowClass() .'>
			<td>'.JText::_('E_S_DESCRIPTION_MODE_STORE').'</td>
			<td>'.tfuHTML::modeRadioList('description_mode_store',
			'class="inputbox"', $row->description_mode_store).'</td>
			<td>'.JText::_('E_D_DESCRIPTION_MODE_STORE').'</td>
		</tr>      
          	
   </tr>
			<tr '. HTML_joomla_flash_uploader::getRowClass() .'>
			<td>'.JText::_('E_S_FORM_FIELDS').'</td>
			<td><input onBlur="javascript:removeSpaces(this);" type="text" class="w250" maxsize="100"
				name="form_fields" value="'.$row->form_fields.'" /></td>
			<td>'.JText::_('E_D_FORM_FIELDS').'</td>
		</tr>       	
          	
		<tr '. HTML_joomla_flash_uploader::getRowClass() .'>
			<td>'.JText::_('E_S_SWF_TEXT').'</td>
			<td><textarea rows="4" name="swf_text" id="swf_text"
				class="w250">'. $row->swf_text .'</textarea></td>
			<td>'.JText::_('E_D_SWF_TEXT').'</td>
		</tr>
	</tbody>
</table>
</div>
';
if ($row->id != '1') {
echo '
<script type="text/javascript" src="components/com_jfuploader/js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="components/com_jfuploader/js/jquery-ui-1.7.2.custom.min.js"></script>
<script>
     $jfu = jQuery.noConflict();
</script>
<script type="text/javascript" src="components/com_jfuploader/js/dragdrop_jfu.js"></script>

<div id="form4">';
if ($row->gid == '') {
echo '
<dl id="system-message">
<dt class="message">Message</dt>
<dd class="message message fade">
	<ul>

		<li>'.JText::_('E_USER_WARN').'</li>
	</ul>
</dd>
</dl>
';
} else {

echo '
<br><p style="text-align:left;">'.JText::_('E_USER_MAIN').'</p>';
echo '
<p>'.JText::_('E_USER_HELP').'</p><p>
'.JText::_('U_FILTER').' <input id="filter" onkeyup="doFilterList(this, \'list_1\');doFilterList(this, \'list_2\');" /> &nbsp;<a onclick="blur(); return resetListFilter();" href="#"><img src="components/com_jfuploader/images/cancel.png" style="margin:-3px;" height=14 border="0" /></a></p>
<fieldset class="batch" style="float:left;margin-top:0px;">       
       <legend>Add users to this profile</legend>
<div class="panel">
<h3>'.JText::_('E_USER_AV_USER').'</h3>
<p>'.JText::_('E_USER_SELECT'). ': 
    <a href="#" onclick=\'return $jfu.dds.selectAll("list_1");\'>'.JText::_('E_USER_ALL').'</a> 
    <a href="#" onclick=\'return $jfu.dds.selectNone("list_1");\'>'.JText::_('E_USER_NONE').'</a> 
    <a href="#" onclick=\'return $jfu.dds.selectInvert("list_1");\'>'.JText::_('E_USER_INVERT').'</a>

</p>
<ul id="list_1"><li class="invisible"></li>' . $a_user . '
</ul>
</div>

<div class="panel">
<h3>'.JText::_('E_USER_AS_USER').'</h3>
<p>'.JText::_('E_USER_SELECT') . ': 
    <a href="#" onclick=\'return $jfu.dds.selectAll("list_2");\'>'.JText::_('E_USER_ALL').'</a> 
    <a href="#" onclick=\'return $jfu.dds.selectNone("list_2");\'>'.JText::_('E_USER_NONE').'</a> 
    <a href="#" onclick=\'return $jfu.dds.selectInvert("list_2");\'>'.JText::_('E_USER_INVERT').'</a>

</p>

<ul id="list_2"><li class="invisible"></li>' . $p_user . '
</ul>
</div>';

echo '
</fieldset>
';

echo '
       <fieldset class="batch" style="margin-top:0px;margin-left:20px;float:left;">       
       <legend>Add user groups to this profile</legend>
        <ul class="checklist usergroups ">
';

foreach ($f_groups as $group) {
$selected = ($group->jselected == "true") ? " checked=checked " : "";  

echo '
	<li>
		<input name="jgroupfront[]" value="'.$group->value.'" id="jgroupfront_'.$group->value.'" ' . $selected . ' type="checkbox">
		<label for="jgroupfront_'.$group->value.'">';
for ($i = 0; $i < $group->level; $i++) {
    echo '<span class="gi">|—</span>';
}		
echo      $group->text .'</label>
	</li>
';
}

echo '</ul>';
if (count($f_groups) == 0) {
  echo '<div class="message fade">'.JText::_('U_USER_GROUP_ALL_ASSIGNED_IN_A_GROUP').'</div>';
} else {
  echo '<div class="" style="clear:both;padding-top:10px;"><p>'.JText::_('U_AVAILABLE_LIST_GROUP').'</p></div>';
}
echo '
</fieldset></div>
';
}

echo '
</div>
';
echo <<< HTML
    <input type="hidden" name="list_2_sent" id="list_2_sent" value="" />
    <input type="hidden" name="list_2_changed" id="list_2_changed" value="no" />
HTML;
}
echo <<< HTML
    <input type="hidden" name="id" value="$row->id" />
    <input type="hidden" name="creation_date" value="$row->creation_date" />
    <input type="hidden" name="option" value="com_jfuploader" />
    <input type="hidden" name="task" value="saveConfig" />
</form>
HTML;

echo '<script type="text/javascript">';

if ($showUserPage) {
echo 'showform("form4");';
} else {
echo 'showform("form1");';
}
echo '
testFolder();
</script>
';
}


function listUsers($rows, $data) {

$us = $data["users"];
$gr = $data["groups"];
$grback = $data["backendgroups"];

$pr = $data["profiles"];
$prgr = $data["profilesgroup"];
$prall = $data["allprofiles"];

$showAdd = $data["showAdd"];
$showAddAdmin = $data["showAddAdmin"];

echo '
<script type="text/javascript" src="components/com_jfuploader/js/jfu.js"></script>
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<h2>'.JText::_('U_TITLE').'</h2>
	'.JText::_('U_TEXT').'	
      <div style="width:540px;float:left;">
       <fieldset class="batch">       
       <legend>'.JText::_('U_ADD_USER_TITLE').'</legend>
        <table cellpadding="4" cellspacing="0" border="0" class="adminlist">
          <thead> 
            <tr>
			   <th align="left" width="250">'.JText::_('U_SEL_PROFILE').'</th>
			   <th align="left" width="230">'.JText::_('U_SEL_USER').'</th>
			   <th align="left" width="40"> </th> 
            </tr>
          </thead> 
      <tr>
         <td style="vertical-align:top;">'.$pr.'</td>
         <td style="vertical-align:top;">'.$us.'</td>
         <td style="vertical-align:top;text-align:center;">';
         if ($showAdd) {
           echo '<a href="#adduser" onclick="return submitform(\'addUser\')"><b>'.JText::_('U_ADD_USER').'</b></a>';
         }
         echo '&nbsp;</td>
      </tr>
    </table>
</fieldset>
    </div>   
<div style="clear:both"></div>

<div style="width:540px;float:left;">

       <fieldset class="batch">       
       <legend>'.JText::_('U_ADD_USER_GROUP_TITLE').'</legend>
        <table cellpadding="4" cellspacing="0" border="0" class="adminlist">
          <thead> 
            <tr>
			   <th align="left" width="250">'.JText::_('U_SEL_PROFILE').'</th>
			   <th align="left" width="230">'.JText::_('U_SEL_USER_GROUP').'</th>
			   <th align="left" width="40"> </th> 
            </tr>
          </thead> 
      <tr>
         <td style="vertical-align:top;">'.$prgr.'</td>
         <td style="vertical-align:top;"><ul class="checklist usergroups ">
';

foreach ($gr as $group) {

echo '
	<li>
		<input name="jgroup[]" value="'.$group->value.'" id="group_'.$group->value.'" type="checkbox">
		<label for="group_'.$group->value.'">';
for ($i = 0; $i < $group->level; $i++) {
    echo '<span class="gi">|—</span>';
}		
echo      $group->text .'</label>
	</li>
';
}

echo '
</ul>	</td>
         <td style="vertical-align:top;text-align:center;">';
         if ($showAdd) {
           echo '<a href="#addgroup" onclick="return submitform(\'addGroup\')"><b>'.JText::_('U_ADD_USER').'</b></a>';
         }
         echo '&nbsp;</td>
      </tr>
    </table>
</fieldset>
</div>   
<div style="clear:both"></div>




<div style="width:540px;float:left;">

       <fieldset class="batch">       
       <legend>'.JText::_('U_ADD_USER_GROUP_B_TITLE').'</legend>
        <table cellpadding="4" cellspacing="0" border="0" class="adminlist">
          <thead> 
            <tr>
			   <th align="left" width="250">'.JText::_('U_SEL_PROFILE').'</th>
			   <th align="left" width="230">'.JText::_('U_SEL_USER_GROUP').'</th>
			   <th align="left" width="40"> </th> 
            </tr>
          </thead> 
      <tr>
         <td style="vertical-align:top;">'.$prall.'</td>
         <td style="vertical-align:top;"><ul class="checklist usergroups ">
';

foreach ($grback as $group) {
echo '
	<li>
		<input name="jgroupback[]" value="'.$group->value.'" id="groupback_'.$group->value.'" type="checkbox">
		<label for="groupback_'.$group->value.'">';
for ($i = 0; $i < $group->level; $i++) {
    echo '<span class="gi">|—</span>';
}		
echo      $group->text .'</label>
	</li>
';
}

echo '</ul>';
if (!$showAddAdmin) {
  echo '<div class="message fade">'.JText::_('U_USER_GROUP_ALL_ASSIGNED').'</div>';
}
echo '</td>
         <td style="vertical-align:top;text-align:center;">';
         if ($showAddAdmin) {
           echo '<a href="#addbackgroup" onclick="return submitform(\'addBackGroup\')"><b>'.JText::_('U_ADD_USER').'</b></a>';
         }
         echo '&nbsp;</td>
      </tr>
    </table>
</fieldset>
</div>   
<div style="clear:both"></div>







<div style="width:540px;float:left;">
<fieldset class="batch"> 
	<legend>'.JText::_('U_MAPPINGS').'</legend>   
  <p>'.JText::_('U_FILTER').' <input name="filter" onkeyup="doFilter(this, \'usermappings\');" type="text" /> &nbsp;<a onclick="blur(); return resetUserFilter();" href="#"><img src="components/com_jfuploader/images/cancel.png" style="margin:-3px;" height=14 border="0" /></a></p>
  <table cellpadding="4" cellspacing="0" border="0" class="adminlist" id="usermappings">
    <thead> 
       <tr>
          <th width="150">'.JText::_('U_LOCATION_TITLE').'</th>
          <th width="150">'.JText::_('U_PROFILE').'</th>
          <th width="150">'.JText::_('U_USER').'</th>
          <th width="150">'.JText::_('U_USER_GROUP').'</th>
          <th width="40">'.JText::_('U_DELETE').'</th> 
       </tr>
    </thead>
';
        $i = 0;
        if (count($rows)==0) {
         echo '<tr class="row0">';
         echo '<td colspan="4"><center>'.JText::_('U_NO_MAPPINGS').'</center></td></tr>';
        } else {
        foreach ($rows as $row) {
        $row->location = ($row->location == 'site') ? JText::_('U_LOCATION_SITE') :  JText::_('U_LOCATION_ADMIN');
        
           $evenodd = $i % 2;
echo <<< HTML
      <tr class="row$evenodd">
        <td>$row->location </td>
        <td>$row->config_name </td>
        <td>$row->username&nbsp;</td>
        <td>$row->title&nbsp;</td>
        <td style="text-align:center;">
        <!-- I stick to the joomla way - therefore not very nice here ... -->
        <input style="display:none" type="checkbox" id="cb$row->myid" name="cid[]"
		               value="$row->myid"
               onclick="isChecked(this.checked);" />
				 <a href="#deleteuser" onclick="return listItemTask('cb$row->myid','deleteUser')">
				    <img src="components/com_jfuploader/images/publish_x.png" border="0" /></a>
        </td></tr>
HTML;
           $i++;
          }
        }
        
        
echo '
      </table> 
</fieldset>      
      </div>
      
      <input type="hidden" name="task" value="" />
      <input type="hidden" name="option" value="com_jfuploader"/>
      <input type="hidden" name="boxchecked" value="0" />
  </form>
';
}

function showHelpRegister() {
global $m,$mybasedir;
$canDo = JFUHelper::getActions();

$language = JFactory::getLanguage();    
$lang = ($language->getTag() == 'de-DE') ? 'de_DE' : 'en_US';

echo '

<style>
.install {
	margin-left: 5px;
	margin-right: 5px;
	margin-top: 10px;
	margin-bottom: 10px;
	padding: 10px;
	text-align:left;
	border: 1px solid #cccccc;
	width:720px;
	background: #F1F1F1;
}

.h3_help {
text-align:left;
border-bottom: 2px solid #DDDDDD;
}
</style>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<h2>'.JText::_('H_TITLE').'</h2>

	<!-- Facebook like button -->	  
  <p>	 
  <div id="fb-root"></div>
  <script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/'.$lang.'/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, \'script\', \'facebook-jssdk\'));</script>
 <div class="fb-like-box" data-href="http://www.facebook.com/tinywebgallery" data-width="700" data-border-color="white" data-show-faces="false" data-stream="true" data-header="false"></div>
	</p>
	<!-- end Facebook like button -->	

	<h3 class="h3_help">'.JText::_('H_H3_HELP').'</h3> 
	'.JText::_('H_H_TEXT').'
	<div style="text-align:left;float:left;">
	<ul>
		<li>'.JText::_('H_H_OVERVIEW').'</li>
		<li>'.JText::_('H_H_HELP').'</li>
		<li>'.JText::_('H_H_TWG').'</li>
		<li>'.JText::_('H_H_FORUM').'</li>
		<li>'.JText::_('H_H_CONFIG').'</li>
		<li>'.JText::_('H_H_MAMBOT').'</li>
		<li>'.JText::_('H_H_REG').'</li>
	</ul>
	</div>
    <h3 class="h3_help">'.JText::_('H_L_TITLE').'</h3>
	  <div style="text-align:left;float:left;">	
	  '.JText::_('H_L_TEXT').'   
	  <div class="install" style="width:600px;margin-left:50px;">
	  <b>'.JText::_('H_L_INFOS').'</b><p> 
';
$limit = return_kbytes(ini_get('memory_limit'));

echo JText::_('H_L_NAME') . " " . $_SERVER['SERVER_NAME'] . "<br>";
	echo JText::_('H_L_LIMIT') ." " . getMaximumUploadSize(). "<br>"; 
	echo JText::_('H_L_MEMORY') . " " . $limit . " <br>"; 	
  echo JText::_('H_L_RESOLUTION') ." ";
  	if (!$limit) {
		  echo  '<font color="green">No limit</font>';
	  } else {
	    $xy = $limit * 1024 / 6.6;
	    $x = floor( sqrt ($xy / 0.75));
	    $y = floor( sqrt($xy / 1.33));
	    
	    if ($x > 4000) {
	      echo "<font color='green'>~ " . $x . " x " . $y . "</font>"; 
      } else if ($x > 2000) {
        echo "<font color='orange'>~ " . $x . " x " . $y . "</font>"; 
      } else {
        echo "<font color='red'>~ " . $x . " x " . $y . "</font>"; 
      }   
	  }	
    echo "<br>";
	echo JText::_('H_L_INPUT') . " " . ini_get('max_input_time') . " s<br>"; 	
	echo JText::_('H_L_EXECUT') . " " . ini_get('max_execution_time') . " s<br>"; 
	echo JText::_('H_L_SOCKET') . " " . ini_get('default_socket_timeout') . " s";

if ($canDo->get('core.admin')) {
    if (substr(@php_uname(), 0, 7) != "Windows"){  
        echo '<p>' . JText::_('H_L_CHMOD1') . ' ' . substr(sprintf('%o', @fileperms(dirname(__FILE__) . "/tfu/tfu_config.php")), -4) ;  
        echo '<br>' . JText::_('H_L_CHMOD2');
        echo '</p><p>
        <button onclick="this.form.task.value=\'chmod755\';this.form.submit();">'.JText::_('H_L_CHMOD755').'</button> 
        <button onclick="this.form.task.value=\'chmod644\';this.form.submit();">'.JText::_('H_L_CHMOD644').'</button> 
        <button onclick="this.form.task.value=\'chmod666\';this.form.submit();">'.JText::_('H_L_CHMOD666').'</button> 
        <button onclick="this.form.task.value=\'chmod777\';this.form.submit();">'.JText::_('H_L_CHMOD777').'</button> 
        </p>
        ';
    }
} else {
 echo '<p>' . JText::_('ACL_MANAGE_NEEDED'). '</p>';
}
    
echo '
	  </p>
	  </div>
	</div>
	
	<h3 class="h3_help">'.JText::_('H_R_TITLE').'</h3>';
if ($canDo->get('core.admin')) {
echo '	
	<div style="text-align:left;float:left;">
';
if ($m == "") {
echo JText::_('H_R_TEXT') .'<ul>
  <li>'.JText::_('H_R_FREEWARE').'</li
  <li>'.JText::_('H_R_REG').'</li></ul>
	  <div class="install" style="width:600px;margin-left:50px;">'.JText::_('H_R_BONUS').'</div>';
printf(JText::_('H_R_REG_10'), "<a href=\"http://www.tinywebgallery.com/en/register_tfu.php\"><b>", "</b></a>");	  
echo '<p>'.JText::_('H_R_REG_HOWTO').'</p>
<div class="install" style="width:600px;margin-left:50px;">
&lt;?php
<table><tr><td>
$l</td><td>=" <input type="text" name="l" size=100> ";</td></tr><tr><td>
$d</td><td>=" <input type="text" name="d" size=100> ";</td></tr><tr><td>
$s</td><td>=" <input type="text" name="s" size=100> ";</td></tr></table>
?&gt;
<p>
<input type="hidden" name="task" value="register" />
<button onclick="this.form.submit();">'.JText::_('H_R_REGISTER').'</button>
</p>
</div>
';
} else if ($m != "" && $m != "s" && $m !="w" ) {
include  dirname(__FILE__) . '/' .$mybasedir . "tfu/twg.lic.php";
echo JText::_('H_R_REG_TO') . " <b>$l</b>";
if ($l == $d) {
  echo " (Enterprise Edition License)";
} else if (strpos($d, "TWG_PROFESSIONAL") !== false) {
  echo " (Professional Edition License)";
} else if (strpos($d, "TWG_SOURCE") !== false) {
  echo " (Source code Edition License)";
} else {
  echo " (Standart Edition License)";
}
echo "<p>" . JText::_('H_R_REG_DEL');
echo '
<input type="hidden" name="task" value="dellic" />
<button onclick="this.form.submit();">'.JText::_('H_R_UNREGISTER').'</button>
</p>';
} else {
echo "<p>" . JText::_('H_R_REG_WRONG');
echo '
<input type="hidden" name="task" value="dellic" />
<button onclick="this.form.submit();">'.JText::_('H_R_UNREGISTER').'</button>
</p>';
}
echo <<< HTML
</div>
	      <input type="hidden" name="option" value="com_jfuploader"/>
	      <input type="hidden" name="boxchecked" value="0" /> 
HTML;

} else {
 echo JText::_('ACL_MANAGE_NEEDED');
}

echo '
	<div style="clear:both;"></div>	
  <h3 class="h3_help">'.JText::_('E_LOG_HEADER').'</h3>';
  if ($canDo->get('core.admin')) {
  echo '
	  <div style="text-align:left;float:left;">
	  '.JText::_('E_LOG_INTRO').'  
	  </div> 
	 <div style="clear:both;"></div>	
   <p> 
	 <div class="logcontainer">';
	 $debugfile =  dirname(__FILE__) . '/' . $mybasedir . "tfu/tfu.log";
	 if (file_exists($debugfile)) {
     $data = file_get_contents($debugfile);
     echo str_replace("\n",'<br>', $data);
   } else {
     echo 'No debug found';
   } 
	 echo '
	 </div>
	 </p>
   <p>&nbsp;<br>
	 <a class="jfu_button" href="#deletelog" onclick="return submitform(\'deletelog\')">
	 '.JText::_('E_LOG_BUTTON').' 
	 </a>
   <p>';
} else {
  echo JText::_('ACL_MANAGE_NEEDED');
}   
echo '   
</form> 
';

} // show help register

function showPlugins($plugins, $show_hint) {
global $m,$mybasedir;

echo'

<style>
.h3_help {
text-align:left;
border-bottom: 2px solid #DDDDDD;
}
</style>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<h2>'.JText::_('H_P_TITLE').'</h2>
'.JText::_('H_P_TEXT').'
	<h3 class="h3_help">'.JText::_('H_P_TITLE_INST').'</h3>
	
 <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist" >
       <thead>
       <tr>
          <th align="left" width="13%">'.JText::_('DB_PLUGIN_FILENAME').'</th>
          <th align="left" width="20%">'.JText::_('H_P_TITLE_NAME').'</th>
          <th align="left" width="60%">'.JText::_('H_P_TITLE_DESC').'</th>  
          <th align="left" width="7%">' .JText::_('H_P_PLUGIN').'</th>  
          </tr>
         </thead>
';
 if (count($plugins) > 0) {
        $i = 0;
        foreach ($plugins as $row) {
           $evenodd = $i++ % 2;        
echo '          
      <tr class="row'.$evenodd.'">  
        <td>'.$row[0].'</td>
        <td>'.$row[1].'</td>
        <td>'.$row[2].'</td>
        <td '.$row[4].'>'.$row[3].'</td>
        </tr>';
        }
} else {
echo '<td colspan=4><center>'.JText::_('H_P_NO_PLUGIN').'</center></td>';
}     
echo '</table>';
if ($show_hint) {
echo '<p>'.JText::_('H_P_PLUGIN_UPDATE').'</p>';
}

 
if (($m != "" && $m != "s" && $m !="w")) { 
  $pluginfile = dirname(__FILE__) . '/' . $mybasedir . "tfu/db_plugin/db_plugin_view.php";
  if (file_exists($pluginfile)) {
    echo '<br><h3 class="h3_help">'.JText::_('DB_PLUGIN_HEADER').'</h3>';
    include $pluginfile;
  }
} 
echo '
      <input type="hidden" name="task" value="" />
      <input type="hidden" name="option" value="com_jfuploader"/>
      <input type="hidden" name="boxchecked" value="0" />
  </form>'; 
} // end showPlugins


} // class
?>