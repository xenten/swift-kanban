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

require_once(dirname(__FILE__).DS.'lib'.DS.'helix.php');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language;?>" >

<head>
    <script type="text/javascript">
        var DM_redirect = function (MobileURL, Home){
            try {
                // avoid loops within mobile site
                if(document.getElementById("dmRoot") != null)
                {
                    return;
                }
                var CurrentUrl = location.href;
                var noredirect = document.location.search;
                if (noredirect.indexOf("no_redirect=true") < 0){
                    if ((navigator.userAgent.match(/(iPhone|iPod|BlackBerry|Android.*Mobile|webOS|Windows CE|IEMobile|Opera Mini|Opera Mobi|HTC|LG-|LGE|SAMSUNG|Samsung|SEC-SGH|Symbian|Nokia|PlayStation|PLAYSTATION|Nintendo DSi)/i)) ) {

                        if(Home){
                            location.replace(MobileURL);
                        }
                        else
                        {
                            location.replace(MobileURL + "?url=" + encodeURIComponent(CurrentUrl));
                        }
                    }
                }
            }
            catch(err){}
        }
        DM_redirect("http://m.swift-kanban.com");
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js" type="text/javascript"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/jquery-ui.min.js" type="text/javascript"></script>
    <script src="/templates/shaper_simplicity_ii/js/cookie.js" type="text/javascript"></script>


    <script src="/templates/shaper_simplicity_ii/js/custom.js" type="text/javascript"></script>

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
    JLoader::register('fieldattach', 'components/com_fieldsattach/helpers/fieldattach.php');
    $socialimagelink_js = fieldattach::getValue( $article_id, 2, false) ;
    /*$socialimagelink = urlencode($socialimagelink_js) ;
    $socialdesc = urlencode(fieldattach::getValue( $this->item->id, 1, false)) ;
    $socialtwittertag = (fieldattach::getValue( $this->item->id, 3, false)) ;*/
    ?>
    <meta property="og:image" content="<?php echo $socialimagelink_js?>" id="social_image" />

</head>

<?php $helix->addFeature('ie6warn'); ?>

<body class="bg clearfix new-header" id='sk_body' >

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

	

	<jdoc:include type="modules" name="debug" />
    <!-- BEGIN ProvideSupport.com Graphics Chat Button Code
<div id="ciw7Q5" style="z-index:100;position:absolute"></div><div id="scw7Q5" style="display:inline"></div><div id="sdw7Q5" style="display:none"></div><script type="text/javascript">var sew7Q5=document.createElement("script");sew7Q5.type="text/javascript";var sew7Q5s=(location.protocol.indexOf("https")==0?"https":"http")+"://image.providesupport.com/js/1bxclixl1hml91srtbhpedbeeb/safe-standard.js?ps_h=w7Q5&ps_t="+new Date().getTime();setTimeout("sew7Q5.src=sew7Q5s;document.getElementById('sdw7Q5').appendChild(sew7Q5)",1)</script><noscript><div style="display:inline"><a href="http://www.providesupport.com?messenger=1bxclixl1hml91srtbhpedbeeb">Online Chat</a></div></noscript>
END ProvideSupport.com Graphics Chat Button Code -->
    <script type="text/javascript">
        var li_items = $('li');
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
        _gaq.push(['_setDomainName', 'swift-kanban.com']);
        _gaq.push(['_trackPageview']);

        (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();
    </script>



    <!-- Google Code for Remarketing tag -->
    <!-- Remarketing tags may not be associated with personally identifiable information or placed on pages related to sensitive categories. For instructions on adding this tag and more information on the above requirements, read the setup guide: google.com/ads/remarketingsetup -->
    <script type="text/javascript">
        /* <![CDATA[ */
        var google_conversion_id = 974084088;
        var google_conversion_label = "kPSfCLjEuSAQ-K-90AM";
        var google_custom_params = window.google_tag_params;
        var google_remarketing_only = true;
        /* ]]> */
    </script>
    <script type="text/javascript" src="https://www.googleadservices.com/pagead/conversion.js">
    </script>
    <noscript>
        <div style="display:inline;">
            <img height="1" width="1" style="border-style:none;" alt="" src="https://googleads.g.doubleclick.net/pagead/viewthroughconversion/974084088/?value=0&amp;label=kPSfCLjEuSAQ-K-90AM&amp;guid=ON&amp;script=0"/>
        </div>
    </noscript>
    <div id="survey" title="Thank you very much for visiting Swift-Kanban!" style="display: none;">
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
            <div id="survey-get-free-text">Try Swift-Kanban at zero-cost for 30 days</div>
        </div>
    </div>
    <a href="https://www.leadformix.com" title="Marketing Automation" onclick="window.open(this.href);return(false);">
        <script type="text/javascript">
            var pkBaseURL = (("https:" == document.location.protocol) ? "https://vlog.leadformix.com/" : "https://vlog.leadformix.com/");
            <!--
            bf_action_name = '';
            bf_idsite = 8062;
            bf_url = pkBaseURL+'bf/bf.php';
            (function() {
                var lfh = document.createElement('script'); lfh.type = 'text/javascript'; lfh.async = true;
                lfh.src = pkBaseURL+'bf/lfx.js';
                var s = document.getElementsByTagName('head')[0]; s.appendChild(lfh);
            })();
            //-->
        </script>
        <noscript><p>Marketing Automation Platform <img src="https://vlog.leadformix.com/bf/bf.php" style="border:0" alt="Marketing Automation Tool"/></p>
        </noscript>
    </a>
</body>

</html>