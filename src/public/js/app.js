/**
 * App object
 * Uppercase — constructors
 * Lowercase — methods & instances
 *
 * @type {{Collections: {}, collections: {}, Models: {}, models: {}, Views: {}, Routers: {}, init: Window.App.init}}
 */

window.App = {
	Collections: {},
	Models: {},
	Views: {},
	Routers: {},

	collections: {},
	models: {},
	views: {},
	routers: {},

	init: function () {
		App.models.upload = new App.Models.Upload();
		App.models.image = new App.Models.Image();
		App.models.app = new App.Models.App();
		
		App.collections.images = new App.Collections.Images();

		App.routers.index = new App.Routers.Index();
		Backbone.history.start();
	}
};

(function ($) {
	$(function () {
		App.init();
	});
})(jQuery);