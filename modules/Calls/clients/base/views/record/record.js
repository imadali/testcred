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
    /**
     * CRED-940 : Sync Tasks Behaviour with Calls ( Fields & Filters )
     */
    extendsFrom: 'RecordView',
    populate_val: false,
    initialize: function (options) {
        this._super('initialize', [options]);
        this.model.on("change:parent_id", this.getLeadVal, this);
    },

    render: function () {
        this._super('render');
        this.hideAppField();
    },

    _dispose: function () {
        this.model.off("change:parent_id");
        this._super('_dispose');
    },

    getLeadVal: function () {
        var self = this;
        if (this.populate_val) {
            if (self.model.get("parent_type") == 'Leads' && self.model.get("parent_id")) {
                var lead = app.data.createBean('Leads', {id: self.model.get("parent_id")});
                lead.fetch({
                    success: function () {
                        if(!self.model.set('is_lead_amount_from_app'))
                        self.model.set('lead_amount_c', lead.get('credit_amount_c'));
                        self.model.set('lead_status_c', lead.get('credit_request_status_id_c'));
                    }
                });
            }
        }
        this.populate_val=true;
    },
    /*
     * Toggle View of Application field based on the view. To hide application field on record view
     */
    hideAppField: function () {
        $('div [data-name="application_name_c"]').hide()
    },
})