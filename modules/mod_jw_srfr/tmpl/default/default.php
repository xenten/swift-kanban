<?php 
/**
 * @version		2.3
 * @package		Simple RSS Feed Reader (module)
 * @author    JoomlaWorks - http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2011 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/*
Here we call the stylesheet style.css from a folder called 'css' and located at the same directory with this template file. If Joomla!'s cache is turned on, we print out the CSS include within a script tag so we're valid and the styling is included properly (it's how Joomla! works unfortunately).
*/
$filePath = substr(JURI::base(), 0, -1).str_replace(JPATH_SITE,'',dirname(__FILE__));

?>

<?php if($mainframe->getCfg('caching')): ?>
<script type="text/javascript">
	//<![CDATA[
	document.write('\
	<style type="text/css" media="all">\
		@import "<?php echo $filePath; ?>/css/style.css";\
	</style>\
	');
	//]]>
</script>
<?php else: ?>
<?php $document->addStyleSheet($filePath.'/css/style.css'); ?>
<?php endif; ?>

<div class="srfrContainer <?php echo $moduleclass_sfx; ?>">
	
	<?php if($feedsBlockPreText): ?>
	<p class="srfrPreText"><?php echo $feedsBlockPreText; ?></p>
	<?php endif; ?>
	
	<ul class="srfrList">
		<?php foreach($output as $key=>$feed): ?>
		<li>
			<?php if($feedItemTitle): ?>
			<a target="_blank" href="<?php echo $feed->itemLink; ?>"><?php echo $feed->itemTitle; ?></a>
			<?php endif; ?>
			

			<?php if($feedItemReadMore): ?>
			<span class="srfrReadMore">
				<a target="_blank" href="<?php echo $feed->itemLink; ?>"><?php echo JText::_('MOD_JW_SRFR_READ_MORE'); ?></a>
			</span>
			<?php endif; ?>

			<span class="clr"></span>
		</li>
		<?php endforeach; ?>	
	</ul>
	
	<?php if($feedsBlockPostText): ?>
	<p class="srfrPostText"><?php echo $feedsBlockPostText; ?></p>
	<?php endif; ?>
	
	<?php if($feedsBlockPostLink): ?>
	<p class="srfrPostTextLink"><a href="<?php echo $feedsBlockPostLinkURL; ?>"><?php echo $feedsBlockPostLinkTitle; ?></a></p>
	<?php endif; ?>
</div>

<div class="clr"></div>
