
<?php
/**
 * @version		$Id: default.php 20196 2011-01-09 02:40:25Z ian $
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.DS.'helpers');

// If the page class is defined, add to class as suffix.
// It will be a separate class if the user starts it with a space
?>
<div class="blog-featured<?php echo $this->pageclass_sfx;?>">
<?php if ( $this->params->get('show_page_heading')!=0) : ?>
	<h1>
	<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h1>
<?php endif; ?>

<?php $leadingcount=0 ; ?>
<?php if (!empty($this->lead_items)) : ?>
<div class="items-leading">
	<?php foreach ($this->lead_items as &$item) : ?>
		<div class="leading-<?php echo $leadingcount; ?><?php echo $item->state == 0 ? ' system-unpublished' : null; ?>">
			<?php
				$this->item = &$item;
				echo $this->loadTemplate('item');
			?>
		</div>
		<?php
			$leadingcount++;
		?>
	<?php endforeach; ?>
</div>
<?php endif; ?>

<?php
if ($count = count($this->intro_items)) :
	// init vars
	$rows    = ceil($count / $this->params->get('num_columns', 2));
	$columns = array();
	$row     = 0;
	$column  = 0;
	$i       = 0;
	
	// create intro columns
	foreach ($this->intro_items as $item) {
		if ($this->params->get('multi_column_order', 1) == 0) {
			// order down
			if ($row >= $rows) {
				$column++;
				$row  = 0;
				$rows = ceil(($count - $i) / ($this->params->get('num_columns', 2) - $column));
			}
			$row++;
		} else {
			// order across
			$column = $i % $this->params->get('num_columns', 2);
		}

		if (!isset($columns[$column])) {
			$columns[$column] = '';
		}

		$this->item =& $item;
		$columns[$column] .= $this->loadTemplate('item');
		$i++;
	}
	
	// render intro columns
	if ($count = count($columns)) {
		echo '<div class="items-row cols-'.$count.' clearfix">';
		for ($i = 0; $i < $count; $i++) {
			$first = ($i == 0) ? ' first' : null;
			$last  = ($i == $count - 1) ? ' last' : null;
			echo '<div class="item column-'.($i+1).'"><div class="item-inner '.$first.$last.' clearfix">'.$columns[$i].'</div></div>';
		}
		echo '</div>';
	}
endif;
?>

<?php if (!empty($this->link_items)) : ?>
	<div class="items-more">
	<?php echo $this->loadTemplate('links'); ?>
	</div>
<?php endif; ?>

<?php if ($this->params->def('show_pagination', 2) == 1  || ($this->params->get('show_pagination') == 2 && $this->pagination->get('pages.total') > 1)) : ?>
	<div class="pagination">
		<?php if ($this->params->def('show_pagination_results', 1)) : ?>
			<p class="counter">
				<?php echo $this->pagination->getPagesCounter(); ?>
			</p>
		<?php  endif; ?>
				<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
<?php endif; ?>

</div>

