/*---------------------------------------------------------------
# Package - Helix Framework  
# ---------------------------------------------------------------
# Author - JoomShaper http://www.joomshaper.com
# Copyright (C) 2010 - 2011 JoomShaper.com. All Rights Reserved.
# license - PHP files are licensed under  GNU/GPL V2
# license - CSS  - JS - IMAGE files  are Copyrighted material 
# Websites: http://www.joomshaper.com - http://www.joomxpert.com
-----------------------------------------------------------------*/
window.addEvent('domready', function () {
    var boxes = [];
    showBox = function (box, caller) {
        box = $(box);
        if (!box) return;
		if ($(caller)) box._caller = $(caller);
        boxes.include(box);
        if (box.getStyle('display') == 'none') {
            box.setStyles({
                display: 'block',
                opacity: 0
            })
        }
        if (box.status == 'show') {
            box.status = 'hide';
            box.set('tween', {
                duration: 400
            }).fade('out')
			if (box._caller) box._caller.removeClass ('show');
        } else {
            boxes.each(function (box1) {
                if (box1 != box && box1.status == 'show') {
                    box1.status = 'hide';
                    box1.set('tween', {
                        duration: 400
                    }).fade('out')
                }
            }, this);
            box.status = 'show';
            box.set('tween', {
                duration: 400
            }).fade('in')
			if (box._caller) box._caller.addClass ('show');
        }
    }
});
