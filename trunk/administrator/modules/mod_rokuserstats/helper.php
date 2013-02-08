<?php
/**
 * @package RokUserStats - RocketTheme
 * @version 1.5.0 September 1, 2010
 * @author RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */
/** ensure this file is being included by a parent file */
defined('_JEXEC') or die( 'Restricted access' );

class rokUserStatsHelper
{
	static function getRows($params, $module) {

		$rows = array();
		$db = JFactory::getDBO();
		$session = JFactory::getSession();

		// currently active users
		$query = 'SELECT * from #__rokuserstats WHERE timestamp >= date_sub(current_timestamp,interval '.$session->getExpire().' second) group by ip, user_id';
		$db->setQuery($query);
		$results = $db->loadResultArray(1);

		$total = 0;
		$guests = 0;
		$registered = 0;

		if (is_array($results)) {
			foreach ($results as $user) {
				if ($user == 0) $guests++;
				else $registered++;
				$total++;
			}
		}


		// unique visits today
		$query = 'select count(total) from (SELECT ip as total from #__rokuserstats WHERE timestamp >= date_sub(current_timestamp,interval 1 day) group by ip, user_id) as foo';
		$db->setQuery($query);
		$currentday = intval($db->loadResult());

		// unique visits yesterday
		$query = 'select count(total) from (SELECT ip as total from #__rokuserstats WHERE timestamp >= date_sub(current_timestamp,interval 2 day) AND timestamp < date_sub(curdate(),interval 1 day) group by ip, user_id) as foo';
		$db->setQuery($query);
		$yesterday = intval($db->loadResult());

		// previous day visits
		$query = 'select count(total) from (SELECT ip as total from #__rokuserstats WHERE timestamp >= date_sub(current_timestamp,interval 3 day) AND timestamp < date_sub(curdate(),interval 2 day) group by ip, user_id) as foo';
		$db->setQuery($query);
		$prevday = intval($db->loadResult());

		// visits past 7 days
		$query = 'select count(total) from (SELECT ip as total from #__rokuserstats WHERE timestamp >= date_sub(current_timestamp,interval 7 day) group by ip, user_id) as foo';
		$db->setQuery($query);
		$currentweek = intval($db->loadResult());

		// unique visits past week
		$query = 'select count(total) from (SELECT ip as total from #__rokuserstats WHERE timestamp >= date_sub(current_timestamp,interval 14 day) AND timestamp < date_sub(curdate(),interval 7 day) group by ip, user_id) as foo';
		$db->setQuery($query);
		$pastweek = intval($db->loadResult());

		// previous week visits
		$query = 'select count(total) from (SELECT ip as total from #__rokuserstats WHERE timestamp >= date_sub(current_timestamp,interval 21 day) AND timestamp < date_sub(curdate(),interval 14 day) group by ip, user_id) as foo';
		$db->setQuery($query);
		$prevweek = intval($db->loadResult());

		// total articles
		$query = 'select count(id) as total from #__content WHERE state = 1';
		$db->setQuery($query);
		$totalarticles = intval($db->loadResult());

		// new articles
		$query = 'select count(id) as total from #__content WHERE state = 1 and created >= date_sub(current_timestamp,interval 7 day)';
		$db->setQuery($query);
		$newarticles = intval($db->loadResult());

		// past articles
		$query = 'select count(id) as total from #__content WHERE state = 1 and created >= date_sub(current_timestamp,interval 14 day) AND publish_up < date_sub(curdate(), interval 7 day)';
		$db->setQuery($query);
		$pastarticles = intval($db->loadResult());

		// userstat data
		$config = JFactory::getConfig();

		$query = 'SELECT TABLE_NAME AS "TableName", table_rows AS "NumOfRows", ROUND((data_length + index_length),2) AS "SizeInKb" FROM information_schema.TABLES WHERE information_schema.TABLES.table_schema="'.$config->get('db').'" and (information_schema.TABLES.table_name = "'.$config->get('dbprefix').'rokuserstats" or information_schema.TABLES.table_name = "'.$config->get('dbprefix').'rokadminaudit")';
		$db->setQuery($query);
		$trackstats = $db->loadObjectList();

		$currentday_trend = $currentday >= $yesterday ? 'up' : 'down';
		$yesterday_trend = $yesterday >= $prevday ? 'up' : 'down';
		$currentweek_trend = $currentweek >= $pastweek ? 'up' : 'down';
		$pastweek_trend = $pastweek >= $prevweek ? 'up' : 'down';
		$article_trend = $newarticles >= $pastarticles ? 'up' : 'down';

		$usermanager_link = 'index.php?option=com_users&view=users';
		$articlemanager_link = 'index.php?option=com_content';
		$module_link = 'index.php?option=com_modules&task=module.edit&id='.$module->id;

		$rows[] = array('none',JTEXT::_('MC_RUS_CURRENT_ACTIVE_USERS'),$total,$usermanager_link);
		$rows[] = array('none',JTEXT::_('MC_RUS_ACTIVE_GUESTS'),$guests,$usermanager_link);
		$rows[] = array('none',JTEXT::_('MC_RUS_ACTIVE_REGISTERED'),$registered,$usermanager_link);
		$rows[] = array($currentday_trend,JTEXT::_('MC_RUS_UNIQUE_VISITS_TODAY'),$currentday);
		$rows[] = array($yesterday_trend,JTEXT::_('MC_RUS_UNIQUE_VISITS_YESTERDAY'),$yesterday);
		$rows[] = array($currentweek_trend,JTEXT::_('MC_RUS_VISITS_THIS_WEEK'),$currentweek);
		$rows[] = array($pastweek_trend,JTEXT::_('MC_RUS_VISITS_PREVIOUS_WEEK'),$pastweek);
		$rows[] = array('none',JTEXT::_('MC_RUS_TOTAL_ARTICLES'),$totalarticles,$articlemanager_link);
		$rows[] = array($article_trend,JTEXT::_('MC_RUS_NEW_ARTICLES_THIS_WEEK'),$newarticles,$articlemanager_link);

		if ($params->get('showstats',1) && isset($trackstats)) {
			foreach ($trackstats as $stats) {
				if ($stats->TableName==$config->get('dbprefix').'rokuserstats') {
					$userstats_rows_trend = intval($stats->NumOfRows) > 100000 ? 'down' : 'up';
					$userstats_size_trend = intval($stats->SizeInKb) > 10000000 ? 'down' : 'up';
					$rows[] = array($userstats_rows_trend,JTEXT::_('UserStat Entries'),$stats->NumOfRows,$module_link);
					$rows[] = array($userstats_size_trend,JTEXT::_('UserStat Table Size'),rokUserStatsHelper::prettySize($stats->SizeInKb),$module_link);
				}
				if ($stats->TableName==$config->get('dbprefix').'rokadminaudit') {
					$adminstats_rows_trend = intval($stats->NumOfRows) > 100000 ? 'down' : 'up';
					$adminstats_size_trend = intval($stats->SizeInKb) > 10000000 ? 'down' : 'up';
					$rows[] = array($adminstats_rows_trend,JTEXT::_('AdminAudit Entries'),$stats->NumOfRows,$module_link);
					$rows[] = array($adminstats_size_trend,JTEXT::_('AdminAudit Table Size'),rokUserStatsHelper::prettySize($stats->SizeInKb),$module_link);
				}
			}


		}


		return $rows;

	}

	static function prettySize($a_bytes)
	{
	    if ($a_bytes < 1024) {
	        return $a_bytes .' B';
	    } elseif ($a_bytes < 1048576) {
	        return round($a_bytes / 1024, 2) .' KB';
	    } elseif ($a_bytes < 1073741824) {
	        return round($a_bytes / 1048576, 2) . ' MB';
	    } elseif ($a_bytes < 1099511627776) {
	        return round($a_bytes / 1073741824, 2) . ' GB';
	    } elseif ($a_bytes < 1125899906842624) {
	        return round($a_bytes / 1099511627776, 2) .' TB';
	    } elseif ($a_bytes < 1152921504606846976) {
	        return round($a_bytes / 1125899906842624, 2) .' PB';
	    } elseif ($a_bytes < 1180591620717411303424) {
	        return round($a_bytes / 1152921504606846976, 2) .' EB';
	    } elseif ($a_bytes < 1208925819614629174706176) {
	        return round($a_bytes / 1180591620717411303424, 2) .' ZB';
	    } else {
	        return round($a_bytes / 1208925819614629174706176, 2) .' YB';
	    }
	}


}
