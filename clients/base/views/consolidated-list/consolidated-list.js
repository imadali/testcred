({
    extendsFrom: 'RecordlistView',

    initialize: function(options) {
        options.meta = _.extend({}, options.meta || {})

        this._super('initialize',[options]);

        var col = this.context.get('collection');

        if (!_.isUndefined(col)) {
            col.setOption('endpoint', this.registerEndpoint({}).endpoint);
        }
    },

    _render : function(){
        this._super('_render');
    },
    
    /**
     * Function to register custom endpoint for the API call
     */
    registerEndpoint: function (options) {
        var self = this;

        options = _.extend({
            endpoint: _.bind(function (method, model, options, callbacks) {
                var url = 'consolidation/' + model.module;
                options.params.fields = this.getFieldsList();
                if (!_.isUndefined(self.context)) {
                    if (!_.isUndefined(self.context.get('order_by')) && self.context.get('order_by')) {
                        options.params.order_by = self.context.get('order_by');
                    }
                }

                if(!_.isEmpty(options.offset)) {
                    options.params.offset = options.offset;
                }
                
                if(!_.isEmpty(options.limit)) {
                    options.params.limit = options.limit;
                }

                return app.api.records(
                    method,
                    url,
                    method == 'update' || method == 'create' ?
                        app.data.getEditableFields(model, options.fields) : model.attributes,
                    options.params,
                    callbacks,
                    options.apiOptions);
                    
            }, this),
        }, options);
        return options;
    },
    
    /**
     * getFieldsList
     * returns the list of Fields to view
     */
    getFieldsList: function () {
        
        var fields = [];
        
        if (!_.isUndefined(this)) {
            
            if (!_.isUndefined(this.meta.panels[0].fields)) {
                _.each( this.meta.panels[0].fields, function(metaField) {
                    fields.push(metaField.name);
                });
            }
        }
        return fields;
    },
  
    addActions:function () {
        if (this.actionsAdded) return;
        this._super("addActions");
        this.leftColumns = [];
        this.actionsAdded = true;
    },

})
