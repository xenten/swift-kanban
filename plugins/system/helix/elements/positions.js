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
	var posID=document.id('jform_position');
	var sp_pos = document.id('sp_pos');
	var sp_tmpl=document.id('sp_tmpl');
	var elmns = document.getElements ('optgroup.sp_optgroup');
	
	//Inject Template and Position dropdown list
	sp_pos.injectAfter(posID.getParent()).style.display=null;
	sp_tmpl.injectAfter(posID.getParent()).style.display=null;	
	
	document.getElement ('optgroup#' + sp_tmpl.value).style.display=null;
	
	//Generate module position by changing template
	sp_tmpl.addEvents({
		change: function(){
			genPos();
		},
		keyup: function(){
			genPos();
		}
	});
	
	//Change module position
	sp_pos.addEvents({
		change: function(){
			posID.value=sp_pos.value;
		},
		keyup: function(){
			posID.value=sp_pos.value;
		}
	});
	
	//Generate Positions
	function genPos() {
		for (i=0; i<elmns.length;i++) {
			elmns[i].style.display="none";
		}
		document.getElement ('optgroup#' + sp_tmpl.value).style.display=null;
		var elmn = document.getElements ('#' + sp_tmpl.value + ' option');
		sp_pos.value = elmn[0].value;	
	}
	
});