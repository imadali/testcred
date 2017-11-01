({
    docTrackCollection: null,
    doc_track_link_name: 'documents_dotb7_document_tracking_1',
    manualDocTrackCollection: null,
    manualDocTrackItem: null,
    
    events: {
        'click #lead_convert_add_manual_entry_button': 'addManualCategory',
        'click #remove_manual_doc_track_record' : 'removeManualCategoryItems',
    },
            
    initialize: function (options) {
        var self = this;
        sub_create_view = this;

        this.doc_track_fields = new Array;
        this.doc_track_category_fields = new Array;
        this.manual_doc_track_fields = new Array;
        this.docTrackCollection = new Backbone.Collection();
	this.manualDocTrackCollection = new Backbone.Collection();
        
        app.view.View.prototype.initialize.call(this, options);
        
        if (self.options.meta.panels && this.options.meta.panels[0] && self.options.meta.panels[0].fields) {
            self.doc_track_fields = this.options.meta.panels[0].fields;
        }
        
        if (self.options.meta.panels && this.options.meta.panels[1] && self.options.meta.panels[1].fields) {
            self.doc_track_category_fields = this.options.meta.panels[1].fields;
        }
        
         //get fields of document tracking for manual entry
        if (this.meta.panels && this.meta.panels[2] && this.meta.panels[2].fields) {
            for (var i in this.meta.panels[2].fields) {
                this.manual_doc_track_fields[i] = this.meta.panels[2].fields[i];
            }
        }
        
        //on changing the category field new row is added
        this.model.on("change:category", this.addDocTrackLeadConvert, this);
        
        this.updateDocTrackItems();
    },
    
    addManualCategory: function(){
        var self = this;
        var docTrackItemView = app.template.getView('leadconvert-tracking.leadconvert-doc-track-manual', self.model.module);
  
        var docTrackItem = App.data.createBean('dotb7_document_tracking');
        docTrackItem.set('id', App.utils.generateUUID());
        /**
        * setDefaultAttributes and getDefaultAttributes Removed in 7.8.0.0 as given in this link http://support.sugarcrm.com/Documentation/Sugar_Versions/7.8/Ent/Sugar_7.8.0.0_Release_Notes/#Development_Changes
        */
        docTrackItem.setDefault(docTrackItem.getDefault());
        docTrackItem.set('status', 'fehlt');
        docTrackItem.set('month', '');
        docTrackItem.set('category', '');
        docTrackItem.category_value = '';
        self.manualDocTrackItem = docTrackItem;
        self.manualDocTrackCollection.add(docTrackItem);
        $("#manual_doc_track_items").append(docTrackItemView(self));
        self._crenderFields();
        $('.main-pane').scrollTop(10000);
    },
    
    removeManualCategoryItems: function(e){
        var self = this;
        var beanID =  $(e.currentTarget).attr('data-id');
        var modelDeleted = '';
        for (var i = 0; i < self.manualDocTrackCollection.models.length; i++) {
            var cid = self.manualDocTrackCollection.models[i].get('id');
            
            if(beanID === cid){
                modelDeleted = self.manualDocTrackCollection.models[i];
                $('#' + beanID).remove();
                break;
            }
        }
        
        if(!_.isEmpty(modelDeleted)){
            self.manualDocTrackCollection.remove(modelDeleted);
        }
        
    },
    
    updateTrackCollection: function(data){
        var self  = this;
        var docTrackItem = App.data.createBean('dotb7_document_tracking');

        docTrackItem.set('id', data['id']);
        /**
        * setDefaultAttributes and getDefaultAttributes Removed in 7.8.0.0 as given in this link http://support.sugarcrm.com/Documentation/Sugar_Versions/7.8/Ent/Sugar_7.8.0.0_Release_Notes/#Development_Changes
        */
        docTrackItem.setDefault(docTrackItem.getDefault());
        docTrackItem.set('status', data['status']);

        var category = App.lang.getAppListStrings("dotb_document_category_list")[data['category']];

        docTrackItem.set('category', category);
        docTrackItem.category_value = data['category'];
        self.docTrackItem = docTrackItem;
        self.docTrackCollection.add(docTrackItem);
    },
    
    addDocTrackLeadConvert: function(){
        var self = this;
        var created_categories = new Array;
        var category = self.model.get('category');

        _.each(category, function (value) {
            if ( !$("div[category-name='"+value+"']").length ) {
                var docTrackItemView = app.template.getView('leadconvert-tracking.leadconvert-doc-track', self.model.module);

                var docTrackItem = App.data.createBean('dotb7_document_tracking');
                docTrackItem.set('id', App.utils.generateUUID());
                /**
                * setDefaultAttributes and getDefaultAttributes Removed in 7.8.0.0 as given in this link http://support.sugarcrm.com/Documentation/Sugar_Versions/7.8/Ent/Sugar_7.8.0.0_Release_Notes/#Development_Changes
                */
                docTrackItem.setDefault(docTrackItem.getDefault());
                docTrackItem.set('status', 'fehlt');

                var category = App.lang.getAppListStrings("dotb_document_category_list")[value];

                docTrackItem.set('category', category);
                docTrackItem.set('month', '');
                docTrackItem.category_value = value;
                self.docTrackItem = docTrackItem;
                self.docTrackCollection.add(docTrackItem);

                $("#doc_track_items").prepend(docTrackItemView(self));

                self._crenderFields();
            }

        });
        
        $('div[category-name]').each(function() {
           created_categories.push($(this).attr('category-name'));
        });

        var toBeDeletedDocTracks = _.difference(created_categories,category);
        _.each(toBeDeletedDocTracks, function (delTracking) {
            var deletedTrackingId = $("div[category-name='"+delTracking+"']").attr('id');
            //delete if not deleted earlier
            if($('#'+deletedTrackingId+'').is(':visible'))
            {
                self.removeDocTrackLeadConvert(deletedTrackingId);
                $('#'+deletedTrackingId+'').remove();
            }
        });

    },
    
    _crenderFields: function () {
        var self = this;
        var fieldElems = {};
        var fieldsToRenderOnAdd = ["category", "month", "status", "description"];
        this.$('span[sfuuid]').each(function () {
            var $this = $(this),
            sfId = $this.attr('sfuuid');
            fieldElems[sfId] = $this;
        });
		
        _.each(this.fields, function (field) {
            if (_.indexOf(fieldsToRenderOnAdd, field.name) !== -1)
                self._renderField(field, fieldElems[field.sfId]);
        });
    },
    
    
    removeDocTrackLeadConvert: function (id) {
        var self = this;
        var beanID = id;
        var beanName = 'dotb7_document_tracking';
        var bean = app.data.createBean(beanName, {
            id: beanID
        });

        self.docTrackCollection.remove(bean);
    },
    
    updateTrackCollectionData: function(data){
        var self  = this;
        
        var docTrackItemView = app.template.getView('leadconvert-tracking.leadconvert-doc-track', self.model.module);
        var docTrackItem = App.data.createBean('dotb7_document_tracking');

        docTrackItem.set('id', data['id']);
        /**
        * setDefaultAttributes and getDefaultAttributes Removed in 7.8.0.0 as given in this link http://support.sugarcrm.com/Documentation/Sugar_Versions/7.8/Ent/Sugar_7.8.0.0_Release_Notes/#Development_Changes
        */
        docTrackItem.setDefault(docTrackItem.getDefault());
        docTrackItem.set('status', data['status']);

        var category = App.lang.getAppListStrings("dotb_document_category_list")[data['category']];

        docTrackItem.set('month', data['month']);
        docTrackItem.set('category', category);
        docTrackItem.set('description', data['notes']);
        
        docTrackItem.category_value = data['category'];
        self.docTrackItem = docTrackItem;
        self.docTrackCollection.add(docTrackItem);
        
        $("#doc_track_items").append(docTrackItemView(self));
        self._crenderFields();
    },
    
    updateManualTrackCollectionData: function(data){
        var self = this;
        var docTrackItemView = app.template.getView('leadconvert-tracking.leadconvert-doc-track-manual', self.model.module);
  
        var docTrackItem = App.data.createBean('dotb7_document_tracking');
        docTrackItem.set('id', App.utils.generateUUID());
        /**
        * setDefaultAttributes and getDefaultAttributes Removed in 7.8.0.0 as given in this link http://support.sugarcrm.com/Documentation/Sugar_Versions/7.8/Ent/Sugar_7.8.0.0_Release_Notes/#Development_Changes
        */
        docTrackItem.setDefault(docTrackItem.getDefault());
        docTrackItem.set('status', data['status']);
        docTrackItem.set('month', data['month']);
        docTrackItem.set('category', data['category']);
        docTrackItem.set('description', data['notes']);
        docTrackItem.category_value = '';
        self.manualDocTrackItem = docTrackItem;
        self.manualDocTrackCollection.add(docTrackItem);
        $("#manual_doc_track_items").append(docTrackItemView(self));
        
        self._crenderFields();
    },
    
    updateDocTrackItems: function () {
        var self = this;
        var categoryData = new Array;
        var url = app.api.buildURL('ConvLead/GetDocumentTrackingRecord');
        app.api.call('create', url, {
                id: self.model.id,
            }, {
            success: _.bind(function (response) {
                if(response){
                    for(var i = 0 ; i< response['docTrack'].length; i++){
                        self.updateTrackCollectionData(response['docTrack'][i]);
                        categoryData.push(response['docTrack'][i]['category']);
                    }
                    for(var i = 0 ; i< response['manualDocTrack'].length; i++){
                        self.updateManualTrackCollectionData(response['manualDocTrack'][i]);
                    }
                    self.model.set('category',  categoryData);
                }
            })
        });
    },
        
})
