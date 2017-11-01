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
    extendsFrom: 'CreateActionsView',
    initialize: function (options) {
        this._super('initialize', [options]);

        if (app.user.attributes.type != "admin") {
            _.each(this.meta.panels, function (panel) {
                _.each(panel.fields, function (field) {
                    if (field.name == "dotb_soko_c") {
                        field.readonly = true;
                    }
                }, this);
            }, this);
        }


    },
    render: function () {
        this._super('render');
        //$('.drawer [data-name="team_name"]').hide();
    },
    saveAndClose: function () {
        this.initiateSave(_.bind(function () {
            if (app.drawer) {
                // custom code
                // if an application is created from lead subpanel and auto_assign_task check box is set trigger action in leads module.
                if (this.model.link) {
                    if (this.model.link.name == 'leads_opportunities_1') {
                        if (this.model.attributes.auto_assign_task)
                            app.events.trigger('setLeadStatus');
                    }
                }
                // end custom code
                app.drawer.close(this.context, this.model);
            }
        }, this));
    },
})