<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div id="rok-stats">
	<ul>
		<?php foreach($rows as $row) : ?>
			<?php if (isset($row[3])) : ?>
				<li class="<?php echo $row[0]; ?>"><span class="desc"><a href="<?php echo $row[3]; ?>"><?php echo $row[1]; ?></a></span><span class="value"><?php echo $row[2]; ?></span></li>
			<?php else : ?>
				<li class="<?php echo $row[0]; ?>"><span class="desc"><?php echo $row[1]; ?></span><span class="value"><?php echo $row[2]; ?></span></li>
			<?php endif; ?>
		<?php endforeach; ?>
	<ul>
</div>
