({
    extendsFrom: 'SubpanelListView',
    initialize: function (options) {
        this._super('initialize', [options]);
    },
    _dispose: function () {
        this._super('_dispose');
    },
    handleQuickEdit: function (model) {
        var self = this;
        var parent_id = model.get('parent_id');
        
        /**
         * CRED-873 : CTI Basic: RT Action Items
         */
        var layout = 'quickedit';
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
                        app.drawer.open({
                            layout: layout,
                            context: {
                                create: true,
                                model: bean,
                                module: bean.get('_module')
                            }
                        },
                        function (data) {
                            self.context.get('collection').resetPagination();
                            self.context.resetLoadFlag();
                            self.context.set('skipFetch', false);
                            //Reset limit on context so we don't "over fetch" (lose pagination)
                            var collectionOptions = self.context.get('collectionOptions') || {};
                            if (collectionOptions.limit)
                                self.context.set('limit', collectionOptions.limit);
                            self.context.loadData({
                                success: function () {
                                    self.collection.trigger('reset');
                                    self.render();
                                    //DOTBASE BEGIN 12032
                                    //C'est ici qu'on déclenche le refresh : quand le subpanel CR a été mis à jour, on veut aussi rafraichir le subpanel Activities
                                    var subpanels = self.context.parent.children;
                                    for (i = 0; i < subpanels.length; i++) {
                                        //self.context.parent.children[i].reloadData(false); 
                                        //Si nous sommes sur le subpanel Activities, on le recharge
                                        if (subpanels[i].attributes.module == 'dotb6_contact_activities') {
                                            subpanels[i].reloadData(false);
                                            /* 
                                             * Checking If all tasks are closed then show alert message
                                             */
                                            var status_alert = true;
                                            var related_tasks = App.data.createBeanCollection('Tasks');
                                            related_tasks.length = 1000;
                                            var filters = [
                                                {"parent_id": parent_id},
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
                                                    if (status_alert) {
                                                        app.alert.show("all-closed-msg", {
                                                            level: 'info',
                                                            messages: app.lang.get('LBL_CLOSED_STATUS_ALERT_MSG', 'Leads'),
                                                            autoClose: false
                                                        });
                                                    }
                                                    //Refreshing the Activities Dashlet
                                                    app.events.trigger('refreshActivitiesDashlet');
                                                },
                                            });
                                        }
                                    }
                                    //DOTBASE END 12032
                                },
                                error: function (error) {
                                    app.alert.show('server-error', {
                                        level: 'error',
                                        messages: 'ERR_GENERIC_SERVER_ERROR'
                                    });
                                }
                            });
                        }
                        );
                    },
                    error: function () {
                        return;
                    }
                }
        );
    },

})