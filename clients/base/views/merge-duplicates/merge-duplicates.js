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
    extendsFrom: 'MergeDuplicatesView',
    relatedPartner: null,
    relatedLeads: null,
    relatedContact: null,
    initialize: function (options) {
        this._super('initialize', [options]);
    },
    /*_prepareRecords: function () {
     var records = this._validateModelsForMerge(this.context.get('selectedDuplicates'));
     for (var key in records) {
     var model = records[key];
     
     console.log('key');
     console.log(model.get('id'));
     console.log(model.get('_module'));
     }
     console.log('records');
     console.log(records);
     this.setPrimaryRecord(this._findPrimary(records));
     return records;
     },*/
    _renderHtml: function () {
        this._super("_renderHtml");
        var self = this;
        var fn = '';
        var all = this.collection.models;
        var length = this.collection.length;
        if (length > 0) {
            _.each(all[0].fields, function (field) {
                fn = field.name;
                if (length == 2) {
                    if (typeof all[0].fields[fn] != 'undefined' && all[0].get(fn) != 'undefined' && self.areEqual(all[0].get(fn), all[1].get(fn))) {
                        if (!(all[0].fields[fn]['required'] == true && all[0].get(fn) == '')) {
                            $('[name="copy_' + fn + '"]').parent().parent().hide();
                            $('.lbl_' + fn).hide();
                        }
                    }
                } else if (length == 3) {
                    if (typeof all[0].fields[fn] != 'undefined' && all[0].get(fn) != 'undefined' && self.areEqual(all[0].get(fn), all[1].get(fn), all[2].get(fn))) {
                        if (!(all[0].fields[fn]['required'] == true && all[0].get(fn) == '')) {
                            $('[name="copy_' + fn + '"]').parent().parent().hide();
                            $('.lbl_' + fn).hide();
                        }
                    }
                } else if (length == 4) {
                    if (typeof all[0].fields[fn] != 'undefined' && all[0].get(fn) != 'undefined' && self.areEqual(all[0].get(fn), all[1].get(fn), all[2].get(fn), all[3].get(fn))) {
                        if (!(all[0].fields[fn]['required'] == true && all[0].get(fn) == '')) {
                            $('[name="copy_' + fn + '"]').parent().parent().hide();
                            $('.lbl_' + fn).hide();
                        }
                    }
                } else if (length == 5) {
                    if (typeof all[0].fields[fn] != 'undefined' && all[0].get(fn) != 'undefined' && self.areEqual(all[0].get(fn), all[1].get(fn), all[2].get(fn), all[3].get(fn), all[4].get(fn))) {
                        if (!(all[0].fields[fn]['required'] == true && all[0].get(fn) == '')) {
                            $('[name="copy_' + fn + '"]').parent().parent().hide();
                            $('.lbl_' + fn).hide();
                        }
                    }
                }
            }, this);

            /*
             * Hiding Duplicate Fields
             */
            var duplicate_fields = ['relative_type_c', 'dotb_correspondence_language', 'dotb_gender_id', 'birthdate', 'dotb_iso_nationality_code', 'dotb_work_permit_since', 'dotb_work_permit_until', 'dotb_employment_type_id', 'dotb_employer_name', 'dotb_employer_npa', 'dotb_employer_town', 'dotb_employed_since', 'dotb_monthly_net_income', 'dotb_monthly_gross_income', 'dotb_has_thirteenth_salary'];
            for (var l = 0; l < length; l++) {
                for (var i = 0; i < duplicate_fields.length; i++) {
                    $('[data-record-id="' + all[l].get('id') + '"] [name="copy_' + duplicate_fields[i] + '"]:first').parent().parent().hide();
                    $('.lbl_' + duplicate_fields[i] + ':first').hide();
                }
            }
        }
        /*
         * Checking for Email Address
         */
        if (length == 2) {
            var email0 = all[0].get('email');
            var email1 = all[1].get('email');
            if (self.areEqual(email0.length, email1.length)) {
                var same_emails = 0;
                var i = 0;
                for (i; i < email0.length; i++) {
                    if (self.areEqual(email0[i]['email_address'], email1[i]['email_address'])) {
                        same_emails = same_emails + 1;
                    }
                }
                if (email0.length == same_emails) {
                    $('[name="copy_email"]').parent().parent().hide();
                    $('.lbl_email').hide();
                }
            }
        } else if (length == 3) {
            var email0 = all[0].get('email');
            var email1 = all[1].get('email');
            var email2 = all[2].get('email');
            if (self.areEqual(email0.length, email1.length, email2.length)) {
                var same_emails = 0;
                var i = 0;
                for (i; i < email0.length; i++) {
                    if (self.areEqual(email0[i]['email_address'], email1[i]['email_address'], email2[i]['email_address'])) {
                        same_emails = same_emails + 1;
                    }
                }
                if (email0.length == same_emails) {
                    $('[name="copy_email"]').parent().parent().hide();
                    $('.lbl_email').hide();
                }
            }
        } else if (length == 4) {
            var email0 = all[0].get('email');
            var email1 = all[1].get('email');
            var email2 = all[2].get('email');
            var email3 = all[3].get('email');
            if (self.areEqual(email0.length, email1.length, email2.length, email3.length)) {
                var same_emails = 0;
                var i = 0;
                for (i; i < email0.length; i++) {
                    if (self.areEqual(email0[i]['email_address'], email1[i]['email_address'], email2[i]['email_address'], email3[i]['email_address'])) {
                        same_emails = same_emails + 1;
                    }
                }
                if (email0.length == same_emails) {
                    $('[name="copy_email"]').parent().parent().hide();
                    $('.lbl_email').hide();
                }
            }
        } else if (length == 5) {
            var email0 = all[0].get('email');
            var email1 = all[1].get('email');
            var email2 = all[2].get('email');
            var email3 = all[3].get('email');
            var email4 = all[4].get('email');
            if (self.areEqual(email0.length, email1.length, email2.length, email3.length, email4.length)) {
                var same_emails = 0;
                var i = 0;
                for (i; i < email0.length; i++) {
                    if (self.areEqual(email0[i]['email_address'], email1[i]['email_address'], email2[i]['email_address'], email3[i]['email_address'], email4[i]['email_address'])) {
                        same_emails = same_emails + 1;
                    }
                }
                if (email0.length == same_emails) {
                    $('[name="copy_email"]').parent().parent().hide();
                    $('.lbl_email').hide();
                }
            }
        }

        $('[name="copy_picture"]').parent().parent().hide();
        $('.lbl_picture').hide();
    },
    areEqual: function () {
        var len = arguments.length;
        for (var i = 1; i < len; i++) {
            if (arguments[i] != arguments[i - 1])
                return false;
        }
        return true;
    },
    haveMatchingElements: function (firstArray, secondArray) {
        var stringsInFirstArray = parse(firstArray, 'string'),
                stringsInSecondArray = parse(secondArray, 'string'),
                numbersInFirstArray = parse(firstArray, 'number'),
                numbersInSecondArray = parse(secondArray, 'number'),
                stringResults = compare(stringsInFirstArray, stringsInSecondArray),
                numberResults = compare(numbersInFirstArray, numbersInSecondArray);

        if (stringResults && numberResults) {
            return true;
        }
        return false;

        function parse(array, type) {
            var arr = [];
            arr = array.sort().filter(function (index) {
                if (typeof index == type)
                    return index;
            });
            return arr;
        }

        function compare(firstArray, secondArray) {
            if (firstArray.length !== secondArray.length)
                return false;
            for (var i = firstArray.length; i--; ) {
                if (firstArray[i] !== secondArray[i])
                    return false;
            }
            return true;
        }
    }
})