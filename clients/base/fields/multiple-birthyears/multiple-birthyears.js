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
        'change .existingAddress': 'updateExistingAddress',
        'click  .btn-edit': 'toggleExistingAddressProperty',
        'click  .removeEmail': 'removeExistingAddress',
        'click  .addEmail': 'addNewAddress',
        'change .newEmail': 'addNewAddress'
    },
    _flag2Deco: {
        primary_address: {
            lbl: "LBL_EMAIL_PRIMARY",
            cl: "primary"
        },
        opt_out: {
            lbl: "LBL_EMAIL_OPT_OUT",
            cl: "opted-out"
        },
        invalid_email: {
            lbl: "LBL_EMAIL_INVALID",
            cl: "invalid"
        }
    },
    
    plugins: ['ListEditable', 'EmailClientLaunch'],
    items: null,

    initialize: function(options) {
        options = options || {};
        options.def = options.def || {};
        if (_.isUndefined(options.def.emailLink)) {
            options.def.emailLink = true;
        }
        if (options.model && options.model.fields && options.model.fields.email1 && options.model.fields.email1.required) {
            options.def.required = options.model.fields.email1.required;
        }
        this._super('initialize', [options]);
        this.addEmailOptions({
            related: this.model
        });

    },
    
    bindDataChange: function() {
        this.model.on('change:' + this.name, function() {
            if (this.action !== 'edit') {
                this.render();
            }
        }, this);
    },
    
    _render: function() {
	var address = this.model.get(this.name);
        if(address === "undefined") address = "";

        if(typeof(address) !== "undefined" && address!=="undefined" && address !== "" && address !== null) {

                var Emailarr = address.split('-*#*-');
                var obj = {};
                var arrayLength = Emailarr.length;
                for (var i = 0; i < arrayLength; i++) {
                        obj[i] = Emailarr[i];
                }
                this.items = obj;
        }

        var emailsHtml = '';
        this._super("_render");

        if (this.tplName === 'edit') {
            var address = this.model.get(this.name);
            var addressArray = new Array();
            if (typeof (address) !== "undefined" && address !== "undefined" && address !== "" && address !== null) {
                addressArray = address.split('-*#*-');
            }

            _.each(this.value, function (email) {
                var address = this.model.get(this.name);
                var addressArray = new Array();
                addressArray = address.split('-*#*-');
                for (var i = 0; i < addressArray.length; i++)
                {
                    email.email_address = addressArray[i];

                    emailsHtml += this._buildEmailFieldHtml(email);
                }
            }, this);
            this.$el.prepend(emailsHtml);
        }
    },
	
    getUserDateFormat: function () {
        return app.user.getPreference('datepref');
    },
	
    _patchPickerMeta: function () {
        var pickerMap = [],
                pickerMapKey, calMapIndex, mapLen, domCalKey, calProp, appListStrings, calendarPropsMap, i, filterIterator;
        appListStrings = app.metadata.getStrings('app_list_strings');
        filterIterator = function (v, k, l) {
            return v[1] !== "";
        };
        calendarPropsMap = ['dom_cal_day_long', 'dom_cal_day_short', 'dom_cal_month_long', 'dom_cal_month_short'];
        for (calMapIndex = 0, mapLen = calendarPropsMap.length; calMapIndex < mapLen; calMapIndex++) {
            domCalKey = calendarPropsMap[calMapIndex];
            calProp = appListStrings[domCalKey];
            if (!_.isUndefined(calProp) && !_.isNull(calProp)) {
                calProp = _.filter(calProp, filterIterator).map(function (prop) {
                    return prop[1];
                });
                calProp.push(calProp);
            }
            switch (calMapIndex) {
                case 0:
                    pickerMapKey = 'day';
                    break;
                case 1:
                    pickerMapKey = 'daysShort';
                    break;
                case 2:
                    pickerMapKey = 'months';
                    break;
                case 3:
                    pickerMapKey = 'monthsShort';
                    break;
            }
            pickerMap[pickerMapKey] = calProp;
        }
        return pickerMap;
    },
	
    _getAppendToTarget: function () {
        var component = this.closestComponent('main-pane') || this.closestComponent('drawer');
        if (component) {
            return component.$el;
        }
        return;
    },
    
    _setupDatePicker: function () {
        var $field = this.$(this.fieldTag),
                userDateFormat = this.getUserDateFormat(),
                options = {
                    format: app.date.toDatepickerFormat(userDateFormat),
                    languageDictionary: this._patchPickerMeta(),
                    weekStart: parseInt(app.user.getPreference('first_day_of_week'), 10)
                };
        var appendToTarget = this._getAppendToTarget();
        if (appendToTarget) {
            options['appendTo'] = appendToTarget;
        }
        $field.datepicker(options);
    },
	
    _buildEmailFieldHtml: function (email) {
        var editEmailFieldTemplate = app.template.getField('multiple-birthyears', 'edit-email-field'),
                emails = this.model.get(this.name),
                index = _.indexOf(emails, email);

        return editEmailFieldTemplate({
            max_length: this.def.len,
            index: index === -1 ? emails.length - 1 : index,
            email_address: email.email_address,
            primary_address: email.primary_address,
            opt_out: email.opt_out,
            invalid_email: email.invalid_email
        });
    },
    
    addNewAddress: function(evt) {
        if (!evt) return;
        var email = this.$(evt.currentTarget).val() || this.$('.newEmail').val(),
            currentValue, emailFieldHtml, $newEmailField;
        email = $.trim(email);
        if ((email !== '') && (this._addNewAddressToModel(email))) {
            currentValue = this.model.get(this.name);
            emailFieldHtml = this._buildEmailFieldHtml({
                email_address: email,
                primary_address: currentValue && (currentValue.length === 1),
                opt_out: false,
                invalid_email: false
            });
            $newEmailField = this._getNewEmailField().closest('.email').before(emailFieldHtml);
            if (this.def.required && this._shouldRenderRequiredPlaceholder()) {
                var label = app.lang.get('LBL_REQUIRED_FIELD', this.module),
                    el = this.$(this.fieldTag).last(),
                    placeholder = el.prop('placeholder').replace('(' + label + ') ', '');
                el.prop('placeholder', placeholder.trim()).removeClass('required');
            }
        }
        this._clearNewAddressField();
    },
    
    updateExistingAddress: function (evt) {
        if (!evt)
            return;

        self = evt;

        var $inputs = this.$('.existingAddress'),
                $input = this.$(evt.currentTarget),
                index = $inputs.index($input),
                newEmail = $input.val(),
                primaryRemoved;
        newEmail = $.trim(newEmail);

        var oldAddress = self.currentTarget.defaultValue;


        if (newEmail === '') {
            primaryRemoved = this._removeExistingAddressInModel(index, oldAddress);
            $input.closest('.email').remove();
            if (primaryRemoved) {
                this.$('[data-emailproperty=primary_address]').first().addClass('active');
            }
        } else {
            this._updateExistingAddressInModel(index, newEmail, oldAddress);
        }
    },
    
    removeExistingAddress: function (evt) {
        if (!evt)
            return;

        self = evt;

        var oldAddress = self.currentTarget.defaultValue;

        var $deleteButtons = this.$('.removeEmail'),
                $deleteButton = this.$(evt.currentTarget),
                index = $deleteButtons.index($deleteButton),
                primaryRemoved, $removeThisField;

        var $inputs = this.$('.existingAddress');
        var oldAddress = $inputs[index].value;

        primaryRemoved = this._removeExistingAddressInModel(index, oldAddress);
        $removeThisField = $deleteButton.closest('.email');
        $removeThisField.remove();
        if (primaryRemoved) {
            this.$('[data-emailproperty=primary_address]').first().addClass('active');
        }
        if (this.def.required && _.isEmpty(this.model.get(this.name))) {
            this.decorateRequired();
        }
    },
    
    toggleExistingAddressProperty: function(evt) {
        if (!evt) return;
        var $property = this.$(evt.currentTarget),
            property = $property.data('emailproperty'),
            $properties = this.$('[data-emailproperty=' + property + ']'),
            index = $properties.index($property);
        if (property === 'primary_address') {
            $properties.removeClass('active');
        }
        this._toggleExistingAddressPropertyInModel(index, property);
    },
    _addNewAddressToModel: function (email) {
        var existingAddresses = this.model.get(this.name) ? app.utils.deepCopy(this.model.get(this.name)) : [],
                dupeAddress = _.find(existingAddresses, function (address) {
                    return (address.email_address === email);
                }),
                success = false;
        if (_.isUndefined(dupeAddress)) {

            if (existingAddresses != "")
                existingAddresses = existingAddresses + "-*#*-" + email;
            else
                existingAddresses = email;

            this.model.set(this.name, existingAddresses);
            success = true;
        }
        return success;
    },
    
    _updateExistingAddressInModel: function (index, newEmail, oldAddress) {
        var existingAddresses = this.model.get(this.name);
        var newAddress = "";
        var addressArray = new Array();
        addressArray = existingAddresses.split('-*#*-');

        if (typeof index != "undefined" && index >= 0)
            addressArray[index] = newEmail;

        for (var i = 0; i < addressArray.length; i++)
        {


            if (i === addressArray.length - 1)
            {
                newAddress += addressArray[i];
            }
            else
            {
                newAddress += addressArray[i] + "-*#*-";
            }
        }



        this.model.set(this.name, newAddress);
    },
    
    _toggleExistingAddressPropertyInModel: function(index, property) {
        var existingAddresses = app.utils.deepCopy(this.model.get(this.name));
        if (property === 'primary_address') {
            existingAddresses[index][property] = false;
            _.each(existingAddresses, function(email, i) {
                if (email[property]) {
                    existingAddresses[i][property] = false;
                }
            });
        }
        if (existingAddresses[index][property]) {
            existingAddresses[index][property] = false;
        } else {
            existingAddresses[index][property] = true;
        }
        this.model.set(this.name, existingAddresses);
    },
    _removeExistingAddressInModel: function (index, oldAddress) {
        var existingAddresses = app.utils.deepCopy(this.model.get(this.name)),
                primaryAddressRemoved = !!existingAddresses[index]['primary_address'];

        if (primaryAddressRemoved) {
            var address = _.first(existingAddresses);
            if (address) {
                address.primary_address = true;
            }
        }



        var addressArray = new Array();
        addressArray = existingAddresses.split('-*#*-');

        var firstAddress = addressArray[0];
        var lastAddress = addressArray[addressArray.length - 1];

        if (oldAddress === firstAddress)
        {
            if (addressArray.length == 1)
            {
                var newAddress = existingAddresses.replace(oldAddress, "");
            }
            else {
                var newAddress = existingAddresses.replace(oldAddress + "-*#*-", "");
            }
        }
        else if (oldAddress === lastAddress)
        {
            var newAddress = existingAddresses.replace("-*#*-" + oldAddress, "");
        }
        else
        {
            var newAddress = existingAddresses.replace(oldAddress + "-*#*-", "");
        }

        this.model.set(this.name, newAddress);
        return primaryAddressRemoved;
    },
    
    _clearNewAddressField: function() {
        this._getNewEmailField().val('');
    },
    
    _getNewEmailField: function() {
        return this.$('.newEmail');
    },
    
    decorateError: function (errors) {
        var emails;
        this.$el.closest('.record-cell').addClass("error");
        emails = this.$('input:not(.newEmail)');
        _.each(errors, function (errorContext, errorName) {
            if (errorName === 'email' || errorName === 'duplicateEmail') {
                _.each(emails, function (e) {
                    var $email = this.$(e),
                            email = $email.val();
                    var isError = _.find(errorContext, function (emailError) {
                        return emailError === email;
                    });
                    if (!_.isUndefined(isError)) {
                        this._addErrorDecoration($email, errorName, [isError]);
                    }
                }, this);
            } else {
                var $email = this.$('input:first');
                this._addErrorDecoration($email, errorName, errorContext);
            }
        }, this);
    },
    
    _addErrorDecoration: function($input, errorName, errorContext) {
        var isWrapped = $input.parent().hasClass('input-append');
        if (!isWrapped)
            $input.wrap('<div class="input-append error ' + this.fieldTag + '">');
        $input.next('.error-tooltip').remove();
        $input.after(this.exclamationMarkTemplate([app.error.getErrorString(errorName, errorContext)]));
    },
    
    bindDomChange: function() {
        if (this.tplName === 'list-edit') {
            this._super("bindDomChange");
        }
    },
    
    format: function(value) {
        value = app.utils.deepCopy(value);
        if (_.isArray(value) && value.length > 0) {
            _.each(value, function(email) {
                email.hasAnchor = this.def.emailLink && !email.opt_out && !email.invalid_email;
            }, this);
        } else if ((_.isString(value) && value !== "") || this.view.action === 'list') {
            value = [{
                email_address: value,
                primary_address: true,
                hasAnchor: true
            }];
        }
        value = this.addFlagLabels(value);
        return value;
    },
    
    addFlagLabels: function(value) {
        var flagStr = "",
            flagArray;
        _.each(value, function(emailObj) {
            flagStr = "";
            flagArray = _.map(emailObj, function(flagValue, key) {
                if (!_.isUndefined(this._flag2Deco[key]) && this._flag2Deco[key].lbl && flagValue) {
                    return app.lang.get(this._flag2Deco[key].lbl);
                }
            }, this);
            flagArray = _.without(flagArray, undefined);
            if (flagArray.length > 0) {
                flagStr = flagArray.join(", ");
            }
            emailObj.flagLabel = flagStr;
        }, this);
        return value;
    },
    
    unformat: function(value) {
        if (this.view.action === 'list') {
            var emails = app.utils.deepCopy(this.model.get(this.name));
            if (!_.isArray(emails)) {
                emails = [];
            }
            emails = _.map(emails, function(email) {
                if (email.primary_address && email.email_address !== value) {
                    email.email_address = value;
                }
                return email;
            }, this);
            if (emails.length == 0) {
                emails.push({
                    email_address: value,
                    primary_address: true
                });
            }
            return emails;
        }
    },
    
    focus: function() {
        if (this.action !== 'disabled') {
            this._getNewEmailField().focus();
        }
    },
    
    _retrieveEmailOptionsFromLink: function($link) {
        return {
            to_addresses: [{
                email: $link.data('email-to'),
                bean: this.emailOptions.related
            }]
        };
    }
})
