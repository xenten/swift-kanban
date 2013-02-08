<?php
/**
 * RD DOWNLOAD
 * @author Robert Deutz (email contact@rdbs.net / site www.rdbs.de)
 * @author Pascal Lohmann
 * @version $Id: form.php 440 2009-11-13 16:22:15Z deutz $
 * @package RD_DOWNLOAD
 * @copyright Copyright (C) 2008 Robert Deutz Business Solution
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 **/

defined('_JEXEC') or die('Restricted access'); ?>

<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}
		// do field validation
		if (form.text.value == "") {
			alert( "<?php echo JText::_( 'RDDOWNLOAD_DOWNLOADMUSTHAVEATITLE', true ); ?>" );
		} else {
			submitform( pressbutton );
		}
	}
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div>
	<fieldset class="adminform">
	<legend><?php echo JText::_( 'DETAILS' ); ?></legend>
	<table class="admintable">
		<tr>
			<td width="110" class="key">
				<label for="title">
					<?php echo JText::_( 'TITLE' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="text" id="text" size="60" value="<?php echo $this->element->text; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="alias">
					<?php echo JText::_( 'RDDOWNLOAD_TARGETFILE' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="filename" id="filename" size="60" value="<?php echo $this->element->filename; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="alias">
					<?php echo JText::_( 'RDDOWNLOAD_KLICKS' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="klicks" id="klicks" size="60" value="<?php echo $this->element->klicks; ?>" />
			</td>
		</tr>
		
		
		<tr>
			<td width="120" class="key">
				<?php echo JText::_( 'RDDOWNLOAD_PUBLISHED' ); ?>:
			</td>
			<td>
				<?php echo JHTML::_( 'select.booleanlist',  'published', 'class="inputbox"', $this->element->published ); ?>
			</td>
		</tr>
	</table>
	</fieldset>
</div>
<div class="clr"></div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_rd_download" />
<input type="hidden" name="id" value="<?php echo $this->element->id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="download" />
</form>
