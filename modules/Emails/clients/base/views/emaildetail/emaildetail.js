({

	extendsFrom: 'RecordView',

	initialize: function(options) {
		this._super('initialize', [options]);
		this.context.on('button:close_button:click', this.closeEmailDetail, this);
	},

	closeEmailDetail: function (e) {
		app.drawer.close();
	}

})