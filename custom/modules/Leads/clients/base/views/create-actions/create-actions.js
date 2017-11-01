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
    initialize: function (options) {
        var self = this;
        this._super('initialize', [options]);
        this.initializeSequenceNumbers();
        //this.model.on("change:dotb_iso_nationality_code_c", self.setWorkprmit, self);
        app.error.errorName2Keys['field_error'] = 'Please select sub status';
        this.model.addValidationTask('check_field', _.bind(this._doValidateStatus, this));
        this.model.on("change:dotb_gender_id_c", this.changeSalutation, this);
        this.model.on("change:dotb_civil_status_id_c", this.changeSalutation, this);
        this.model.on("change:dotb_correspondence_language_c", this.changeSalutation, this);
    },
     _doValidateStatus: function(fields, errors, callback) {
        var self = this;
        if (this.model.get('credit_request_status_id_c') == '11_closed' && (this.model.get('credit_request_substatus_id_c') == '' || this.model.get('credit_request_substatus_id_c') == null)) {
            errors['credit_request_substatus_id_c'] = errors['credit_request_substatus_id_c'] || {};
            errors['credit_request_substatus_id_c'].field_error = true;
        }
        callback(null, fields, errors);
    },
    setWorkprmit: function () {
        var nationality = this.model.get('dotb_iso_nationality_code_c');
        if (nationality == 'CH') {
            this.model.set('dotb_work_permit_type_id_c', ' ');
        }else{
            this.model.set('dotb_work_permit_type_id_c', '');
        }
    },
     _dispose: function() {
        this.model.off("change:dotb_gender_id_c");
        this.model.off("change:dotb_civil_status_id_c");
        this.model.off("change:dotb_correspondence_language_c");
        this._super('_dispose');
     },
    changeSalutation: function (e) {
        var self = this;
        var salutationMapping = {
            "de_" : "sehr_geehrter_herr",
            "de_m" : "sehr_geehrter_herr",
            "de_f" : "sehr_geehrte_frau",
            "en_" : "dear_mr",
            "en_m_" : "dear_mr",
            "en_f_" : "dear_mrs",
            "en_m" : "dear_mr",
            "en_f" : "dear_mrs",
            "it_" : "egregio_signor",
            "it_m" : "egregio_signor",
            "it_f" : "egregia_signora",
            "fr_" : "monsieur",
            "fr_m" : "monsieur",
            "fr_f" : "madame",
        }
        var key = self.model.get("dotb_correspondence_language_c") + "_" + self.model.get("dotb_gender_id_c");
        self.model.set("salutation", salutationMapping[key]);
        
        /*if(self.model.get("dotb_correspondence_language_c") == "en"){
            if(self.model.get("dotb_civil_status_id_c") == 'married' && self.model.get("dotb_gender_id_c") == 'f'){
                self.model.set("salutation", "dear_mrs");
            }
            else{
                var key = self.model.get("dotb_correspondence_language_c") + "_" + self.model.get("dotb_gender_id_c") + '_';
                if(salutationMapping[key]){
                    self.model.set("salutation", salutationMapping[key]);
                }
            }
        }*/
    },
    getCustomSaveOptions: function () {
        var options = {};
        if (this.context.get('prospect_id')) {
            options.params = {};
            options.params.relate_to = 'Prospects';
            options.params.relate_id = this.context.get('prospect_id');
            this.context.unset('prospect_id');
        }
        return options;
    },
    // initializeSequenceNumbers
    initializeSequenceNumbers: function () {
        if (this.model.get('id') === undefined || this.model.get('id') == '') {
            self = this;
            var url = app.api.buildURL('Leads/get_lead_sequence_number', null, null, null);

            app.api.call("GET", url, null, {
                success: function (data) {
                    self.model.set('reference_number_c', data.reference_number);
                    self.model.set('credit_request_number_c',
                            data.credit_request_number);
                }
            });
        }

    },
    // Overloading the save function to recalculate the numbers at the last moment
    // (In case other records have been created in the meantime)
    save: function () {
        this.initializeSequenceNumbers();

        switch (this.context.lastSaveAction) {
            case this.SAVEACTIONS.SAVE_AND_CREATE:
                this.saveAndCreate();
                break;
            case this.SAVEACTIONS.SAVE_AND_VIEW:
                this.saveAndView();
                break;
            default:
                this.saveAndClose();
        }
    },
})