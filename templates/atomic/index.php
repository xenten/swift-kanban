<?php
/**
 * @package		Joomla.Site
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/* The following line loads the MooTools JavaScript Library */
JHtml::_('behavior.framework', true);

/* The following line gets the application object for things like displaying the site name */
$app = JFactory::getApplication();
?>
<?php echo '<?'; ?>xml version="1.0" encoding="<?php echo $this->_charset ?>"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
<head>
    <!-- The following JDOC Head tag loads all the header and meta information from your site config and content. -->
    <jdoc:include type="head" />



    <style type="text/css">
        @media only screen and (max-width: 480px) {
            body,table,td,a,li {
                -webkit-text-size-adjust:none !important;
            }
            table {width: 100% !important;}

            .responsive-image img {
                height: auto !important;
                max-width: 150px !important;
                width: 100% !important;
            }
        }
    </style>

</head>
<body>
<div class="container">
    <?php if($this->countModules('atomic-search') or $this->countModules('position-0')) : ?>
        <div class="joomla-search span-7 last">
            <jdoc:include type="modules" name="atomic-search" style="none" />
            <jdoc:include type="modules" name="position-0" style="none" />
        </div>
    <?php endif; ?>
</div>
<?php if($this->countModules('atomic-topmenu') or $this->countModules('position-2') ) : ?>
    <jdoc:include type="modules" name="atomic-topmenu" style="container" />
    <jdoc:include type="modules" name="position-1" style="container" />
<?php endif; ?>

<div class="container">
    <div class="span-16 append-1">
        <?php if($this->countModules('atomic-topquote') or $this->countModules('position-15') ) : ?>
            <jdoc:include type="modules" name="atomic-topquote" style="none" />
            <jdoc:include type="modules" name="position-15" style="none" />

        <?php endif; ?>
        <jdoc:include type="message" />
        <jdoc:include type="component" />
        <hr />
        <?php if($this->countModules('atomic-bottomleft') or $this->countModules('position-11')) : ?>
            <div class="span-7 colborder">
                <jdoc:include type="modules" name="atomic-bottomleft" style="bottommodule" />
                <jdoc:include type="modules" name="position-11" style="bottommodule" />

            </div>
        <?php endif; ?>

        <?php if($this->countModules('atomic-bottommiddle') or $this->countModules('position-9')
            or $this->countModules('position-10')) : ?>
            <div class="span-7 last">
                <jdoc:include type="modules" name="atomic-bottommiddle" style="bottommodule" />
                <jdoc:include type="modules" name="position-9" style="bottommodule" />
                <jdoc:include type="modules" name="position-10" style="bottommodule" />

            </div>
        <?php endif; ?>
    </div>
    <?php if($this->countModules('atomic-sidebar') || $this->countModules('position-7')
        || $this->countModules('position-4') || $this->countModules('position-5')
        || $this->countModules('position-3') || $this->countModules('position-6') || $this->countModules('position-8'))
        : ?>
        <div class="span-7 last">
            <jdoc:include type="modules" name="atomic-sidebar" style="sidebar" />
            <jdoc:include type="modules" name="position-7" style="sidebar" />
            <jdoc:include type="modules" name="position-4" style="sidebar" />
            <jdoc:include type="modules" name="position-5" style="sidebar" />
            <jdoc:include type="modules" name="position-6" style="sidebar" />
            <jdoc:include type="modules" name="position-8" style="sidebar" />
            <jdoc:include type="modules" name="position-3" style="sidebar" />
        </div>

    <?php endif; ?>

    <!--
    <div class="joomla-footer span-16 append-1">
        <hr />
        &copy;<?php echo date('Y'); ?> <?php echo htmlspecialchars($app->getCfg('sitename')); ?>
			</div>
			-->
</div>
<jdoc:include type="modules" name="debug" />


<script type="text/javascript">
    var _gaq = _gaq || [];
    var pluginUrl =
        '//www.google-analytics.com/plugins/ga/inpage_linkid.js';
    _gaq.push(['_require', 'inpage_linkid', pluginUrl]);
    _gaq.push(['_setAccount', 'UA-2949348-11']);
    _gaq.push(['_setDomainName', 'swiftkanban.com']);
    _gaq.push(['_trackPageview']);

    (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();


</script>

<script type="text/javascript" src="https://dv140.infusionsoft.com/app/webTracking/getTrackingCode?trackingId=d25b4e00bc03e9f13c524618683d62d5"></script>

</body>
</html>
