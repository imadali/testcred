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
    extendsFrom: 'MassupdateView',

    /**
    * Performs mass export on selected records
    * CRED-845 : Check if regular user then show message deletion not allowed
    */
    massExport: function() {
        if(app.user.get('type') != 'admin'){
            app.alert.show("regular-user", {
                level: 'info',
                messages: app.lang.get('LBL_REGULAR_USER_EXPORT_MSG'),
                autoClose: false
            });
        } else {
            this._super('massExport');
        }
    },
    
    /**
    * CRED-987 : Merging Records: Leads and Contacts
    * Popup dialog message to confirm delete action
    */
    warnDelete: function() {
        if(app.user.get('type') != 'admin'){
            app.alert.show("regular-user", {
                level: 'info',
                messages: app.lang.get('LBL_REGULAR_USER_DELETE_MSG'),
                autoClose: false
            });
        } else {
            this._super('warnDelete');
        }
},
    
})
