

({
    extendsFrom: 'SubpanelListView',
    lead_id: null,
    /* events: {
     'click th input[name=mass_close]': 'selectUnselct',		
     }, */
    initialize: function (options) {
        this._super('initialize', [options]);
        this.context.on('list:activity-preview:fire', this.handleActivityPreview, this);
        this.context.on('list:activity-quickedit:fire', this.handleActivityQuickEdit, this);
        this.context.on('list:close_task:fire', this.closeTask, this);
        this.context.on('list:hide_task:fire', this.hideTask, this);
        this.context.on('list:close_and_hide_task:fire', this.closeAndHideTask, this);
        this.context.on('list:share_task:fire', this.shareTask, this);
        // `dataViewName` corresponds to the list of fields the API should retrieve.
        this.dataViewName = options.name || 'subpanel-list';
        var limit = this.context.get('limit') || app.config.maxSubpanelResult;
        // Setup max limit on collection's fetch options for this subpanel's context
        //if(this.context.get('module') === 'Cases'){
        limit = 12;
        //}
        if (limit) {
            this.context.set('limit', limit);
            //supanel-list extends indirectly ListView, and `limit` determines # records displayed
            this.limit = limit;
            // FIXME SC-3670 needs to remove this `collectionOptions` mess.
            var collectionOptions = this.context.has('collectionOptions') ? this.context.get('collectionOptions') : {};
            this.context.set('collectionOptions', _.extend(collectionOptions, {limit: limit}));
        }

        //Override the recordlist row template
        this.rowTemplate = app.template.getView('recordlist.row');

        this.layout.on("hide", this.toggleList, this);
        // Listens to parent of subpanel layout (subpanels)
        this.listenTo(this.layout.layout, 'filter:change', this.renderOnFilterChanged);
        this.listenTo(this.layout, 'filter:record:linked', this.renderOnFilterChanged);

        //event register for preventing actions
        //when user escapes the page without confirming deletion
        app.routing.before("route", this.beforeRouteUnlink, this, true);
        $(window).on("beforeunload.unlink" + this.cid, _.bind(this.warnUnlinkOnRefresh, this));

        this.events['click th input[name=mass_close]'] = 'selectUnselct';
    },
    _dispose: function () {
        this._super('_dispose');
    },
    selectUnselct: function () {
        var all = $('th input[name=mass_close]').is(':checked');
        if (all) {
            $('input[name=mass_close]').attr('checked', true);
        } else {
            $('input[name=mass_close]').attr('checked', false);
        }
    },
    /*_renderField: function(field) {
     var fieldName = field.name,
     fieldModule = field.model.get('_module'),
     fieldType = field.def.type || 'default';
     if(!((fieldName === 'status' || fieldName === 'date_due') && fieldModule === 'Tasks')){
     if (fieldName === 'name') {
     field.model.module = fieldModule;
     } else if (fieldName === 'module') {
     field.model.set({
     module: field.model.get('moduleNameSingular')
     });
     } else if (fieldName === 'related_contact') {
     var contact, contactId;
     field.model.module = 'Contacts';
     switch (fieldModule) {
     case 'Emails':
     contact = '';
     contactId = '';
     break;
     case 'Notes':
     case 'Calls':
     case 'Meetings':
     case 'Tasks':
     contact = field.model.get('contact_name');
     contactId = field.model.get('contact_id');
     break;
     }
     field.model.set({
     related_contact: contact,
     related_contact_id: contactId
     });
     } else if (fieldName === 'status' && fieldModule === 'Emails') {
     var fieldStatus = field.model.get('status'),
     emailStatusDom = app.lang.getAppListStrings('dom_email_status');
     if (!_.contains(emailStatusDom, fieldStatus)) {
     fieldStatus = emailStatusDom[fieldStatus]
     }
     field.model.set({
     status: fieldStatus
     });
     }
     }
     this._super('_renderField', [field]);
     },*/

    handleActivityQuickEdit: function (model) {
        if (model.get('_module') !== 'Emails') {
            var bean = app.data.createBean(model.get('_module'), {_module: model.get('_module'), id: model.get('id')});
            bean.set('parent_id', this._currentUrl.split("/").pop());
            this.handleQuickEdit(bean);
        } else {
            app.alert.show('no_edit_emails', {
                level: 'error',
                messages: app.lang.get('LBL_EMAIL_NOT_EDITABLE', 'Leads'),
                autoClose: true
            });
        }
    },
    closeTask: function (model) {
        var taskId = model.get("id");
        this.updateTask(taskId, 'close');
    },
    hideTask: function (model) {
        var taskId = model.get("id");
        this.updateTask(taskId, 'hide');
    },
    closeAndHideTask: function (model) {
        var taskId = model.get("id");
        this.updateTask(taskId, 'close_and_hide');
    },
    shareTask: function (model) {
        var taskId = model.get("id");
        var href = Backbone.history.location.href;
        href = href.split("#");
        href = href[0] + '#Tasks/' + taskId;
        var subject= 'Shared Task '+ model.get("name") +' from SugarCRM';
        var share_body='<p>Please checkout Task '+model.get("name")+' from SugarCRM</p><a href="'+href+'" data-mce-href="'+href+'">'+href+'</a> <br class="signature-begin">';
        app.drawer.open({
            layout: 'compose',
            context: {
                create: true,
                module: 'Emails',
                prepopulate: {
                    subject: subject,
                    html_body: share_body,
                    placement: 'bottom',
                    action: 'email',
                }
            }
        });
    },
    updateTask: function (task_id, action) {
        app.alert.show('process-id', {
            level: 'process',
            title: 'In Process...' //change title to modify display from 'Loading...'
        });
        var self = this;
        var task = app.data.createBean('Tasks', {id: task_id});
        task.fetch({
            success: function () {
                var msg = '';
                if (action == 'close') {
                    if(task.attributes.name == "Outlook-Task"){
                        task.attributes.hide = true;
                    }
                    task.attributes.status = 'closed';
                    msg = 'LBL_TASK_ACTION_CLOSE';
                } else if (action == 'hide') {
                    task.attributes.hide = true;
                    msg = 'LBL_TASK_ACTION_HIDE';
                } else if (action == 'close_and_hide') {
                    task.attributes.status = 'closed';
                    task.attributes.hide = true;
                    msg = 'LBL_TASK_ACTION_CLOSE_HIDE';
                }

                task.save(
                        {}, {
                    success: function () {
                        // self.collection.remove(model);
                        var parent_model = self.context.parent.attributes.model;
                        var linkName = 'historical_summary';
                        var subpanelCollection = parent_model.getRelatedCollection(linkName);
                        subpanelCollection.fetch({relate: true});
                        app.events.trigger('refreshActivitiesDashlet');
                        /* 
                         * Checking If all tasks are closed then show alert message
                         */
                        var status_alert = true;
                        var related_tasks = App.data.createBeanCollection('Tasks');
                        related_tasks.length = 1000;
                        var filters = [
                            {"parent_id": parent_model.get('id')},
                        ];
                        related_tasks.fetch({
                            "filter": filters,
                            limit: 1000,
                            success: function (models, options) {
                                var dup = _.find(related_tasks.models, function (model) {
                                    var status = model.get('status');
                                    if (status != 'closed') {
                                        status_alert = false;
                                    }
                                });
                                app.alert.dismiss('process-id');
                                if (status_alert && action != 'hide') {
                                    app.alert.show("all-closed-msg", {
                                        level: 'info',
                                        messages: app.lang.get('LBL_CLOSED_STATUS_ALERT_MSG', 'Leads'),
                                        autoClose: false
                                    });
                                }
                                if (msg != '') {
                                    app.alert.show(action, {
                                        level: 'success',
                                        messages: app.lang.get(msg, 'dotb6_contact_activities'),
                                        autoClose: true
                                    });
                                }
                            },
                        });

                    }

                });

            }
        });
    },
    handleActivityPreview: function (model) {
        /**
         * CRED-873 : CTI Basic: RT Action Items
         */
        var url = app.api.buildURL(model.get('_module'), 'read', {id: model.get('id')});
        if (model.get('_module') == 'Calls') {
            layout = 'create';
            url += '?view=record';
        }
        app.api.call(
                'read', url, null,
                {
                    success: function (data) {
                        bean = app.data.createBean(data._module, data);
                        app.events.trigger('preview:render', bean, null);
                    },
                    error: function () {
                        return;
                    }
                }
        );
    },
    warnUnlink: function (model) {
        /* if(model.get('parent_type') == 'Emails') {
         app.alert.show('no_unlink_emails', {
         level : 'error',
         messages : "Emails can't be unlinked"
         });
         } else {
         this._super('warnUnlink',[model]);
         } */
        if (model.get('_module') == 'Emails') {
            app.alert.show('no_unlink_emails', {
                level: 'error',
                messages: "Emails can't be unlinked"
            });
        } else {
            //update link to unlink the record
            this.link_name = model.link.name;
            model.link.name = model.get('_module').toLowerCase();
            this._super('warnUnlink', [model]);
        }

    },
    /**
     * Unlink (removes) the selected model from the list view's collection
     */
    unlinkModel: function () {
        var self = this,
                model = this._modelToUnlink;

        model.destroy({
            //Show alerts for this request
            showAlerts: {
                'process': true,
                'success': {
                    messages: self.getUnlinkMessages(self._modelToUnlink).success
                }
            },
            relate: true,
            success: function () {
                var redirect = self._targetUrl !== self._currentUrl;
                self._modelToUnlink = null;
                self.collection.remove(model, {silent: redirect});

                if (redirect) {
                    self.unbindBeforeRouteUnlink();
                    //Replace the url hash back to the current staying page
                    app.router.navigate(self._targetUrl, {trigger: true});
                    return;
                }

                //set link of the collection
                self.collection.link.name = self.link_name;

                //Refreshing the Activities Dashlet
                app.events.trigger('refreshActivitiesDashlet');
                
                // We trigger reset after removing the model so that
                // panel-top will re-render and update the count.
                self.collection.trigger('reset');
                self.render();
                
            }
        });
    },
})
