<?php
/**
 * @package RokQuickLinks - RocketTheme
 * @version 1.5.0 September 1, 2010
 * @author RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */
/** ensure this file is being included by a parent file */
defined('_JEXEC') or die( 'Restricted access' );

class rokQuickLinksHelper {
	static function isConfigured(&$params) {
		if ($params->get('quickfields') == '[]') {
			echo '<p>'.JTEXT::sprintf('MC_RQL_CONFIGURE_IT', rokQuickLinksHelper::getConfigureLink()).'</p>';
			return false;
		} else {
			return true;
		}

	}

	static function getConfigureLink($module) {
		return 'index.php?option=com_modules&task=module.edit&id='.$module->id;
	}

	static function cleanPath($path) {
		if (substr($path,-1) != '/') $path.= '/';
		return $path;
	}

	static function getLinks(&$params) {
		$default = '[{"icon":"newspaper_add.png","target":"self","link":"index.php?option=com_content&amp;task=article.edit","title":"Add Article"},{"icon":"images.png","target":"self","link":"index.php?option=com_media","title":"Media Manager"},{"icon":"drawer_open.png","target":"self","link":"index.php?option=com_categories&amp;section=com_content","title":"Category Manager"},{"icon":"cog.png","target":"self","link":"index.php?option=com_config","title":"Configuration"},{"icon":"brick.png","target":"self","link":"index.php?option=com_installer","title":"Install New"},{"icon":"color_management.png","target":"self","link":"index.php?option=com_templates","title":"Templates"}]';

		$links = array();
		$fields = json_decode(str_replace("|","'", str_replace("'", '"', $params->get('quickfields', $default))));

		// get data
		foreach($fields as $field){
			$icon = $field->icon;
			$link = $field->link;
			$title = $field->title;

			$target = isset($field->target) ? $field->target : 'self';


			if ($icon == '-1')
				$links[] = null;
			else
				$links[] = array($icon,$link,$title,$target);
		}
		return $links;

	}
}
