<?php
/**
 * RD DOWNLOAD
 * @author Robert Deutz (email contact@rdbs.net / site www.rdbs.de)
 * @author Pascal Lohmann 
 * @version $Id: default.php 428 2009-11-13 12:14:38Z deutz $
 * @package RD_DOWNLOAD
 * @copyright Copyright (C) 2008 Robert Deutz Business Solution
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 **/

defined('_JEXEC') or die('Restricted access'); ?>

<form action="index.php" method="post" name="adminForm">
<div id="editcell">
	<table class="adminlist">
	<thead>
		<tr>
		<tr>
			<th width="5">
				<?php echo JText::_( 'RDDOWNLOAD_NUM' ); ?>
			</th>
			<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
			</th>
			<th  class="title">
				<?php echo JHTML::_('grid.sort',   'RDDOWNLOAD_TITLE', 'a.text', @$lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
				<th  class="title">
				<?php echo JHTML::_('grid.sort',   'RDDOWNLOAD_TARGETFILE', 'a.filename', @$lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
				<th  class="title">
				<?php echo JHTML::_('grid.sort',   'RDDOWNLOAD_KLICKS', 'a.klicks', @$lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
			<th width="5%" align="center">
				<?php echo JHTML::_('grid.sort',   'RDDOWNLOAD_PUBLISHED', 'a.published', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
			<th width="1%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',   'RDDOWNLOAD_ID', 'a.id', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
		</tr>
			
	</thead>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$row = &$this->items[$i];
		
		$published		= JHTML::_('grid.published', $row, $i );
		$checked 	= JHTML::_('grid.id',   $i, $row->id );
		$link 		= JRoute::_( 'index.php?option=com_rd_download&controller=download&task=edit&cid[]='. $row->id );

		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $row->id; ?>
			</td>
			<td>
				<?php echo $checked; ?>
			</td>
			<td>
				<a href="<?php echo $link; ?>"><?php echo $row->text; ?></a>
			</td>
			<td>
				<a href="<?php echo $link; ?>"><?php echo $row->filename; ?></a>
			</td>
			
			<td>
				<?php echo $row->klicks; ?>
			</td>
			
			<td align="center">
				<?php echo $published;?>
			</td>
			<td align="center">
				<?php echo $row->id; ?>
			</td>
		</tr>
		<?php
		$k = 1 - $k;
	}
	?>
	</table>
</div>

<input type="hidden" name="option" value="com_rd_download" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="download" />
</form>
