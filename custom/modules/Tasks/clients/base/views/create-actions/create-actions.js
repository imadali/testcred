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
	
	initialize : function(options) { 
        this._super('initialize', [ options ]);
        app.error.errorName2Keys['field_error'] = 'Due Date must be after current date';
        this.model.addValidationTask('check_field', _.bind(this._doValidateDueDate, this));
        this.model.on("change:parent_id", this.getLeadVal, this);
        this.model.on("change:category_c", this.fillTaskSubject, this);
    },
     _doValidateDueDate: function(fields, errors, callback) {
        var self = this;
        if (this.model.get('date_due') && app.date.compareDates(self.model.get('date_due'), Date()) == -1) {

            errors['date_due'] = errors['date_due'] || {};
            errors['date_due'].field_error = true;
        }
        callback(null, fields, errors);
    },
    render: function () {
        this._super('render');
	this.getLeadVal();
    },
    _dispose: function() {
        this.model.off("change:parent_id");
        this.model.off("change:category_c");
        this._super('_dispose');
     },
    getLeadVal: function () {
        var self = this;
        // self.model.set('assigned_user_id',null);
        // self.model.set('assigned_user_name',null);
        self.model.set('team_id','1');
        self.model.set('team_name','Global');
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

    //to fill subject when Category value changes. So that formula can be removed from the field
    fillTaskSubject: function(){
        if (!_.isEmpty(this.model.get("category_c"))) {
            var self = this;
            var category = app.lang.getAppListStrings('dotb_task_categories_list')[self.model.get("category_c")];
            self.model.set('name', category);
        } else {
            this.model.set('name', '');
        }
    }
})
