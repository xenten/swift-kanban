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

if(version_compare(JVERSION, '1.6.0', 'ge')) {
	jimport('joomla.form.formfield');
	class JFormFieldTemplate extends JFormField {

		var	$type = 'template';

		function getInput(){
			return JElementTemplate::fetchElement($this->name, $this->value, $this->element, $this->options['control']);
		}
	}
}

jimport('joomla.html.parameter.element');

class JElementTemplate extends JElement {

	var $_name = 'template';

	function fetchElement($name, $value, & $node, $control_name) {

		jimport('joomla.filesystem.folder');
		$mainframe = &JFactory::getApplication();
		$fieldName = (version_compare( JVERSION, '1.6.0', 'ge' )) ? $name : $control_name.'['.$name.']';

		$moduleTemplatesPath = JPATH_SITE.DS.'modules'.DS.'mod_jw_srfr'.DS.'tmpl';
		$moduleTemplatesFolders = JFolder::folders($moduleTemplatesPath);
		
		$db =& JFactory::getDBO();
		if(version_compare( JVERSION, '1.6.0', 'ge' )) {
			$query = "SELECT template FROM #__template_styles WHERE client_id = 0 AND home = 1";
		} else {
			$query = "SELECT template FROM #__templates_menu WHERE client_id = 0 AND menuid = 0";
		}
		
		$db->setQuery($query);
		$template = $db->loadResult();
		$templatePath = JPATH_SITE.DS.'templates'.DS.$template.DS.'html'.DS.'mod_jw_srfr';
		
		if (JFolder::exists($templatePath)){
			$templateFolders = JFolder::folders($templatePath);
			$folders = @array_merge($templateFolders, $moduleTemplatesFolders);
			$folders = @array_unique($folders);
		} else {
			$folders = $moduleTemplatesFolders;
		}

		sort($folders);

		$options = array();
		foreach($folders as $folder) {
			$options[] = JHTML::_('select.option', $folder, $folder);
		}

		return JHTML::_('select.genericlist', $options, $fieldName, 'class="inputbox"', 'value', 'text', $value);
	}

}
