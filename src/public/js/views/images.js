App.Views.Images = function () {
	this.$el = $('#images_list');

	this.infoView = new App.Views.Info({
		el: $('#images_info'),
		model: App.models.app
	});

	this.listView = new Backbone.CollectionView({
		el: this.$el,
		modelView: App.Views.Image,
		collection: App.collections.images
	});

	this.paginationView = new App.Views.Pagination({
		el: $('#images_pagination'),
		model: App.models.app
	});

	return this;
}

App.Views.Images.prototype.render = function () {
	this.infoView.render();
	this.listView.render();
	this.paginationView.render();
}

App.Views.Images.prototype.animationStart = function () {
	this.$el.parent().addClass('anim-active');
}

App.Views.Images.prototype.animationEnd = function () {
	this.$el.parent().removeClass('anim-active');
}

App.Views.Images.prototype.renderPage = function (id) {
	App.views.images.animationStart();
	App.collections.images.getPage(id).done(function (resp) {
		App.collections.images.update(null, resp, null);
	});
}