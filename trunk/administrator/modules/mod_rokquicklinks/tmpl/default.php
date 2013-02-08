<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="rok-quicklinks-customize mc-button">
	<span class="button">
		<a href="<?php echo $modulepath ?>"><?php echo JTEXT::_('MC_RQL_CUSTOMIZE'); ?></a>
	</span>
</div>
<div class="rok-quicklinks">
	<ul>
	<?php foreach ($quicklinks as $ql) : ?>
		<?php if ($ql != null) : ?>
		<li>
			<a href="<?php echo $ql[1]; ?>"<?php echo $ql[3] == 'blank' ? ' target="_blank"' : ''; ?>>
				<span class="rok-quicklink-box">
					<img src="<?php echo $iconpath.$ql[0]; ?>" alt="<?php echo $ql[2]; ?>" /><br />
					<strong><?php echo $ql[2]; ?></strong>
				</span>
			</a>
		</li>
		<?php endif; ?>
	<?php endforeach; ?>
	</ul>
</div>
