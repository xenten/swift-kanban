<?php
/**
 * RD DOWNLOAD
 * @author Robert Deutz (email contact@rdbs.net / site www.rdbs.de)
 * @author Pascal Lohmann
 * @version $Id: view.html.php 429 2009-11-13 12:43:25Z deutz $
 * @package RD_DOWNLOAD
 * @copyright Copyright (C) 2008 Robert Deutz Business Solution
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 **/
 
jimport( 'joomla.application.component.view');
/**
 * View class
 * 
 * @package RD_DOWNLOAD
 */
class RdDownloadViewDownload extends JView
{
	function display($tpl = null)
	{
		$model	  = &$this->getModel();
  		$rows     = $model->getDownloadList();
		$this->assignRef('rows'  , $rows);
		parent::display($tpl);
	}	
}

