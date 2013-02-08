<?php 
/**
 * Simple PopUp - Joomla Plugin
 * 
 * @package    Joomla
 * @subpackage Plugin
 * @author Anders Wasén
 * @link http://wasen.net/
 * @license		GNU/GPL, see LICENSE.php
 * plg_simplefilegallery is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

defined('_JEXEC') or die('Restricted access'); // no direct access 

$app = &JFactory::getApplication();
$user = &JFactory::getUser();
		
$document =& JFactory::getDocument();

$document->addStyleSheet( JURI::root().'plugins/content/simplepopup/spustyle.css' );
$document->addStyleSheet( JURI::root().'plugins/content/simplepopup/fancybox/jquery.fancybox-1.3.4.css');
$document->addScript( JURI::root()."plugins/content/simplepopup/jquery-1.4.3.min.js" );
$document->addScriptDeclaration("jQuery.noConflict();");

$spu_aligntext = $this->params->get( 'spu_aligntext', 'center' );
$spu_boxwidth = $this->params->get( 'spu_boxwidth', '400' );
$spu_boxheight = $this->params->get( 'spu_boxheight', 'auto' );
$spu_autodimensions = $this->params->get( 'spu_autodimensions', 'false' );

		
if( $app->get('jqueryspu') === true ) {
	// Pray that correct version is loded
} else {
?>
<!--script type="text/javascript" src="<?php echo JURI::root(); ?>plugins/content/simplepopup/jquery-1.4.3.min.js"></script-->
<?php
	$app->set('jqueryspu', true);
}
?>
<!-- SPU HTML GOES BELOW -->

<script language="javascript" type="text/javascript">
<!--
var addText = '';

-->
</script>
<script type="text/javascript" src="<?php echo JURI::root(); ?>plugins/content/simplepopup/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<script type="text/javascript" src="<?php echo JURI::root(); ?>plugins/content/simplepopup/fancybox/jquery.fancybox-1.3.4.js"></script>
<script language="javascript" type="text/javascript">

jQuery(document).ready(function() {
	
	var autodim = <?php echo $spu_autodimensions; ?>;
	
	jQuery.fancybox(
		''+addText,
		{
        	'autoDimensions'	: autodim,
			'width'         	: '<?php echo $spu_boxwidth; ?>',
			'height'        	: '<?php echo $spu_boxheight; ?>',
			'transitionIn'		: 'elastic',
			'transitionOut'		: 'elastic'
		}
	);
});

-->
</script>

<!-- FancyBox -->
<div id="spuSimplePoPup" style="display: none;">
	<div class="spu_content" style="text-align: <?php echo $spu_aligntext; ?>;">
		<?php echo $this->popupmsg; ?>
	</div>
</div>

<script language="javascript" type="text/javascript">
<!--

// FancyBox below
addText = document.getElementById('spuSimplePoPup').innerHTML;

-->
</script>
<!-- END SFG HTML -->