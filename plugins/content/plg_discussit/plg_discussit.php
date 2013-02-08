<?php
/**

DiscussIt Joomla! 1.6/1.7/2.5 plugin v2.0

by Peter Bennett

 */

defined ( '_JEXEC' ) or die ( 'Restricted access' );

jimport ( 'joomla.methods' );
jimport ( 'joomla.application.application' );
jimport ( 'joomla.application.router' );
jimport ( 'joomla.plugin.plugin' );
jimport ( 'joomla.html.parameter' );
jimport ( 'joomla.language.language' );

require_once ('OAuth' . DS . 'discussit.php');

function di_getparam($param) {
	
	$db = JFactory::getDBO ();
	$query = "SELECT val FROM #__di_settings WHERE name = '$param'";
	$db->setQuery ( $query );
	$response = $db->loadResult ();
	
	return $response;

}

function getseo($url, $widgetid) {
	
	try {
		$spiders = array ('Googlebot', 'Yammybot', 'Openbot', 'Yahoo', 'Slurp', 'msnbot', 'ia_archiver', 'Lycos', 'Scooter', 'AltaVista', 'Teoma', 'Gigabot', 'Googlebot-Mobile' );
		
		foreach ( $spiders as $spider ) {
			if (strstr ( $spider, $_SERVER ['HTTP_USER_AGENT'] )) {
				
				$SEO = new discussit ();
				$res = $SEO->thread_init ( $url, $widgetid );
				$tid = $res->ThreadID;
				$mt = $res->ModType;
				$tg = $SEO->thread_get ( $tid, $mt );
				$count = $tg->Count;
				$out = '';
				for($counter = 0; $counter <= $count - 1; $counter += 1) {
					$body = $tg->MessageList->Message [$counter]->Body . '</br>';
					$nickname = $tg->MessageList->Message [$counter]->Nickname . '</br>';
					
					$out = $out . '<div>' . $body . $nickname . '</div>';
				}
				
				$messages = 'Number of comments: ' . $count . $out;
				
				$seo = '<div id="commp">' . '<div id="comm">' . $messages . '</div>' . '</div>';
			}
		
		}
	} catch ( exception $e ) {
		
		$seo = "SEO timeout";
	}
	if (! isset ( $seo )) {
		$seo = '';
	}
	return $seo;
}

class plgContentPlg_Discussit extends JPlugin {
	/**

	 * Constructor

	 */
	
	function plgContentPlg_Discussit(&$subject, $params) {
		parent::__construct ( $subject, $params );
	}
	
	function onContentBeforeDisplay($article, &$params, $limitstart) {
		global $sc;
		
		/**
		 * 
		 * comment controller
		 * 
		 */
		
		if ((isset ( $_POST ['txtdiBody'] ) && isset ( $_POST ['txtdiNickname'] ) && isset ( $_POST ['txtdiEmail'] )) || (isset ( $_POST ['txtdiReplyBody'] ) && isset ( $_POST ['txtdiRNickname'] ) && isset ( $_POST ['txtdiREmail'] ))) {
			
			if (isset ( $_POST ['diRT'] )) {
				
				$diRT = $_POST ['diRT'];
				
				$db = JFactory::getDBO ();
				$query = "SELECT cid FROM `#__di_comments` WHERE did = '$diRT'";
				$db->setQuery ( $query );
				$com_rt = $db->loadResult ();
			
			} else
				$com_rt = 0;
			
			try {
				
				if (isset ( $_POST ['txtdiReplyBody'] ) && isset ( $_POST ['txtdiRNickname'] ) && isset ( $_POST ['txtdiREmail'] )) {
					$com_body = $_POST ['txtdiReplyBody'];
					$com_name = $_POST ['txtdiRNickname'];
					$com_mail = $_POST ['txtdiREmail'];
					$com_replyto = $_POST ['diRT'];
				} else {
					$com_body = $_POST ['txtdiBody'];
					$com_name = $_POST ['txtdiNickname'];
					$com_mail = $_POST ['txtdiEmail'];
					$com_replyto = '';
				}
				
				//TODO: get comments from prev 24 hrs compare to $com_body;
				$query = "SELECT comment FROM #__di_comments WHERE comment = " . $db->quote ( $com_body );
				
				//TODO: add escape string!!!
				$db = JFactory::getDBO ();
				$db->setQuery ( $query );
				$resp = $db->loadResult ();
				
				if ($resp != '')
					return "<script>alert('Duplicate comment detected!')</script>";
				
				$query = "INSERT INTO #__di_comments (comment, name, email, pid, date, parent" . ") VALUES (" . "'$com_body', '$com_name', '$com_mail', '$params->id', NOW(), '$com_rt')";
				$db = JFactory::getDBO ();
				$db->setQuery ( $query );
				$db->query ();
				$cid = $db->insertid ();
				
				$di = new discussit ();
				$wid = di_getparam ( 'widgetID' );
				$realurl = JURI::current();
				$pageurl = 'http://www.discussit.com/' . $wid . '/' . $params->id;
				$cip = '127.0.0.1';
				
				if (isset ( $_POST ['txtdiRNickname'] )) {
					$cuser = $_POST ['txtdiRNickname'];
				} else {
					$cuser = $_POST ['txtdiNickname'];
				}
				
				if (isset ( $_POST ['txtdiREmail'] )) {
					$cemail = $_POST ['txtdiREmail'];
				} else {
					$cemail = $_POST ['txtdiEmail'];
				}
				
				$curl = 'test';
				//$replyto = 0;
				if (isset ( $_POST ['txtdiReplyBody'] )) {
					$comment_body = $_POST ['txtdiReplyBody'];
				} else {
					$comment_body = $_POST ['txtdiBody'];
				}
				$rm = $di->message_post ( $cid, $wid, $pageurl, $realurl, $_POST ['diIdent'], $cuser, $cip, $cemail, $curl, $comment_body, $com_replyto );
				$carray = explode ( ',', $rm );
				$did = $carray [1];
				
				echo "<script>alert('Message posted');</script>";
				
				$query = "UPDATE #__di_comments SET did = '$did' WHERE cid='$cid'";
				$db = JFactory::getDBO ();
				$db->setQuery ( $query );
				$db->query ();
			
			} catch ( Exception $e ) {
			
			}
		}
		
		$sections = di_getparam ( 'sections' );
		
		$sect = explode ( ',', $sections );
		
		if (JRequest::getCmd ( 'option' ) == 'com_content' && JRequest::getCmd ( 'view' ) == 'article') {
			//article mode
			

			if (! empty ( $sect )) {
				
				$catId = $params->catid;
				if (in_array ( $catId, $sect )) {
					$this->params->set ( 'showcomments', '1' );
				} else {
					$this->params->set ( 'showcomments', '0' );
				}
			} else {
				$this->params->set ( 'showcomments', '0' );
			}
			
			if (strpos ( $params->text, '{nocomments}' ) != false) 

			{
				$outText = str_replace ( "{nocomments}", '', $params->text );
				$params->text = $outText;
				$this->params->set ( 'showcomments', '0' );
			}
			if (strpos ( $params->text, '{showcomments}' ) != false) {
				$outText = str_replace ( "{showcomments}", '', $params->text );
				$params->text = $outText;
				$this->params->set ( 'showcomments', '1' );
			}
		
		} else { //frontpage mode
			

			if (! empty ( $sect )) {
				$catId = $params->catid;
				if (in_array ( $catId, $sect )) {
					$sc = 'yes';
				} else {
					$sc = 'no';
				}
			} else {
				$sc = 'no';
			}
			
			if (strpos ( $params->introtext, '{nocomments}' ) != false) {
				
				$outText = str_replace ( "{nocomments}", '', $params->introtext );
				
				$params->introtext = $outText;
				
				$sc = 'no';
			
			}
			if (strpos ( $params->introtext, '{showcomments}' ) != false) {
				$outText = str_replace ( "{showcomments}", '', $params->introtext );
				$params->introtext = $outText;
				
				$sc = 'yes';
			}
		}
		
		return '';
	
	}
	
	public function onContentAfterDisplay($article, &$params, $limitstart) {
		

		$db = JFactory::getDbo ();
		$query = 'select * from #__extensions where element = "plg_discussit"';
		$db->setQuery ( $query );
		
		if (JRequest::getCmd ( 'option' ) == 'com_content' && JRequest::getCmd ( 'view' ) == 'article') {
			if ($this->params->get ( 'showcomments' ) == '1') {
				
				if (di_getparam ( 'poweredBy' ) == '1') {
					$poweredBy = '<img border="0" src="http://account.dis.cuss.it/Content/i/cp.gif" alt="Comments powered by Dis.cuss.It" />';
				
				} else {
					$poweredBy = '';
				}
				
				if ((is_numeric ( di_getparam ( 'widgetWidth' ) ) == true) and (is_numeric ( di_getparam ( 'widgetMargin' ) ) == true)) {
					if (di_getparam ( 'widgetWidth' ) != '0') {
						$distyle = ' style="width: ' . di_getparam ( 'widgetWidth' ) . 'px; margin-top:' . di_getparam ( 'widgetMargin' ) . 'px"';
					} else {
						$distyle = ' style="width: AUTO; margin-top:' . di_getparam ( 'widgetMargin' ) . 'px"';
					}
				} else {
					$distyle = ' style="width: AUTO; margin-top:' . di_getparam ( 'widgetMargin' ) . 'px"';
				}
				
				$db = JFactory::getDBO ();
				$query = "SELECT * FROM #__di_langs";
				$db->setQuery ( $query );
				$languages = $db->loadResultArray ();
				
				$langObj = JFactory::getLanguage ();
				$langTag = $langObj->getTag ();
				$lang = substr ( $langTag, 0, 2 );
				if (! in_array ( $lang, $languages )) {
					$lang = di_getparam ( 'Langs' );
				}
				
				$w1 = '<!--DiscussIt Comments Plugin for Joomla v2.0-->' . '<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script><script type="text/javascript">  jQuery.noConflict() ;</script>' . '<script type="text/javascript">var widgetID="';
				
				$w2 = '"</script>' . '<script src="http://blob.discussit.com/javascript/j-' . $lang . '.js" type="text/javascript"></script>' . '<link href="http://account.discussit.com/content/widget/thread/' . di_getparam ( 'widgetID' ) . '.css" rel="stylesheet" type="text/css" />' . '<div id="diThr"' . $distyle . '>';
				
				$w3 = '</div>' . '<a href="http://dis.cuss.it" title="Comments powered by DiscussIt" id="dilnkback"><div id="diPoweredBy">' . $poweredBy . '</div></a>' . '<!--DiscussIt Comments-->';
				//   
				

				if (di_getparam ( 'widgetID' ) != '') {
					$user = JFactory::getUser ();
					$usrinfo = JFactory::getUser ();
					$usrname = $usrinfo->name;
					$usremail = $usrinfo->email;
					$jsuname = '<script type="text/javascript">jQuery(window).load(function() { jQuery("#txtdiNickname").val("' . $usrname . '"); jQuery("#txtdiEmail").val("' . $usremail . '"); } );</script>';
					$widgetid = di_getparam ( 'widgetID' );
					$tref = '"; var threadRef="' . $params->id;
					$url = 'http://www.discussit.com/' . $widgetid . '/' . $params->id;
					
					if ($user->username != NULL) {
						//logged in logic
						$jsuname = '<script type="text/javascript">jQuery(window).load(function() { 
										jQuery("#txtdiNickname").parent().parent().replaceWith("<p>Logged in as: <strong>' . $user->name . '</strong></p><input id=\"txtdiNickname\" name=\"txtdiNickname\" type=\"hidden\" value=\"' . $user->name . '\"/>"); 
										jQuery("#txtdiEmail").parent().parent().replaceWith("<input id=\"txtdiEmail\" name=\"txtdiEmail\" type=\"hidden\" value=\"' . $user->email . '\"/>");
									} );
									function diReply(b) {
										var a = "#dim_" + jQuery("#diRT").attr("value");
										jQuery(a).append(jQuery(b[0].widget));
										jQuery("#direp").show("slow");
										jQuery("#direp").find(".diOAuth").text(
												"Replying to: " + jQuery(a).find(".diAuth").text());
										jQuery("#txtdiRNickname").val(jQuery("#txtdiNickname").val());
										jQuery("#txtdiREmail").val(jQuery("#txtdiEmail").val());
										jQuery("#txtdiRURL").val(jQuery("#txtdiURL").val());
										jQuery("#txtdiReplyBody").focus();
										jQuery("#di-eralert").parent().parent().hide();
										jQuery("#txtdiRNickname").parent().parent().replaceWith("<p>Logged in as: <strong>' . $user->name . '</strong></p><input id=\"txtdiRNickname\" name=\"txtdiRNickname\" type=\"hidden\" value=\"' . $user->name . '\"/>");
										jQuery("#txtdiREmail").parent().parent().replaceWith("<input id=\"txtdiREmail\" name=id=\"txtdiREmail\" type=\"hidden\" value=\"' . $user->email . '\"/>");
									}
									var messageSent = 0;
									function diPosted(){
										
										messageSent = 1;
									
									}
									
									function diCbEnd(){
									
									if (messageSent == 1){
										jQuery("#txtdiNickname").parent().parent().replaceWith("<p>Logged in as: <strong>' . $user->name . '</strong></p><input id=\"txtdiNickname\" type=\"hidden\" value=\"' . $user->name . '\"/>"); 
										jQuery("#txtdiEmail").parent().parent().replaceWith("<input id=\"txtdiEmail\" type=\"hidden\" value=\"' . $user->email . '\"/>");
									}
									
									}
									
									</script>';
					
					} else {
						// anonymous logic
						

						if (di_getparam ( 'anonView' ) == '0') {
							if (di_getparam ( 'anonPost' ) == '1') {
														$jsuname = '<script type="text/javascript">jQuery(window).load(function() { 
										jQuery("#txtdiBody").parent().parent().parent().parent().parent().replaceWith(\'<p id="diLITP">You must be logged in to post comments.</p>\');
										jQuery(".diRep").hide();
								} );</script>';
							
							} else {
								// do nothing
								$jsuname = '';
							}
						} else {
							return '<p id="diLITV">You must be logged in to view comments.</p>';
						}
					
					}
					
					if (di_getparam('closeComments') == 1  && $params->created <= strtotime ( "-14 day" )) {
						$jsuname = '<script type="text/javascript">jQuery(window).load(function() { 
											jQuery("#txtdiBody").parent().parent().parent().parent().parent().replaceWith(\'<p id="diTC">This thread is now closed.</p>\');
											jQuery(".diRep").hide();
										} );</script>';
					}
					
					$user = JFactory::getUser ();
					
					$content = '<form class="comment-form" id="comment-form" name="comment-form" action="" method="post">';
					
					$content .= $w1 . $widgetid . $tref . $w2 . getseo ( $url, $widgetid ) . $w3 . $jsuname;
					
					$content .= '<script src="plugins/content/plg_discussit/plg_discussit.js" type="text/javascript"></script>';
					$content .= '<form class="comment-form" name="comment-form" action="" method="post">';
					$content .= '</form>';
				} 

				else if (di_getparam ( 'Key' ) == '') {
					$content = 'Please enter correct API key in plugin settings!';
				} else {
					
					$content = 'Error: widgetID not found. Please check that scripts can make outgoing connections from this host.';
				
				}
			
			} 

			else {
				$content = di_getparam ( 'disabledText' );
			}
		
		} else if (JRequest::getCmd ( 'option' ) == 'com_content' && JRequest::getCmd ( 'view' ) == 'featured') { //front-page mode
			

			if (di_getparam ( 'ShowViewAdd' ) != '') {
				if ($GLOBALS ['sc'] == 'yes') {
					
					$num_com = '';
					//' [' . $num_com . ']'
					$query = "SELECT COUNT(*) FROM #__di_comments WHERE pid = '$params->id' and status in ('a', 'h')";
					$db = JFactory::getDbo ();
					$db->setQuery ( $query );
					$count = $db->loadResult ();
					
					if (di_getparam ( 'showCount' ) == 1)
						$num_com = " ($count) ";
					
					$route = JRoute::_ ( JURI::base () . 'index.php?view=article&catid=' . $params->catslug . '&id=' . $params->slug );
					$link = '<a href="' . $route . '">' . di_getparam ( 'ShowViewAdd' ) . '</a>' . $num_com;
					$content = $link;
				} else {
					$content = '';
				}
			} 

			else {
				$content = '';
			}
		
		} else {
			$content = '';
		}
		
		return $content;
	}

}	