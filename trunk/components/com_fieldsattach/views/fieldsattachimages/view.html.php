<?php
/**
 * @version		$Id: view.html.php 15 2011-09-02 18:37:15Z cristian $
 * @package		fieldsattach
 * @subpackage		Components
 * @copyright		Copyright (C) 2011 - 2020 Open Source Cristian Grañó, Inc. All rights reserved.
 * @author		Cristian Grañó
 * @link		http://joomlacode.org/gf/project/fieldsattach_1_6/
 * @license		License GNU General Public License version 2 or later
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * fieldsattachs View
 */
class fieldsattachViewfieldsattachimages extends JView
{
	/**
	 * fieldsattachs view display method
	 * @return void
	 */
	function display($tpl = null) 
	{
		// Get data from the model
		//$items = $this->get('Items');
		//$pagination = $this->get('Pagination');
                // Assign data to the view
		/*$this->items            =  $this->get('Items');
		$this->pagination       = $this->get('Pagination');
                $this->state		= $this->get('State');
                $this->categories	= $this->get('CategoryOrders');
                
	 
		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}*/
		

		// Set the toolbar
		//$this->addToolBar();
                 $fieldsattachid = JRequest::getVar("fieldsattachid");
                echo $script = '
                    <script type="text/javascript">
                     window.parent.update_gallery'.$fieldsattachid.'();
                     window.parent.SqueezeBox.close();
                     
                        
		</script>';

                //$this->setDocument();

		// Display the template
		//parent::display($tpl); 
	}

         
 
	 
	/**
	 * Method to set up the document properties
	 *
	 * @return void
	 */
	protected function setDocument()
	{
 
		$document = JFactory::getDocument();
	 	$document->addScript(JURI::root() . "media/system/js/modal.js");
                $fieldsattachid = JRequest::getVar("id");
                $script = "
                     window.parent.SqueezeBox.close();
                     window.parent.update_gallery".$fieldsattachid."();
                        
		});";
                
        $document->addScriptDeclaration($script); 
        $document->addStyleSheet(JURI::root() . "media/system/css/modal.css") ;
	}
}
