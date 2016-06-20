App.Models.App = Backbone.DeepModel.extend({
	url: '/public/images.php',
	defaults: {
		pagination: {
			items: 0,
			pages: 1,
			currentPage: 1,
			pagesLimit: 10,
			pageSize: 12,
			storages: {}
		}
	},
	initialize: function () {
		
	},
});