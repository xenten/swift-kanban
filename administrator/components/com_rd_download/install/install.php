<?php
/**
* @version $Id: install.php 444 2009-11-13 17:11:15Z deutz $
* @copyright * 2009 * Robert Deutz Business Solution * www.rdbs.de *
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$status = new JObject();
// check if the content plugin is installed
$db= JFactory::getDBO(); 
$db->setQuery("SELECT * FROM #__jos_plugins WHERE element = 'rd_download' AND folder='content'");
$result=$db->loadObject();
$status->set('rd_download_plugin', false);
if (!is_null($result))
{
	// plugin installed, plulished ???
	if ($result->published == 0)
	{
		$row = JTable::getInstance('plugin');
		$row->load($result->id);
		$row->published = 1;
		if (!$row->store()) 
		{
			$status->set('rd_download_plugin_published', false);
		}
		else
		{
			$status->set('rd_download_plugin_published', true);
		}
	}
	$status->set('rd_download_plugin_published', true);
	$status->set('rd_download_plugin', true);
}
else
{
	// Insert in database
	$row = JTable::getInstance('plugin');
	$row->name = 'RD Download Plugin';
	$row->ordering = 0;
	$row->folder = 'content';
	$row->iscore = 0;
	$row->access = 0;
	$row->client_id = 0;
	$row->element = 'rd_download';
	$row->published = 1;
	$row->params = '';
	if (!$row->store()) 
	{
		// Install failed, roll back changes
		$this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.$db->stderr(true));
		return false;
	}
	$status->set('rd_download_plugin', true);
	$status->set('rd_download_plugin_published', true);
}	

	
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
	
// copy table directory form site to admin
$site_path  = 'components'.DS.'com_rd_download'; 
$admin_path = 'administrator'.DS.'components'.DS.'com_rd_download'; 
JFolder::copy($site_path.DS.'tables', $admin_path.DS.'tables', JPATH_ROOT);

// move the Content plugin to it's location
$admin_path   = 'administrator'.DS.'components'.DS.'com_rd_download';
$plugins_path = 'plugins';
JFile::move($admin_path.DS.$plugins_path.DS.'content'.DS.'rd_download.xml',  $plugins_path.DS.'content'.DS.'rd_download.xml', JPATH_ROOT);
JFile::move($admin_path.DS.$plugins_path.DS.'content'.DS.'rd_download.php',  $plugins_path.DS.'content'.DS.'rd_download.php', JPATH_ROOT);


// Output status
?>
<h1>Robert Deutz Business Solution - Run Digital Download</h1>
<p>Visit: <a href="http://www.rdbs.de" target="_blank">Our Website</a> | <a href="http://community.rdbs.net" target="_blank">RDBS Community</a> | <a href="http://www.run-digital.com" target="_blank">Run-Digital</a> | <a href="http://www.robert-deutz.de" target="_blank">My Blog</a></p> 
<script>$$('table.adminform')[0].getElementsByTagName('tr')[0].setStyle('display', 'none');</script>
<table class="adminlist">
	<thead>
		<tr>
			<th class="title"><?php echo JText::_('Task'); ?></th>
			<th width="60%"><?php echo JText::_('Status'); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
	</tfoot>
	<tbody>
	
		<?php 
		$i=0;
		if ($status->get('rd_download_plugin')) {  ?>
		
			<tr class="row" <?php echo $i;?>>
	            <td class="key"><?php echo JText::_('RD DOWNLOAD Plugin'); ?></td>
	            <td><?php echo ($status->get('rd_download_plugin_published')) ? '<strong>'.JText::_('Published').'</strong>' : '<em>'.JText::_('NOT Published').'<br /> Plugin must be Published!</em>'; ?>
	            </td>
	        </tr>
	    <?php } else { ?>    
			<tr class="row" <?php echo $i;?>>
	            <td class="key"><?php echo JText::_('RD DOWNLOAD Plugin'); ?></td>
	            <td><?php echo 'Plugin must be installed and published!</em>'; ?>
	            </td>
	        </tr>
        <?php } 
        	$i++;
        ?>
 		<tr class="row" <?php echo $i;?>>
			<td class="key"><?php echo JText::_('PHP Version'); ?></td>
			<td>
				<?php echo version_compare(phpversion(), '5.2', '>=')
					? '<strong>'.JText::_('OK').'</strong> - '.phpversion()
					: '<em>'.JText::_('You need at least PHP v5.2 to use RD DOWNLOAD You are using: ').phpversion().'</em>'; ?>
			</td>
		</tr>
	</tbody>
</table>
<p style="text-align:center;">&copy; Copyright 2009 by Robert Deutz - <a href="http://www.rdbs.de" target="_blank">Robert Deutz Business Solution</a></p>