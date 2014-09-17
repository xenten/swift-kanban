<?php

/*---------------------------------------------------------------

# Package - Joomla Template based on Helix Framework   

# ---------------------------------------------------------------

# Template Name - Shaper Simplicity - II

# Template Version 1.0.0

# ---------------------------------------------------------------

# Author - JoomShaper http://www.joomshaper.com

# Copyright (C) 2010 - 2011 JoomShaper.com. All Rights Reserved.

# license - PHP files are licensed under  GNU/GPL V2

# license - CSS  - JS - IMAGE files  are Copyrighted material 

# Websites: http://www.joomshaper.com - http://www.joomxpert.com

-----------------------------------------------------------------*/




//no direct accees

defined ('_JEXEC') or die ('resticted aceess');
if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

require_once(dirname(__FILE__).DS.'lib'.DS.'helix.php');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language;?>" >

<head>


    <meta name="google-site-verification" content="PlGXADnejeFl_IriIvP90JZPX-jjoxrxQ5YBwR8QHj8" />
    <?php
    $app = JFactory::getApplication();
    $this->setTitle( $this->getTitle() . ' | ' . $app->getCfg( 'sitename' ) );

    $helix->loadHead();

    $helix->addCSS('template.css,joomla.css,custom.css,modules.css,typography.css,css3.css,chr');

    if ($helix->getDirection() == 'rtl') $helix->addCSS('template_rtl.css');

    $helix->getStyle();

    $helix->favicon('favicon.ico');



    ?>
    <link rel="stylesheet" href="/templates/shaper_simplicity_ii/css/chrome.css" type="text/chrome/safari" />

    <?php
    $option = JRequest::getCmd('option');
    $view = JRequest::getCmd('view');
    $article_id = 0;
    if ($option=="com_content" && $view=="article") {
        $ids = explode(':',JRequest::getString('id'));
        $article_id = $ids[0];
    }

    ?>
    <meta property="og:image" content="<?php echo $socialimagelink_js?>" id="social_image" />
    <!-- start Mixpanel -->
    <script type="text/javascript">(function(f,b){if(!b.__SV){var a,e,i,g;window.mixpanel=b;b._i=[];b.init=function(a,e,d){function f(b,h){var a=h.split(".");2==a.length&&(b=b[a[0]],h=a[1]);b[h]=function(){b.push([h].concat(Array.prototype.slice.call(arguments,0)))}}var c=b;"undefined"!==typeof d?c=b[d]=[]:d="mixpanel";c.people=c.people||[];c.toString=function(b){var a="mixpanel";"mixpanel"!==d&&(a+="."+d);b||(a+=" (stub)");return a};c.people.toString=function(){return c.toString(1)+".people (stub)"};i="disable track track_pageview track_links track_forms register register_once alias unregister identify name_tag set_config people.set people.set_once people.increment people.append people.track_charge people.clear_charges people.delete_user".split(" ");
            for(g=0;g<i.length;g++)f(c,i[g]);b._i.push([a,e,d])};b.__SV=1.2;a=f.createElement("script");a.type="text/javascript";a.async=!0;a.src="//cdn.mxpnl.com/libs/mixpanel-2.2.min.js";e=f.getElementsByTagName("script")[0];e.parentNode.insertBefore(a,e)}})(document,window.mixpanel||[]);
        mixpanel.init("d28f41404fb553e418a803139fc2d468");
    </script>
    <!-- end Mixpanel -->
    <!-- start BoostSuite -->
    <script type="text/javascript">
        var _bsc = _bsc || {};
        (function() {
            var bs = document.createElement('script');
            bs.type = 'text/javascript';
            bs.async = true;
            bs.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://d2so4705rl485y.cloudfront.net/widgets/tracker/tracker.js';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(bs, s);
        })();
    </script>
    <!-- end BoostSuite -->
</head>

<?php $helix->addFeature('ie6warn'); ?>

<body class="bg clearfix new-header" id='sk_body'>

<a href="https://plus.google.com/116495124719251971526" rel="publisher"></a>
<!-- Checking if page is home page (for mixpanel tracking)
?php
$app = JFactory::getApplication();
$menu = $app->getMenu();
if ($menu->getActive() == $menu->getDefault()) {
    <script>mixpanel.track("Home");</script>
}
?>
Ending mixpanl 'home' tracking -->

<div class="bg1">

    <div class="sp-wrap main-bg clearfix">

        <?php $helix->addFeature('toppanel'); ?>

        <div id="header" class="clearfix">

            <?php $helix->addFeature('logo') /*--- Add logo ---*/?>

            <?php $helix->addFeature('fontsizer') /*--- Font resizer ---*/?>

            <?php if ($helix->countModules('search')) { /*--- Search Module ---*/?>

                <div id="search">

                    <jdoc:include type="modules" name="search" />

                </div>

            <?php } ?>

        </div>

        <?php $helix->addFeature('hornav') /*--Main Navigation--*/?>

        <div class="bg-box"></div>
        <div class="header-holder"></div>

        <!--Module Position slides-->

        <?php if($helix->countModules('slides')) { /*--- Slideshow module ---*/?>

            <div id="slides" class="sp-inner clearfix">

                <jdoc:include type="modules" name="slides" />

            </div>

        <?php } ?>

        <div id='body_content'>

            <!--Module Position user1 to user4-->

            <?php if($mods= $helix->getModules('user1,user2,user3,user4,user5,user6')) { ?>

                <div id="sp-userpos" class="clearfix">

                    <div class="sp-inner">

                        <?php $helix->renderModules($mods,'sp_xhtml','eqHeight');?>

                    </div>

                </div>

                <?php $helix->fixHeight('eqHeight') /*Equal height for eqHeight class*/?>

            <?php } ?>



            <?php $helix->loadLayout(); /*--Load mainbody layout--*/?>



            <!--Module Position breadcrumbs-->

            <?php if($helix->countModules('breadcrumbs')) { ?>

                <div class="clr"></div>

                <div id="breadcrumbs" class="sp-inner clearfix">

                    <jdoc:include type="modules" name="breadcrumbs" />

                    <?php $helix->addFeature('totop') ?>

                </div>

            <?php } ?>



            <!--Module Position bottom1 to bottom6-->

            <?php if($mods= $helix->getModules('bottom1,bottom2,bottom3,bottom4,bottom5,bottom6')) { ?>

                <div id="sp-bottom" class="clearfix">

                    <div class="sp-inner">

                        <?php $helix->renderModules($mods,'sp_flat','equal');?>

                    </div>

                </div>

            <?php } ?>

        </div>

    </div>

</div>


<!--Footer-->

<div id="sp-footer" class="clearfix">

    <div class="sp-wrap">

        <div class="sp-inner">

            <?php $helix->addFeature('helixlogo'); /*--- Helix logo ---*/?>

            <div class="cp">

                <?php $helix->addFeature('copyright') /*--- Show copyright ---*/?>
                <div style="display: none;">
                    <?php $helix->addFeature('brand') /*--You are not allowed to remove or modify brand link. You need to purchase copyright removal license from http://www.joomshaper.com/copyright-removal-license.html in order to remove this link.--*/ ?>

                    <?php $helix->addFeature('validator') /*--- CSS and XHTML validator ---*/?>
                </div>
            </div>

            <?php if ($helix->countModules('footer-nav')) /*--- Module position footer-nav ---*/{ ?>

                <div id="footer-nav">

                    <jdoc:include type="modules" name="footer-nav" />

                </div>

            <?php } ?>

        </div>

    </div>

</div>

</div>



<?php $helix->addFeature('analytics'); /*--- Google analytics tracking code ---*/?>

<?php $helix->addFeature('jquery'); /*--- Load jQuery library ---*/?>

<?php $helix->addFeature('ieonly'); /*--- IE only Feature ---*/?>

<?php /*$helix->getFonts() /*--- Standard and Google Fonts ---*/?>

<?php $helix->compress(); /* --- Compress CSS and JS files --- */ ?>

<script src="/templates/shaper_simplicity_ii/js/jquery-ui.min.js" type="text/javascript"></script>


<script src="/templates/shaper_simplicity_ii/js/custom.js" type="text/javascript"></script>

<jdoc:include type="modules" name="debug" />
<!-- BEGIN ProvideSupport.com Graphics Chat Button Code
<div id="ciw7Q5" style="z-index:100;position:absolute"></div><div id="scw7Q5" style="display:inline"></div><div id="sdw7Q5" style="display:none"></div><script type="text/javascript">var sew7Q5=document.createElement("script");sew7Q5.type="text/javascript";var sew7Q5s=(location.protocol.indexOf("https")==0?"https":"http")+"://image.providesupport.com/js/1bxclixl1hml91srtbhpedbeeb/safe-standard.js?ps_h=w7Q5&ps_t="+new Date().getTime();setTimeout("sew7Q5.src=sew7Q5s;document.getElementById('sdw7Q5').appendChild(sew7Q5)",1)</script><noscript><div style="display:inline"><a href="http://www.providesupport.com?messenger=1bxclixl1hml91srtbhpedbeeb">Online Chat</a></div></noscript>
END ProvideSupport.com Graphics Chat Button Code -->
<script type="text/javascript">
    var li_items = document.getElementsByTagName('li');
    for(i = 0 ; i<li_items.length; i++) {

        if(li_items[i].innerHTML.toLowerCase().indexOf("free trial!") >= 0)
        {
            li_items[i].className = li_items[i].className + ' free-trial-menu';
            break;
        }
    }
</script>
<script type="text/javascript">
    var chats = document.getElementsByName("LiveChat");
    if(chats.length>0)
    {
        for(var i = 0 ; i < chats.length ; i++)
        {
            chats[i].href='javascript:psgiLtow();';
            chats[i].innerHTML='Live Chat';
        }
    }
</script>
<script type="text/javascript">
    social_titles();
</script>
<script type="text/javascript">

</script>

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


<div id="survey" title="Thank you very much for visiting SwiftKanban!" style="display: none;">
    <div id="survey-text">We hope you are enjoying your visit to our site. Would you please take a 1 minute survey for a chance to win a <span style="color:#fd631d;">$25 Amazon Gift Card</span> (or equivalent)?</div>
    <div class="survey-buttons">
        <center>
            <input type="button" value="Yes" name="yes" id='yes-btn' onclick="survey_click('yes')" />
            <input type="button" value="No" name="no-btn" id='no-btn' onclick="survey_click('no')"/>
        </center>
    </div>
</div>
<div id="survey-get-free" title="" style="display: none;">
    <div>
        <div id="survey-get-free-text">Try SwiftKanban at zero-cost for 30 days</div>
    </div>
</div>


<div style="position: fixed;width: 100%;height: 40px;background-color: transparent;z-index: 0;top:0;left: 0;" id='popup_head'>

</div>

</body>

</html>