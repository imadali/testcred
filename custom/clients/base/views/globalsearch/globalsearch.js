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
	extendsFrom:'GlobalsearchView',
	
    initialize: function(options) {
        this._super('initialize', [options]);
    },
	
	fireSearchRequest: function(term, plugin) {
        var searchModuleNames = this._getSearchModuleNames(),
            moduleList = searchModuleNames.join(','),
            self = this,
            maxNum = app.config && app.config.maxSearchQueryResult ? app.config.maxSearchQueryResult : 5,
            params = {
                q: term,
                fields: 'name, id,credit_request_status_id_c', // added leads status field
                module_list: moduleList,
                max_num: maxNum
            };
        app.api.search(params, {
            success: function(data) {
                var formattedRecords = [];
                _.each(data.records, function(record) {
                    if (!record.id) {
                        return;
                    }
                    var formattedRecord = {
                        id: record.id,
                        name: record.name,
                        module: record._module,
						record_status_label: app.lang.get('LBL_CREDIT_REQUEST_STATUS_ID', record._module),
						record_status_value: app.lang.getAppListStrings('dotb_credit_request_status_list')[record.credit_request_status_id_c],
                        link: '#' + app.router.buildRoute(record._module, record.id)
                    };
                    if ((record._search.highlighted)) {
                        _.each(record._search.highlighted, function(val, key) {
                            var safeString = self._escapeSearchResults(val.text);
                            if (key !== 'name') {
                                formattedRecord.field_name = app.lang.get(val.label, val.module);
                                formattedRecord.field_value = safeString;
                            } else {
                                formattedRecord.name = safeString;
                            }
                        });
                    }
                    formattedRecords.push(formattedRecord);
                });
                plugin.provide({
                    next_offset: data.next_offset,
                    records: formattedRecords,
                    module_list: moduleList
                });
            },
            error: function(error) {
                app.error.handleHttpError(error, plugin);
                app.logger.error("Failed to fetch search results in search ahead. " + error);
            }
        });
    },
})