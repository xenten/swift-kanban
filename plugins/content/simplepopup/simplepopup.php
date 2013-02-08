<?php
/**
 * Simple PopUp - Joomla Plugin
 * 
 * @package    Joomla
 * @subpackage Plugin
 * @author Anders Wasén
 * @link http://wasen.net/
 * @license		GNU/GPL, see LICENSE.php
 * plg_simplefilegallery is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
// Import library dependencies
jimport('joomla.plugin.plugin');

define('SPU_PATH', dirname(__FILE__).DS.'simplepopup');

class plgContentSimplePopUp extends JPlugin
{
   /**
    * Constructor
    *
    * For php4 compatability we must not use the __constructor as a constructor for
    * plugins because func_get_args ( void ) returns a copy of all passed arguments
    * NOT references.  This causes problems with cross-referencing necessary for the
    * observer design pattern.
    */
	
	
    function plgContentSimplePopUp( &$subject, $config )
    {
			
			parent::__construct( $subject, $config );
 
            // load plugin parameters
            $this->_plugin = &JPluginHelper::getPlugin( 'content', 'simplepopup' );
            $this->params = new JParameter( $this->_plugin->params );
    }
 
	function onAfterDisplayContent(&$article, &$params, $limitstart)
	{
		JPlugin::loadLanguage( 'plg_content_simplepopup', JPATH_ADMINISTRATOR );		//Load the plugin language file - not in contructor in case plugin called by third party components
		$application = &JFactory::getApplication();

		$regex = "#{simplepopup\b(.*?)\}(.*?){/simplepopup}#s";
		$article->text = preg_replace_callback( $regex, array('plgContentSimplePopUp', 'render'), $article->text, -1, $count );
		
	}
	
	
	function render( &$matches )
    {
		
		$html   = '';
		/*
		$ix = 0;
		do {
			echo $matches[$ix];
			$ix++;
		} while (strlen($matches[$ix]) > 0);
		*/
		$this->popupmsg = $matches[0];
		$this->popupmsg = str_replace('{simplepopup}', '', $this->popupmsg);
		$this->popupmsg = str_replace('{/simplepopup}', '', $this->popupmsg);
		
		//echo "<textarea>".$this->popupmsg."</textarea>";
		
		ob_start();
		
		// This is only a test to call external PHP file
		//$html .= '<div>'.SPUAjaxServlet::getPopUp('HEJ!').'</div>';
		
        if (is_readable(SPU_PATH.DS.'default.php')) {
			include(SPU_PATH.DS.'default.php');
		} else {
			JError::raiseError(500, JText::_('Failed to load default.php'));
		}
		$html = ob_get_clean();
        
        return $html;
		
    }

 
}
?>
