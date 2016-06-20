App.Views.Info = Backbone.View.extend({
	template: _.template($('#tpl-panel').html()),
	initialize: function () {
		this.listenTo(this.model, 'change', this.render);
	},
	render: function () {
		this.$el.html(this.template(this.model.attributes));
		this.$el.show();
	},
});