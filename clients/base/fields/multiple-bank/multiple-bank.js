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
        'change .existingBank': 'updateExistingBank',
        'click  .removeBank': 'removeExistingBank',
        'click  .addBank': 'addNewBank',
        'change .newBank': 'addNewBank',
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
        if(this.name == 'applied_name_fremdbank') {
            this.context.on('newAppliedBank', _.bind(this.addEmptyBankRow, this));
        }
        if(this.name == 'approved_name_fremdbank') {
            this.context.on('newApprovedBank', _.bind(this.addEmptyBankRow, this));
        }
        if(this.name == 'contract_name_fremdbank') {
            this.context.on('newContractBank', _.bind(this.addEmptyBankRow, this));
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
                obj[i] = bank_arr[i];
            }
            this.items = obj;
        }

        var banksHtml = '';
        this._super("_render");

        if (this.tplName === 'edit' && !_.isEmpty(this.model.get(this.name))) {
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
                        bank_list.bank = bankArray[i];
                        banksHtml += this._buildBankFieldHtml(bank_list);
                    }
                }
            }, this);
            this.$el.prepend(banksHtml);
        }
        
        if (this.tplName === 'edit' && _.isEmpty(this.model.get(this.name))) {
            banksHtml = '';
            var elements = '';
            if (this.name == 'approved_name_fremdbank' && this.model.get('approved_transfer_fee') === true) {
                elements = this.model.get('applied_name_fremdbank');
            }
            if (this.name == 'contract_name_fremdbank' && this.model.get('contract_transfer_fee') === true) {
                elements = this.model.get('approved_name_fremdbank');
            }
            var elementArray = new Array();
            elementArray = elements.split(' , ');
            if (elements !== '' && elements !== 'NULL') {
                for (var i = 0; i < elementArray.length; i++)
                {
                    banksHtml += this._buildBankFieldHtml('');
                    this. _addNewBankToModel('NULL');
                }
                this.$el.prepend(banksHtml);
            }
        }

    },
    _buildBankFieldHtml: function (bank) {
        var editBankFieldTemplate = app.template.getField('multiple-bank', 'edit-multiple-bank-field'),
        banks = this.model.get(this.name),
        index = _.indexOf(banks, bank);


        return editBankFieldTemplate({
            bank: bank.bank,
        });
    },
    addNewBank: function (evt) {
        if (!evt)
            return;
        var bank = this.$(evt.currentTarget).val() || this.$('.newBank').val(),
               currentValue, bankFieldHtml, $newBankField;
        bank = $.trim(bank);
        if ((bank !== '') && (this._addNewBankToModel(bank))) {
            currentValue = this.model.get(this.name);
            bankFieldHtml = this._buildBankFieldHtml({
                bank: bank,
            });
            $newBankField = this._getNewBankField().closest('.bank').before(bankFieldHtml);
                    
            /**
             * CRED-884 : 725 - Additional Fields Customizations
             */
            if(this.name == 'applied_name_fremdbank') {
                this.context.trigger('newAppliedBalance');
            }
            if(this.name == 'approved_name_fremdbank') {
                this.context.trigger('newApprovedBalance');
            }
            if(this.name == 'contract_name_fremdbank') {
                this.context.trigger('newContractBalance');
            }
        }
        this._clearNewBankField();

    },
    updateExistingBank: function (evt) {
        if (!evt)
            return;

        self = evt;

        var $inputs = this.$('.existingBank'),
        $input = this.$(evt.currentTarget),
        index = $inputs.index($input),
        newBank = $input.val();
        newBank = $.trim(newBank);

        var oldBanks = self.currentTarget.defaultValue;

        this._updateExistingBankInModel(index, newBank, oldBanks);
    },
    removeExistingBank: function (evt) {
        if (!evt)
            return;

        self = evt;

        var oldAddress = self.currentTarget.defaultValue;

        var $deleteButtons = this.$('.removeBank'),
                $deleteButton = this.$(evt.currentTarget),
                index = $deleteButtons.index($deleteButton),
                primaryRemoved, $removeThisField;

        var $inputs = this.$('.existingBank');
        var oldBanks = $inputs[index].value;
        if (oldBanks == '') {
            oldBanks = 'NULL';
        }       
        if (oldBanks !== '') {
            primaryRemoved = this._removeExistingBankInModel(index, oldBanks);
        }
        
        $removeThisField = $deleteButton.closest('.bank');
        $removeThisField.remove();
    },
    _addNewBankToModel: function (bank) {
        var existingBanks = this.model.get(this.name) ? app.utils.deepCopy(this.model.get(this.name)) : [],
                dupeAddress = _.find(existingBanks, function (address) {
                    return (address.bank === bank);
                }),
                success = false;
        if (_.isUndefined(dupeAddress)) {
            if (existingBanks != "")
                existingBanks = existingBanks + " , " + bank;
            else
                existingBanks = bank;

            this.model.set(this.name, existingBanks);

            success = true;
        }
        return success;
    },
    _updateExistingBankInModel: function (index, newBank, oldAddress) {
        var existingBanks = this.model.get(this.name);

        var newAddress = "";
        var addressArray = new Array();
        if (!_.isNull(existingBanks)) {
             addressArray = existingBanks.split(' , ');
        }

        if (typeof index != "undefined" && index >= 0)
            addressArray[index] = newBank;

        for (var i = 0; i < addressArray.length; i++) {
            if(!_.isUndefined(addressArray[i])  && addressArray[i] !== ''){
                if (i === addressArray.length - 1) {
                    newAddress += addressArray[i];
                } else {
                    newAddress += addressArray[i] + " , ";
                }
            } else {
                if (i === addressArray.length - 1) {
                    newAddress += 'NULL';
                } else {
                    newAddress += "NULL , ";
                }
            }
        }
        newAddress = newAddress.replace(/,\s*$/, "");
        this.model.set(this.name, newAddress);
    },
    _removeExistingBankInModel: function (index, oldAddress) {
        var existingBanks = app.utils.deepCopy(this.model.get(this.name)),
                primaryAddressRemoved = !!existingBanks[index];

        var addressArray = new Array();
        addressArray = existingBanks.split(' , ');
        var firstAddress = addressArray[0];
        var lastAddress = addressArray[addressArray.length - 1];

        if (oldAddress === firstAddress) {
            if (addressArray.length == 1) {
                var newAddress = existingBanks.replace(oldAddress, "");
            } else {
                var newAddress = existingBanks.replace(oldAddress + " , ", "");
            }
        } else if (oldAddress === lastAddress) {
            var newAddress = existingBanks.replace(" , " + oldAddress, "");
        } else if(index < addressArray.length) {
             var newAddress = existingBanks.replace(oldAddress + " , ", "");
        } else {
            var newAddress = existingBanks;
        }

        if(newAddress === ''){
             this.model.set(this.name, '');
             this.items = null;
            
        } else {
             this.model.set(this.name, newAddress);
        }
        
        return primaryAddressRemoved;
    },
    _clearNewBankField: function () {
        this._getNewBankField().val('');
    },
    _getNewBankField: function () {
        return this.$('.newBank');
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
    addEmptyBankRow: function (param) {
        this.fieldMapping = new Array();
        this.fieldMapping['applied_saldo'] = 'applied_name_fremdbank';
        this.fieldMapping['applied_name_fremdbank'] = 'applied_saldo';
        this.fieldMapping['approved_saldo'] = 'approved_name_fremdbank';
        this.fieldMapping['approved_name_fremdbank'] = 'approved_saldo';
        this.fieldMapping['contract_saldo'] = 'contract_name_fremdbank';
        this.fieldMapping['contract_name_fremdbank'] = 'contract_saldo';

        if(!_.isEmpty(this.model.get(this.fieldMapping[this.name]))) {
            var currentValue, bankFieldHtml, $newBankField;
            if (this._addNewBankToModel('NULL')) {
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
            this.context.off('newAppliedBank');
            this.context.off('newApprovedBank');
            this.context.off('newContractBank');
            this.context.off('button:save_button:click');
        }

    }
})
