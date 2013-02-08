<?php

//echo '<p>[ <a href="?option=com_discussit&action=resetapi">factory reset</a> ]</p>';

jimport ( 'joomla.form.form' );
$plugin = JPluginHelper::getPlugin ( 'content', 'plg_discussit' );
$plgparams = new JRegistry ();
$plgparams->loadString ( $plugin->params );
include (JPATH_PLUGINS . DS . 'content' . DS . 'plg_discussit' . DS . 'OAuth' . DS . 'discussit.php');
$siteID = di_getparam ( 'siteID' );
$widgetID = di_getparam ( 'widgetID' );
$clientID = di_getparam ( 'clientID' );
$clientSecret = di_getparam ( 'clientSecret' );
$refreshToken = di_getparam ( 'refreshToken' );

$action = JRequest::getVar ( 'action' );

switch ($action) {
	case 'enterapiform' :
		
		echo '
		<style type="text/css">html{overflow:auto !important}</style>
		
		<form action="?option=com_discussit&action=submitapi&tmpl=component" id="adminForm" method="post"> 		
			<img src="components/com_discussit/media/images/DiscussitLogo_square.png" />	
			<br /> <br />
			<table width="560px">	 
			<tr><td><label for="comment">Enter API Key Here:</label></td><td><input type = "text" id="key" name="key" size="54"></td><td colspan=""><input type="submit" name="submit" onclick="window.parent.document.getElementById( \'sbox-window\' ).close;"/></td></tr> 
			</table></form>		
			<div id="console">		
			';
		
		break;
	
	case 'submitapi' :
		
		echo '<style type="text/css">html{overflow:auto !important}</style>';
		
		$mtime = microtime ();
		$mtime = explode ( " ", $mtime );
		$mtime = $mtime [1] + $mtime [0];
		$starttime = $mtime;
		
		$apikey = trim ( JRequest::getVar ( 'key' ) );
		
		$di = new discussit ();
		
		$details = $di->get_details_from_api_key ( $apikey );
		
		if (! in_array ( 'curl', get_loaded_extensions () )) {
			
			die ( 'Error - cURL not forund. <hr /> cURL must be installed, enabled and not blocked for DiscussIt to function properly. ' );
		
		}
		
		echo '<br />Retrieved: ' . date ( 'Y-m-d D H:i:s' ) . '<hr />';
		
		$mtime = microtime ();
		$mtime = explode ( " ", $mtime );
		$mtime = $mtime [1] + $mtime [0];
		$endtime = $mtime;
		$totaltime = ($endtime - $starttime);
		echo "Request completed in $totaltime seconds <hr />";
		
		if ($details->siteID == '' || $details->widgetID == '' || $details->clientID == '' || $details->clientSecret == '' || $details->refreshToken == '') {
			echo 'Error - Incorrect API key click <a href="?option=com_discussit&action=enterapiform&tmpl=component">here</ a> to try again.';
		
		} else {
			
			foreach ( $details as $key => $detail ) {
				
				if ($key != 'accessToken') {
					
					$query = "UPDATE #__di_settings SET val = '$detail' WHERE name = '$key'";
					
					$db = JFactory::getDBO ();
					$db->setQuery ( $query );
					$db->query ();
				
				}
			}
				$rpcadd = JURI::root() . 'components/com_discussit/xmlrpc.php';
			
			$endpoint = $di->set_endpoint ( $details->widgetID, $rpcadd, $details->siteID );
			
			echo 'Success!';
		
		}
		
		break;
	
	case 'resetapi' :
		
		$query = "UPDATE #__di_settings SET val = '' WHERE name IN ('siteID', 'widgetID', 'clientID', 'clientSecret', 'refreshToken' )";
		$db = JFactory::getDBO ();
		$db->setQuery ( $query );
		$db->query ();
		
		echo 'Keys reset - <a href="?option=com_discussit">Go Back</a>';
		
		break;
	
	default :
		
		if ($siteID == '' || $widgetID == '' || $clientID == '' || $clientSecret == '' || $refreshToken == '') {
			
			JHTML::_ ( 'behavior.modal' );
			
			$link = '?option=com_discussit&action=enterapiform&tmpl=component';
			//echo '<fieldset class="adminform"><legend>Hello</legend>';
			//echo '<div id="topBar" class="width-90">';
			echo '<a rel="{handler: \'iframe\', size: {x: 602, y: 250}, onClose: function(){location.reload(true)}}" href="' . $link . '" class="modal">Click here to enter your API key.</a>';
			
			echo '<br /><br /><hr /><br /><br />';
		
		} else {

			JHTML::_ ( 'behavior.formvalidation' );
			?>
			
			
			
			<form action="?option=com_discussit&action=updateSettings" class="form-validate" method="post">
			<div id="myGroupOfFields" class="width-40 fltlft">
			<fieldset class="adminform"><legend><?php echo JText::_('Basic Options'); ?></legend>
			<ul class="adminformlist">
			
			<?php
			
			$db = JFactory::getDBO ();
			$query = "SELECT * from #__di_settings ORDER BY id";
			$db->setQuery ( $query );
			$rows = $db->loadObjectList ( 'id' );
			
			echo '<table>';
			foreach ( $rows as $row ) {
				
				echo renderBlock ( $row->name, $row->label, $row->type, $row->val, $row->desc );
			
			}
			echo '</table>';
						
			?>
		        
			<li><input type="submit" name="SubmitButton" value="Save" /></li>
			<li><input type="hidden" name="option" value="com_discussit" /></li>
			<li><input type="hidden" name="controller" value="admin-form" /></li>
			<li><input type="hidden" name="task" value="save" /></li>
			</ul></fieldset></div></form>
			<div id="myComments" class="width-60 fltrt">
			<fieldset class="adminform"><legend><?php echo JText::_('Recent Comments'); ?></legend>
			
			<?php
			
			$db = JFactory::getDBO ();
			$query = "SELECT * from #__di_comments ORDER BY cid DESC LIMIT 22";
			$db->setQuery ( $query );
			$rows = $db->loadObjectList ();
			echo '<table class="adminlist"><tr><th>comment</th><th>name</th><th>email</th><th>website</th></tr>';
			
			foreach ( $rows as $row ) {
				echo '<tr><td>' . $row->comment . '</td><td>' . $row->name . '</td><td>' . $row->email . '</td><td><a target="_new" href="' . $row->website . '">' . $row->website . '</a></td></tr>';
			
			}
			echo '</table></div>';
		
		}
		
		break;
	
	case 'updateSettings' :
		// TODO: submit form
		$db = JFactory::getDBO ();
		$query = "SELECT * from #__di_settings";
		$db->setQuery ( $query );
		$rows = $db->loadObjectList ();
		echo '<pre>';
		
		$keys = array ();
		$values = array ();
		foreach ( $rows as $key => $row ) {
			
			if (isset ( $_POST [$row->name] )) {
				
				if (is_array ( $_POST [$row->name] )) {
					$_POST [$row->name] = implode ( ',', $_POST [$row->name] );
				}
				$query = "UPDATE #__di_settings SET val = '" . $_POST [$row->name] . "' WHERE name = '$row->name'";
				$db->setQuery ( $query );
				$db->query ();
			
			}
		
		}
		
		echo '<p>Settings updated click <a href="javascript:history.go(-1)">here</a> to go back</p>';
		
		break;

}

function renderBlock($name, $label, $type, $val, $desc = '') {
	$label = '<label for="' . $name . '">' . $label . '</label>';

	
	switch ($type) {
		
		case '0' :
			//text

			$content = '<input type="text" id="' . $name . '" name="' . $name . '" value="' . $val . '" />';
			
			break;
		
		case '1' :
			//bool

			if ($val == 0) {
				$sel0 = ' checked ';
				$sel1 = '';
			} else {
				$sel1 = ' checked ';
				$sel0 = '';
			}
			
			$content = '<fieldset id="' . $name . '" name="' . $name . '" class="radio">' . '<input type="radio" id="' . $name . '" name="' . $name . '" value="0" ' . $sel0 . '/><label for="' . $name . '">No</label>' . '<input type="radio" id="' . $name . '" name="' . $name . '" value="1" ' . $sel1 . ' /><label for="' . $name . '">Yes</label>' . '</fieldset>';
			
			break;
		
		case '2' :
			//textarea
			$content = '<textarea id="' . $name . '" rows="4" style="width:145px" name="' . $name . '">' . $val . '</textarea>';
			
			break;
		
		case '3' :
			//sections
			$active = NULL;
			$javascript = NULL;
			$name = 'sections[]';
			$size = 10;
			$db = & JFactory::getDBO ();
			$query = 'SELECT id AS value, title AS text' . ' FROM #__categories' . ' WHERE published = 1 AND extension = \'com_content\'';
			$db->setQuery ( $query );
			$categories = $db->loadObjectList ();
			
			$category = JHTML::_ ( 'select.genericlist', $categories, $name, 'class="inputbox" multiple="multiple" style="width:145px" size="' . $size . '" ' . $javascript, 'value', 'text', explode ( ',', di_getparam ( 'sections' ) ) );
			$content = $category;
			
			break;
		
		case '4' :
			//langs
			
			$url = 'http://account.discussit.com/external/ListAvailableLanguages';
			$attr = '';
			$ch = curl_init ();
			$timeout = 10;
			curl_setopt ( $ch, CURLOPT_URL, $url );
			//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
			$data = curl_exec ( $ch );
			curl_close ( $ch );
			
			$json = json_decode ( $data );
			$languages = ( array ) $json;
			
			$db = JFactory::getDBO ();
			$query = "SELECT * FROM #__di_langs";
			$db->setQuery ( $query );
			$results = $db->loadResultArray ();
			if (! is_array ( $languages ))
				$lanuages = array ($languages );
			foreach ( $languages as $language ) {
				
				$sc = substr ( $language->ShortCode, 0, 2 );
				
				if (! in_array ( $sc, $results )) {
					$db = JFactory::getDBO ();
					
					$query = "INSERT INTO #__di_langs (tag, language) VALUES ($sc, '$language->Name')";
					$db->setQuery ( $query );
					$db->query ();
				}
			
			}
		
			$lang = di_getparam('langs');
			
			$content = JHTML::_ ( 'select.genericlist', $languages, 'langs', trim ( $attr ), 'ShortCode', 'Name', $lang );
			break;
		
		case '5' :
			
			$content = '';
			break;
		
		case '6' :
			$content = '<input type="text" id="' . $name . '" name="' . $name . '" value="' . $val . '" class="required validate numeric" />';
			break;
	}
	
	if ($content != '') {
		$block = '<tr><th>' . $label . '</th><td>' . $content . '</td>' . di_helpbutton ( $desc ) . '</tr>' . PHP_EOL;
	} else
		$block = '';
	return $block;

}
function di_getparam($param) {
	
	$db = JFactory::getDBO ();
	$query = "SELECT val FROM #__di_settings WHERE name = '$param'";
	$db->setQuery ( $query );
	$response = $db->loadResult ();
	
	return $response;
}
function di_helpbutton($desc) {
	
	JHTML::_ ( 'behavior.tooltip' );
	
	$helpbutton = '<td>' . JHTML::tooltip ( $desc, '', JURI::root () . 'administrator/components/com_discussit/media/images/help.png', '', '' ) . '</td>';

	return $helpbutton;

}
?>