App.Views.Image = Backbone.View.extend({
	url: '/public/image.php',
	template: _.template($('#tpl-image').html()),
	events: {
		'click [data-action="delete"]': 'handleDelete',
		'click [data-action="get-link"]': 'handleGetLink',
		'click [data-action="get-code"]': 'handleGetCode'
	},
	initialize: function () {
		this.render();
	},
	animationStart: function(){
		this.$el.find('.panel-body').addClass('anim-active');
	},
	animationEnd: function(){
		this.$el.find('.panel-body').removeClass('anim-active');
	},
	handleGetLink: function(e){
		var _this = this;
		new Clipboard('.btn', {
			text: function(trigger) {
				return _this.model.get('url');
			}
		});
	},
	handleGetCode: function(e){
		var _this = this;
		new Clipboard('.btn', {
			text: function(trigger) {
				return '<img src="' + _this.model.get('url')+ '">';
			}
		});
	},
	handleDelete: function () {
		var _this = this;
		var data = this.model.toJSON();
		data.method = 'DELETE';

		this.animationStart();
		
		$.ajax({
			url: this.url,
			type: 'POST',
			data: data,
			success: function(){
				_this.model.destroy();
				App.views.images.$el.append('<li class="anim-active">');
				App.collections.images.fetch({
					success: function (col, resp, opts) {
						App.collections.images.reset(resp.images, opts);
						App.models.app.set('pagination', resp.pagination);
					}
				});
			}
		});
	},
	render: function () {
		this.$el.html(this.template(this.model.toJSON()));
	},
});