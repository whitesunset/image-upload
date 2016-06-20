App.Views.Message = Backbone.View.extend({
	template: _.template($('#tpl-message').html()),
	initialize: function () {
		this.render();
	},
	render: function () {
		this.$el.html(this.template(this.model.toJSON()));
	},
});