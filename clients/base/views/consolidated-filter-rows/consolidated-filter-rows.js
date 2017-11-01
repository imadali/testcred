({
    extendsFrom : 'FilterRowsView',

    /**
     * @override
     * @param {Object} opts
     */
    initialize: function(opts) {
        this.formRowTemplate = app.template.get("filter-rows.filter-rows-partial");
        this._super('initialize', [opts]);
        $('.filter-options').prepend($('.filter-definition-container'));
    },

    /**
     * Loads the list of filter fields for supplied module.
     *
     * @param {string} module The module to load the filter fields for.
     */
    loadFilterFields: function(module) {
        if (_.isEmpty(app.metadata.getModule(module, 'filters'))) {
            return;
        }
        
        this.filterFields = {};
        
        this.filterFields['assigned_user_name'] = app.lang.get('LBL_ASSIGNED_TO', module);
        this.filterFields['date_entered'] = app.lang.get('LBL_DATE_ENTERED', module);
        this.filterFields['date_modified'] = app.lang.get('LBL_DATE_MODIFIED', module);
        
        if(module == 'Calls') {
            this.filterFields['date_end'] = app.lang.get('LBL_DUE_DATE', module);
        } else if(module == 'Tasks') {
            this.filterFields['date_due'] = app.lang.get('LBL_DUE_DATE', module);
        }
        
        this.filterFields['provider_contract_no'] = app.lang.get('LBL_PROVIDER_CONTRACT_NUMBER', module);
        this.filterFields['name'] = app.lang.get('LBL_SUBJECT', module);
        this.filterFields['application_user_approval_c'] = app.lang.get('LBL_USER_APPROVAL', module);
        this.filterFields['lead_status_c'] = app.lang.get('LBL_LEAD_STATUS', module);
        this.filterFields['team_name'] = app.lang.get('LBL_TEAMS', module);

        this.fieldList = app.data.getBeanClass('Filters').prototype.getFilterableFields(module);
        this.fieldList.status.options = 'consolidated_status_list';
        
    },

    openForm: function(filterModel) {
        this._super('openForm',[filterModel]);
        $('.filter-options').prepend($('.filter-definition-container'));
    },
})
