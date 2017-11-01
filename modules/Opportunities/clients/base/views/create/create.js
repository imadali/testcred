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
	
	initialize : function(options) { 
        this._super('initialize', [ options ]);
        
        if(app.user.attributes.type != "admin"){
            _.each(this.meta.panels, function(panel) {
                 _.each(panel.fields, function(field) {
                         if(field.name == "dotb_soko_c"){
                             field.readonly = true;
                         }
                 }, this);
             }, this);
        }
        
        /**
         * CRED-1028 : Handling Transfer-Items: Additional Requirement
         */
        this.model.on("change:approved_transfer_fee", this.copyAppliedValues, this);
        this.model.on("change:contract_transfer_fee", this.copyApprovedValues, this);
        
    },
	
	saveAndClose: function() {
        this.initiateSave(_.bind(function() {
            if (app.drawer) {
				// custom code
				// if an application is created from lead subpanel and auto_assign_task check box is set trigger action in leads module.
				if(this.model.link){
					if(this.model.link.name == 'leads_opportunities_1'){
						if(this.model.attributes.auto_assign_task)
							app.events.trigger('setLeadStatus');
					}
				}
				// end custom code
                app.drawer.close(this.context, this.model);
            }
        }, this));
    },
    
    /**
     * CRED-1028 : Handling Transfer-Items: Additional Requirement
     */
    copyAppliedValues: function () {
        /**
         * Panel : Approved By the Bank
         */
        if (this.model.get('approved_transfer_fee')) {
            if (_.isEmpty(this.model.get('approved_saldo'))) {
                this.model.set('approved_saldo', this.model.get('applied_saldo'));
            }

            if (_.isEmpty(this.model.get('approved_name_fremdbank'))) {
                this.model.set('approved_name_fremdbank', this.model.get('applied_name_fremdbank'));
            }

        } else {
            this.model.set('approved_saldo', '');
            this.model.set('approved_name_fremdbank', '');
        }
    },
    /**
     * CRED-1028 : Handling Transfer-Items: Additional Requirement
     */
    copyApprovedValues: function () {
        /**
         * Panel : Customer's Choice
         */
        if (this.model.get('contract_transfer_fee')) {
            if (_.isEmpty(this.model.get('contract_saldo'))) {
                this.model.set('contract_saldo', this.model.get('approved_saldo'));
            }

            if (_.isEmpty(this.model.get('contract_name_fremdbank'))) {
                this.model.set('contract_name_fremdbank', this.model.get('approved_name_fremdbank'));
            }
        } else {
            this.model.set('contract_saldo', '');
            this.model.set('contract_name_fremdbank', '');
        }
    },
    
    _dispose: function () {
        this.model.off("change:approved_transfer_fee");
        this.model.off("change:contract_transfer_fee");
        this._super('_dispose');
    },
	
})