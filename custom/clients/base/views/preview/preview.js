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
    extendsFrom: 'PreviewView',
    initialize: function (options) {
        this._super('initialize', [options]);
    },
    _render: function (offset) {
        this._super('_render');
        var module = this.model.get('_module');
        if (module == "Leads") {
        $('div[name="leads_documents"]').parent().hide();
        $('div[name=""]').parent().hide();
        }
    },
})