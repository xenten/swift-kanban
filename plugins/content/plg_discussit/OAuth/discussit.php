<?php
/**
 * 
 * Discussit Test Class
 * 
 */
if(!isset($_SESSION))session_start();
require_once 'discussit.oauth.php';
class discussit
{
	
	var $apibase = 'https://account.discussit.com/dev/API/v2_0/DiscussIt.svc/';
	//var $apibase = 'https://account.discussit/API/v2_0/DiscussIt.svc/';
	function __construct()
	{
		
	}
	
function widget_list()
	{
		$request_uri = $this->apibase . 'widgets/get_xml';

		$req = new discussitOauth();
		$result = $req->doRequest($request_uri, null);
		$xmlObj = simplexml_load_string($result);
		
		return $xmlObj;
	}
	
	function message_post($commentId, $widgetid, $url, $realurl, $identifier, $author, $author_ip, $author_email, $author_url, $msg_body, $replyid)
	{
		$request_uri = $this->apibase . 'messages/message/add_xml';
		$body = '<MessagePost xmlns=\'http://dis.cuss.it\' xmlns:i=\'http://www.w3.org/2001/XMLSchema-instance\'><Body>' . $msg_body . '</Body><Email>' . $author_email . '</Email><IP>' . $author_ip . '</IP><Identifier/><MessageIDLegacy>' . $commentId . '</MessageIDLegacy><Mug i:nil=\'true\'/><Nickname>' . $author . '</Nickname><Notify>false</Notify><ProfileURL>' . $author_url . '</ProfileURL><ReplyTo>' . $replyid . '</ReplyTo><ThreadID i:nil=\'true\'/><URL>' . $url . '</URL><URLReal>' . $realurl . '</URLReal><WidgetID>' . $widgetid . '</WidgetID></MessagePost>';
		$_SESSION['di_sent_xml'] = htmlspecialchars($body);
		$req = new discussitOauth();
		$result = $req->doRequest($request_uri, $body);
		$xmlObj = simplexml_load_string($result);
		
		return $xmlObj;
	}
	
	function message_edit($commentId, $widgetid, $url, $msg_body, $status)
	{
		$request_uri = $this->apibase . 'messages/message/edit_xml';
		$body = '<MessageEdit xmlns=\'http://dis.cuss.it\' xmlns:i=\'http://www.w3.org/2001/XMLSchema-instance\'><Body>' . $msg_body . '</Body><MessageID></MessageID><MessageIDLegacy>' . $commentId . '</MessageIDLegacy><Status>' .$status . '</Status><URL>' . $url . '</URL><WidgetID>' . $widgetid . '</WidgetID></MessageEdit>';
		$req = new discussitOauth();
		$result = $req->doRequest($request_uri, $body);
		$xmlObj = simplexml_load_string($result);
		
		return $xmlObj;
	}
	
	function thread_init($url, $widgetid)
	{
		$request_uri = $this->apibase . 'messages/thread/init_xml';
		$body = '<ArrayOfstring xmlns=\'http://schemas.microsoft.com/2003/10/Serialization/Arrays\' xmlns:i=\'http://www.w3.org/2001/XMLSchema-instance\'>
					<string>' . $url . '</string><string>' . $widgetid . '</string></ArrayOfstring>'; 
		$req = new discussitOauth();
		$result = $req->doRequest($request_uri, $body);
		$xmlObj = simplexml_load_string($result);
		
		return $xmlObj;
	}	
	
	function thread_get($threadid, $modtype)
	{
		$request_uri = $this->apibase . 'messages/thread/getbyid_xml';
		$body = '<ArrayOfstring xmlns=\'http://schemas.microsoft.com/2003/10/Serialization/Arrays\' xmlns:i=\'http://www.w3.org/2001/XMLSchema-instance\'>
					<string>' . $threadid . '</string><string>' . $modtype . '</string></ArrayOfstring>'; 
		$req = new discussitOauth();
		$result = $req->doRequest($request_uri, $body);
		$xmlObj = simplexml_load_string($result);
		
		return $xmlObj;
	}	
	function message_update($id, $status)
	{
		$request_uri = $this->apibase . 'messages/status/update_xml';
		$body = '<ArrayOfstring xmlns=\'http://schemas.microsoft.com/2003/10/Serialization/Arrays\' xmlns:i=\'http://www.w3.org/2001/XMLSchema-instance\'><string>' . $id .  '</string><string>' . $status . '</string></ArrayOfstring>';
		$req = new discussitOauth();
		$result = $req->doRequest($request_uri, $body);
		$xmlObj = simplexml_load_string($result);
		
		return $xmlObj;
	}
	function moderation_type($widget_id, $mod_type)
	{
		$request_uri = $this->apibase . 'widgets/updateModerationType_xml';
		$body = '<ArrayOfstring xmlns=\'http://schemas.microsoft.com/2003/10/Serialization/Arrays\' xmlns:i=\'http://www.w3.org/2001/XMLSchema-instance\'><string>' . $widget_id .  '</string><string>' . $mod_type . '</string></ArrayOfstring>';
		$req = new discussitOauth();
		$result = $req->doRequest($request_uri, $body);
		$xmlObj = simplexml_load_string($result);
		
		return $xmlObj;
	}
	function exportwp2di($xml)
	{

		$request_uri = $this->apibase . 'messages/import_xml';
		$req = new discussitOauth();
		$result = $req->doRequest($request_uri, $xml);
		$xmlObj = new SimpleXMLElement($result);
		
		return $xmlObj;
	}
	
	function widget_get_recent($widgetid)
	{
		$anhourago = mktime(date("H")-1, date("i"), date("s"), date("m"), date("d"),   date("Y"));
		$request_uri = $this->apibase . 'widgets/recentmessages_xml';
		$body = '<ArrayOfstring xmlns=\'http://schemas.microsoft.com/2003/10/Serialization/Arrays\' xmlns:i=\'http://www.w3.org/2001/XMLSchema-instance\'>
					<string>' . $widgetid . '</string><string>' . date('Y\/m\/d H\:i\:s', $anhourago) . '</string><string>True</string></ArrayOfstring>'; 
		$req = new discussitOauth();
		$result = $req->doRequest($request_uri, $body);
		$xmlObj = new SimpleXMLElement($result);
		
		return $xmlObj;
	}
	function get_details_from_api_key($key)
	{


		$oauth = new discussitOauth;
		$dikeys = new stdClass;
		$dikeys = $oauth->get_code_from_api_key($key);

		if ($dikeys->siteID == '' || $dikeys->widgetID == '' || $dikeys->clientID == '' || $dikeys->clientSecret == '' || $dikeys->authCode == '' )
		{
			return false;			
		}
		else 
		{
			
			$token = $oauth->get_access_token_from_code($dikeys->authCode, $dikeys->clientID, $dikeys->clientSecret);
			
			$details = new stdClass;
			
			$details->siteID = $dikeys->siteID;
			$details->widgetID = $dikeys->widgetID;
			$details->clientID = $dikeys->clientID;
			$details->clientSecret = $dikeys->clientSecret;
			$details->siteID = $dikeys->siteID;
			$details->accessToken = $token->access_token;
			$details->refreshToken = $token->refresh_token;
			
			
			return $details;
			
		}
	
	}
	function set_endpoint($widgetid, $endpoint, $password)
	{
		$request_uri = $this->apibase . '/webhook/setendpoint_xml';
		$body = '<ArrayOfstring xmlns=\'http://schemas.microsoft.com/2003/10/Serialization/Arrays\' xmlns:i=\'http://www.w3.org/2001/XMLSchema-instance\'>
					<string>' . $widgetid . '</string><string>JOOMLA</string><string>' . $endpoint . '</string><string>moderator</string><string>' . $password . '</string></ArrayOfstring>'; 
		$req = new discussitOauth();
		$result = $req->doRequest($request_uri, $body);
		$xmlObj = new SimpleXMLElement($result);
		
		return $xmlObj;
	}
	
}?>