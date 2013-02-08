<?php 
/**
 * @version		2.3
 * @package		Simple RSS Feed Reader (module)
 * @author    JoomlaWorks - http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2011 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// JoomlaWorks reference parameters
$mod_name               = "mod_jw_srfr";
$mod_copyrights_start   = "\n\n<!-- JoomlaWorks \"Simple RSS Feed Reader\" Module (v2.3) starts here -->\n";
$mod_copyrights_end     = "\n<!-- JoomlaWorks \"Simple RSS Feed Reader\" Module (v2.3) ends here -->\n\n";

// API
$mainframe	= &JFactory::getApplication();
$document 	= &JFactory::getDocument();
$db 				= &JFactory::getDBO();
$user 			= &JFactory::getUser();
$aid 				= $user->get('aid');

// Assign paths
$sitePath 	= JPATH_SITE;
$siteUrl  	= substr(JURI::base(), 0, -1);

// Module parameters
$moduleclass_sfx 							= $params->get('moduleclass_sfx','');
$template 										= $params->get('template','default');
$srfrFeeds 										= $params->get('srfrFeeds');
$srfrFeedsArray 							= explode("\n",$srfrFeeds);
$perFeedItems 								= $params->get('perFeedItems',5);
$totalFeedItems 							= $params->get('totalFeedItems',10);
$feedTimeout									= $params->get('feedTimeout',5);
$feedTitle										= $params->get('feedTitle',1);
$feedFavicon									= $params->get('feedFavicon',1);
$feedItemTitle								= $params->get('feedItemTitle',1);
$feedItemDate									= $params->get('feedItemDate',1);
$feedItemDateFormat						= $params->get('feedItemDateFormat','j M Y | g:i a');
$feedItemDescription					= $params->get('feedItemDescription',1);
$feedItemDescriptionWordlimit	= $params->get('feedItemDescriptionWordlimit',40);
$feedItemImageHandling				= $params->get('feedItemImageHandling',2);
$feedItemImageResizeWidth			= $params->get('feedItemImageResizeWidth',200);
$feedItemImageResampleQuality	= $params->get('feedItemImageResampleQuality',80);
$feedItemReadMore							= $params->get('feedItemReadMore',1);
$feedsBlockPreText						= $params->get('feedsBlockPreText');
$feedsBlockPostText						= $params->get('feedsBlockPostText');
$feedsBlockPostLink						= $params->get('feedsBlockPostLink');
$feedsBlockPostLinkURL				= $params->get('feedsBlockPostLinkURL');
$feedsBlockPostLinkTitle			= $params->get('feedsBlockPostLinkTitle');
$srfrCacheTime								= $params->get('srfrCacheTime',30);
$cacheLocation								= 'cache'.DS.$mod_name;

// Includes
require_once(dirname(__FILE__).DS.'helper.php');

// Fetch content
$output = SimpleRssFeedReaderHelper::getFeeds($srfrFeedsArray,$totalFeedItems,$perFeedItems,$feedTimeout,$feedItemDateFormat,$feedItemDescriptionWordlimit,$cacheLocation,$srfrCacheTime,$feedItemImageHandling,$feedItemImageResizeWidth,$feedItemImageResampleQuality,$feedFavicon);

// Output content with template
echo $mod_copyrights_start;
require(JModuleHelper::getLayoutPath($mod_name,$template.DS.'default'));
echo $mod_copyrights_end;

// END
