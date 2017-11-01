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
    events: {
        'change .existingAmount': 'updateExistingAmount',
        'click  .removeAmount': 'removeExistingAmount',
        'click  .addAmount': 'addNewAmount',
        'change .newAmount': 'addNewAmount',
        'click a[name=cancel_button]': 'changeToDetail',
    },
    plugins: ['ListEditable'],
    items: null,
    initialize: function (options) {
        options = options || {};
        options.def = options.def || {};
        this._super('initialize', [options]);
        
        /**
         * CRED-884 : 725 - Additional Fields Customizations
         */
        if(this.name == 'applied_saldo') {
           this.context.on('newAppliedBalance', _.bind(this.addEmptyAmountRow, this));
        }
        if(this.name == 'approved_saldo') {
           this.context.on('newApprovedBalance', _.bind(this.addEmptyAmountRow, this));
        }
        if(this.name == 'contract_saldo') {
           this.context.on('newContractBalance', _.bind(this.addEmptyAmountRow, this));
        }
        this.context.on('button:save_button:click',_.bind(this.changeToEdit,this));
    },
    bindDataChange: function () {
        this.model.on('change:' + this.name, function () {
            if (this.action !== 'edit') {
                this.render();
            }
        }, this);
    },
        
    changeToEdit : function() {
        if(this.model.get(this.name) == 'NULL') {
            this.model.set(this.name,'');
        }
        this.options.viewName = 'detail';    
        this.tplName = 'detail';    
        this._render();     
    },
    
    changeToDetail : function() {
        this.options.viewName = 'detail';  
        this.tplName = 'detail';    
        this._render();     
    },
    
    _render: function () {
        var bank = this.model.get(this.name);
        if (bank === "undefined")
            bank = "";

        if (typeof (bank) !== "undefined" && bank !== "undefined" && bank !== "" && bank !== null) {

            var bank_arr = bank.split(' , ');
            var obj = {};
            var arrayLength = bank_arr.length;
            for (var i = 0; i < arrayLength; i++) {
                var value = parseFloat(bank_arr[i]).toFixed(2);
                obj[i] = value;
            }
            this.items = obj;
        }
        
        var banksHtml = '';
        this._super("_render");

        if (this.tplName === 'edit' && !_.isEmpty(this.model.get(this.name)) ) {
            var bank = this.model.get(this.name);
            var bankArray = new Array();

            _.each(this.value, function (bank_list) {
                var bank = this.model.get(this.name);
                var bankArray = new Array();
                bankArray = bank.split(' , ');
                for (var i = 0; i < bankArray.length; i++)
                {
                    if(bankArray[i] == 'NULL'){
                        banksHtml += this._buildBankFieldHtml('');
                    } else {
                        var value = parseFloat(bankArray[i]).toFixed(2);
                        bank_list.bank = value;
                        banksHtml += this._buildBankFieldHtml(bank_list);
                    }
                    
                }
            }, this);
            this.$el.prepend(banksHtml);
        }
        
        if (this.tplName === 'edit' && _.isEmpty(this.model.get(this.name))) {
            banksHtml = '';
            var elements = '';
            if (this.name == 'approved_saldo' && this.model.get('approved_transfer_fee') === true) {
                elements = this.model.get('applied_saldo');
            }
            if (this.name == 'contract_saldo' && this.model.get('contract_transfer_fee') === true) {
                elements = this.model.get('approved_saldo');
            }
            var elementArray = new Array();
            elementArray = elements.split(' , ');
            if (elements !== '' && elements !== 'NULL') {
                for (var i = 0; i < elementArray.length; i++)
                {
                    banksHtml += this._buildBankFieldHtml(''); 
                    this. _addNewAmountToModel('NULL');
                }
                this.$el.prepend(banksHtml);
            }
            
        }
    },
    _buildBankFieldHtml: function (bank) {
        var editBankFieldTemplate = app.template.getField('multiple-balance', 'edit-multiple-balance-field'),
                banks = this.model.get(this.name),
                index = _.indexOf(banks, bank);


        return editBankFieldTemplate({
            bank: bank.bank,
        });
    },
    addNewAmount: function (evt) {    
        if (!evt)
            return;
        var bank = this.$(evt.currentTarget).val() || this.$('.newAmount').val(),
                currentValue, bankFieldHtml, $newBankField;
        bank = $.trim(bank);
        var value = parseFloat(bank).toFixed(2);
        if ((value !== '') && value != 'NaN' && (this._addNewAmountToModel(value))) {
            currentValue = this.model.get(this.name);
            bankFieldHtml = this._buildBankFieldHtml({
                bank: value,
            });
            $newBankField = this._getNewBankField().closest('.bank').before(bankFieldHtml);
                    
            /**
             * CRED-884 : 725 - Additional Fields Customizations
             */
            if(this.name == 'applied_saldo') {
                this.context.trigger('newAppliedBank');
            }
            if(this.name == 'approved_saldo') {
                this.context.trigger('newApprovedBank');
            }
            if(this.name == 'contract_saldo') {
                this.context.trigger('newContractBank');
            }
        }
        this._clearNewBankField();
        
    },
    updateExistingAmount: function (evt) {
        if (!evt)
            return;

        self = evt;

        var $inputs = this.$('.existingAmount'),
            $input = this.$(evt.currentTarget),
            index = $inputs.index($input),
            newBank = $input.val();
        newBank = $.trim(newBank);

        var oldBanks = self.currentTarget.defaultValue;
       
        if(newBank % 1 === 0 && !isNaN(newBank) && newBank !== '' ){
            newBank = parseFloat(newBank).toFixed(2);
            $input.val(newBank);       
        } else if (isNaN(newBank)) {
            $input.val('');
        }
        this._updateExistingAmountInModel(index, newBank, oldBanks);
    },
    removeExistingAmount: function (evt) {
        if (!evt)
            return;

        self = evt;

        var oldBalance = self.currentTarget.defaultValue;

        var $deleteButtons = this.$('.removeAmount'),
                $deleteButton = this.$(evt.currentTarget),
                index = $deleteButtons.index($deleteButton),
                primaryRemoved, $removeThisField;

        var $inputs = this.$('.existingAmount');
        var oldBanks = $inputs[index].value;
        if (oldBanks == '') {
            oldBanks = 'NULL';
        }      
        primaryRemoved = this._removeExistingAmountInModel(index, oldBanks);
        $removeThisField = $deleteButton.closest('.bank');
        $removeThisField.remove();
    },
    _addNewAmountToModel: function (bank) {
        var existingBanks = this.model.get(this.name) ? app.utils.deepCopy(this.model.get(this.name)) : [],
                dupeBalance = _.find(existingBanks, function (address) {
                    return (address.bank === bank);
                }),
                success = false;
        if (_.isUndefined(dupeBalance)) {
            if (existingBanks != "")
                existingBanks = existingBanks + " , " + bank;
            else
                existingBanks = bank;

            this.model.set(this.name, existingBanks);

            success = true;
        }
        return success;
    },
    _updateExistingAmountInModel: function (index, newBank, oldBalance) {
        var existingBanks = this.model.get(this.name);

        var newBalance = "";
        var addressArray = new Array();
        if (!_.isNull(existingBanks)) {
            addressArray = existingBanks.split(' , ');
        }

        if (typeof index != "undefined" && index >= 0)
            addressArray[index] = newBank;

        for (var i = 0; i < addressArray.length; i++) {
            if(!_.isUndefined(addressArray[i])  && addressArray[i] !== ''){
                if (i === addressArray.length - 1) {
                    newBalance += addressArray[i];
                } else {
                    newBalance += addressArray[i] + " , ";
                }
            } else {
                if (i === addressArray.length - 1) {
                    newBalance += 'NULL';
                } else {
                    newBalance += "NULL , ";
                }
            }
        }
        this.model.set(this.name, newBalance);
    },
    _removeExistingAmountInModel: function (index, oldBalance) {
        var existingBanks = app.utils.deepCopy(this.model.get(this.name)),
                primaryBalanceRemoved = !!existingBanks[index];

        var addressArray = new Array();
        addressArray = existingBanks.split(' , ');
        var firstBalance = addressArray[0];
        var lastBalance = addressArray[addressArray.length - 1];
        if (oldBalance === firstBalance) {
            if (addressArray.length == 1) {
                var newBalance = existingBanks.replace(oldBalance, "");
            } else {
                var newBalance = existingBanks.replace(oldBalance + " , ", "");
            }
        } else if (oldBalance === lastBalance) {
            var newBalance = existingBanks.replace(" , " + oldBalance, "");
        } else if(index < addressArray.length){
            var newBalance = existingBanks.replace(oldBalance + " , ", "");
        } else {
            var newBalance = existingBanks;
        }
        if (newBalance === '' || newBalance === 'NULL') {
            this.model.set(this.name, '');
            this.items = null;

        } else {
            this.model.set(this.name, newBalance);
        }
        
        return primaryBalanceRemoved;
    },
    _clearNewBankField: function () {
        this._getNewBankField().val('');
    },
    _getNewBankField: function () {
        return this.$('.newAmount');
    },
    bindDomChange: function () {
        if (this.tplName === 'list-edit') {
            this._super("bindDomChange");
        }
    },
    format: function (value) {
        var value_copy = app.utils.deepCopy(value);
        if ((_.isString(value) && value !== "") || this.view.action === 'list') {
            value = [{
                    new_bank: value_copy,
                }];
        }
        return value;
    },
    unformat: function (value) {
        if (this.view.action === 'list') {
            var banks = app.utils.deepCopy(this.model.get(this.name));
            if (!_.isArray(banks)) {
                banks = [];
            }
        }
    },
    focus: function () {
        if (this.action !== 'disabled') {
            this._getNewBankField().focus();
        }
    },
    
    /**
     * CRED-884 : 725 - Additional Fields Customizations
     */
    addEmptyAmountRow: function (param) {    
        this.fieldMapping = new Array();
        this.fieldMapping['applied_saldo'] = 'applied_name_fremdbank';
        this.fieldMapping['applied_name_fremdbank'] = 'applied_saldo';
        this.fieldMapping['approved_saldo'] = 'approved_name_fremdbank';
        this.fieldMapping['approved_name_fremdbank'] = 'approved_saldo';
        this.fieldMapping['contract_saldo'] = 'contract_name_fremdbank';
        this.fieldMapping['contract_name_fremdbank'] = 'contract_saldo';
        
        if(!_.isEmpty(this.model.get(this.fieldMapping[this.name]))) {
            var currentValue, bankFieldHtml, $newBankField;
            if (this._addNewAmountToModel('NULL')) {
                currentValue = this.model.get(this.name);
                bankFieldHtml = this._buildBankFieldHtml({
                    bank: ' ',
                });
                $newBankField = this._getNewBankField().closest('.bank').before(bankFieldHtml);
            }
            this._clearNewBankField();
            this.options.viewName = 'edit';   
            this._render(); 
        }
    },
    _dispose: function() {
        this._super('_dispose');
        if (this.context) {
            this.context.off('newAppliedBalance');
            this.context.off('newApprovedBalance');
            this.context.off('newContractBalance');
            this.context.off('button:save_button:click');
        }
    }
})