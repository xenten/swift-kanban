/*---------------------------------------------------------------
# Package - Helix Framework  
# ---------------------------------------------------------------
# Author - JoomShaper http://www.joomshaper.com
# Copyright (C) 2010 - 2011 JoomShaper.com. All Rights Reserved.
# license - PHP files are licensed under  GNU/GPL V2
# license - CSS  - JS - IMAGE files  are Copyrighted material 
# Websites: http://www.joomshaper.com - http://www.joomxpert.com
-----------------------------------------------------------------*/
window.addEvent("domready",function(){

	[$('jform_params_mods'), $('jform_params_position'), $('jform_params_showgrouptitle')].each(function(el,j){
		el.getParent().setStyle('display','none');
	});
	
	if ($('jform_params_subcontent1').checked) {
		$('jform_params_mods').getParent().setStyle('display','');	
	}	else if ($('jform_params_subcontent2').checked) {
		$('jform_params_position').getParent().setStyle('display','');	
	} 
	
	$('jform_params_subcontent1').addEvent("click", function(){
		[$('jform_params_mods'), $('jform_params_position')].each(function(el){
			el.getParent().setStyle('display','none');
		});
	
		$('jform_params_mods').getParent().setStyle('display','');		
	});

	$('jform_params_subcontent2').addEvent("click", function(){
		[$('jform_params_mods'), $('jform_params_position')].each(function(el,j){
			el.getParent().setStyle('display','none');
		});
		$('jform_params_position').getParent().setStyle('display','');		
	});

	$('jform_params_subcontent0').addEvent("click", function(){
		[$('jform_params_mods'), $('jform_params_position')].each(function(el,j){
			el.getParent().setStyle('display','none');
		});
	});

	//Group Title
	if ($('jform_params_group1').checked) {
		$('jform_params_showgrouptitle').getParent().setStyle('display','');
	} else {
		$('jform_params_showgrouptitle').getParent().setStyle('display','none');
	}
	
	$('jform_params_group0').addEvent("click", function(){
		$('jform_params_showgrouptitle').getParent().setStyle('display','none');
	});	

	$('jform_params_group1').addEvent("click", function(){
		$('jform_params_showgrouptitle').getParent().setStyle('display','');
	});	
	
});