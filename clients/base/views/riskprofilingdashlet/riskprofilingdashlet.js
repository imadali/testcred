({
    plugins: ['Dashlet'],
    bankCount : 6,
    bankNames : null,
    leadScore : null,
    trafficLight : null,
    dashletData : null,
    
    events : {
        'click .record-panel-header': 'togglePanel',

    },
    
    initialize: function(options) {
        this._super('initialize', [options]);
        app.events.on('record-saved', _.bind(this.recordSaved, this));
       
    },
    
    recordSaved: function(){
        this.loadData();
    },

    loadData: function (options) {
            this._super('loadData',[options]);
            options = options || {};
            if (_.isFunction(options.complete))
            {
            options.complete();
            }

            var self=this;
            self.moduleName = 'dotb9_risk_profiling';
            var lead = app.data.createBean('Leads', {id: this.model.id});
            lead.fetch({
                success: function(){
                    
                    self.leadScore = lead.get('deltavista_score_c');
                    self.trafficLight = lead.get('dotb_traffic_light_c');
                   
                }
            });

            //Api call for getting the data
            url = app.api.buildURL('Leads/RiskProfile/'+this.model.id , null, null);
            app.api.call('read', url, null, {
                success: function(response){
                        self.dashletData = response;
                        _.each(self.dashletData , function(value , index){
                            _.each(value , function(newvalue , newindex){
                                   
                                    self.riskProfiling = {};
                                    self.temp = {};
                                    if(newindex != 'flag'){
                                     
                                        _.each(newvalue , function(newvalue2 , newindex2){
                                               
                                            x = app.lang.get(newindex2, 'dotb9_risk_profiling');
                                            self.riskProfiling[x] = newvalue2;
                                               
                                      
                                        self.dashletData[index][newindex] = self.riskProfiling;

                                        },this);
                                       
                                    }
                                    else{
                                        var color = self.dashletData[index][newindex];
                                        self.dashletData[index][newindex] = color ;
                                    }

                              },this);
                              
                        },this);
                     
                        self.render();
                        $('.risk-profile').parents().eq(0).css('max-height','680px');
                        $('.risk-profile').parents().eq(1).css('max-height','680px');
                        $('.risk-profile').parents().eq(2).css('max-height','720px');
                },
        });
        
    },
    _renderHtml: function () {
        this._super('_renderHtml');
    },
    
    //Toggle panels in the view
    togglePanel: function(e) {
       
        var $panelHeader = this.$(e.currentTarget);
        if ($panelHeader && $panelHeader.next()) {
            $panelHeader.next().toggle();
            $panelHeader.toggleClass('panel-inactive panel-active');
        }
        if ($panelHeader && $panelHeader.find('i')) {
            $panelHeader.find('i').toggleClass('fa-chevron-up fa-chevron-down');
        }
        var panelName = this.$(e.currentTarget).parent().data('panelname');
        var state = 'collapsed';
        if (this.$(e.currentTarget).next().is(":visible")) {
            state = 'expanded';
        }
        
        this.savePanelState(panelName, state);
    },
     savePanelState: function(panelID, state) {
        if (this.createMode) {
            return;
        }
        var panelKey = app.user.lastState.key(panelID + ':tabState', this);
        app.user.lastState.set(panelKey, state);
    },
   
    _dispose : function(){
        app.events.off('record-saved');
    }
    
})
