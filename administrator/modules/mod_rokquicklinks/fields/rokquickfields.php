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

// Include the syndicate functions only once
require_once (dirname(__FILE__).'/../helper.php');

class JFormFieldRokQuickFields extends JFormField {

	var	$_name = 'rokquickfields';
	var $directory = null;

	public function getInput(){

		$document 	= JFactory::getDocument();
		$path = JURI::Root(true)."/administrator/modules/mod_rokquicklinks/";
		$document->addStyleSheet($path.'admin/css/quickfields.css');
		$document->addScript($path.'admin/js/quickfields'.$this->_getJSVersion().'.js?'.CURRENT_VERSION);

		$directory = $this->form->getValue('iconpath','params',$this->form->getFieldAttribute('iconpath','default',null, 'params'));
		$this->directory = rokQuickLinksHelper::cleanPath($directory);

		$value = str_replace("|", "'", str_replace("'", '"', $this->value));

		$output = "";

		// hackish way to close tables that we don't want to use
		//$output .= '</td></tr><tr><td colspan="2">';

		// real layout
		$output .= '<div id="quicklinks-admin">'."\n";
		$output .=  $this->populate($value);
		$output .= '</div>'."\n";

		$output .= "<input id='quicklinks-dir' value=\"".JURI::Root(true). $this->directory ."\" type='hidden' />";
		$output .= "<input id='".$this->id."' name='".$this->name."' type='hidden' value=\"".$this->value."\" />";

		echo $output;
	}

	function getLabel() {
		return "";
	}

	function populate($value){
		$blocks = json_decode($value, true);
		$output = '';

		for($i = 1; $i <= count($blocks); $i++){
			$output .= $this->layout($blocks[$i - 1], $i);
		}

		return $output;
	}

	function populateIcons($selectedIcon = false,$index){
		$path = JPATH_ROOT . str_replace('/', DS, $this->directory);
		if (file_exists($path)) {
			$icons = scandir($path);
			$output = '';

			$output .= '<select class="inputbox quicklinks-select" id="jform_params_icon-'.$index.'" name="jform[params][icon-'.$index.']">';

			foreach($icons as $icon){
				$pathinfo = pathinfo($icon);
				$ext = $pathinfo['extension'];

				if ($ext == 'png' || $ext == 'jpg' || $ext == 'bmp' || $ext == 'gif'){
					if (basename($selectedIcon) == $pathinfo['filename'] . "." . $ext) $selected = ' selected="selected"';
					else $selected = '';

					$output .= '<option value="'.$pathinfo['basename'].'"'.$selected.'>'.$pathinfo['filename'].'</option>'."\n";
				}
			}
			$output .= '</select>';

		} else {
			$output = "<span class=\"error\">ERROR: Icon Path does not exist.</span>";
			$output .= '<select style="display:none;" id="jform_params_icon-'.$index.'" name="jform[params][icon-'.$index.']"><option value="blank.png"></option></select>';
		}
		return $output;
	}

	function populateTargets($selectedTarget = false){
		$output = '';
		$targets = array('self' => 'Current Page', 'blank' => 'New Page');

		foreach($targets as $key => $target){
			if ($selectedTarget == $key) $selected = ' selected="selected"';
			else $selected = '';

			$output .= '<option value="'.$key.'"'.$selected.'>'.$target.'</option>'."\n";
		}

		return $output;
	}


	function layout($block, $index){
		$icon = JUri::root(true) . $this->directory . $block['icon'];
		$title = $block['title'];
		$link = $block['link'];
		$target = isset($block['target']) ? $block['target'] : 'self';

		return '
			<div class="quicklinks-block">
				<div class="quicklinks-icon"><img src="'.$icon.'" /></div>
				<div class="quicklinks-title">
					<span>'.JTEXT::_('MC_RQL_TITLE').'</span>
					<input class="text_area quick-input" id="jform_params_title-'.$index.'" name="jform[params][title-'.$index.']" value="'.$title.'" type="text" />
				</div>
				<div class="quicklinks-link">
					<span>'.JTEXT::_('MC_RQL_LINK').'</span>
					<input class="text_area quick-input" id="jform_params_link-'.$index.'" name="jform[params][link-'.$index.']" value="'.$link.'" type="text" />
				</div>
				<div class="quicklinks-targetlist">
					<span>'.JTEXT::_('MC_RQL_TARGET').'</span>
					<select class="inputbox quicklinks-select" id="jform_params_target-'.$index.'" name="jform[params][target-'.$index.']">
						'.$this->populateTargets($target).'
					</select>
				</div>
				<div class="quicklinks-iconslist">
					<span>'.JTEXT::_('MC_RQL_ICON').'</span>
						'.$this->populateIcons($icon, $index).'
				</div>

				<div class="quicklinks-controls">
					<div class="quicklinks-add"></div>
					<div class="quicklinks-remove"></div>
				</div>
				<div class="quicklinks-move"></div>
			</div>
		';
	}

	function _getJSVersion() {
		if (version_compare(JVERSION, '1.5', '>=') && version_compare(JVERSION, '1.6', '<')){
			if (JPluginHelper::isEnabled('system', 'mtupgrade')){
				return "-mt1.2";
			} else {
				return "";
			}
		} else {
			return "";
		}
	}

}
