/**
 * Controller File
 * Custom subpanel_field Type Field
 */
({
    extendsFrom: 'BaseField',
    /*
    * Events for subpanel buttons
    */
    events: {
        'click .trigger_preview': 'openPreview',
        'click .trigger_select': 'linkExistingRecord',
        'click .trigger_delete': 'deleteRecord',
        'click .trigger_create': 'createRecord',
        'click .trigger_edit': 'editRecord',
        'click .trigger_inline_edit': 'inlineEditRecord',
        'click .remove_inline_edit': 'removeInlineEditRecord',
        'click .trigger_inline_save': 'saveInlineRecord',
        'click .sortable': 'sortByColumn',
        'click .dropdown-toggle': 'toggleRowActionDropdown',
        'click .trigger_preview_all_documents' : 'previewAllDocuments',
        'click .trigger_preview_single_document' : 'previeSingleDocument',
    },

    filteredCollection: null,
    columns: null,
    columnsMeta: null,
    rowViews: {},
    orderBy: {},
    relatedFields: null,
    subpanelModule: null,
    subpanelModel: null,
    fieldMapping: null,
    primary_field: null,
    primary_field_label: null,
    name_field: null,
    total_mrc: 0,
    total_fee: 0,
    div_id: null,
    subpanelCreateView: 'subpanel-create',
    isSubpanelField: true,
    prevView: null,
    siteUrl: null,
    dateEnteredDir:null,
    previewPDFDoc: null,
    /**
    * calling parent initialize
    * @param {Function} initialize
    * @param {Object} options
    */
    initialize: function(options) {
        this._super('initialize', [options]);
        this.columns = this.def.columns;

        this.relatedFields = this.def.relatedFields;
        this.subpanelModule = this.def.relatedModule;
        this.fieldMapping = this.def.fieldMapping;
        this.primary_field = this.def.primary_field;
        this.subpanelCreateView = this.def.subpanelCreateView || 'subpanel-create';
        this.name_field = this.def.name_field;
        this.MappingFieldsValues = this.getMappingValues();
        this.filteredCollection = new Backbone.Collection();
        this.columnsMeta = this.getColumnsMeta();
        this.createWrapper = "action_wrapper" + this.def.linkField;
        this.createDiv = "createNewRecord" + this.def.linkField;
        if (typeof this.def.docLinkField !== 'undefined') {
            this.docLinkField = this.def.docLinkField;
        }
        this.mrc_fields = this.def.mrc_fields;
        this.fee_fields = this.def.fee_fields;
        this.total_mrc = 0;
        this.total_fee = 0;
        this.div_id = this.subpanelModule + "_" + this.def.linkField + "_so";
        this.on('field:model_saved:fire', this.reRenderView, this);
        this.fetchRecords(false);
        this.on('create:btn:enable', this.handleCreateButton, this);
    
        // listner refresh the document collection of Click of Review Merge button
        app.events.on('refreshDocumentPanel', _.bind(this.reRenderView, this));
        app.events.on('previewCreditConsumerDoc', _.bind(this.previewCreditConsumerDoc, this));
                
        if(!$('.document-preview').length) {
            $('.preview-pane').after("<div class='document-preview'></div>");
        }
        else {
            $('.document-preview').show();
        }
                
        selfSubpanel = this;
        ctx = App.context.getContext();
        var parentModule = this.module;
        if(!this.previewPDFDoc){
            this.previewPDFDoc = App.view.createView({name:'document-preview', context: ctx, module: parentModule , model: this.model }); 
            $el = $('.document-preview');
            this.previewPDFDoc.setElement($($el)); 
        }
                
    },
    /**
    * Get site url
    */
    getSiteUrl: function() {
        var self = this;
        app.api.call('read', App.api.buildURL('get_site_url'), {}, {
            success: _.bind(function(response) {
                self.siteUrl = response;
            }, this)
        });
    },
    handleCreateButton: function(disable) {
        if (disable) {
            this.$('.trigger_create').addClass('disabled');
        } else {
            this.$('.trigger_create').removeClass('disabled');
        }
    },
    /**
    * Remove validation errors and marks
    */
    removeValidationErrors: function() {
        $('.error').removeClass('error');
        $('.error-tooltip').remove();
        $('[data-toggle="tab"] .icon-exclamation-sign').remove();
    },
    /**
    * Get each field meta from model vardefs
    */
    getColumnsMeta: function() {
        var meta = new Array;
        this.subpanelModel = app.data.createBean(this.subpanelModule);
        for (var index in this.columns) {
            var field = this.columns[index]['name'];
            var type = this.columns[index]['type'] || false;
            var options = this.columns[index]['options'] || false;
            var readonly = this.columns[index]['readonly'] || false;
            if (this.primary_field && field == this.primary_field) {
                this.primary_field_label = app.lang.get(this.columns[index]['label'], this.subpanelModule);
            }
            var fmeta = this.subpanelModel.fields[field];
            if (type) {
                fmeta['type'] = type;
            }
            if (options) {
                fmeta['options'] = options;
            }
            if (readonly) {
                fmeta['readonly'] = readonly;
            }
            meta.push(fmeta);
        }
        return meta;
    },
    /**
    * Hide/Show row drop down (Edit / Delete) according to positioning relative to parent div
    */
    toggleRowActionDropdown: function(e) {
        var div_id = this.div_id; //this.subpanelModule+"_"+this.def.linkField;
        var relativeY = ($(e.currentTarget).offset().top + $(e.currentTarget).height()) - $('#' + div_id).offset().top;
        var dropdown = $(e.currentTarget).next()[0];
        var display = $(dropdown).css('display');
        var dropDownHeight = $(dropdown).height();
        var bottomOfDiv = $('#' + div_id).position().top + $('#' + div_id).outerHeight(true);
        var calculated = bottomOfDiv - (dropDownHeight + relativeY);
        if (calculated < 25 && display == "none") {
            $(dropdown).addClass('dropdown_upside');
        } else {
            $(dropdown).removeClass('dropdown_upside');
        }
    },
    /**
    * Get mapped fields from metadata and retrieve their values
    */
    getMappingValues: function() {
        var fieldValues = {};
        for (var field in this.fieldMapping) {
            fieldValues[field] = this.model.get(this.fieldMapping[field]);
        }
        return fieldValues;
    },
    /**
    * Refetch subpanel data and render view.
    */
    reRenderView: function() {
        this.filteredCollection = new Backbone.Collection();
        this.fetchRecords(false);
    },
    /**
    * Render subpanel view and its inner fields.
    */
    _render: function() {
        this.removeValidationErrors();
        this._super('_render');
        //this.renderSubpanelFields();
        //this.handleSpecificSubpanelLogic();
        $('.file_download_link').tooltip();
        $('.doc_cat_tooltip').tooltip();
        $('.doc_month_tooltip').tooltip();
        $('.doc_status_tooltip').tooltip();
        $('.doc_notes_tooltip').tooltip();
    },

    /**
    * Render subpanel fields by getting their sfuuid.
    */
    renderSubpanelFields: function() {
        var self = this;
        $('.rowcell>span[sfuuid]').each(function() {
            var $this = $(this),
            sfId = $this.attr('sfuuid');
            var field = self.view.fields[sfId];
            if (field) {
                field.setElement($this || self.$("span[sfuuid='" + sfId + "']"));

                try {
                    field.render();
                } catch (e) {
                    if (field.type != "phone-number-range") {
                    }
                }
            }
        });
    },

    fetchDocTracking: function(id, callback) {
        var self=this;
        /*var url = app.api.buildURL('getRelatedDocumentCategory/'+'Documents'+'/'+id+'/'+'documents_dotb7_document_tracking_1', null, null, null);
                app.api.call('GET', url, null,{*/
        var url = App.api.buildURL('Documents', this.def.DocTrackingLink, {
            id: id,
            link: true
        }, {
            max_num: 1000,
        });
                
        app.api.call('read', url, {}, {
            success: _.bind(function(response) {
                for (var i = response.records.length - 1; i >= 0; i--) {
                    var str1 = response.records[i].category;
                    var str = app.lang.getAppListStrings('dotb_document_category_list')[response.records[i].category];
                    if(typeof str === 'undefined'){
                        response.records[i].category = str1;
                    }else{
                        response.records[i].category = str;
                    }
                    response.records[i].status = app.lang.getAppListStrings('status_list')[response.records[i].status];
                                        
                    var str = response.records[i].month.toString();
                    var arr = str.split(',');
                    var selected_month = new Array;
                                        
                    for (var m=0; m<arr.length; m++){
                        selected_month.push(app.lang.getAppListStrings('document_month_list')[arr[m]]);
                    }

                    response.records[i].month = selected_month;
                    //response.records[i].month = app.lang.getAppListStrings('document_month_list')[response.records[i].month];
                }
                /*
                * Sorting by Category
                */
                var sorted_by_cat=self.sortBy(response.records, 'category',false,false);
                for (var i = response.records.length - 1; i >= 0; i--) {
                    response.records[i]=sorted_by_cat[i][1];
                }                            
                callback(id, response);
            }, this)
        });
    },
    sortBy: function(obj, sortedBy, isNumericSort, reverse){
        sortedBy = sortedBy || 1; // by default first key
        isNumericSort = isNumericSort || false; // by default text sort
        reverse = reverse || false; // by default no reverse

        var reversed = (reverse) ? -1 : 1;

        var sortable = [];
        for (var key in obj) {
            if (obj.hasOwnProperty(key)) {
                sortable.push([key, obj[key]]);
            }
        }
        if (isNumericSort)
            sortable.sort(function (a, b) {
                return reversed * (a[1][sortedBy] - b[1][sortedBy]);
            });
        else
            sortable.sort(function (a, b) {
                var x = a[1][sortedBy].toLowerCase(),
                y = b[1][sortedBy].toLowerCase();
                return x < y ? reversed * -1 : x > y ? reversed : 0;
            });
        return sortable; 
    },
    /**
    * Fetch related records for subpanel data.
    */
    fetchRecords: function(recalculate) {
        var self = this;
        //var modelId = this.model.get('id');
        var collection = app.data.createRelatedCollection(this.model, this.def.linkField);
        collection.fetch({
            relate: true,
            limit: -1,
            success: function(coll) {
                for (var i = 0; i < collection.models.length; i++) {
                    collection.models[i].template = "detail";
                    self.filteredCollection.add(collection.models[i]);
                    self.setColumnClass('document_name', '');
                    self.setColumnClass('date_entered', '');
                    self.fetchDocTracking(collection.models[i].id, function(id, response) {
                        self.filteredCollection.get(id).relDocTracking = response.records;
                        self._render();
                    });
                }
                self._render();
            }
        });
    },
    /**
    * Preview button listener to show preview of clicked record.
    */
    openPreview: function(e) {
        var self = this;
        var row = $(e.currentTarget).closest('tr');
        var beanName = $(row).attr('module');
        var beanID = $(row).attr('data-id');
        var previewCollection = new Backbone.Collection();
        var bean = app.data.createBean(beanName, {
            id: beanID
        });
        bean.fetch({
            success: function() {
                previewCollection.add(bean);

                var previewMetaName = "preview";
                if (self.subpanelCreateView != 'subpanel-create') {
                    var previewMetaName = "preview-" + self.subpanelCreateView;
                    app.events.trigger("preview:custom_subpanel:meta", previewMetaName);
                }
                app.events.trigger("preview:render", bean, previewCollection, true);
            }
        });
    },
    /**
    * Listener function for error validation. To avoid all cells from being colored red.
    */
    handleValidationError: function(errors) {
        this._super('handleValidationError', [errors]);
        this.$el.closest('.record-cell').removeClass('error');
    },
    /**
    * Listener function for deleting clicked record.
    */
    deleteRecord: function(e) {
        var self = this;
        var row = $(e.currentTarget).closest('tr');
        var beanName = $(row).attr('module');
        var beanID = $(row).attr('data-id');
        var bean = app.data.createBean(beanName, {
            id: beanID
        });
        app.alert.show('delete_confirmation', {
            level: 'confirmation',
            messages: app.lang.get('LBL_DELETE_CONFIRM', self.module),
            onConfirm: _.bind(function() {
                app.alert.show('deleting_record', {
                    level: 'process',
                    title: 'Deleting'
                });
                var success_message = app.lang.get('LBL_DELETE_SUCCESS', self.module);
                var error_message = app.lang.get('LBL_DELETE_ERROR', self.module);
                /**
                *	Remove all related document tracking records
                */

                App.api.call('create', App.api.buildURL('DocTracking/removeDocRelTracks'), {
                    "documentId": beanID,
                }, {
                    success: function(data) {
                        app.alert.dismissAll();

                        bean.destroy({
                            success: function(model, response) {
                                self.filteredCollection.remove(model);
                                self._render();
                                app.alert.dismiss('deleting_record', {
                                    level: 'process',
                                    title: 'Deleting'
                                });
                                app.alert.show('deleted', {
                                    level: 'success',
                                    messages: success_message,
                                    autoClose: true,
                                    autoCloseDelay: 8000
                                });
                                                                
                                if($('.document-preview').is(':visible') && $('.document-preview').html().length > 0 ){
                                    var id = self.model.get('id');
                                    if(self.previewPDFDoc){
                                        self.previewPDFDoc.previewPDFDocRender(self.module, id );
                                    }
                                }
                            },
                            error: function(model, response, options) {
                                app.alert.dismiss('deleting_record', {
                                    level: 'process',
                                    title: 'Deleting'
                                });
                                app.alert.show('not_deleted', {
                                    level: 'error',
                                    messages: error_message,
                                    autoClose: true,
                                    autoCloseDelay: 8000
                                });
                                self.filteredCollection = new Backbone.Collection();
                                self.fetchRecords(true);
                            }
                        });
                    },
                    error: function(error) {
                        app.alert.show('saving_error', {
                            level: 'error',
                            messages: "Failed to remove document tracking records",
                            autoClose: true,
                            autoCloseDelay: 8000
                        });
                    },
                });
            }, this)
        });
    },
    /**
    * Listener function to full-edit the clicked record.
    */
    editRecord: function(e) {
        if (this.prevView)
            this.prevView.hideView();
        var self = this;
        this.handleCreateButton(false);
        var row = $(e.currentTarget).closest('tr');
        var beanName = $(row).attr('module');
        var beanID = $(row).attr('data-id');
        var model = self.filteredCollection.get(beanID);
        $('#' + this.createWrapper).append("<div id='" + this.createDiv + "'></p>");
        var ele = $('#' + this.createDiv);
        var newModel = App.data.createBean(beanName);
         if(model){
            var attrs = _.clone(model.attributes);
            newModel.set(attrs);
        }
        newModel.parentView = this;
		
        //set multi-select category field
        var selected_categories = new Array;
        var url = App.api.buildURL('Documents', this.def.DocTrackingLink, {
            id: beanID,
            link: true
        }, {
            max_num: 1000,
        });
        app.api.call('read', url, {}, {
            success: _.bind(function(response) {
                for (var i = response.records.length - 1; i >= 0; i--) {
                    selected_categories.push(response.records[i].category);
                }
                newModel.set('category',selected_categories);
                var vieww = App.view.createView({
                    module: beanName,
                    name: this.subpanelCreateView,
                    model: newModel
                });
                this.prevView = vieww;
                vieww.setElement(ele);
                vieww.render();
            }, this)
        });
    },
    /**
    * Listener function to inline-edit the clicked record.
    */
    inlineEditRecord: function(e) {
        var self = this;
        var row = $(e.currentTarget).closest('tr');
        //var beanName = $(row).attr('module');
        var beanID = $(row).attr('data-id');
        var model = self.filteredCollection.get(beanID);
        model.template = "edit";
        self._render();
    },
    /**
    * Listener function when cancel is clicked for inline edited record.
    */
    removeInlineEditRecord: function(e) {
        var self = this;
        var row = $(e.currentTarget).closest('tr');
        //var beanName = $(row).attr('module');
        var beanID = $(row).attr('data-id');
        var model = self.filteredCollection.get(beanID);
        model.revertAttributes();
        model.template = "detail";
        self._render();
    },
    /**
    * Listener function when save is clicked for inline edited record.
    */
    saveInlineRecord: function(e) {
        var fields = {};
        var primary_field = this.primary_field;
        var self = this;
        _.each(this.columnsMeta, function(field, i) {
            fields[field.name] = field;
            if (field.type == "phone-number-range") {
                fields[field.start_range_field] = self.subpanelModel.fields[field.start_range_field];
                fields[field.end_range_field] = self.subpanelModel.fields[field.end_range_field];
                fields[field.all_range_field] = self.subpanelModel.fields[field.all_range_field];
            }
        });
        var row = $(e.currentTarget).closest('tr');
        var beanName = $(row).attr('module');
        var beanID = $(row).attr('data-id');
        var modelToSave = self.filteredCollection.get(beanID);
        modelToSave.doValidate(fields, function(isValid) {
            if (isValid) {
                $(e.currentTarget).hide();
                if (primary_field) {
                    var pfv = modelToSave.get(primary_field);
                    var id = modelToSave.get('id');
                    var parent_replace_model_id = self.model.get('replace_order_id');
                    var coll = app.data.createBeanCollection(self.subpanelModule);
                    coll.fetch({
                        success: function(models, options) {
                            var dup = _.find(coll.models, function(model) {
                                var primary_field_value = model.get(primary_field);
                                var idc = model.get('id');
                                var order_id = model.get('order_id');
                                return (primary_field_value == pfv && idc != id && replaceOrderList.indexOf(order_id) == -1);
                            });
                            var type = typeof dup;
                            if (type == "undefined") {
                                self.saveRecord(modelToSave);
                            } else {
                                var url = "#" + self.module + "/" + dup.get('order_id');
                                var href = "<a href='" + url + "'>" + dup.get('order_name') + "</a>";
                                var message = self.primary_field_label + " already exists in " + href;
                                app.alert.show('not_saved', {
                                    level: 'warning',
                                    title: ' ',
                                    messages: message,
                                    autoClose: true,
                                    autoCloseDelay: 8000
                                });
                                $(e.currentTarget).show();
                            }
                        }
                    });
                } else {
                    self.saveRecord(modelToSave);
                }
            } else {
                return false;
            }
        });
    },
    /**
    * Linked with above function
    */
    saveRecord: function(modelToSave) {
        app.alert.show('saving_record', {
            level: 'process',
            title: 'Saving'
        });
        var self = this;
        var nameValue = modelToSave.get(this.name_field);
        modelToSave.set('name', nameValue);
        modelToSave.save({}, {
            success: function(model, response) {
                app.alert.dismiss('saving_record', {
                    level: 'process',
                    title: 'Saving'
                });
                app.alert.show('saved', {
                    level: 'success',
                    messages: "Record Saved",
                    autoClose: true,
                    autoCloseDelay: 8000
                });
                self.reRenderView();
            },
            error: function(model, response, options) {
                app.alert.dismiss('saving_record', {
                    level: 'process',
                    title: 'Saving'
                });
                app.alert.show('not_saved', {
                    level: 'error',
                    messages: "Record not saved",
                    autoClose: true,
                    autoCloseDelay: 8000
                });
                self.reRenderView();
            }
        });
    },
    /**
    * Listener function when save is clicked for fully edited record. Event is triggered from Related module's 
    * "Subpanel-create" view
    */
    createRecord: function(e) {
        if (this.prevView)
            this.prevView.hideView();
        if (!this.$('.trigger_create').hasClass("disabled")) {
            var row = $(e.currentTarget).closest('tr');
            var beanName = $(row).attr('module');
            var model = app.data.createBean(beanName, this.MappingFieldsValues);
            var current_user = app.user.id;
            model.set('assigned_user_id', current_user);
            /**
            * setDefaultAttributes and getDefaultAttributes Removed in 7.8.0.0 as given in this link http://support.sugarcrm.com/Documentation/Sugar_Versions/7.8/Ent/Sugar_7.8.0.0_Release_Notes/#Development_Changes
            */
            model.setDefault(model.getDefault);
            $('#' + this.createWrapper).append("<div id='" + this.createDiv + "'></p>");
            var ele = $('#' + this.createDiv);
            model.parentView = this;

            var vieww = App.view.createView({
                module: beanName,
                name: this.subpanelCreateView,
                model: model
            });
            this.prevView = vieww;
            vieww.setElement(ele);
            vieww.render();
            this.handleCreateButton(true);
        }
    },
    /**
    * Set/unset ascending or descending class to specific column
    */
    setColumnClass: function(field, direction) {
        for (var i in this.columns) {
            if (this.columns[i]['name'] == field && direction!='') {
                this.columns[i]['sorting_class'] = "sorting_" + direction;
            } else if(this.columns[i]['name'] == field && direction == ''){
                this.columns[i]['sorting_class'] = "sorting";
            } else {
                this.columns[i]['sorting_class'] = "sorting";
            }
        }
        if(field=='date_entered'){
            if(direction=='')
                this.dateEnteredDir= "sorting";
            else
                this.dateEnteredDir= "sorting_" + direction;
        }else{
            this.dateEnteredDir= "sorting";
        }
    },
    /**
    * Sort subpanel data when a column is clicked
    */
    sortByColumn: function(e) {
        var self = this;
        var eventTarget = $(e.currentTarget);
        var orderBy = eventTarget.data('orderby');
        if (!orderBy) {
            orderBy = eventTarget.data('fieldname');
        }
        if (orderBy === self.orderBy.field) {
            self.orderBy.direction = self.orderBy.direction === 'desc' ? 'asc' : 'desc';
        } else {
            self.orderBy.field = orderBy;
            self.orderBy.direction = 'desc';
        }
        var query_string = self.orderBy.field + ":" + self.orderBy.direction;
        var url = app.api.buildURL(this.module, this.def.linkField, {
            id: this.model.get('id'),
            link: true
        }, {
            limit: '-1',
            order_by: query_string
        });
        app.api.call('read', url, {}, {
            success: _.bind(function(response) {
                self.filteredCollection = new Backbone.Collection();
                self.setColumnClass(self.orderBy.field, self.orderBy.direction);
                for (var i in response.records) {
                    var model = app.data.createBean(response.records[i]._module, response.records[i]);
                    model.template = "detail";
                    self.filteredCollection.add(model);
                    self.fetchDocTracking(model.id, function(trackingId, trackingResponse) {
                        self.filteredCollection.get(trackingId).relDocTracking = trackingResponse.records;
                        self._render();
                    });
                }
                self._render();
            }, this)
        });
    },

    /**
    * Specific Subpanel Logics start from here
    */
    handleSpecificSubpanelLogic: function() {
        var self = this;
        if (this.subpanelModule == "order_phone_numbers") {
            _.each(this.filteredCollection.models, function(model, i) {
                if (model.template == "edit") {
                    self.enableDisableRTField(model, model.get('type'));
                    model.on("change:type", self.enableDisableRTField, self);
                } else {
                    model.off("change:type");
                }
            });
        }
    },
    enableDisableRTField: function(model, type) {
        var mcid = model.cid;
        var rtnumberField = _.last(_.filter(this.view.fields, function(field) {
            return (field.name == "rt_number" && field.model.cid == mcid);
        }));
        rtnumberField.setDisabled(type != "inbound_toll_free");
    },
   
    previewAllDocuments: function(){
        var self1 = this;
        self1.expandView();
            
        // adding custom div at the end of preview-pane for handling documents preview
        App.events.trigger('list:preview:decorate', false);
        App.events.trigger('preview:close');
                  
        $('.dashboard-pane').hide();
            
        var id = self1.model.get('id');
        if(self1.previewPDFDoc){
            self1.previewPDFDoc.previewSingleDoc = false;
            self1.previewPDFDoc.previewAllDoc = true;
            self1.previewPDFDoc.previewPDFDocRender(self1.module, id );
        }
    },
        
    previeSingleDocument: function(e){
        var self = this;
        currentTarget = $(e.currentTarget);
        if(currentTarget.attr('id')){
                
            var record_id = currentTarget.attr('id');
            var module_name = 'Documents';
                
            self.expandView();
                
            App.events.trigger('list:preview:decorate', false);
            App.events.trigger('preview:close');
                
            $('.dashboard-pane').hide();
            if(self.previewPDFDoc){
                self.previewPDFDoc.previewSingleDoc = true;
                self.previewPDFDoc.previewAllDoc = false;
                self.previewPDFDoc.previewPDFDocRender(module_name,record_id);
            }
        } 
    },

    /**
    * to preview credit customer document in leads module
    */
    previewCreditConsumerDoc: function(docId){
        var self = this;
        if(docId){
                
            var record_id = docId;
            var module_name = 'Documents';
                
            self.expandView();
                
            App.events.trigger('list:preview:decorate', false);
            App.events.trigger('preview:close');
                
            $('.dashboard-pane').hide();
            if(self.previewPDFDoc){
                self.previewPDFDoc.previewSingleDoc = true;
                self.previewPDFDoc.previewAllDoc = false;
                self.previewPDFDoc.previewPDFDocRender(module_name,record_id);
            }

            //to switch to document panel
            var parent = $('[data-panelname="LBL_RECORDVIEW_PANEL5"]').parent().attr("id").split("view")[0];
            $('.tab.'+parent+' a').trigger('click');
            var position = $('[data-panelname="LBL_RECORDVIEW_PANEL5"]').position().top;
            $('.main-pane').scrollTop(position);
            var childs = $('[data-panelname="LBL_RECORDVIEW_PANEL5"]').children("div");
            $(childs[0]).removeClass('panel-inactive');
            $(childs[0]).addClass('panel-active');
            $(childs[1]).show(); 
        } 
    },
        
    _dispose : function(){
        app.events.off('refreshDocumentPanel');
        app.events.off('previewCreditConsumerDoc');
        this._super('_dispose');
    },
        
    expandView: function(){
        if($('.main-pane').hasClass('span12')){
            $('.main-pane').removeClass('span12');
            $('.main-pane').addClass('span8');

            $('.sidebar-content').removeClass('side-collapsed');
            $('.sidebar-toggle').trigger('click');
        }
    }

})