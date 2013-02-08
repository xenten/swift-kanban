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
	var spToppanel = {
		initialize: function () {	
			this.open = false;
			this.wrapper =document.getElement('.sp-toppanel-wrap').setStyle('display', 'block');			
			this.container =document.id('sp-toppanel');
			this.box = this.container.inject(new Element('div', {'id': 'toppanel_container'}).inject(this.container.getParent()));
			this.handle = document.id('toppanel-handler');
			this.box = new Fx.Slide(this.box,{transition: Fx.Transitions.Expo.easeOut});
			this.box.hide();			
			this.handle.addEvent('click', this.toggle.bind(this));
		},

		show: function () {
			this.box.slideIn();
			this.open = true;
		},

		hide: function () {
			this.box.slideOut();
			this.open = false;
		},

		toggle: function () {
			if (this.open) {
				this.hide();
			} else {
				this.show();
			}
		}
	};
spToppanel.initialize();
})