<?php
/**
 * @version		$Id: blog.php 20196 2011-01-09 02:40:25Z ian $
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');

?>
<div class="blog<?php echo $this->pageclass_sfx;?>">
<?php if ($this->params->get('show_page_heading', 1)) : ?>
	<h1>
		<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h1>
	<?php endif; ?>

	<?php if ($this->params->get('show_category_title', 1) OR $this->params->get('page_subheading')) : ?>
	<h2>
		<?php echo $this->escape($this->params->get('page_subheading')); ?>
		<?php if ($this->params->get('show_category_title')) : ?>
			<span class="subheading-category"><?php echo $this->category->title;?></span>
		<?php endif; ?>
	</h2>
	<?php endif; ?>
	
<?php if ($this->params->get('show_description', 1) || $this->params->def('show_description_image', 1)) : ?>
	<div class="category-desc">
	<?php if ($this->params->get('show_description_image') && $this->category->getParams()->get('image')) : ?>
		<img src="<?php echo $this->category->getParams()->get('image'); ?>"/>
	<?php endif; ?>
	<?php if ($this->params->get('show_description') && $this->category->description) : ?>
		<?php echo JHtml::_('content.prepare', $this->category->description); ?>
	<?php endif; ?>
	<div class="clr"></div>
	</div>
<?php endif; ?>

<?php $leadingcount=0 ; ?>
<?php if (!empty($this->lead_items)) : ?>
<div class="items-leading">
	<?php foreach ($this->lead_items as &$item) : ?>
		<div class="leading-<?php echo $leadingcount; ?><?php echo $item->state == 0 ? 'class="system-unpublished"' : null; ?>">
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

	<?php echo $this->loadTemplate('links'); ?>

<?php endif; ?>


	<?php if (!empty($this->children[$this->category->id])&& $this->maxLevel != 0) : ?>
		<div class="cat-children">
		<h3>
<?php echo JTEXT::_('JGLOBAL_SUBCATEGORIES'); ?>
</h3>
			<?php echo $this->loadTemplate('children'); ?>
		</div>
	<?php endif; ?>

<?php if (($this->params->def('show_pagination', 1) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) : ?>
	<div class="pagination">
		<?php  if ($this->params->def('show_pagination_results', 1)) : ?>
		<p class="counter">
			<?php echo $this->pagination->getPagesCounter(); ?>
		</p>

		<?php endif; ?>
		<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
<?php  endif; ?>

</div>
<?php
if (
    ($this->category->id == 10)
    || ($this->category->id == 12)
    ):
    ?>

<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
<script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script>
<div id="fb-root"></div>
<script>
    <!--  Google +1 Code starts here -->
    (function() {
        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
        po.src = 'https://apis.google.com/js/plusone.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
    })();
    <!--  Google +1 Code ends here -->

    <!--  Facebook Like Code starts here -->
    (function(d, s, id)
    {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
    <!--  Facebook Like Code ends here -->
    <!--  Facebook Share Code starts here -->
    function fbs_click(sharetitle, shareurl)
    {
        u=shareurl;
        t=sharetitle;
        window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(u)+'&t='+encodeURIComponent(t),'sharer',
            'toolbar=0,status=0,width=660,height=360,resizable=0,scrollbars=0');
        return false;
    }
    <!--  Facebook Share Code ends here -->
</script>
<script src="//platform.linkedin.com/in.js" type="text/javascript"></script>
<?php endif; ?>