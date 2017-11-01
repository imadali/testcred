/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/06_Customer_Center/10_Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */
({
    plugins: ['EllipsisInline', 'MetadataEventDriven'],

    events: {
        'click .app-show': 'showAppication',
    },
    _render: function() {
        if (this.view.name === 'record' || this.view.name === 'audit') {
            this.def.link = false;
        } else if (this.view.name === 'preview') {
            this.def.link = _.isUndefined(this.def.link) ? true : this.def.link;
        }
        this._super('_render');
    },

    showAppication: function() {
        var self = this;
        selfrt = this;
        // console.log("self", self);

        var appModel = App.data.createBean('Opportunities', {
            id: self.model.id
        });
        var prev_auto_task_created = true;
        appModel.fetch({
            success: function() {
                prev_auto_task_created = appModel.attributes.auto_task_created;
                app.drawer.open({
                    layout: 'create',
                    context: {
                        module: "Opportunities",
                        model: appModel,
                        create: true
                    },
                }, function(context, model) {
                        app.events.trigger('refreshActivities');
                        if(prev_auto_task_created == false && model.attributes.auto_task_created == true){
                            // console.log('Set Lead Status'); 
                            app.events.trigger('setLeadStatus');  
                        }
                        self.view.layout.collection.fetch({relate: true});
                });
            },
        });
    }
})