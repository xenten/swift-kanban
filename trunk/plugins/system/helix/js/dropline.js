/*---------------------------------------------------------------
# Package - Joomla Template based on Helix Framework   
# ---------------------------------------------------------------
# Author - JoomShaper http://www.joomshaper.com
# Copyright (C) 2010 - 2011 JoomShaper.com. All Rights Reserved.
# license - PHP files are licensed under  GNU/GPL V2
# license - CSS  - JS - IMAGE files  are Copyrighted material 
# Websites: http://www.joomshaper.com - http://www.joomxpert.com
-----------------------------------------------------------------*/

window.addEvent('domready',function() {
	var mainlist=$$('#hornav li');
	var sublist=$$('#sublevel ul.level-1');
	if (!sublist) return;
	var timedelay;
	var sublevelDiv= document.id('sublevel') ;
	
	mainlist.each(function(el,i) {
		el.addEvent('mouseenter', function() {
			showDropLine();
			sublist[i].style.display='block';
		});

		el.addEvent('mouseleave', function() {
			showActive();
		});
		
		hideAll();
		show_active();
	});
	
	sublevelDiv.addEvent('mouseenter',function() {
		if (timedelay) clearTimeout(timedelay);
	});

	sublevelDiv.addEvent('mouseleave',function() {
		showActive();
	});
	
	function hideAll() {
		sublist.each(function(el) {
			el.style.display='none';
		});
	}
	
	function show_active() {
		mainlist.each(function(el,i) {
			if (el.hasClass('active')) {
				sublist[i].style.display='block';
			}
		});
	}
	
	function showActive() {
		if (timedelay) clearTimeout(timedelay);
		timedelay = setTimeout(function() {
			hideAll();
			show_active();
		},500);
	}
		
	function showDropLine() {
		if (timedelay) clearTimeout(timedelay);
		hideAll();
	}
});