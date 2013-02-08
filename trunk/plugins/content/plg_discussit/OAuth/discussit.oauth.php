<?php 
class discussitOauth
{

	var $apibase = 'https://account.discussit.com/dev/API/v2_0/DiscussIt.svc/';
	//var $apibase = 'https://account.discussit/API/v2_0/DiscussIt.svc/';
	var $oauthserver = 'https://diauth.accesscontrol.windows.net';
	public $platform;
	
	function doRequest($request_uri, $body)
	{
		
		try { 
			
			$refreshtoken = di_getparam('refreshToken'); 
			
			$this->refresh_access_token($refreshtoken);
						
			$access_token = $_SESSION['access_token'];
			$url = $request_uri;
			$ch = curl_init ();
			$timeout = 10;
			curl_setopt ( $ch, CURLOPT_URL, $url );
			curl_setopt ( $ch, CURLOPT_POST, true );
			curl_setopt ( $ch, CURLOPT_POSTFIELDS, $body );
			curl_setopt ( $ch, CURLINFO_HEADER_OUT, 1 );
			curl_setopt ( $ch, CURLOPT_HTTPHEADER, Array ("Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8", "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7", "Accept-Encoding: gzip, deflate", "Accept-Language: en-us,en;q=0.5", "Cache-Control: no-cache", "Connection: keep-alive", "Pragma: no-cache", "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10.6; rv:5.0.1) Gecko/20100101 Firefox/5.0.1", "Authorization: Bearer " . $access_token, "Content-Type: application/xml" ) );
			curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
			curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
			curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
			
			$data = curl_exec ( $ch );
			
			$cinfo = curl_getinfo ( $ch );
			
			$_SESSION['di_curl_info'] = $cinfo['http_code'];
			
			
			
			if (curl_error ( $ch )) 
			{
				throw new Exception ( curl_error ( $ch ) );
				
				
				
			}
			
			curl_close ( $ch );
			
			

			return $data;
			
			
		} catch ( Exception $e ) {
			
			echo '<script>alert("Message: ' . $e->getMessage () . '")</script>';
		
		}
		
	}
	
function get_code_from_api_key($api)
	{
		$url = 'http://account.discussit.com/external/LoginFromAPIKey';
		
		$postVars = array ('apiKey' => $api, 'consumerName' => 'WP');
		
		
		$cleanVars = http_build_query ( $postVars );
		
		$ch = curl_init ();
		$timeout = 10;
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_POST, true );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $cleanVars );
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, Array ("Content-Type: application/x-www-form-urlencoded" ) );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
		//curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 1 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
		
		$data = curl_exec ( $ch );
		
		curl_close ( $ch );
		


		$json = json_decode ( $data );
			
		
		return $json;
		
	}
	
	function get_access_token_from_code($code, $clientID, $clientSecret, $redirect="https://www.discussit.com/")//redirect?
	{
		
		//if successful get_token
		$postVars = array ('client_id' => $clientID, 'client_secret' => $clientSecret, 'code' => $code, 'grant_type' => 'authorization_code', 'redirect_uri' => $redirect, 'scope' => 'uri:discussit' );
		
		$url = 'https://diauth.accesscontrol.windows.net/v2/OAuth2-13';
		
		$cleanVars = http_build_query ( $postVars );
		
		
		$ch = curl_init ();
		$timeout = 10;
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_POST, true );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $cleanVars );
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, Array ("Content-Type: application/x-www-form-urlencoded" ) );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
		//curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 1 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
		
		$data = curl_exec ( $ch );
		
		curl_close ( $ch );
		
	

		$json = json_decode ( $data );
		
		
		$_SESSION['access_token'] = $json->access_token;
		
		return $json;
		
		
	}
	
function refresh_access_token($refreshCode, $redirect = '') {
		
		$clientid = di_getparam('clientID'); 
		$clientsecret= di_getparam('clientSecret'); 
  		  		
		$postVars = array ('client_id' => $clientid, 'client_secret' => $clientsecret, 'refresh_token' => $refreshCode, 'grant_type' => 'refresh_token', 'redirect_uri' => $redirect, 'scope' => 'uri:discussit' );
				
		$url = $this->oauthserver . '/v2/OAuth2-13';
		
		$cleanVars = http_build_query ( $postVars );
		
		$ch = curl_init ();
		$timeout = 10;
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_POST, true );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $cleanVars );
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, Array ("Content-Type: application/x-www-form-urlencoded" ) );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
		//curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 1 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
		
		$data = curl_exec ( $ch );
		
		curl_close ( $ch );
		

		$json = json_decode ( $data );

		$_SESSION['access_token'] = $json->access_token;
		
		return $json->access_token;
	
		}
}
?>