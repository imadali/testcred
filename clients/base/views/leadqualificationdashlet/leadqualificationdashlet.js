({
    plugins: ['Dashlet'],
    fieldData : null,
    leadData : null,
    singlePanel : false,
    moduleName : null,
    deltavistaScore : null,
    deltavistaRiskClass : null,
    
    events : {
        'click .leadbox-panel ': 'getPanel',
    },
    
    initialize: function(options) {
        this._super('initialize', [options]);
        app.events.on('record-saved', _.bind(this.recordSaved, this));
     
    },
    
    recordSaved: function(){
        
        this.loadData();
    },
    _renderHtml: function () {
        this._super('_renderHtml');
        this.moduleName = 'Leads';
    },

     loadData: function (options) {
       
        this._super('loadData',[options]);
        options = options || {};
        if (_.isFunction(options.complete))
        {
        options.complete();
        }
        var self=this;
        self.leadData = {};
        self.panelList = {};
        url = app.api.buildURL('Leads/LeadQualification/'+this.model.id , null, null);
        app.api.call('read', url, null, {
                success: function(response){
                        self.fieldData = response.panels;
                        self.leadData =  self.fieldData;
                        
                        /**
                         * CRED-852 : Add RiskClass field in Lead qualification dashlet
                         */
                        self.deltavistaScore = response.deltavista_score;
                        self.deltavistaRiskClass = response.deltavista_riskclass;
                        self.render();
                        $('.leadbox').parents().eq(0).css('max-height','850px');
                        $('.leadbox').parents().eq(1).css('max-height','850px');
                        $('.leadbox').parents().eq(2).css('max-height','850px');
                },

        });
        
    },
    
   
    getPanel : function(event){
        this.singlepanel = true;
        var id = event.currentTarget.id;
        if(id == "LBL_RECORD_BODY"){
                var parent = $('[data-panelname="panel_body"]').parent().attr("id").split("view")[0];
                $('.tab.'+parent+' a').trigger('click');
                var position = $('[data-panelname="panel_body"]').position().top;
                $('.main-pane').scrollTop(position);
                var childs = $('[data-panelname="panel_body"]').children("div");
                $(childs[0]).removeClass('panel-inactive');
                $(childs[0]).addClass('panel-active');
                $(childs[1]).show();     
        }
        else if(id == "LBL_NAME"){
            
        }
	else if(id == "LBL_PARTNER_PANEL"){
            
        }
        else{
                var parent = $('[data-panelname="'+id+'"]').parent().attr("id").split("view")[0];
                $('.tab.'+parent+' a').trigger('click');
                var position = $('[data-panelname="'+id+'"]').position().top;
                $('.main-pane').scrollTop(position);
                var childs = $('[data-panelname="'+id+'"]').children("div");
                $(childs[0]).removeClass('panel-inactive');
                $(childs[0]).addClass('panel-active');
                $(childs[1]).show();     
        }
        
        this.leadData = {};
        this.leadData[id] = this.fieldData[id];   
        this.render();

    },
    _dispose : function(){
        app.events.off('record-saved');
    }
    
})
