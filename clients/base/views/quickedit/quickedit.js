
({
    extendsFrom : 'CreateView',
    initialize : function(options) {
        options.meta = _.extend({}, app.metadata.getView(null, 'quickedit'),
                options.meta);
        this._super('initialize', [ options ]);
    },
    render: function() {
        result = this._super('render', []);
        app.events.trigger('preview:render',this.context.parent.get('model'), this.context.parent.get('collection'));
        return result;
    },
    buildSuccessMessage: function(model) {
        return app.lang.get('LBL_RECORD_SAVED');
    }
})