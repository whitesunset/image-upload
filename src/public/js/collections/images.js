App.Models.Image = Backbone.Model.extend({
	url: '/public/image.php',
	defaults: {
		id: null,
		storage: '',
		url: '',
		name: ''
	},
	destroy: function (options) {
		this.trigger('destroy', this, this.collection, options);
	}
});

App.Collections.Images = Backbone.PageableCollection.extend({
	url: '/public/images.php',
	model: App.Models.Image,
	prefetched: {},
	state: {
		firstPage: 1,
		currentPage: 1,
		pageSize: 12
	},
	queryParams: {
		currentPage: "current_page",
		pageSize: "page_size"
	},
	update: function (col, resp, opts) {
		App.collections.images.reset(resp.images, opts);
		App.models.app.set('pagination', resp.pagination);

		App.views.images.animationEnd();
	}
});