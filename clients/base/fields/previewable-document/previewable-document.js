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
    extendsFrom: 'BaseField',
    events: {
        'click .preview_credit_consumer_check': 'previewCreditCustomerCheck',
    },
    cc_document: null,

    initialize: function(options) {
        this._super('initialize', [options]);

        //call to get latest doc linked to lead record with category Credit Check Customer
        var beanId = this.model.id;
        var self = this;
        var url = app.api.buildURL('Leads/getCCDoc/'+beanId);
        app.api.call('read', url, null, {
            success: function (response) {
                self.cc_document = response;
                self.render();
            },
        });
    },

    /**
    * Preview button listener to show preview of clicked record.
    */
    previewCreditCustomerCheck: function(e) {
        var self = this;
        currentTarget = $(e.currentTarget);
        if(currentTarget.attr('data-id')){
            var record_id = currentTarget.attr('data-id');
            app.events.trigger('previewCreditConsumerDoc',record_id);
        } 
    },

    /**
     * @inheritdoc
     * dispose
     */
    _dispose: function() {
        this._super('_dispose');
    }
})