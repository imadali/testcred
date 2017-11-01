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
    extendsFrom: 'CreateView',
    from_wf : false,

    initialize : function(options) { 
        this._super('initialize', [ options ]);
        app.error.errorName2Keys['field_error'] = 'Due Date must be after current date';
        this.model.addValidationTask('check_field', _.bind(this._doValidateDueDate, this));
        this.model.on("change:parent_id", this.getLeadVal, this);
        this.model.on("change:parent_id", this.populateFromApp, this);
        this.model.on("change:application_name_c", this.updateNewTask, this);
        this.model.on("change:category_c", this.fillTaskSubject, this);
        this.from_wf = this.context.get('from_wf');
        this.setTeams();
    },
     _doValidateDueDate: function(fields, errors, callback) {
        var self = this;
        if (this.model.get('date_due') && app.date.compare(self.model.get('date_due'), Date()) == -1) {

            errors['date_due'] = errors['date_due'] || {};
            errors['date_due'].field_error = true;
        }
        callback(null, fields, errors);
    },
    render: function () {
        this._super('render');
        this.getLeadVal();
        this.populateFromApp();
        this.hideAppField();
    },
    _dispose: function() {
        this.model.off("change:parent_id");
        this.model.off("change:application_name_c");
        this.model.off("change:category_c");
        this._super('_dispose');
     },
    getLeadVal: function () {
        var self = this;
        // self.model.set('assigned_user_id',null);
        // self.model.set('assigned_user_name',null);
        /* CRED-944 : Moved to function that is called from initialize
        self.model.set('team_id','1');
        self.model.set('team_name','Global'); */
        if(self.model.get("parent_type") == 'Leads' && self.model.get("parent_id")){
            var lead = app.data.createBean('Leads', {id: self.model.get("parent_id")});
            lead.fetch({
                success: function() {
                    self.model.set('lead_status_c', lead.get('credit_request_status_id_c'));
                    //lead_amount_c field is used instead of amount_c
                    self.model.set('lead_amount_c', lead.get('credit_amount_c'));
                    self.model.set('bank_c', lead.get('dotb_bank_name_c'));
                }
            });
        }
    },
    
    /*
     * Populating values in the fields on create view with the response from the API
     */
    populateFromApp: function () {
        var url = app.api.buildURL('Tasks', 'PopulateTask');
        if (this.model.get("parent_type") === 'Leads' && this.model.get("parent_id")) {
                app.alert.show('fetching-data', {
                    level: 'process',
                    title: 'Fetching values from related appication...',
                    autoClose: false,
                });
            app.api.call('create', url, {
                parent_id: this.model.get('parent_id'),
                parent_type: this.model.get('parent_type'),
            }, {
                success: _.bind(function (response) {
                    app.alert.dismiss('fetching-data');
                    if (response.count === 'single') {
                        this.model.set('user_id_c', response.user_id_c);
                        this.model.set('application_user_approval_c', response.approval);
                        this.model.set('application_provider_c', response.provider);
                        this.model.set('provider_contract_no', response.provider_contract);
                        $('div [data-name="application_name_c"]').hide();
                    } else if (response.count === 'multiple') {
                        var options = new Object();
                        options[""] = "";
                        _.each(response, function (item, index) {
                            if (index !== 'count') {
                                options[index] = item;
                            }
                        });
                        var application_field = this.getField('application_name_c', this.model);
                        if (application_field !== null) {
                            application_field.def.setOptions = options;
                            application_field.items = options;
                            application_field.def.required = true;
                            application_field.render();
                        }
                    } else if (response.count === 'none') {
                        app.alert.show('no-related-app', {
                            level: 'error',
                            title: 'No related Application was found',
                            autoClose: true,
                        });
                    }

                }, this),
            });
        }
    },
    /*
     * Populating values from the selected application in case 1:n
     */
    updateNewTask: function () {
        app.alert.show('fetching-values', {
                level: 'process',
                title: 'Fetching values...',
                autoClose: false,
        });
        var application = app.data.createBean('Opportunities', {id: this.model.get("application_name_c")});
        application.fetch({
            success: _.bind(function () {
                app.alert.dismiss('fetching-values');
                this.model.set('user_id_c', application.get('user_id_c'));
                this.model.set('application_user_approval_c', application.get('dotb_user_approval_c'));
                this.model.set('application_provider_c',application.get('provider_id_c'));
                this.model.set('provider_contract_no', application.get('provider_contract_no'));
            }, this),
        });
    },
    /*
     * Toggle View of Application field based on the view
     */
    hideAppField: function () {
        if (this.model.get('id')) {
            $('div [data-name="application_name_c"]').hide();
        } else {
            $('div [data-name="application_name_c"]').show();
            $('.record .record-cell [data-name="application_name_c"]').parent()
                    .find('.record-label').append("<span style='color:red'>*</span>");
        }

        if (this.from_wf == true) {
            $('div [data-name="application_name_c"]').show();
            $('.record .record-cell [data-name="application_name_c"]').parent()
                    .find('.record-label').append("<span style='color:red'>*</span>");
        }

    },

    //to fill subject when Category value changes. So that formula can be removed from the field
    fillTaskSubject: function(){
        if (!_.isEmpty(this.model.get("category_c"))) {
            var self = this;
            var category = app.lang.getAppListStrings('dotb_task_categories_list')[self.model.get("category_c")];
            self.model.set('name', category);
        } else {
            this.model.set('name', '');
        }
    },

    /*
     * CRED-944 : Team was not selected properly
     */
    setTeams: function () {
        var self = this;
        var global_team_obj = {"display_name":"Global", "id":"1","name":"Global","name_2":"","primary":false,"selected":false};
        var global_team = Array();
        global_team.push(global_team_obj);
        self.model.setDefault('team_name', global_team);
    },
})
