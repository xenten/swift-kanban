<?php
/**
 * RD DOWNLOAD
 * @author Robert Deutz (email contact@rdbs.net / site www.rdbs.de)
 * @author Pascal Lohmann 
 * @version $Id: download.php 440 2009-11-13 16:22:15Z deutz $
 * @package RD_DOWNLOAD
 * @copyright Copyright (C) 2008 Robert Deutz Business Solution
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 **/
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.controller');

class RdDownloadControllerDownload extends JController
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add'  , 	'edit' );
		$this->registerTask( 'unpublish', 	'publish');
	}

	
	function display()
	{
	  	JRequest::setVar( 'view', 'download' );
		parent::display();
	}
	
	
	/**
	 * display the edit form
	 * @return void
	 */
	function edit()
	{
		JRequest::setVar( 'view', 'download' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function save()
	{
		$model = $this->getModel('download');

		if ($model->store()) {
			$msg = JText::_( 'RDDOWNLOAD_SAVED' );
		} else {
			$msg = JText::_( 'RDDOWNLOAD_ERRORSAVING' );
		}

		$link = 'index.php?option=com_rd_download';
		$this->setRedirect($link, $msg);
	}

	/**
	 * remove record(s)
	 * @return void
	 */
	function remove()
	{
		$model = $this->getModel('download');
		if(!$model->delete()) {
			$msg = JText::_( 'RDDOWNLOAD_ERRORREMOVE' );
		} else {
			$msg = JText::_( 'RDDOWNLOAD_REMOVED' );
		}

		$this->setRedirect( 'index.php?option=com_rd_download', $msg );
	}

	
	/**
	* Publishes or Unpublishes one or more records
	* @param array An array of unique category id numbers
	* @param integer 0 if unpublishing, 1 if publishing
	* @param string The current url option
	*/
	function publish()
	{
		$this->setRedirect( 'index.php?option=com_rd_download' );

		// Initialize variables
		$db			=& JFactory::getDBO();
		$cid		= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$task		= JRequest::getCmd( 'task' );
		$publish	= ($task == 'publish');
		$n			= count( $cid );

		if (empty( $cid )) {
			return JError::raiseWarning( 500, JText::_( 'RDDOWNLOAD_NOITEMSSEL' ) );
		}

		JArrayHelper::toInteger( $cid );
		$cids = implode( ',', $cid );

		$query = 'UPDATE #__rd_download'
		. ' SET published = ' . (int) $publish
		. ' WHERE id IN ( '. $cids .' )'
		;
		$db->setQuery( $query );
		if (!$db->query()) {
			return JError::raiseWarning( 500, $db->getError() );
		}
		$this->setMessage( JText::sprintf( $publish ? 'RDDOWNLOAD_ITEMSPUB' : 'RDDOWNLOAD_ITEMSUNPUB', $n ) );

	}
	
	
	/**
	 * cancel editing a record
	 * @return void
	 */
	function cancel()
	{
		$msg = JText::_( 'RDDOWNLOAD_CANCELLED' );
		$this->setRedirect( 'index.php?option=com_rd_download', $msg );
	}
}
