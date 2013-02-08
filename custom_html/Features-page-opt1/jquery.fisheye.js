/*************************************************************
* jQuery FishEye plugin v0.5.0
* Copywright (c) 2008 Ian Suttle
* http://www.iansuttle.com/blog
*
* Licensed under the GNU General Public License: 
* http://www.gnu.org/licenses/gpl.html
**************************************************************/
;(function($) {
	jQuery.fn.FishEye = function(args) {
		var container = this;
		var fishEyeItemName = args.fishEyeItemName;
		var maxScalePct = args.maxScalePct;
		var scaleStepPct = args.scaleStepPct;
		var currentlyOn;

		function loadFishEye() {
			$(container).mouseout(function() {
				//currentlyOn = null;
				//setTimeout(function() { resetContainer(this); }, 2000);
			});

			var x = 0;
			$(fishEyeItemName, container).each(function(i) {
				$(this).mouseover(function() {
					expandMe(this, maxScalePct);
				});

				$(this).mouseout(function() {
					currentlyOn = null;
					setTimeout(function() { resetContainer(this); }, 2000);
				});

				$(this).attr("index", x);
				$(this).attr("origHeight", $(this).height());
				$(this).attr("origWidth", $(this).width());
				x++;
			});
		};

		function expandMe(me, growthPct) {
			if ($(me).attr("index") == currentlyOn)
				return;

			resetContainer();

			//mouse over this one
			expandByStep(me, maxScalePct);
			currentlyOn = $(me).attr("index");

			//change siblings to the next
			var next = $(me).next();
			var nextStep = growthPct - scaleStepPct;
			while (true) {
				if (next == 'undefined' || nextStep <= 0)
					break;

				expandByStep(next, nextStep); //update element
				next = $(next).next(); //retrieve next element to update
				nextStep -= scaleStepPct; //set up next growth pct
			};

			//change siblings to the prev
			next = $(me).prev();
			nextStep = growthPct - scaleStepPct;
			while (true) {
				if (next == null || nextStep <= 0)
					break;

				expandByStep(next, nextStep); //update element
				next = $(next).prev(); //retrieve next element to update
				nextStep -= scaleStepPct; //set up next growth pct
			};
		};

		function expandByStep(me, growthPct) {
			var newHeight = ($(me).attr("origHeight") * (growthPct / 100)) + $(me).height();
			var newWidth = ($(me).attr("origWidth") * (growthPct / 100)) + $(me).width();

			$(me).height(newHeight + 'px');
			$(me).width(newWidth + 'px');
		};

		function resetContainer() {
			if (currentlyOn != null)
				return;

			$(fishEyeItemName, container).each(function(i) {
				resetMe($(this));
			});
		};

		function resetMe(me) {
			$(me).height($(me).attr("origHeight") + 'px');
			$(me).width($(me).attr("origWidth") + 'px');
		};

		//call the constructor
		loadFishEye();
		//return jQuery object
		return container;
	}
})(jQuery);