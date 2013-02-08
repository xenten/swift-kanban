/*---------------------------------------------------------------
# Package - Helix Framework  
# ---------------------------------------------------------------
# Author - JoomShaper http://www.joomshaper.com
# Copyright (C) 2010 - 2011 JoomShaper.com. All Rights Reserved.
# license - PHP files are licensed under  GNU/GPL V2
# license - CSS  - JS - IMAGE files  are Copyrighted material 
# Websites: http://www.joomshaper.com - http://www.joomxpert.com
-----------------------------------------------------------------*/
var Equalizer = new Class({
	initialize: function(elements) {
		this.elements = $$(elements);
	},
	equalize: function(hw) {
		if(!hw) { hw = 'height'; }
		var max = 0,
			prop = (typeof document.body.style.maxHeight != 'undefined' ? 'min-' : '') + hw; //ie6 ftl
			offset = 'offset' + hw.capitalize();
		this.elements.each(function(element,i) {
			var calc = element[offset];
			if(calc > max) { max = calc; }
		},this);
		this.elements.each(function(element,i) {
			element.setStyle(prop,max - (element[offset] - element.getStyle(hw).toInt()));
		});
		return max;
	}
});