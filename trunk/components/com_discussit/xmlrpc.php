<?php

$xml = file_get_contents('php://input');

if (isset($xml) ) {
	
	define( '_JEXEC', 1 );
	define( 'JPATH_BASE', dirname(dirname(dirname(__FILE__))) );
	define( 'DS', DIRECTORY_SEPARATOR );
	
	require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
	require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
	$mainframe =& JFactory::getApplication('site');
	$mainframe->initialise();
	
	$db = JFactory::getDBO();
	$query = "SELECT val FROM `#__di_settings` WHERE name = 'siteID' ";
	$db->setQuery($query);
	
	$siteID = $db->loadResult(); 
	
	$db = JFactory::getDBO();
	$query = "SELECT val FROM `#__di_settings` WHERE name = 'widgetID' ";
	$db->setQuery($query);
	
	$widgetID = $db->loadResult();
	
	$xml = file_get_contents('php://input');
	
	$xml_obj = new SimpleXMLElement($xml);
	$method = '';
	$method = $xml_obj->methodName[0];
	$rpc_vars = array();	
	
	foreach ($xml_obj->children() as $second_gen) {
	  	foreach ($second_gen->children() as $third_gen) {
		  	foreach ($third_gen->children() as $fourth_gen) {
		  		foreach ($fourth_gen->children() as $fifth_gen) {
		  		array_push($rpc_vars, (string)$fifth_gen[0]) ;
		  		}
		  	}
	  	}
	}

	if ($rpc_vars[0] == "moderator" && $rpc_vars[1] == $siteID) {	
		switch ($method) {
			
			case 'di_moderator.test':
				
				echo'<methodResponse>
					   <params>
					      <param>
					         <value><string>Test Passed.</string></value>
					      </param>
					   </params>
					</methodResponse>';
				
			break;
			
			case 'di_moderator.updateComment':
				
				$comment_id = $rpc_vars[2];
				$status = $rpc_vars[3];
				if ($status == "approve") $set_status = "a";
				if ($status == "trash") $set_status = "w";
				if ($status == "spam") $set_status = "s";
				if ($status == "hold") $set_status = "h";
				
				$db = JFactory::getDBO();
				$query = "UPDATE `#__di_comments` SET status = '$set_status' WHERE cid = '$comment_id'";
				$db->setQuery($query);
				$db->query();	
				
				echo'<methodResponse>
					   <params>
					      <param>
					         <value><string>Updated comment ' . $comment_id . '.</string></value>
					      </param>
					   </params>
					</methodResponse>';
				
			break;
			
			case 'di_moderator.editComment':
				
				$comment_id = $rpc_vars[3];
				$message_body = $rpc_vars[4];
				$status = $rpc_vars[5];
				if ($status == "approve") $set_status = "a";
				if ($status == "trash") $set_status = "w";
				if ($status == "spam") $set_status = "s";
				if ($status == "hold") $set_status = "h";
				
				$db = JFactory::getDBO();
				$query = "UPDATE `#__di_comments` SET status = '$set_status', comment = '$message_body' WHERE cid = '$comment_id'";
				$db->setQuery($query);
				$db->query();	
				
				echo'<methodResponse>
					   <params>
					      <param>
					         <value><string>Edited comment ' . $comment_id . '.</string></value>
					      </param>
					   </params>
					</methodResponse>';
				
			break;
			
			case 'di_moderator.addComment':
				
					require_once  ( JPATH_BASE .DS.'plugins'.DS.'content' .DS. 'plg_discussit' .DS. 'OAuth' .DS. 'discussit.php');
					// TODO: get pid
					$com_body = $rpc_vars[4];
					$com_name = $rpc_vars[5];
					$com_mail = '';
					$com_purl = explode('/', $rpc_vars[2]);
					$pid = end($com_purl);
					$com_rt = $rpc_vars[3];
					$com_did = $rpc_vars[9];
					
					
					$query = "INSERT INTO #__di_comments (
						comment,
						name,
						email,
						pid,
						date,
						parent,
						did
						) VALUES (
						'$com_body',
						'$com_name',
						'$com_mail',
						'$pid',
						NOW(),
						'$com_rt',
						'$com_did'
						)";
					$db = JFactory::getDBO ();
					$db->setQuery ( $query );
					$db->query();
					$cid = $db->insertid();
		
				echo'<methodResponse>
					   <params>
					      <param>
					         <value><int>' . $db->insertid() . '</int></value>
					      </param>
					   </params>
					</methodResponse>';
				
			break;
			
			
			default:
			
				echo'<methodResponse>
					   <params>
					      <param>
					         <value><string>Error: Incorrect Method Supplied.</string></value>
					      </param>
					   </params>
					</methodResponse>';
				
			break;	
		}
	}
	else {
		echo'<methodResponse>
			   <params>
			      <param>
			         <value><string>Access denied.</string></value>
			      </param>
			   </params>
			</methodResponse>';
	}
}

if (!isset($rpc_vars)) $rpc_vars = 'vars not sent';
//TODO: remove this line
//file_put_contents ("test.txt", $xml, FILE_APPEND);

?>