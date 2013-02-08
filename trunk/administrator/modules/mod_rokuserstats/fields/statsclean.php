<?php
/**
 * @version  2.6 April 10, 2012
 * @author  RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license  http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
defined('JPATH_BASE') or die();

require_once(JPATH_ADMINISTRATOR.'/templates/rt_missioncontrol/lib/missioncontrol.class.php');
require_once(JPATH_ADMINISTRATOR.'/templates/rt_missioncontrol/lib/rtmcupdates.class.php');

/**
 * @package     missioncontrol
 * @subpackage  admin.elements
 */
class JFormFieldStatsClean extends JFormField {

	public function getLabel() {}

	public function getInput() {
        global $mctrl;

        $mctrl = MissionControl::getInstance();

        $mctrl->document->addStylesheet('modules/mod_rokuserstats/tmpl/rokuserstatsadmin.css');
        $mctrl->document->addScript('modules/mod_rokuserstats/tmpl/rokuserstatsadmin.js');

        $clean_url = "?process=ajax&amp;model=statscleaner&amp;action=";


        $html = "
        <div id='cleanData'>
            <span class='mc-button'><span class=\"spinner\"></span><a href='".$clean_url."user' id='cleanUserDataBtn'>Clear UserStat Data</a></span>
            <span class='mc-button'><span class=\"spinner\"></span><a href='".$clean_url."admin' id='cleanAdminDataBtn'>Clear AdminAudit Data</a></span>
        </div>";

        return $html;
	}

}
?>
