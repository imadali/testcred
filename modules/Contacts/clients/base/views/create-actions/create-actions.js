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
        this._super('initialize', [options]);
        //for lead qualification rule
        this.model.addValidationTask('Custom Validation Logic', _.bind(this._requiredFieldsForPartner, this));

        this.model.on("change:dotb_gender_id", this.changeSalutation, this);
        this.model.on("change:dotb_civil_status_id", this.changeSalutation, this);
        this.model.on("change:dotb_correspondence_language", this.changeSalutation, this);
        this.model.on("change:dotb_iso_nationality_code", this.hideWorkPermit, this);
        this.model.on("change:birthdate", this.ageCalculation, this);
        //this.model.on("change:relative_type_c", this.toggleRecordPanel, this);
        //Function Binding for Auto-populating  country from postal code
        this.events['blur input[name=primary_address_postalcode]'] = 'populateAddress';
        this.events['blur input[name=correspondence_address_postalcode]'] = 'populateCorrespondenceAddress';
        this.events['blur input[name=dotb_bank_zip_code]'] = 'populateBankAddress';
        this.events['blur input[name=dotb_employer_npa]'] = 'populateEmployerAddress';
        this.events['blur input[name=dotb_second_job_employer_npa]'] = 'populateSecondEmployerAddress';
        
        /**
         * CRED-1044 : Assigned to user missing when contact created by a non admin user
         */
        /*if (app.user.attributes.type != "admin") {
            _.each(this.meta.panels, function (panel) {
                _.each(panel.fields, function (field) {
                    if (field.name == "assigned_user_name") {
                        field.readonly = true;
                    }
                }, this);
            }, this);
        }*/
    },  
    setRelative: function () {
        if (typeof this.model.link !== 'undefined') {
            if (this.model.link.name == 'leads_contacts_1' || this.model.link.name == 'contacts_contacts_1') {
                this.model.set('relative_type_c', 'partner');
                // this.model.set('relative_type_dup_c','partner');
                $(".tab .LBL_RECORDVIEW_PANEL31").find('a').trigger('click');
                //setting lead address to partner address 
                var self = this;
                var lead = app.data.createBean('Leads', {id: self.model.link.bean.id});

                lead.fetch({
                    success: function () {
                        // copy Primary Address
                        self.model.set({"address_c_o": lead.get('address_c_o')});
                        self.model.set({"primary_address_city": lead.get('primary_address_city')});
                        self.model.set({"primary_address_country": lead.get('primary_address_country')});
                        self.model.set({"primary_address_postalcode": lead.get('primary_address_postalcode')});
                        self.model.set({"primary_address_street": lead.get('primary_address_street')});
                        self.model.set({"primary_resident_since_c": lead.get('primary_resident_since_c')});
                        // copy Primary Address
                        self.model.set({"correspondence_address_c_o": lead.get('correspondence_address_c_o')});
                        self.model.set({"correspondence_address_city": lead.get('correspondence_address_city')});
                        self.model.set({"correspondence_address_country": lead.get('correspondence_address_country')});
                        self.model.set({"correspondence_address_postalcode": lead.get('correspondence_address_postalcode')});
                        self.model.set({"correspondence_address_street": lead.get('correspondence_address_street')});
                        
                         //$("input[name=primary_address_postalcode]").trigger('blur');
                         //$("input[name=correspondence_address_postalcode]").trigger('blur');
                        
                    }
                });
            }
        }
    },
    _dispose: function () {
        this.model.off("change:dotb_gender_id");
        this.model.off("change:dotb_civil_status_id");
        this.model.off("change:dotb_correspondence_language");
        this.model.off("change:birthdate");
        //this.model.off("change:relative_type_c");
        this._super('_dispose');
    },
    render: function (argument) {
        this._super('render');
        this.setRelative();
        this.toggleRecordPanel();
    },
    toggleRecordPanel: function () {
        if (this.model.get('relative_type_c') == "partner" || this.model.get('relative_type_c') == "married") {
            $("#drawers li.tab.LBL_RECORDVIEW_PANEL31").show();
            $("#drawers li.tab.LBL_RECORDVIEW_PANEL10").remove();
            $("#drawers li.tab.LBL_RECORDVIEW_PANEL14").remove();
            $("#drawers li.tab.LBL_RECORDVIEW_PANEL19").remove();
            
            $("#drawers #tabContent #LBL_RECORDVIEW_PANEL10view1201").remove();
            $("#drawers #tabContent #LBL_RECORDVIEW_PANEL14view1201").remove();
            $("#drawers #tabContent #LBL_RECORDVIEW_PANEL19view1201").remove();
            $("li.tab.LBL_RECORDVIEW_PANEL10 a").click();
        } else {
            $("#drawers li.tab.LBL_RECORDVIEW_PANEL31").remove();
            $("#drawers #tabContent #LBL_RECORDVIEW_PANEL31view1201").remove();
        }
    },
        populateLocation: function (postalcode,city,country) {
        var self = this;
        var postalCode = this.model.get(postalcode);
        var postalCodes = ["9485", "9486", "9487", "9488", "9498", "9489", "9490", "9491", "9492", "9493", "9494", "9495", "9496", "9497"];
        if (postalCodes.indexOf(postalCode) == -1) {
            $.ajax({
                url: "https://api.zippopotam.us/CH/" + postalCode,
                success: function (response) {
                    if(country!='')
                    self.model.set(country, response.country);
                    self.model.set(city, response.places[0]['place name']);

                },
                error: function (jqXHR, exception) {
                    //console.log("Location not found");
                }
            });
        }
        else {
            $.ajax({
                url: "https://api.zippopotam.us/LI/" + postalCode,
                success: function (response) {
                    self.model.set(city, response.places[0]['place name']);

                },
                error: function (jqXHR, exception) {
                    //console.log("Location not found");
                }
            });
        }
    },

    populateAddress: function () {
        this.populateLocation('primary_address_postalcode','primary_address_city','primary_address_country');
    },
    populateCorrespondenceAddress: function () {
        this.populateLocation('correspondence_address_postalcode','correspondence_address_city','correspondence_address_country');
    },
    populateBankAddress: function () {
        this.populateLocation('dotb_bank_zip_code','dotb_bank_city_name','');
    },
    populateEmployerAddress: function () {
        this.populateLocation('dotb_employer_npa','dotb_employer_town','');
    },
    populateSecondEmployerAddress: function () {
        this.populateLocation('dotb_second_job_employer_npa','dotb_second_job_employer_town','');
    },
    ageCalculation: function () {
        var birthdate = this.model.get("birthdate");
        var today = new Date();
        var birthDate = new Date(birthdate);
        var age = today.getFullYear() - birthDate.getFullYear();
        var m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        if (age > 0) {
            this.model.set({"dotb_age_c": age});
        }
    },
    changeSalutation: function (e) {
        var self = this;
        var salutationMapping = {
            "_m": "sehr_geehrter_herr",
            "_f": "sehr_geehrte_frau",
            "de_": "sehr_geehrter_herr",
            "de_m": "sehr_geehrter_herr",
            "de_f": "sehr_geehrte_frau",
            "en_": "dear_mr",
            "en_m_": "dear_mr",
            "en_f_": "dear_mrs",
            "en_m": "dear_mr",
            "en_f": "dear_mrs",
            "it_": "egregio_signor",
            "it_m": "egregio_signor",
            "it_f": "egregia_signora",
            "fr_": "monsieur",
            "fr_m": "monsieur",
            "fr_f": "madame",
        };
        var key = self.model.get("dotb_correspondence_language") + "_" + self.model.get("dotb_gender_id");
        self.model.set("salutation", salutationMapping[key]);

        /*if(self.model.get("dotb_correspondence_language") == "en"){
         if(self.model.get("dotb_civil_status_id") == 'married' && self.model.get("dotb_gender_id") == 'f'){
         self.model.set("salutation", "dear_mrs");
         }
         else{
         var key = self.model.get("dotb_correspondence_language") + "_" + self.model.get("dotb_gender_id") + '_';
         if(salutationMapping[key]){
         self.model.set("salutation", salutationMapping[key]);
         }
         }
         }*/
    },
    _requiredFieldsForPartner: function (fields, errors, callback) {
        var related_lead_id = this.model.get('leads_contacts_1leads_ida');
        var first_name = this.model.get('first_name');
        var dob = this.model.get('birthdate');

        if (related_lead_id != '' && related_lead_id != undefined) {
            if (first_name == '' || first_name == undefined) {
                errors['first_name'] = "First Name is required";
                callback(null, fields, errors);
            }

            //if(dob == undefined || dob == ''){
            //errors['birthdate'] = "Birthdate is required";
            //callback(null, fields, errors);
            //}
        }
        callback(null, fields, errors);
    },
        hideWorkPermit: function (e) {
        var nationality = this.model.get("dotb_iso_nationality_code");
        if(nationality=='ch'){
            $('[data-name="dotb_work_permit_since"]').parent().hide();
            }else{
             $('[data-name="dotb_work_permit_since"]').parent().show();   
            }
    },
})