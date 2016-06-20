App.Views.Index = Backbone.View.extend({
	template: _.template($('#tpl-index').html()),
	el: $('#container'),
	initialize: function () {
		this.render();
		this.listenTo(App.models.upload, 'change', this.renderUpload())
		this.listenTo(App.collections.images, 'add remove', this.renderList())
	},
	events: {
		'click [data-action="toggle-upload"]': 'handleUploadVisibility'
	},
	render: function () {
		this.$el.html(this.template(this.model.toJSON()));
		App.views.images = new App.Views.Images();
	},
	renderList: function () {
		App.views.images.render();
		$.material.init();
	},
	renderUpload: function () {
		new App.Views.Upload({
			el: this.$el.find('#upload'),
			model: App.models.upload
		});
	},
	render404: function () {
		this.$el.find('.error-404').show();
	},
	handleUploadVisibility: function () {
		this.$el.find('.upload').toggle();
	},
	
});