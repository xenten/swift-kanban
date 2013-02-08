<?php
/**
 * RD DOWNLOAD
 * @author Pascal Lohmann (email contact@rdbs.net / site www.rdbs.de)
 * @version $Id: default.php 441 2009-11-13 16:30:32Z deutz $
 * @package RD_DOWNLOAD
 * @copyright Copyright (C) 2008 Robert Deutz Business Solution
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 **/
 
defined('_JEXEC') or die('Restricted access'); 

$config = JComponentHelper::getParams( 'com_rd_download' );
$verbose = $config->get('verbose');

$filedir = $config->get('downloaddir');
?>

<h2><?php echo JText::_('RDDOWNLOAD_LISTTITLE'); ?></h2>
<?

if (is_dir($filedir)) 
{
	if (is_array($this->rows) && count($this->rows) != 0)
	{
		echo '<ul class=rddownloadflist>';
	
		foreach ($this->rows as $row) 
		{
			$filename=$row->filename;
			if (in_array($filename, scandir($filedir))) 
			{
				echo '<li>'.$row->text. ' <small><em>(<a href="index.php?option=com_rd_download&amp;view=download&amp;task=dl&amp;id='.$row->id.'">'.$row->text.'</a> - Downloads: '.$row->klicks.')</em></small></li>';
			}
			else
			{
				echo $verbose == 1 ? '<li>'.JText::sprintf('RDDOWNLOAD_FILENOTFOUND',$filename). '</li>' : '';
			}
		}
		echo '</ul>';
	}	
}
else 
{ 
	echo $verbose == 1 ? JText::sprintf('RDDOWNLOAD_DOWNLOADDIRNOTVAILD',$filedir): '';
}

