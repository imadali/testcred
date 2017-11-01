
({
    extendsFrom : 'QuickEditView',
    from_wf : null,
    app_approval_rule : null,
    task_id : null,
    initialize : function(options) {
        this._super('initialize', [ options ]);
        this.model.on("change:parent_id", this.populateFromApp, this);
        this.model.on("change:application_name_c", this.updateNewTask, this);
        this.model.on("change:category_c", this.fillTaskSubject, this);
        this.from_wf = this.context.get('from_wf');
        this.app_approval_rule = this.context.get('app_approval_rule');
        this.task_id = this.context.get('task_id');
    },
    render: function() {
        this._super('_render');
        if (this.from_wf === true) {
            this.populateFromApp();
        }
        this.hideAppField();
    },
    _dispose: function() {
        this.model.off("change:parent_id");
        this.model.off("change:application_name_c");
        this.model.off("change:category_c");
        this._super('_dispose');
     },
	 
	 createRecordWaterfall: function (callback) {
       var self = this;
       var success = _.bind(function () {
           var acls = this.model.get('_acl');
           if (!_.isEmpty(acls) && acls.access === 'no' && acls.view === 'no') {
               //This happens when the user creates a record he won't have access to.
               //In this case the POST request returns a 200 code with empty response and acls set to no.
               this.alerts.showSuccessButDeniedAccess.call(this);
               callback(false);
           } else {
               this._dismissAllAlerts();
               app.alert.show('create-success', {
                   level: 'success',
                   messages: this.buildSuccessMessage(this.model),
                   autoClose: true,
                   autoCloseDelay: 10000,
                   onLinkClick: function () {
                       app.alert.dismiss('create-success');
                   }
               });
               callback(false);
           }
           /* custom code added to call Approval-Dispatch-Logic if app_approval_rule is set*/
           if (this.app_approval_rule) {
               //api call to set user approval in the selected application and in the task as well
               var url = app.api.buildURL('Tasks/PopulateApplication');
               app.api.call('create', url, {
                   task_id: this.task_id,
               }, {
                   success: _.bind(function (response) {
                       //application user approval saved
                       app.events.trigger('refreshApplications');
                       app.events.trigger('refreshActivities');
                       this.task_id = null;
                   }, this),
               });
				
           }
           /* END custom code added to save related products*/
       }, this),
       error = _.bind(function (e) {
           if (e.status == 412 && !e.request.metadataRetry) {
			   this.handleMetadataSyncError(e);
           } else {
			   this.alerts.showServerError.call(this);
			   callback(true);
           }
       }, this);

       this.saveModel(success, error);
   },
	 
    /*
     * Populating values in the fields on create view with the response from the API
     */
    populateFromApp: function () {
        var url = app.api.buildURL('Tasks', 'PopulateTask');
        if (this.model.get("parent_type") === 'Leads' && this.model.get("parent_id")) {
                app.alert.show('fetching-data', {
                    level: 'process',
                    title: 'Fetching values from related appication...',
                    autoClose: false,
                });
            app.api.call('create', url, {
                parent_id: this.model.get('parent_id'),
                parent_type: this.model.get('parent_type'),
            }, {
                success: _.bind(function (response) {
                    app.alert.dismiss('fetching-data');
                    if (response.count === 'single') {
                        this.model.set('user_id_c', response.user_id_c);
                        this.model.set('application_user_approval_c', response.approval);
                        this.model.set('application_provider_c', response.provider);
                    } else if (response.count === 'multiple') {
                        var options = new Object();
                        options[""] = "";
                        _.each(response, function (item, index) {
                            if (index !== 'count') {
                                options[index] = item;
                            }
                        });
                        var application_field = this.getField('application_name_c', this.model);
                        if (application_field !== null) {
                            application_field.def.setOptions = options;
                            application_field.items = options;
                            application_field.def.required = true;
                            application_field.render();
                        }
                    } else if (response.count === 'none') {
                        app.alert.show('no-related-app', {
                            level: 'error',
                            title: 'No related Application was found',
                            autoClose: true,
                        });
                    }

                }, this),
            });
        }
    },
    /*
     * Populating values from the selected application in case 1:n
     */
    updateNewTask: function () {
        app.alert.show('fetching-values', {
                level: 'process',
                title: 'Fetching values...',
                autoClose: false,
        });
        var application = app.data.createBean('Opportunities', {id: this.model.get("application_name_c")});
        application.fetch({
            success: _.bind(function () {
                app.alert.dismiss('fetching-values');
                this.model.set('user_id_c', application.get('user_id_c'));
                this.model.set('application_user_approval_c', application.get('dotb_user_approval_c'));
                this.model.set('application_provider_c',application.get('provider_id_c'));
            }, this),
        });
    },
    /*
     * Toggle View of Application field based on the view
     */
    hideAppField: function () {
        if (this.from_wf === true) {
            $('div [data-name="application_name_c"]').show();
            $('.record .record-cell [data-name="application_name_c"]').parent()
                    .find('.record-label').append("<span style='color:red'>*</span>");
        } else {
            $('div [data-name="application_name_c"]').hide();
        }

    },
	
    //to fill subject when Category value changes. So that formula can be removed from the field
    fillTaskSubject: function(){
        if (!_.isEmpty(this.model.get("category_c"))) {
            var self = this;
            var category = app.lang.getAppListStrings('dotb_task_categories_list')[self.model.get("category_c")];
            self.model.set('name', category);
        } else {
            this.model.set('name', '');
        }
    }
    
})