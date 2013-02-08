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

    <script src="/templates/shaper_simplicity_ii/js/custom.js" type="text/javascript"></script>
    <script src="http://static.dudamobile.com/DM_redirect.js" type="text/javascript"></script>
    <script type="text/javascript">DM_redirect("http://m.swift-kanban.com");</script>

    <link href='http://fonts.googleapis.com/css?family=Arimo:400,700' rel='stylesheet' type='text/css'/>
    <link href='http://fonts.googleapis.com/css?family=Kameron:400,700' rel='stylesheet' type='text/css'/>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,800&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <?php
    $app = JFactory::getApplication();

    $helix->loadHead();

    $helix->addCSS('template.css,joomla.css,custom.css,modules.css,typography.css,css3.css,chr');

    if ($helix->getDirection() == 'rtl') $helix->addCSS('template_rtl.css');

    $helix->getStyle();

    $helix->favicon('favicon.ico');



    ?>
    <link rel="stylesheet" href="/templates/shaper_simplicity_ii/css/chrome.css" type="text/chrome/safari" />
</head>

<?php $helix->addFeature('ie6warn'); ?>

<body class="bg clearfix">

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



