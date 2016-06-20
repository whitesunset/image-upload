App.Views.Pagination = Backbone.View.extend({
	template: _.template($('#tpl-pagination').html()),
	initialize: function () {
		this.listenTo(this.model, 'change', this.render);
	},
	events: {
		'click a': 'handlePage'
	},
	handlePage: function (e) {
		var $li = $(e.target).parent();
		if ($li.hasClass('disabled')) return;

		var pageID = +$(e.target).data('page');
		if (pageID === App.models.app.get('pagination').currentPage) return;

		App.routers.index.navigate('page/' + pageID, {trigger: true});
	},
	render: function () {
		var model = this.model.toJSON().pagination;
		var pages = _.range(1, model.pages + 1);
		if (model.pages === 1) return;
		if (model.currentPage > model.pages) return;

		model.pagesCount = model.pages;

		if (model.pages > model.pagesLimit) {
			pages = _.range(1, model.pages + 1);
			if (model.currentPage <= model.pagesLimit / 2) {
				pages = pages.slice(0, model.pagesLimit - 2);
			}
			else if (model.pages - model.currentPage <= model.pagesLimit / 2) {
				pages = pages.slice(-model.pagesLimit + 2);
			}
			else {

				pages = pages.slice(model.currentPage - model.pagesLimit / 2, model.pagesLimit - 2);
			}

		}


		model.pages = pages;

		this.$el.html(this.template(model));
		this.delegateEvents();
	},
});