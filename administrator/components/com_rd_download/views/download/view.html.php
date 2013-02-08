<?php
/**
 * RD DOWNLOAD
 * @author Robert Deutz (email contact@rdbs.net / site www.rdbs.de)
 * @author Pascal Lohmann 
 * @version $Id: view.html.php 428 2009-11-13 12:14:38Z deutz $
 * @package RD_DOWNLOAD
 * @copyright Copyright (C) 2008 Robert Deutz Business Solution
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 **/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

/**
 * Class RddownloadViewDownload
 * 
 * @package RD_DOWNLOAD
 */
class RddownloadViewDownload extends JView
{
	/**
	 * standard display function
	 * 
	 * @param string $tpl
	 * @return void
	 **/
	function display($tpl = null)
	{
		if ($this->getLayout() == "form") 
		{
		 $this->displayEdit($tpl);
		 return;
		}
		
		JToolBarHelper::title(   JText::_( 'RDDOWNLOAD_DOWNLOADMANAGER' ), 'generic.png' );
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::deleteList();
		JToolBarHelper::editListX();
		JToolBarHelper::addNewX();
		JToolBarHelper::preferences('com_rd_download', '150');

		// Get data from the model
		$items		= & $this->get( 'DataList');

		$this->assignRef('items',		$items);
		parent::display($tpl);

	}

	/**
	 * Edit view
	 *
	 * @param string $tpl
	 * @return void
	 */
	function displayEdit($tpl=null)
	{
		$element	=& $this->get('Data');
		$isNew		= ($element->id < 1);

		$text = $isNew ? JText::_( 'RDDOWNLOAD_NEW' ) : JText::_( 'RDDOWNLOAD_EDIT' );
		JToolBarHelper::title(   JText::_( 'RDDOWNLOAD_DOWNLOAD' ).': <small>[ ' . $text.' ]</small>' );
		JToolBarHelper::save();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'RDDOWNLOAD_CLOSE' );
		}
		$this->assignRef('element',		$element);
		parent::display($tpl);
	}

}
