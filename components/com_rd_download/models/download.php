<?php
/**
 * RD DOWNLOAD
 * @author Robert Deutz (email contact@rdbs.net / site www.rdbs.de)
 * @author Pascal Lohmann
 * @version $Id: download.php 426 2009-11-13 11:21:01Z deutz $
 * @package RD_DOWNLOAD
 * @copyright Copyright (C) 2008 Robert Deutz Business Solution
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 **/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.model');

/**
 * RdDownloadModelDownload
 * 
 * @package RD_DOWNLOAD
 *
 */
class RdDownloadModelDownload extends JModel
{

	function getDownloadList( )
	{
		$query	= "SELECT * FROM #__rd_download WHERE published = 1";
		return $this->_getList( $query );
	}
}
