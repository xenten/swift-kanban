<?php
/**
 * RD DOWNLOAD
 * @author Robert Deutz (email contact@rdbs.net / site www.rdbs.de)
 * @author Pascal Lohmann 
 * @version $Id: download.php 442 2009-11-13 16:39:26Z deutz $
 * @package RD_DOWNLOAD
 * @copyright Copyright (C) 2008 Robert Deutz Business Solution
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 **/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
jimport('joomla.application.component.controller');

/**
 * Download Component Controller
 */
class RdDownloadController extends JController
{
	function dl()
	{
		$config  		= JComponentHelper::getParams( 'com_rd_download' );
		$verbose 		= $config->get ( 'verbose' );
		$downloaddir 	= $config->get('downloaddir');		
    	$id 	 		= JRequest::getVar('id');
		$db 			= JFactory::getDBO();

		$query 	= "SELECT * FROM #__rd_download WHERE id = $id";
		$db->setQuery($query);
		
		$result = $db->loadObject();
		$filename = $result->filename;
		
		$db->setQuery("UPDATE #__rd_download SET klicks=klicks+1 WHERE id = $id");
		if (!$db->query() && $verbose)
		{
			return JError::raiseError(JText::_('COULDNOTUPDATEDOWNLOADCOUNTER'));
		}

		$dpfad = $downloaddir.DS.$filename;
		
		ob_end_clean();
		header("Cache-Control: public, must-revalidate");
		header('Cache-Control: pre-check=0, post-check=0, max-age=0');
		header("Pragma: no-cache");
		header("Expires: 0"); 
		header("Content-Description: File Transfer");
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
		header("Content-Type: application/octetstream");
		header('Content-Disposition: attachment; filename='.$filename);
    	header("Content-Transfer-Encoding: binary\n");
    	readfile($dpfad);
	    exit;
	}
}
