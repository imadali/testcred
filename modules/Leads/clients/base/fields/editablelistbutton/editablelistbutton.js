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
    extendsFrom: 'EditablelistbuttonField',
    initialize: function (options) {
        this._super("initialize", [options]);
    },
    _save: function() {
        var self = this,
            successCallback = function(model) {
                self.changed = false;
                self.view.toggleRow(model.id, false);
            },
            options = {
                success: successCallback,
                error: function(error) {
                    if (error.status === 409) {
                        app.utils.resolve409Conflict(error, self.model, function(model, isDatabaseData) {
                            if (model) {
                                if (isDatabaseData) {
                                    successCallback(model);
                                } else {
                                    self._save();
                                }
                            }
                        });
                    }
                },
                complete: function() {
                    // remove this model from the list if it has been unlinked
                    if (self.model.get('_unlinked')) {
                        self.collection.remove(self.model, { silent: true });
                        self.collection.trigger('reset');
                        self.view.render();
                    } else {
                        self.setDisabled(false);
                    }
                    // Refreshing Leads subpanel for populating value of Contract in non-db fields
                    if(self.context.parent) {
                        var parentMod = self.context.parent.get('model');
                        if(_.isEqual(parentMod.module,'Leads')) {
                            app.events.trigger('refreshLeadSubpanelOnLead');
                        } else if(_.isEqual(parentMod.module,'Contacts')) {
                            app.events.trigger('refreshLeadSubpanelOnContact');
                        } 
                    }
                },
                lastModified: self.model.get('date_modified'),
                //Show alerts for this request
                showAlerts: {
                    'process': true,
                    'success': {
                        messages: app.lang.get('LBL_RECORD_SAVED', self.module)
                    }
                },
                relate: this.model.link ? true : false
            };

        options = _.extend({}, options, this.getCustomSaveOptions(options));
        
        this.model.save({}, options);
    },
})