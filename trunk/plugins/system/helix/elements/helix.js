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
	var tabs = [];
	var options = [];
	var opt_iterator = -1;
	var div_gen,div;
	var cur_version ='1.8.0';
	var update_url = 'http://helix.joomshaper.com/updates/help-update-j16.raw';
	var base_table = $$('.fltlft')[0];
	
	//Info area
	var helix_details = new Element('div',{"class":"helix-details clearfix"});
	helix_details.injectInside(base_table.getParent());

	//Tab button area
	var helix_title_area = new Element('div',{"class":"helix-title-area"});
	helix_title_area.injectInside(base_table.getParent());
	var helix_tabs = new Element('ul',{"class":"helix-tabs"});
	helix_tabs.injectInside(helix_title_area);
	
	//Tab item area
	var helix_panel = new Element('div',{"class":"helix-panel"});
	helix_panel.injectInside(base_table.getParent());
	var helix_inner = new Element ('div',{"class":"helix-inner"});
	helix_inner.innerHTML='<div class="helix-params-area clearfix"></div>';
	helix_inner.injectInside(helix_panel);


	document.getElements('.panel h3.title').each(function(el){
		div_gen = new Element('li',{"class":"tabs-title","id":'sp-'+ el.get('text').replace(/\s+/g,"-").toLowerCase()});//Set title as id in lower case			
		div_gen.innerHTML = '<span class="tab-l"><span class="tab-r"><span class="tab-text">'+el.get('text')+'</span></span></span>';			
		div_gen.injectInside(helix_tabs);
	})

	document.getElements('.panel .content').each(function(el){
		div = new Element('div',{"class":"tabs-item"});
		div.innerHTML = el.innerHTML;			
		div.injectInside(document.getElement('.helix-params-area'));
	})
	
	//Menu Assignment Tab
	var assign_tab = new Element('li',{"class":"tabs-title","id":"sp-menu-assign"});
	assign_tab.innerHTML = '<span class="tab-l"><span class="tab-r"><span class="tab-text">Menu Assignment</span></span></span>';
	assign_tab.injectBefore($$('li.tabs-title').getLast());

	var assign_item = new Element('div',{"class":"tabs-item"});
	$$('.adminform legend')[1].dispose();//remove legend
	assign_item.innerHTML = $$('.adminform')[1].innerHTML;
	assign_item.injectBefore($$('.tabs-item').getLast());

	//Help and Update Tab
	var update_tab = new Element('li',{"class":"tabs-title","id":"sp-help-update"});
	update_tab.innerHTML = '<span class="tab-l"><span class="tab-r"><span class="tab-text">Help &amp; Update</span></span></span>';
	update_tab.injectAfter($$('li.tabs-title').getLast());
	
	var update_item = new Element('div',{"class":"tabs-item"});
	update_item.innerHTML = '<div id="sp_update_div"><fieldset class="panelform"><ul class="adminformlist"><li><label>Updates : </label><fieldset style="text-align:center"><span id="sp_spinner">&nbsp;</span>Loading update data...</fieldset></li></ul></fieldset></div>';
	update_item.injectAfter($$('.tabs-item').getLast());

	document.id('sp-help-update').addEvent("click", function(){//update function
		new Asset.javascript(update_url,{
	   		onload: function(){
				if (cur_version<helix_version) {
					document.id('sp_update_div').empty().innerHTML = '<fieldset class="panelform"><ul class="adminformlist"><li><label>Updates : </label><fieldset><p>Helix version ' + helix_version + ' is available. <a target="_blank" href="' + helix_link + '">Click here</a> to download.</p></fieldset></li><li><label>Live Help : </label><fieldset><p>' + help_text + '</p></fieldset></li></ul></fieldset>';
				} else {
					document.id('sp_update_div').empty().innerHTML = '<fieldset class="panelform"><ul class="adminformlist"><li><label>Updates : </label><fieldset><p>You are using latest version of Helix Framework.</p></fieldset></li><li><label>Live Help : </label><fieldset><p>' + help_text + '</p></fieldset></li></ul></fieldset>';				
				}	
	   		}
		});		
	});	
	
	document.getElement('.pane-sliders').getParent().dispose();//remove slider-pan
	
	//Template Description area
	var desc = new Element('div',{"class":"helix-desc"});
	desc.innerHTML = document.getElement('.sp-template-desc').innerHTML;
	desc.injectInside(helix_inner);
	
	var clear = new Element('div',{"class":"clr"});
	clear.injectAfter(document.getElement('.helix-desc'));	
	
	//remove all parent tables
	var admin_details=document.getElement('.adminformlist');
	admin_details.getParent().getParent().removeClass('width-60 fltlft').addClass('helix-details');
	
	document.getElement('.sp-template-desc').dispose();
	$$('.adminform')[1].getParent().dispose();
});


var HelixTab = new Class({//Based on jTabs
	getOptions: function(){
		return {

			display: 0,
			
			onActive: function(title, description){
				description.fade('in');
				description.setStyle('display', 'block');
				title.addClass('open').removeClass('closed');
			},

			onBackground: function(title, description){
				description.fade('out');
				description.setStyle('display', 'none');
				title.addClass('closed').removeClass('open');
			}

		};
	},

	initialize: function(options){
		this.setOptions(this.getOptions(), options);
		this.titles = document.getElements('ul.helix-tabs li.tabs-title');//
		this.descriptions = document.getElements('.helix-panel .tabs-item');//
		
		for (var i = 0, l = this.titles.length; i < l; i++){
			var title = this.titles[i];
			var description = this.descriptions[i];
			title.setStyle('cursor', 'pointer');
			title.addEvent('click', this.display.bind(this, i));
		}

		if ($chk(this.options.display)) this.display(this.options.display);

		if (this.options.initialize) this.options.initialize.call(this);
	},

	hideAllBut: function(but){
		for (var i = 0, l = this.titles.length; i < l; i++){
			if (i != but) this.fireEvent('onBackground', [this.titles[i], this.descriptions[i]])
		}
	},

	display: function(i){
		this.hideAllBut(i);
		this.fireEvent('onActive', [this.titles[i], this.descriptions[i]])
	}
});

HelixTab.implement(new Events);
HelixTab.implement(new Options);

window.addEvent("domready",function(){ 
	new HelixTab(); 
});