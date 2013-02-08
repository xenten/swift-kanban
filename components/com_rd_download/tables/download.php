<?php
/**
 * RD DOWNLOAD
 * @author Pascal Lohmann (email contact@rdbs.net / site www.rdbs.de)
 * @version $Id: download.php 426 2009-11-13 11:21:01Z deutz $
 * @package RD_DOWNLOAD
 * @copyright Copyright (C) 2008 Robert Deutz Business Solution
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 **/

// no direct access
defined('_JEXEC') or die('Restricted access');

class TableDownload extends JTable
{
	/** @var int Primary key */
	var $id					= 0;
	/** @var string */
	var $text				= '';
	/** @var string */
	var $filename	  		= '';
	/** @var string */
	var $klicks 			= 0;

	var $published			= 0;
	/** @var int */

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableDownload(& $db) {
		parent::__construct('#__rd_download', 'id', $db);
	}
}
