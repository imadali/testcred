({
    extendsFrom: 'PanelTopView',
    parentModel: '',
    initialize: function (options) {
        this._super('initialize', [options]);

        this.context.on('paneltop:create-call:fire', this.createRelatedCall, this);
        this.context.on('paneltop:create-email:fire', this.createRelatedEmail, this);
        this.context.on('paneltop:create-meeting:fire', this.createRelatedMeeting, this);
        this.context.on('paneltop:create-note:fire', this.createRelatedNote, this);
        this.context.on('paneltop:create-task:fire', this.createRelatedTask, this);
        this.context.on('paneltop:close_tasks:fire', this.closeSelectedTask, this);
    },
    createRelatedCall: function (event) {
        //this.createRelatedRecord('Calls', 'calls');
        this.openCustomDrawer('Calls');
    },
    createRelatedEmail: function (event) {
        //this.createRelatedRecord('Emails', 'archived_emails');
        this.openCustomDrawer('Emails');
    },
    createRelatedMeeting: function (event) {
        //this.createRelatedRecord('Meetings', 'meetings');
        this.openCustomDrawer('Meetings');
    },
    createRelatedNote: function (event) {
        //this.createRelatedRecord('Note', 'notes');
        this.openCustomDrawer('Notes');
    },
    openCustomDrawer: function (module) {
        var self = this;
        self.parentModel = self.context.get('parentModel');
        var parent_id = self.parentModel.get('id');
        var parent_type = self.parentModule;
        var appModel = App.data.createBean(module, {});
        appModel.set('is_default', 1);
        appModel.set('parent_id', parent_id);
        appModel.set('parent_type', parent_type);
        app.drawer.open({
            layout: 'create',
            context: {
                module: module,
                model: appModel,
                create: true
            },
        }, function (context, model) {
            self.reloadSubpanelForActivities('historical_summary');
            
            /**
             * CRED-940 : Update all calls with same status as related Leads
             */
            if(module == 'Calls') {
                app.events.trigger('refreshLeadStatus');
            }
        });
    },
    createRelatedTask: function (event) {
        var parent_model = this.context.get('parentModel');
        var status = parent_model.get('credit_request_status_id_c');
        var parentModue = this.parentModule;
        var self = this;
        self.parentModel = parent_model;
        if (status == '' && parentModue == 'Leads' ) {
            app.alert.show("status-empty", {
                level: 'confirmation',
                messages: app.lang.get('LBL_NO_TASK_FOR_STATUS_EMPTY', 'dotb6_contact_activities'),
                autoClose: false,
                onConfirm: function(){
                    self.openCustomTaskDrawerForActivities();
                },
                onCancel: function(){
                }
            });
        } else if (status == '00_pendent_geschlossen' && parentModue=='Leads') {
            app.alert.show("status-00", {
                level: 'error',
                title: 'Error:',
                messages: app.lang.get('LBL_NO_TASK_FOR_STATUS00', 'dotb6_contact_activities'),
                autoClose: false
            });
        }else if (status == '11_closed' && parentModue=='Leads') {
            app.alert.show("status-00", {
                level: 'error',
                title: 'Error:',
                messages: app.lang.get('LBL_NO_TASK_FOR_STATUS11', 'dotb6_contact_activities'),
                autoClose: false
            });
        } else {
            
             if (parentModue == 'Contacts'){
                this.createRelatedRecord('Tasks', 'all_tasks');
            }else{
                //this.createRelatedRecord('Tasks', 'tasks');
                self.openCustomDrawer('Tasks');
            }
        }
    },
    openCustomTaskDrawerForActivities: function(){
        var self = this;
        App.drawer.open({
                layout: 'create',
                context: {
                    create: true,
                    module: 'Tasks'
                }
            }, function () {
                self.reloadSubpanelForActivities('historical_summary');
        });  
    },
    reloadSubpanelForActivities: function (link) {
        var self = this;
        var subpanelCollection = self.parentModel.getRelatedCollection(link);
        subpanelCollection.fetch({
            relate: true
        });
        app.events.trigger('refreshActivitiesDashlet');
    },
    closeSelectedTask: function (event) {
        app.alert.show('process-close-id', {
            level: 'process',
            title: 'In Process...' //change title to modify display from 'Loading...'
        });
        var parent_model = this.context.get('parentModel');
        var parent_id = parent_model.get('id');
        var parent_module = parent_model.get('_module');
        var self = this;
        /* 
         * Checking If all tasks are closed then show alert message
         */
        var msg, val, task, record_id, mass_succ = false;
        var related_tasks = App.data.createBeanCollection('Tasks');
        related_tasks.length = 1000;
        if(parent_module === 'Contacts'){
            var filters = [
                {"contact_id": parent_id},
            ];
        }else{
            var filters = [
                {"parent_id": parent_id},
            ];
        }
        related_tasks.fetch({
            "filter": filters,
            limit: 1000,
            success: function (models, options) {
                var ids = [];
                var hide_tasks = [];
                var count = 0;
                _.find(related_tasks.models, function (model) {
                    record_id = model.get('id');
                    val = $('[name=dotb6_contact_activities_' + record_id + '] input[name=mass_close]').is(':checked');
                    if (model.get('_module') == 'Tasks' && val == true && model.get('status') != 'closed' && model.get('name') != 'Outlook-Task') {
                        ids.push(record_id);
                        mass_succ = true;
                        count++;
                    }
                    if (model.get('_module') == 'Tasks' && val == true && model.get('status') != 'closed' && model.get('name') == 'Outlook-Task') {
                        ids.push(record_id);
                        hide_tasks.push(record_id);
                        mass_succ = true;
                        count++;
                    }
                });
                if (mass_succ) {
                    if (count == 1) {
                        msg = app.lang.get('LBL_ONE_TASK_SUCC', 'dotb6_contact_activities');
                    } else {
                        msg = count + ' ' + app.lang.get('LBL_MUL_TASKS_SUCC', 'dotb6_contact_activities');
                    }
                    var url = App.api.buildURL('MassClose/CloseTasks');
                    app.api.call('create', url, {
                        tasks_ids: ids,
                        hide_tasks : hide_tasks,
                        parent_module: parent_module,
                        parent_id: parent_id,
                    }, {
                        success: _.bind(function (all_closed) {
                            app.alert.dismiss('process-close-id');
                            app.alert.show('succ-closed-id', {
                                level: 'success',
                                title: 'Success:',
                                messages: msg,
                                autoClose: false
                            });
                            var linkName = 'historical_summary';
                            var subpanelCollection = parent_model.getRelatedCollection(linkName);
                            subpanelCollection.fetch({relate: true});
                            app.events.trigger('refreshActivitiesDashlet');
                            if (all_closed == true) {
                                app.alert.show("all-closed-msg", {
                                    level: 'info',
                                    title: 'Notice:',
                                    messages: app.lang.get('LBL_CLOSED_STATUS_ALERT_MSG', 'Leads'),
                                    autoClose: false
                                });
                            }

                        }, this)
                    });
                } else {
                    app.alert.dismiss('process-close-id');
                    app.alert.show("all-closed-err", {
                        level: 'error',
                        title: 'Error:',
                        messages: app.lang.get('LBL_NO_TASK_SELECTED', 'dotb6_contact_activities'),
                        autoClose: false
                    });
                }
            },
        });
    },
    openCreateDrawer: function (module, link) {

        link = link || this.context.get('link');
        //FIXME: `this.context` should always be used - SC-2550
        var context = (this.context.get('name') === 'tabbed-dashlet') ?
                this.context : (this.context.parent || this.context);
        var parentModel = context.get('model') || context.parent.get('model'),
                model = this.createLinkModel(parentModel, link),
                self = this;
        
        app.drawer.open({
            layout: 'create',
            context: {
                create: true,
                module: model.module,
                model: model
            }
        }, function (context, model) {
            if (!model) {
                return;
            }

            self.context.resetLoadFlag();
            self.context.set('skipFetch', false);
            // All the views have this method, but since this plugin
            // can officially be attached to a field, we need this
            // safe check.
            if (_.isFunction(self.loadData)) {
                self.loadData();
            }
            //DOTBASE BEGIN 12032
            //C'est ici qu'on declenche le refresh : a la fermeture du drawer, on veut rafraichir le subpanel Activities
            self.refreshActivitiesSubpanel(self.context.parent);
            //DOTBASE END 12032
        });
        //}
    },
})