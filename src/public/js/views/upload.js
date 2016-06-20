App.Views.Upload = Backbone.View.extend({
	template: _.template($('#tpl-upload').html()),
	initialize: function () {
		this.messages = new App.Collections.Base();
		this.listenTo(this.messages, 'update', this.handleErrors);

		this.render();
	},
	bindings: {
		'[name="storage"]': 'storage',
		'[name="url"]': 'url',

		// swicth input visibility depends on source type
		'.source-file': {
			observe: 'source_type',
			visible: function (val, options) {
				return val === 'file';
			},
		},
		// swicth input visibility depends on source type
		'.source-url': {
			observe: 'source_type',
			visible: function (val, options) {
				return val === 'url';
			}
		}
	},
	events: {
		// handle source type switcher
		'click .btn-group span': 'handleGroup',
		'submit form': 'handleSubmit'
	},
	handleGroup: function (e) {
		e.preventDefault();

		var $el = $(e.target);
		var $container = $($el.parent());
		var field = $container.data('field');
		var val = $el.data('content');

		this.model.set(field, val);
		this.model.set('url', '');
		$container.find('span').removeClass('btn-raised active');
		$el.addClass('btn-raised active')
	},
	handleSubmit: function (e) {
		e.preventDefault();
		this.messages.reset();

		var _this = this;
		var model = this.model.toJSON();
		var data = new FormData(this);
		var files = this.$el.find('[name="file"]').prop('files');

		data.append('method', 'PUT');
		data.append('storage', model.storage);
		data.append('source_type', model.source_type);
		data.append('url', model.url);

		if (model.source_type === 'file' && _.isEmpty(files)) {
			var msg = new App.Models.Message({
				message: 'Please choose image before submit'
			})
			this.messages.set(msg);
		}

		if (model.source_type === 'url' && model.url === '') {
			var msg = new App.Models.Message({
				message: 'Please input image URL before submit'
			})
			this.messages.set(msg);
		}

		/*if (App.collections.images.findWhere({hash: md5(file.name + model.storage)})){
			var msg = new App.Models.Message({
				type: 'warning',
				message: 'File already exist'
			})
			this.messages.set(msg);
		};*/

		if (model.source_type === 'file') {
			_.each(files, function (file) {
				data.append('files[]', file);
			});
		}

		if (this.messages.toJSON().length > 0) return;

		App.views.images.animationStart();

		$.ajax({
			url: '/public/image.php',
			dataType: 'text',
			type: 'POST',
			contentType: false,
			cache: false,
			processData: false,
			data: data,
			success: function (code) {
				if(code == 400){
					var msg = new App.Models.Message({
						type: 'danger',
						message: 'Wrong file format. Only PNG, GIF and JPG are allowed.'
					});
					_this.messages.set(msg);
				}
				if(code == 401){
					var msg = new App.Models.Message({
						type: 'danger',
						message: 'Wrong URL format. '
					});
					_this.messages.set(msg);
				}
				if(code == 500){
					var msg = new App.Models.Message({
						type: 'danger',
						message: 'Something went wrong. Please call 911'
					});
					_this.messages.set(msg);
				}

				// reset form fields with dirty hacks
				_this.model.set('url', '');
				_this.$el.find('.file-overlay').val('');
				_this.$el.find('[name="file"]').replaceWith(_this.$el.find('[name="file"]').val('').clone(true));

				App.collections.images.fetch({
					success: App.collections.images.update
				});
			}
		});
	},
	handleErrors: function () {
		if (this.messages.toJSON().length === 0) return;

		var messagesView = new Backbone.CollectionView({
			el: this.$el.find("#upload_messages"), // must be UL or TABLE element
			modelView: App.Views.Message,
			collection: this.messages,
			selectable: false,
		});
		messagesView.render();
	},
	render: function () {
		this.$el.html(this.template(this.model.toJSON()));

		this.stickit();
	},
});