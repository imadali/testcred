({
    extendsFrom: 'OpportunitiesRecordView',
    fieldMapping : null,
    initialize: function (options) {
        this._super('initialize', [options]);

        if (app.user.attributes.type != "admin") {
            _.each(this.meta.panels, function (panel) {
                _.each(panel.fields, function (field) {
                    if (field.name == "dotb_soko_c") {
                        field.readonly = true;
                    }
                }, this);
            }, this);
        }
        this.model.on("change:provider_id_c", this.resetPPI, this);
        this.populateFieldMapping();
        
        /**
         * CRED-1028 : Handling Transfer-Items: Additional Requirement
         */
        this.model.on("change:approved_transfer_fee", this.copyAppliedValues, this);
        this.model.on("change:contract_transfer_fee", this.copyApprovedValues, this);
    },
    
    render: function () {
        this._super('render');
    },
    
    resetPPI: function () {

        var x = this.model.get("provider_id_c");
        if (x == "rci") {
            this.model.set({"ppi_c": ""});
            this.model.save();
        }

    },
    
    /**
     * CRED-846 : 725 - Additional Requirement
     * 
     * Toggling Saldo and Name Fremdbank when corresponding field is chaged to Edit Mode
     */
    handleEdit: function(e, cell) {
        var target,
            cellData,
            field;

        if (e) { 
            target = this.$(e.target);
            cell = target.parents('.record-cell');
        }

        cellData = cell.data();
        field = this.getField(cellData.name);

        this.inlineEditMode = true;

        this.setButtonStates(this.STATE.EDIT);
        
        this.toggleField(field);

        if (cell.closest('.headerpane').length > 0) {
            this.toggleViewButtons(true);
            this.adjustHeaderpaneFields();
        }
        
        //Toggling corresponding fields to edit mode
        if (this.fieldMapping[cellData.name]) {
            var alternate_field = this.getField(this.fieldMapping[cellData.name]);
            if (!_.isNull(alternate_field)) {
                this.toggleField(alternate_field);
            }
        }
    },
    
    populateFieldMapping: function () {
        this.fieldMapping = new Array();
        this.fieldMapping['applied_saldo'] = 'applied_name_fremdbank';
        this.fieldMapping['applied_name_fremdbank'] = 'applied_saldo';
        this.fieldMapping['approved_saldo'] = 'approved_name_fremdbank';
        this.fieldMapping['approved_name_fremdbank'] = 'approved_saldo';
        this.fieldMapping['contract_saldo'] = 'contract_name_fremdbank';
        this.fieldMapping['contract_name_fremdbank'] = 'contract_saldo';
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
        this.model.off("change:provider_id_c");
        this.model.off("change:approved_transfer_fee");
        this.model.off("change:contract_transfer_fee");
        this._super('_dispose');
    },

})