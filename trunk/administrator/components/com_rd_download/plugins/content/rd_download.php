<?php
/**
 * RD DOWNLOAD
 * @author Robert Deutz (email contact@rdbs.net / site www.rdbs.de)
 * @author Pascal Lohmann
 * @version $Id: rd_download.php 440 2009-11-13 16:22:15Z deutz $
 * @package RD_DOWNLOAD
 * @copyright Copyright (C) 2008 Robert Deutz Business Solution
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 **/

defined ( '_JEXEC' ) or die ( 'Restricted access' );

$mainframe->registerEvent ( 'onPrepareContent', 'botrddownload' );

function botrddownload(&$row) 
{
	if ( strpos( $row->text, '{rddl}' ) === false ) 
	{
		return true;
	}

	$config = JComponentHelper::getParams ( 'com_rd_download' );
	$verbose = $config->get ( 'verbose',1 );

	// regular expression for the bot
	$regex = "#{rddl}(.*?){/rddl}#s";

	$lang =& JFactory::getLanguage();
	if($lang->load('com_rd_download') === false && $verbose == 1)
	{
		$row->text = preg_replace( $regex, 'COULD NOT LOAD LANGUAGE FILE FOR COM_RD_DOWNLOAD', $row->text );
		return true;
	}
	
	// check whether plugin has been unpublished
	if ( !JPluginHelper::isEnabled('content','rd_download') ) 
	{
		$row->text = preg_replace( $regex, '', $row->text );
		return true;
	}

	$downloaddir = $config->get ( 'downloaddir','downloads' );

	if($downloaddir{0} != '/')
	{
		// relative dir, make it absolute
		$downloaddir=JPATH_ROOT.DS.$downloaddir;
	}

	if (!is_dir($downloaddir))
	{
		$row->text = preg_replace( $regex, 'DIRECTORY NOT VALID:'. $downloaddir, $row->text );
		return true;
	}
	
	$matches=array();
 	// find all instances of plugin and put in $matches
	preg_match_all( $regex, $row->text, $matches );

	// Number of plugins
 	$count = count( $matches[0] );

 	// plugin only processes if there are any instances of the plugin in the text
 	if ( $count ) 
 	{
		$db =& JFactory::getDBO();
		
		for($i=0;$i<$count;$i++)
		{
			$m = $matches[0][$i];
			$m = str_replace ('{rddl}','',$m);
			$m = str_replace ('{/rddl}','',$m);
			$filename = trim($matches[1][$i]);
			$pubfound = false;
			$query = "SELECT * FROM #__rd_download WHERE filename='$filename'";
			$db->setQuery ( $query );
			$rows = $db->loadObjectList ();
			$rc = count ( $rows );
			if ( is_array ( $rows ) && $rc != 0 )
			{
				for($j=0;$j<$rc;$j++)
				{
					$r=$rows[$j];
					if($r->published == 1)
					{
						//$row=$r;
						$pubfound=true;
						break;							
					}
				}
			}
			$filefound = in_array($filename,scandir ( $downloaddir ));
			if ($pubfound && $filefound) 
			{
				$replace = '<a href="index.php?option=com_rd_download&view=download&task=dl&id='.$r->id.'">'.$r->text.'</a>';
			}
			else
			{
				if ($filefound)
				{
					if ($rc == 0)
					{
						// new file insert in table
						$db->setQuery 	( "INSERT INTO `#__rd_download` ( `id` ,`text` ,`filename` ,`klicks` , `published` ) VALUES ".
											"( NULL , '$filename', '$filename', '0', '1' )" 
										);
						if($db->Query())
						{
							// get last id
							$db->setQuery('SELECT LAST_INSERT_ID( )');
							$id = $db->loadResult();
							$replace = '<a href="index.php?option=com_rd_download&view=download&task=dl&id='.$id.'">'.$filename.'</a>';
						}
						else 
						{	
							// error
							$replace = JText::_('RDDOWNLOAD_FATALCOULDNOTIMPORTINTABLE');
						}
					} 
					else 
					{	
						$replace = '';
						if ($verbose == 1)
						{
							$replace = JText::sprintf('RDDOWNLOAD_FILEUNPUBLISHED',$filename);
						}
					}
				}
				else 
				{
					// file not found
					$replace = '';
					if ($verbose == 1)
					{
						$replace = JText::sprintf('RDDOWNLOAD_FILENOTFOUND',$filename, $downloaddir);
					}
				}
			}
			$row->text 	= str_replace($matches[0][$i], $replace, $row->text );			
		}
 	}
	return true;
}
