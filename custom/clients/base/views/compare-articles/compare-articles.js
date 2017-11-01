({
    plugins: ['Dashlet'],
    body_difference : null,
    
    initialize: function(options) {
        this._super('initialize', [options]);
        app.events.on('refreshComparisonDashlet', _.bind(this.refreshComparisonDashlet, this));
     
    },
    
    refreshComparisonDashlet: function(){
        this.loadData();
    },
	
    _renderHtml: function () {
        this._super('_renderHtml');
    },

    loadData: function (options) {
        this._super('loadData',[options]);
        options = options || {};
        if (_.isFunction(options.complete))
        {
            options.complete();
        }
        var self = this;
        url = app.api.buildURL('KBContents/KBBodyComparison/'+this.model.id , null, null);
        app.api.call('read', url, null, {
                success: function(response){
                    self.body_difference = '<b>' + app.lang.get("LBL_NO_DIFFERENCE_IN_ARTICLE") + '</b>';
                    if(!_.isEmpty(response))
                        self.body_difference = response;
					self.render();
                },
        });
        
    },
    
    _dispose : function(){
        app.events.off('refreshComparisonDashlet');
    }
    
})
