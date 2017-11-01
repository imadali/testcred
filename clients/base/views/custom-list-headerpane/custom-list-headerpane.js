
({
    extendsFrom: 'HeaderpaneView',
    
    initialize: function(options) {
        this._super('initialize', [options]);
    },
    
    _render: function () {
        this._super('_render');
    },
    
     _formatTitle: function() {
        this._super('_formatTitle');
        if(this.name == 'custom-list-headerpane') {
            return app.lang.get('LBL_CONSOLIDATED_HEADER', this.module);
        }
    },
})
