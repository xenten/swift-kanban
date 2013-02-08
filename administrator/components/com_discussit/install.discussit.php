<?php
/**
 * 
 * Enable plugin after component install.
 * 
 */

defined ( '_JEXEC' ) or die ( 'Restricted access' );

jimport ( 'joomla.installer.installer' );
jimport ( 'joomla.application.application' );



class com_discussitInstallerScript
	{
	function postflight($type,$parent) {
	
	    $componentInstaller =& JInstaller::getInstance();
	    $installer = new JInstaller();
	    $db =& JFactory::getDBO();
	    $pathToPlgMylibrary = $componentInstaller->getPath('source') . DS . 'plg_discussit';
	    
	
	    $query = 'UPDATE ' . $db->nameQuote('#__extensions')
	           . ' SET ' . $db->nameQuote('enabled') . ' = 1'
	           . ' WHERE ' . $db->nameQuote('element') . ' = ' . $db->Quote('plg_discussit')
	           . ' AND ' .   $db->nameQuote('folder')  . ' = ' . $db->Quote('content');
	    $db->setQuery($query);
	    if (!$db->query()) {
	        echo '<p>'.JText::_('FAILED TO ENABLE REQUIRED PLUGIN: Discussit').'</p>';
	    } else {
	        echo '<p>'.JText::_('ENABLED REQUIRED PLUGIN: Discussit').'</p>';
	        
	        echo '<h3>Don\'t forget to enter your API key in plugin administration!</h3>';
	    }
	    
	 return true;   
	 
	}
}
