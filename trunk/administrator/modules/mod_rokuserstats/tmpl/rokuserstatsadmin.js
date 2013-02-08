/**
 * @version  2.6 April 10, 2012
 * @author  RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license  http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */


((function(){

	var MCUserStats = new Class({
		initialize: function(){
			this.elements = document.getElements('#cleanData .mc-button');
			this.attach();
		},

		attach: function(element){
			elements = element ? new Elements(element) : this.elements;

			elements.each(function(element){
				var click = element.retrieve('mcuserstats:click', function(event){
						if (event) event.stop();
						this.click.call(this, event, element);
					}.bind(this));

				element.addEvent('click', click);
			}, this);
		},

		detach: function(element){
			elements = element ? new Elements(element) : this.elements;

			elements.each(function(element){
				var click = element.retrieve('mcuserstats:click');

				element.removeEvent('click', click);
			}, this);
		},

		click: function(event, element){
			if (event) event.stop();

			var ajax = element.retrieve('mcuserstats:ajax', new Request({
				url: element.getElement('a').get('href'),
				onRequest: function(){
					element.addClass('loading');
				},
				onSuccess: function(response){
					if (response == '1') element.removeClass('loading');
					else throw new Error('Ajax went through correctly by didnt clear the DB. Called with URL: "'+this.options.url+'"');
				},
				onFailure: function(xhr){ throw new Error('Ajax failed with the url: "'+this.options.url+'"'); }
			}));

			ajax.cancel().send();
		},

		onRequest: function(element){
			console.log(element, this);
		}
	});

	var init = function(){
		new MCUserStats();
	};

	window.addEvent('domready', init);
})());
