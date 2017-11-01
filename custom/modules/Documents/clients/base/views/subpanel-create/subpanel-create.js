/* Custom create view for inline subpanels*/
({
    fieldss: null,
    grid: null,
    docTrackCollection: null,
    manualDocTrackCollection: null,
    manualDocTrackItem: null,
    mergedDocCollection: null,
    validDTC: null,
    reCounter: null,
    doc_track_link_name: 'documents_dotb7_document_tracking_1',
    deleteRecordsId: [],
    events: {
        'change input[name=document_name]': 'validateMe',
        'click #s2id_autogen128': 'validateMe',
        'click #oaf_save_button': 'saveAll',
        'click #oaf_cancel_button': 'hideView',
        'click #add_dt_button': 'addDocTrack',
        //'click #remove_doc_track_record': 'removeDocTrack',
        'click #add_manual_entry_button': 'addManualEntry',
        'click #remove_manual_doc_track_record': 'removeManualDocTrack',
    },
    initialize: function (options) {
        var self = this;
        sub_create_view = this;
        this.reCounter = 0;
        this.fieldss = new Array;
        this.grid = new Array;
        this.doc_track_fields = new Array;
        this.manual_doc_track_fields = new Array;

        this.docTrackCollection = new Backbone.Collection();
        this.manualDocTrackCollection = new Backbone.Collection();
        this.mergedDocCollection = new Backbone.Collection();
        //fetch related document tracking records
        this.updateDocTrackItems();
        this.deleteRecordsId = [];
        app.view.View.prototype.initialize.call(this, options);
        for (var i in this.meta.panels[0].fields) {
            var groupField = this.meta.panels[0].fields[i];
            this.grid[i] = new Array;
            for (var j in groupField) {
                var field = groupField[j]['name'];
                this.fieldss.push(this.model.fields[field]);
                if (field != "") {
                    var fieldd = this.model.fields[field];
                } else {
                    var fieldd = {};
                }
                fieldd['span'] = groupField[j]['span'];
                if (groupField[j]['dismiss_label']) {
                    fieldd['dismiss_label'] = groupField[j]['dismiss_label'];
                }
                this.grid[i].push(fieldd);
            }
        }
        //get fields of document tracking to show as line items
        if (this.meta.panels && this.meta.panels[1] && this.meta.panels[1].fields) {
            for (var i in this.meta.panels[1].fields) {
                if (i == 0) {
                    this.grid[i].push(this.meta.panels[1].fields[i]);
                } else {
                    this.doc_track_fields[i] = this.meta.panels[1].fields[i];
                }
            }
        }

        //get fields of document tracking for manual entry
        if (this.meta.panels && this.meta.panels[2] && this.meta.panels[2].fields) {
            for (var i in this.meta.panels[2].fields) {
                this.manual_doc_track_fields[i] = this.meta.panels[2].fields[i];
            }
        }

        //on changing the category field new row is added
        this.model.on("change:category", this.addDocTrack, this);
    },
    hideView: function (e) {
        this.deleteRecordsId = [];
        if (this && this.model/* && !this.hideCancel*/) {
            this.model.parentView.trigger('create:btn:enable', false);
            this.model.revertAttributes();
            this.unbind('click');
            this.dispose();
        }
    },
    validateMe: function (e) {
        var doc_name = this.model.get('document_name');
        if (doc_name) {
            $('input[name=document_name]').removeClass('error');
        } else {
            $('input[name=document_name]').addClass('error');
        }


    },
    saveAll: function (e) {
        var self = this;
        self.validDTC = 0;

        self.mergedDocCollection = new Backbone.Collection();

        _.each(self.docTrackCollection.models, function (model) {
            self.mergedDocCollection.add(model);
        });

        _.each(self.manualDocTrackCollection.models, function (model1) {
            self.mergedDocCollection.add(model1);
        });

        var num_of_trackings = self.mergedDocCollection.length;
        var category_value = $('input:hidden[name=category]').val(); // getting value of multi-select type category field
        var doc_name = self.model.get('document_name');

        if (!_.isEmpty(category_value)) {
            if (doc_name) {
                for (var i = 0; i < num_of_trackings; i++) {
                    self.mergedDocCollection.models[i].doValidate({"category": "category"}, function (isValid) {
                        if (isValid) {
                            self.validDTC++;
                            if (self.validDTC == num_of_trackings) {
                                self.saveModel();
                            }
                        }
                    });
                }
            } else {
                $('input[name=document_name]').addClass('error');
                app.alert.show('saving_error', {
                    level: 'error',
                    messages: app.lang.get('ERR_RESOLVE_ERRORS'),
                    autoClose: true,
                    autoCloseDelay: 8000
                });
            }
        } else if (num_of_trackings) {
            if (doc_name) {
                for (var i = 0; i < num_of_trackings; i++) {
                    self.mergedDocCollection.models[i].doValidate({"category": "category"}, function (isValid) {
                        if (isValid) {
                            self.validDTC++;
                            if (self.validDTC == num_of_trackings) {
                                self.saveModel();
                            }
                        }
                    });
                }
            } else {
                $('input[name=document_name]').addClass('error');
                app.alert.show('saving_error', {
                    level: 'error',
                    messages: app.lang.get('ERR_RESOLVE_ERRORS'),
                    autoClose: true,
                    autoCloseDelay: 8000
                });
            }
        } else {
            $('input[name=category]').parent().parent().addClass('error');
            app.alert.show('saving_error', {
                level: 'error',
                messages: app.lang.get('LBL_ADD_DOC_CATEGORY', 'Leads'),
                autoClose: true,
                autoCloseDelay: 8000
            });
            if (!doc_name) {
                $('input[name=document_name]').addClass('error');
            }

        }

        /*if (num_of_trackings && !_.isEmpty(category_value)) {
         if(doc_name){
         for (var i = 0; i < num_of_trackings; i++) {
         self.mergedDocCollection.models[i].doValidate({"category": "category"}, function (isValid) {
         if (isValid) {
         self.validDTC++;
         if (self.validDTC == num_of_trackings) {
         self.saveModel();
         }
         }
         });
         }
         } else{
         $('input[name=document_name]').addClass('error');
         app.alert.show('saving_error', {
         level: 'error',
         messages: app.lang.get('ERR_RESOLVE_ERRORS'),
         autoClose: true,
         autoCloseDelay: 8000
         });
         }
         } else {
         app.alert.show('saving_error', {
         level: 'error',
         messages: app.lang.get('LBL_ADD_DOC_CATEGORY', 'Leads'),
         autoClose: true,
         autoCloseDelay: 8000
         });
         }*/

    },
    setStatusVal: function () {
        var self = this;
        for (var i = 0; i < self.docTrackCollection.models.length; i++) {
            var id = self.docTrackCollection.models[i].id;
            var status = $($('.dt-radio-enum[model-id="' + id + '"]:checked')[0]).attr('value');
            self.docTrackCollection.models[i].attributes.status = status;
        }
    },
    saveModel: function (e) {
        var fields = {};
        var self = this;

        //this.setStatusVal();
        _.each(this.fieldss, function (field, i) {
            if (field && field.name) {
                fields[field.name] = field;
            }
        });
        this.model.doValidate(fields, function (isValid) {
            if (isValid) {
                $('#oaf_save_button').hide();
                $('#oaf_save_button_dummy').show();
                app.alert.show('saving_record', {
                    level: 'process',
                    title: 'Saving'
                });
                /**
                 *	Only temp API will return filename_guid
                 *	So update model id only when 
                 *	new record, model.id is null and filename_guid != null
                 */

                self.model.id = self.model.get("filename_guid") ? self.model.get("filename_guid") : self.model.id;
                self.model.set("id", self.model.id);
                self.model.save({}, {
                    success: function (model, response) {
                        var documentId = model.get('id');
                        // Parent record information
                        var parent = {};
                        parent.link = self.model.parentView.def.linkField;
                        if (typeof self.model.parentView.def.docLinkField !== 'undefined') {
                            // your code here
                            parent.originalLink = self.model.parentView.def.docLinkField;
                        }
                        parent.module = self.model.parentView.module;
                        parent.id = self.model.parentView.model.id;
                        self.setDocTrackName();
                        App.api.call('create', App.api.buildURL('DocTracking/SaveDocument'), {
                            "documentId": documentId,
                            "docTrackCollection": JSON.stringify(self.docTrackCollection),
                            "manualDocTrackCollection": JSON.stringify(self.manualDocTrackCollection),
                            "toBeDeletedTrackCollecion": JSON.stringify(self.deleteRecordsId),
                            "parent": parent,
                        }, {
                            success: function (data) {
                                if (data) {
                                    app.alert.dismissAll();
                                    app.alert.show('saved', {level: 'success', messages: "Record Saved", autoClose: true, autoCloseDelay: 8000});
                                    self.deleteRecordsId = [];
                                    self.model.parentView.trigger('field:model_saved:fire');
                                }
                            },
                            error: function (error) {
                                app.alert.show('saving_error', {
                                    level: 'error',
                                    messages: "Failed to save document",
                                    autoClose: true,
                                    autoCloseDelay: 8000
                                });
                            },
                        });
                    },
                    error: function (model, response, options) {
                        app.alert.dismiss('saving_record', {level: 'process', title: 'Saving'});
                        app.alert.show('not_saved', {level: 'error', messages: "Record not saved", autoClose: true, autoCloseDelay: 8000});
                        self.model.parentView.trigger('field:model_saved:fire');
                        $('#oaf_save_button').show();
                        $('#oaf_save_button_dummy').hide();
                    }
                });
            } else {
                return false;
            }
        });
    },
    UploadFile: function () {
        var self = this;
        $input = self.$('input[type=file]');

        var file = app.api.file(
                'create',
                {
                    //Set id to temp if we save a temporary file to reach correct API
                    id: 'temp',
                    module: 'Opportunities',
                    field: 'email_document'
                },
        $input,
                {
                    success: function (data) {
                        var resultField = data['email_document'];
                        if (resultField) {
                            file_details = {
                                'id': data['record']['id'],
                                'file_mime_type': data['record']['file_mime_type'],
                                'filename': data['record']['email_document'],
                            };

                            self.files.push(file_details);

                            //show files
                            self.uploadAttachmentSuccessful(data);
                        }

                    }
                },
        {
            temp: true
        }
        );
    },
    updateDocTrackItems: function () {
        var self = this;
        /*var id = self.model.get('id');
         var url = app.api.buildURL('getRelatedDocumentCategory/'+'Documents'+'/'+id+'/'+'documents_dotb7_document_tracking_1', null, null, null);
         app.api.call('GET', url, null,{
         success: _.bind(function(response) {
         self.docTrackCollection = response['doc_tracking'];
         self.manualDocTrackCollection = response['manual_tracking'];
         console.log('Success Meesga e;');
         
         self.render();
         
         }, this),
         });
         */
        var collection = App.data.createRelatedCollection(self.model, self.doc_track_link_name);
        collection.fetch({
            relate: true,
            limit: -1,
            success: function (coll) {
                for (var i = 0; i < coll.models.length; i++) {
                    var str = App.lang.getAppListStrings("dotb_document_category_list")[coll.models[i].attributes.category];

                    if (typeof str === 'undefined') {

                        var tempModel1 = coll.models[i];
                        self.manualDocTrackCollection.add(tempModel1);
                    } else {
                        var tempModel2 = coll.models[i];
                        self.docTrackCollection.add(tempModel2);
                    }
                }

                for (var j = 0; j < self.docTrackCollection.models.length; j++) {
                    self.reCounter++;
                    self.docTrackCollection.models[j].reCounter = self.reCounter;
                    var category_temp = self.docTrackCollection.models[j].attributes.category;
                    self.docTrackCollection.models[j].category_value = category_temp;
                    self.docTrackCollection.models[j].category = App.lang.getAppListStrings("dotb_document_category_list")[category_temp];
                }

                self.render();
            }
        });
    },
    setDocTrackName: function () {
        var self = this;
        for (var i = 0; i < self.docTrackCollection.models.length; i++) {
            var dtName = sub_create_view.model.get("document_name") + " ";
            dtName += App.lang.getAppListStrings("status_list")[self.docTrackCollection.models[i].get("status")] + " ";
            dtName += self.docTrackCollection.models[i].get("category") + " ";
            self.docTrackCollection.models[i].set("name", dtName);
        }
    },
    addDocTrack: function (e) {
        var self = this;
        var created_categories = new Array;
        var category = self.model.get('category');

        _.each(category, function (value) {
            // category exist
            if (!$("div[category-name='" + value + "']").length) {
                this.reCounter++;
                var docTrackItemView = app.template.getView('subpanel-create.doc-track-item', self.model.module);
                var docTrackItem = App.data.createBean('dotb7_document_tracking');
                docTrackItem.set('id', App.utils.generateUUID());
                docTrackItem.setDefaultAttributes(docTrackItem.getDefaultAttributes());
                // docTrackItem.set('documents_checked', false);
                // docTrackItem.set('documents_recieved', false);
                docTrackItem.set('status', 'fehlt');
                docTrackItem.set('month', '');
                var category = App.lang.getAppListStrings("dotb_document_category_list")[value];
                docTrackItem.set('category', category);
                docTrackItem.category_value = value;
                self.docTrackItem = docTrackItem;
                self.docTrackCollection.add(docTrackItem);
                $("#doc_track_items").prepend(docTrackItemView(self));
                self._crenderFields();
                //$($('.dt-radio-enum[model-id="' + docTrackItem.id + '"]')[1]).attr("checked", 1)
            } else {
                //if value was deleted earlier
                if ($("div[category-name='" + value + "']").is(':hidden'))
                {
                    this.reCounter++;
                    var docTrackItemView = app.template.getView('subpanel-create.doc-track-item', self.model.module);
                    var docTrackItem = App.data.createBean('dotb7_document_tracking');
                    docTrackItem.set('id', App.utils.generateUUID());
                    docTrackItem.setDefaultAttributes(docTrackItem.getDefaultAttributes());
                    // docTrackItem.set('documents_checked', false);
                    // docTrackItem.set('documents_recieved', false);
                    docTrackItem.set('status', 'fehlt');
                    docTrackItem.set('month', '');
                    var category = App.lang.getAppListStrings("dotb_document_category_list")[value];
                    docTrackItem.set('category', category);
                    docTrackItem.category_value = value;
                    self.docTrackItem = docTrackItem;
                    self.docTrackCollection.add(docTrackItem);
                    $("#doc_track_items").prepend(docTrackItemView(self));
                    self._crenderFields();

                }

            }
        });


        $('div[category-name]').each(function () {
            created_categories.push($(this).attr('category-name'));
        });

        toBeDeletedDocTracks = _.difference(created_categories, category);
        _.each(toBeDeletedDocTracks, function (delTracking) {
            var deletedTrackingId = $("div[category-name='" + delTracking + "']").attr('id');
            //delete if not deleted earlier
            if ($('#' + deletedTrackingId + '').is(':visible'))
            {
                self.removeDocTrack(deletedTrackingId);
            }

        });
        var num_of_cat = $("ul.select2-choices li").length;
        if (num_of_cat > 1) {
            $('input[name=category]').parent().parent().removeClass('error');
        } else {
            $('input[name=category]').parent().parent().addClass('error');
        }
    },
    addManualEntry: function () {
        var self = this;
        var docTrackItemView = app.template.getView('subpanel-create.manual-doc-track-item', self.model.module);
        // console.log('docTrackItemView :: '+docTrackItemView);
        var docTrackItem = App.data.createBean('dotb7_document_tracking');
        docTrackItem.set('id', App.utils.generateUUID());
        docTrackItem.setDefaultAttributes(docTrackItem.getDefaultAttributes());
        docTrackItem.set('status', 'fehlt');
        docTrackItem.set('month', '');
        docTrackItem.set('category', '');
        docTrackItem.category_value = '';
        self.manualDocTrackItem = docTrackItem;
        self.manualDocTrackCollection.add(docTrackItem);
        $("#manual_doc_track_items").prepend(docTrackItemView(self));
        self._crenderFields();
    },
    _crenderFields: function () {
        var self = this;
        // In terms of performance it is better to search the DOM once for
        // all the fields, than to search the DOM for each field. That's why
        // we cache placeholders locally and pass them to
        // {@link View.Field#_renderField}.
        var fieldElems = {};
        // var fieldsToRenderOnAdd = ["category", "status", "documents_checked", "documents_recieved", "description"];
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
    removeDocTrack: function (id) {
        var self = this;
        // var beanID = $(e.currentTarget).attr('data-id')
        var beanID = id;
        var modelDeleted = null;

        for (var i = 0; i < self.docTrackCollection.models.length; i++) {
            var cid = self.docTrackCollection.models[i].get('id');

            if (beanID === cid) {
                modelDeleted = self.docTrackCollection.models[i];
                self.deleteRecordsId.push(cid);
                $('#' + id).remove();
                break;
            }
        }
        if (!_.isEmpty(modelDeleted)) {
            self.docTrackCollection.remove(modelDeleted);
        }

        //$('#' + model.id).remove();
        /*var beanName = 'dotb7_document_tracking';
         var bean = app.data.createBean(beanName, {
         id: beanID
         });*/
        // app.alert.show('delete_confirmation', {
        // level: 'confirmation',
        // messages: app.lang.get('LBL_DELETE_CONFIRM', self.module),
        // onConfirm: _.bind(function () {
        /*  app.alert.show('deleting_record', {
         level: 'process',
         title: 'Deleting'
         }); */
        /*var success_message = app.lang.get('LBL_DELETE_SUCCESS', self.module);
         var error_message = app.lang.get('LBL_DELETE_ERROR', self.module);
         bean.destroy({
         success: function (model, response) {
         self.docTrackCollection.remove(model);
         $('#' + model.id).remove();
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
         },
         error: function (response) {
         if (response.status == '404'  || response.code == 'not_found' ) {
         self.docTrackCollection.remove(bean);
         $('#' + bean.id).remove();
         app.alert.dismissAll();
         }
         else {
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
         }
         }
         }); */
        // }, this)
        // });
    },
    removeManualDocTrack: function (e) {
        var self = this;
        var beanID = $(e.currentTarget).attr('data-id');
        var modelDeleted = '';
        for (var i = 0; i < self.manualDocTrackCollection.models.length; i++) {
            var cid = self.manualDocTrackCollection.models[i].get('id');

            if (beanID === cid) {
                modelDeleted = self.manualDocTrackCollection.models[i];
                self.deleteRecordsId.push(cid);
                $('#' + beanID).remove();
                break;
            }
        }

        if (!_.isEmpty(modelDeleted)) {
            self.manualDocTrackCollection.remove(modelDeleted);
        }

    }
})