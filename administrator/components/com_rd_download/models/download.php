<?php
/**
 * RD DOWNLOAD
 * @author Robert Deutz (email contact@rdbs.net / site www.rdbs.de)
 * @author Pascal Lohmann 
 * @version $Id: download.php 428 2009-11-13 12:14:38Z deutz $
 * @package RD_DOWNLOAD
 * @copyright Copyright (C) 2008 Robert Deutz Business Solution
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 **/

// Check to ensure this file is included in Joomla!
defined ( '_JEXEC' ) or die ();
jimport ( 'joomla.application.component.model' );

class RdDownloadModelDownload extends JModel 
{
	var $_data = array();
	
	/**
	 * Constructor that retrieves the ID from the request
	 *
	 * @access	public
	 * @return	void
	 */
	function __construct() {
		parent::__construct ();
		
		$array = JRequest::getVar ( 'cid', 0, '', 'array' );
		$this->setId ( ( int ) $array [0] );
	}
	
	/**
	 * Method to set the id
	 *
	 * @access	public
	 * @param	int Hello identifier
	 * @return	void
	 */
	function setId($id) {
		// Set id and wipe data
		$this->_id = $id;
		$this->_data = null;
	}
	
	/**
	 * Method to get data
	 * @return object with data
	 */
	function &getData() {
		// Load the data
		if (empty ( $this->_data )) {
			$query = ' SELECT * FROM #__rd_download ' . '  WHERE id = ' . $this->_id;
			$this->_db->setQuery ( $query );
			$this->_data = $this->_db->loadObject ();
		}
		if (! $this->_data) {
			$this->_data = new stdClass ( );
			$this->_data->id = 0;
			$this->_data->text = null;
			$this->_data->filename = null;
			$this->_data->klicks = 0;
			$this->_data->published = null;
		}
		return $this->_data;
	}
	
	/**
	 * Method to store a record
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function store() {
		$row = & $this->getTable ();
		
		$data = JRequest::get ( 'post' );
		
		// Bind the form fields to the hello table
		if (! $row->bind ( $data )) {
			$this->setError ( $this->_db->getErrorMsg () );
			return false;
		}
		
		// Make sure the hello record is valid
		if (! $row->check ()) {
			$this->setError ( $this->_db->getErrorMsg () );
			return false;
		}
		
		// Store the web link table to the database
		if (! $row->store ()) {
			$this->setError ( $row->getErrorMsg () );
			return false;
		}
		
		return true;
	}
	
	/**
	 * Method to delete record(s)
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function delete() {
		$cids = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		$row = & $this->getTable ();
		
		if (count ( $cids )) {
			foreach ( $cids as $cid ) {
				if (! $row->delete ( $cid )) {
					$this->setError ( $row->getErrorMsg () );
					return false;
				}
			}
		}
		return true;
	}
	
	function getDataList() {
		// Lets load the data if it doesn't already exist
		if (empty ( $this->_data )) {
			$query = ' SELECT * ' . ' FROM #__rd_download ';
			
			$this->_data = $this->_getList ( $query );
		}
		
		return $this->_data;
	}

}
