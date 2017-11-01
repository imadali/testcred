({
    className: 'leadconvert-document-preview',
	lead_field: null,
	leadModel:null,
    saveCategories : true,
    events: {
        'click a[name=leadconvert_preview_close_button]': '_leadconvertPreviewCloseButton',
        'click a[name=leadconvert_merge_button]': '_createLead',
    },
    
    leadConvertTracking: null,
    
    initialize: function(options) {
        var self = this;
        this.lead_field = new Array;
        app.view.View.prototype.initialize.call(this, options);
        this.leadModel = App.data.createBean('dummy');
        this.leadModel.fields = this.meta.panels[0].fields;
         _.each(this.meta.panels[0].fields, function (field) {
                self.lead_field.push(field);
        }); 
       
       //Displaying Message about Fetchng Campaigns
       app.alert.show('campaign-fetch', {
                level: 'process',
                title: 'Fetching Active Campaigns...',
                autoClose: true,
        });
        //get active campaigns        
        var campaign_array = new Object();
        campaign_array[''] = '';
        var campaigns = app.data.createBeanCollection('Campaigns');
        campaigns.fetch({
            fields: ['id', 'name'],
            filter:[{'status':'Active'}],
            success: function(filteredCollection) {
                _.each(filteredCollection.models, function(model, i) {
                    campaign_array[model.attributes.id] = model.attributes.name;
                });

                self.lead_field[1].options = campaign_array;
                self.render();
                self.renderDocTrackingView();
                app.alert.dismiss('campaign-fetch');
            },
            error : function(){
                app.alert.dismiss('campaign-fetch');
                app.alert.show('no-active-campign', {
                    level: 'error',
                    title: 'Fetching Active Campaigns Failed',
                    autoClose: true,
                });
                self.renderDocTrackingView();
            }
        });
        
        this._super('initialize', [options]);
    },  
   
    render: function() {
        this._super('render');
    },  
    
    _renderHtml: function() {
        this._super('_renderHtml');
     },
    
    _leadconvertPreviewCloseButton: function(){
        // For unsetting the Data of LeadConvert Treacking View
        this.leadConvertTracking.model.set('category','');
        this.leadConvertTracking.docTrackCollection = null;
        this.leadConvertTracking.dispose();
        
        if($('#close_merge_view')) {
           $('#close_merge_view').show();
        }
        app.drawer.close();
    },
    
    _createLead: function(){
        var self = this;
        self._createLeadAndMergeDocuments('ignore');
    },
    
    _createLeadAndMergeDocuments: function(ignore_duplicate){
        var self = this;
        var linkName = 'leads';
        var pdfPath = new Array;

        $('input[name=images_checkbox]:checked').each(function () {
            pdfPath.push({'page_number': $(this).attr('page_number'), 'pdf_file_path': $(this).attr('pdf_file_path')});
        });

        var json_docTrackCollection  = JSON.stringify(self.leadConvertTracking.docTrackCollection.models);
        var json_pdfPath = JSON.stringify(pdfPath);
        var manual_json_docTrackCollection = JSON.stringify(self.leadConvertTracking.manualDocTrackCollection.models);
        
        var num_of_trackings = self.leadConvertTracking.manualDocTrackCollection.models.length;
        for (var i = 0; i < num_of_trackings; i++) {
            var id = self.leadConvertTracking.manualDocTrackCollection.models[i].get('id');
            var category = $('#'+id).find('input[name=category]').val();
            if(_.isEmpty(category)){
                self.saveCategories = false;
                $('#'+id).find('input[name=category]').addClass('error');
            }
        }
		
		var lead_status = self.leadModel.get("lead_status");
        var lead_campaign = self.leadModel.get("lead_campaign");
		
        // First validate the Model for manual categories
        if(self.saveCategories && (lead_status != '' && !_.isUndefined(lead_status))){
            app.alert.show('creating-lead-process', {
                level: 'process',
                title: 'Creating Lead...',
                autoClose: false,
            });
            
            var url = app.api.buildURL('ConvLead/CreateLead');
            app.api.call('create', url, {
                id: self.model.id,
                ignore_duplicate: ignore_duplicate,
                docTrackCollection : json_docTrackCollection,
                manualDocTrackollection: manual_json_docTrackCollection,
                pdfPath : json_pdfPath,
				lead_status : lead_status,
                lead_campaign : lead_campaign,
            }, {
                success: _.bind(function (response) {
                    
                    // showing cross mark as it gets hidden by Opening drawer for lead conversion
                    if($('#close_merge_view')) {
                        $('#close_merge_view').show();
                    } 
                    
                    app.alert.dismissAll();
                    app.alert.show('create_lead_success', {
                        level: response.level,
                        title: response.message,
                        autoClose: false
                    });

                    self.leadConvertTracking.model.set('category','');
                    self.leadConvertTracking.dispose();
                    app.drawer.close();

                    var subpanelCollection = self.model.getRelatedCollection(linkName);
                    subpanelCollection.fetch({
                        relate: true
                    });

                }, this),
                error: _.bind(function (response) {
                    app.alert.dismissAll();
                    app.alert.show('creating-lead-error', {
                        level: 'error',
                        title: 'Faild to create Lead',
                        autoClose: false,
                    });
                }, this),
            });  
        }else{
            self.saveCategories = true;
            app.alert.show('saving_error', {
                level: 'error',
                messages: app.lang.get('LBL_ADD_DATA_IN_REQUIRED_FIELDS', 'Contacts'),
                autoClose: true,
                autoCloseDelay: 8000
            });
        }
    },
    
   renderDocTrackingView : function(){
       var self = this;
        
        if(!$('.drawer.active').find('.document-preview').length) {
            $('.drawer.active').find('.preview-pane').after("<div class='document-preview'></div>");
        }else {
            $('.drawer.active').find('.preview-pane').show();
        }
        
        // For Doc Tracking Preview on Page
        if($('#document_categories_content')){
            $el = $('#document_categories_content');
            ctx = App.context.getContext();
            self.leadConvertTracking = App.view.createView({name:'leadconvert-tracking', context: ctx, module: self.module , model: self.model}); 
            self.leadConvertTracking.setElement($($el));
            self.leadConvertTracking.render();
        }
   }
})
