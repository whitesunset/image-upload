App.Routers.Index = Backbone.Router.extend({
	routes: {
		'': 'index',
		'#': 'index',
		'page/:id': 'page',

		'*notFound': 'notFound'
	},
	initialize: function () {
		App.views.index = new App.Views.Index({
			model: App.models.app
		});
	},
	page: function (pageID) {
		App.views.images.renderPage(+pageID);
		App.models.app.set('pagination.currentPage', +pageID);
	},
	index: function () {
		App.views.images.renderPage(1);
	},
	notFound: function(){
		App.views.index.render404();
	}
});