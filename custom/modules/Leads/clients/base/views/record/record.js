({
    extendsFrom: 'RecordView',
    pdfContact: {},
    custom_change: false,
    civil_change: false,
    date_change: 0,
    partner: false,
    residence_alert: false,
    show_warning_alert: false,
    pdfPartnerContact: {},
    initialize: function (options) {

        this.plugins = _.union(this.plugins || [], ['HistoricalSummary']);
        app.view.invokeParent(this, {type: 'view', name: 'record', method: 'initialize', args: [options]});
        app.events.on('addressAlert', _.bind(this.residenceAlert, this));
        app.events.on('refreshActivities', _.bind(this.refreshActivities, this));
        app.events.on('refreshApplications', _.bind(this.refreshApplications, this));

        app.events.on('refreshActivitiesAferSendDocuments', _.bind(this.refreshActivitiesAferSendDocuments, this));
        // listner to set lead status after application is created
        app.events.on('setLeadStatus', _.bind(this.setLeadStatus, this));
        app.events.on('updateSendEmailButtonForCRIF', _.bind(this.updateSendEmailButtonForCRIF, this));
        
        // listener for enabling inline edit for front-view fields
        app.events.on('enableEdiForFrontViewForLeads', _.bind(this.enableEdiForFrontViewForLeads, this));
        
        app.events.on('refreshLeadSubpanelOnLead', _.bind(this.refreshLeadSubpanelOnLead, this));

        // add listener for the custom buttons
        this.context.on('button:dotb_request_deltavista_button:click', this.deltavistaRequest, this);
        this.context.on('button:dotb_request_intrum_button:click', this.intrumRequest, this);
        this.context.on('button:dotb_cembra_pdf_export_button:click', this.initializePdfGeneration, this);
        this.context.on('button:generate_briefing_pdf_eny:click', this.generateEnyBriefingPDF, this);
        this.context.on('button:generate_briefing_pdf_bob:click', this.generateBobBriefingPDF, this);
        this.context.on('button:generate_briefing_pdf_bank_now_casa:click', this.generateBankNowCasaBriefingPDF, this);
        this.context.on('button:convert_to_pdf_preview:click', this.convertToPDFPreview, this);
        this.context.on('button:remove_extra_categories:click', this.removeExtraCategories, this);

        this.context.on('script:lead:retrieved', this.checkLaunchPDFGen);
        this.context.on('script:related_partner_contact:retrieved', this.checkLaunchPDFGen);
        this.context.on('script:ready_to_pdf_gen', this.generatePdf);
        this.context.on('button:send_documents:click', this.sendDocuments, this);
        this.context.on('button:email_to_crif:click', this.emailToCrif, this);
        this.context.on('button:email_missing_doc:click', this.emailMissingDoc, this);
        this.context.on('button:rci_portal:click', this.rciPortal, this);
        this.model.on("change:dotb_had_past_credit_c", this.pastCreditAlert, this);
        this.model.on("change:dotb_iso_nationality_code_c", this.setWorkprmit, this);

        this.model.on("change:dotb_civil_status_id_c", this.civilStatusAlert, this);
        this.model.on("change:dotb_resident_since_c", this.residenceAlert, this);
        this.model.on("change:dotb_partner_agreement_c", this.partnerAlert, this);

        this.model.on("change:dotb_gender_id_c", this.changeSalutation, this);
        this.model.on("change:dotb_civil_status_id_c", this.changeSalutation, this);
        this.model.on("change:dotb_correspondence_language_c", this.changeSalutation, this);

        //Function Binding for Making Fields empty in model
        this.model.on("change:dotb_has_enforcements_c", this.emptyField, this);
        this.model.on("change:dotb_past_enforcements_c", this.emptyField, this);
        this.model.on("change:dotb_iso_nationality_code_c", this.emptyField, this);
        this.model.on("change:dotb_employment_type_id_c", this.emptyField, this);
        this.model.on("change:dotb_has_second_income_c", this.emptyField, this);
        this.model.on("change:dotb_has_second_job_c", this.emptyField, this);
        this.model.on("change:dotb_is_home_owner_c", this.emptyField, this);
        this.model.on("change:credit_request_status_id_c", this.showWarningAlert, this);

        this.model.on("change:dotb_has_alimony_payments_c", this.emptyAlimentField, this);
        this.model.on("change:dotb_rent_alimony_income_c", this.emptyIncomeDescField, this);
        this.model.on("change:birthdate", this.ageCalculation, this);

        app.error.errorName2Keys['field_error'] = 'Please select sub status';
        this.model.addValidationTask('check_field', _.bind(this._doValidateStatus, this));
        var self = this;
        
        this.residence_alert = true;
        //Function Binding for Auto-populating  country from postal code
        this.events['blur input[name=primary_address_postalcode]'] = 'populateAddress';
        this.events['blur input[name=correspondence_address_postalcode]'] = 'populateCorrespondenceAddress';
        this.events['blur input[name=dotb_bank_zip_code_c]'] = 'populateBankAddress';
        this.events['blur input[name=dotb_employer_npa_c]'] = 'populateEmployerAddress';
        this.events['blur input[name=dotb_second_job_employer_npa_c]'] = 'populateSecondEmployerAddress';
        
        //Binding Function to Unregister Delete ShortCut
        this.on('render', this.registerShortcuts, this);
    },
    _doValidateStatus: function (fields, errors, callback) {
        var self = this;
        if ((this.model.get('credit_request_status_id_c') == '11_closed' || this.model.get('credit_request_status_id_c') == '00_pendent_geschlossen') && (this.model.get('credit_request_substatus_id_c') == '' || this.model.get('credit_request_substatus_id_c') == null)) {
            errors['credit_request_substatus_id_c'] = errors['credit_request_substatus_id_c'] || {};
            errors['credit_request_substatus_id_c'].field_error = true;
        }
        callback(null, fields, errors);
    },
    enableEdiForFrontViewForLeads: function(){
        var self = this;
        self.setButtonStates(self.STATE.EDIT);
    },
    _dispose: function () {
        app.events.on('addressAlert', _.bind(this.residenceAlert, this));
        app.events.off('refreshActivitiesAferSendDocuments');
        app.events.off('updateSendEmailButtonForCRIF');
	app.events.off('enableEdiForFrontViewForLeads');
	app.events.off('refreshLeadSubpanelOnLead');
        
        this.model.off("change:dotb_has_second_job_c");
        this.context.off('button:dotb_request_deltavista_button:click');
        this.context.off('button:dotb_request_intrum_button:click');
        this.context.off('button:dotb_cembra_pdf_export_button:click');
        
        this.context.off('button:convert_to_pdf_preview:click');
        this.context.off('button:remove_extra_categories:click');

        this.context.off('script:lead:retrieved');
        this.context.off('script:related_partner_contact:retrieved');
        this.context.off('script:ready_to_pdf_gen');
        this.context.off('button:send_documents:click');
        this.model.off("change:dotb_had_past_credit_c");
        this.model.off("change:dotb_iso_nationality_code_c");

        this.model.off("change:dotb_civil_status_id_c");
        this.model.off("change:dotb_resident_since_c");
        this.model.off("change:dotb_partner_agreement_c");
        this.model.off("change:dotb_gender_id_c");
        this.model.off("change:dotb_civil_status_id_c");
        this.model.off("change:dotb_correspondence_language_c");
        this.model.off("change:dotb_has_enforcements_c");
        this.model.off("change:dotb_past_enforcements_c");
        this.model.off("change:dotb_iso_nationality_code_c");
        this.model.off("change:dotb_employment_type_id_c");
        this.model.off("change:dotb_has_second_income_c");
        this.model.off("change:dotb_has_second_job_c");
        this.model.off("change:dotb_is_home_owner_c");
        this.model.off("change:credit_request_status_id_c");

        this.model.off("change:dotb_has_alimony_payments_c");
        this.model.off("change:dotb_rent_alimony_income_c");
        this.model.off("change:birthdate");
        this._super('_dispose');
        app.alert.dismissAll();
    },
    emptyAlimentField: function () {
        var aliments = this.model.get("dotb_has_alimony_payments_c");
        if (aliments == "no" || aliments == "") {
            this.model.set({"dotb_aliments_c": ""});
        }

    },
    emptyIncomeDescField: function () {
        var alimony = this.model.get("dotb_rent_alimony_income_c");
        if (alimony == "no" || alimony == "") {
            this.model.set({"dotb_additional_income_desc_c": ""});
        }

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
    populateLocation: function (postalcode, city, country) {
        var self = this;
        var postalCode = this.model.get(postalcode);
        var postalCodes = ["9485", "9486", "9487", "9488", "9498", "9489", "9490", "9491", "9492", "9493", "9494", "9495", "9496", "9497"];
        
        var list1 = ['primary_address_city','correspondence_address_city'];
        var list2 = ['dotb_bank_city_name_c','dotb_employer_town_c', 'dot_second_job_employer_town_c'];
        
        if (postalCodes.indexOf(postalCode) == -1) {
            $.ajax({
                url: "https://api.zippopotam.us/CH/" + postalCode,
                success: function (response) {
                    if (country != '')
                        self.model.set(country, response.country);
                    
                    var places = self.getPlacesData(response);
                    if(list1.indexOf(city)!== -1) {
                        self.setPlaceDataForList1(places, city, response);
                    }
                    else if(list2.indexOf(city)!== -1 ) {
                        self.setPlaceDataForList2(places, city, response);
                    } 
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
                    var places = self.getPlacesData(response);
                    if(list1.indexOf(city)!== -1) {
                        self.setPlaceDataForList1(places, city, response);
                    }
                    else if(list2.indexOf(city)!== -1 ) {
                        self.setPlaceDataForList2(places, city, response);
                    } 
                },
                error: function (jqXHR, exception) {
                    //console.log("Location not found");
                }
            });
        }
    },
    // Get Array of return places if any
    getPlacesData: function(response) {
        var places = [];  
        if(_.isArray(response.places) && _.size(response.places) > 1) {
            _.each(response.places, function (placeName, index) {
                if(!_.isEmpty(placeName['place name'])){
                    places.push(placeName['place name']);
                }
            }, this);
        }
        return places;
    },
    
    setPlaceDataForList1: function(places, city, response){
        var self = this;
        self.getCustomFieldView(places, city, response);
    },
    
    setPlaceDataForList2: function(places, city, response){
        var self = this;
        // Custom InlineEdit on field
        self.getCustomInLineEditOnField(city, response);
        self.getCustomFieldView(places, city, response);
    },
    // change field to custom field view
    getCustomFieldView: function(places, city, response){
        var self = this;
        if(!_.isEmpty(places)) {
            var primary_city_html = '';
            primary_city_html = self.getCustomDropDownOptions(places, city);
            self.replaceHtmlOfAddress(city, primary_city_html); 
        }
        else {
            var fld = self.getField(city);
            fld.render();
            self.model.set(city, response.places[0]['place name']);
        } 
    },
    // Marking inline edit of field to True
    getCustomInLineEditOnField: function(city, response){
        var self = this;
        // to enable inline edit on this field for multiple locations
        var fld = self.getField(city);
        if(fld.action === 'detail' && _.size(response.places) > 1 ){
            self.toggleField(fld);
        }
    },
    // Getting custom html options of dropdown
    getCustomDropDownOptions: function(places,city){
        var primary_city_html = '';
        _.each(places, function (place, index) {
            if(!index){
                primary_city_html+='<option value="">'+app.lang.get('LBL_PLEASE_CHOOSE_OPTION','Leads')+'</option>';
            }
            primary_city_html+='<option value="'+place+'">'+place+'</option>';
        }, this);
        
        primary_city_html = '<select id="'+city+'" name="'+city+'" style="margin-bottom: 1px;">'+
                            primary_city_html+'</select>';
        return primary_city_html;
    },
    //Replacing custom html on field
    replaceHtmlOfAddress: function(city, primary_city_html) {
        var self = this;
        $("[name='"+city+"']").replaceWith(primary_city_html); 

        $('#'+city).bind('change', function(){
            self.model.set(city, this.value);
        });
    },
    populateAddress: function () {
        this.populateLocation('primary_address_postalcode', 'primary_address_city', 'primary_address_country');
    },
    populateCorrespondenceAddress: function () {
        this.populateLocation('correspondence_address_postalcode', 'correspondence_address_city', 'correspondence_address_country');
    },
    populateBankAddress: function () {
        this.populateLocation('dotb_bank_zip_code_c', 'dotb_bank_city_name_c', '');
    },
    populateEmployerAddress: function () {
        this.populateLocation('dotb_employer_npa_c', 'dotb_employer_town_c', '');
    },
    populateSecondEmployerAddress: function () {
        this.populateLocation('dotb_second_job_employer_npa_c', 'dot_second_job_employer_town_c', '');
    },
    emptyField: function () {
        if (this.model.get("dotb_is_home_owner_c") == 'yes') {
            this.model.set({"dotb_housing_costs_c": ""});
        }
        else {
            this.model.set({"dotb_mortgage_amount_c": ""});
        }

        if (this.model.get("dotb_has_enforcements_c") != "open") {
            this.model.set({"dotb_current_enforcement_num_c": ""});
            this.model.set({"dotb_current_enforcement_amo_c": ""});
        }
        if (this.model.get("dotb_past_enforcements_c") != "yes") {
            this.model.set({"dotb_past_enforcement_number_c": ""});
            this.model.set({"dotb_past_enforcement_amount_c": ""});
        }
        if (this.model.get("dotb_iso_nationality_code_c") == "ch") {
            this.model.set({"dotb_work_permit_type_id_c": ""});
            this.model.set({"dotb_work_permit_since_c": ""});
            this.model.set({"dotb_work_permit_until_c": ""});
        }

        var d = new Date(),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

        if (month.length < 2)
            month = '0' + month;
        if (day.length < 2)
            day = '0' + day;

        var x = [year, month, day].join('-');
        var since = this.model.get('dotb_employed_since_c');


        var todate = x.split("-");
        var sincedate = since.split("-");


        var d2 = new Date(todate[0], todate[1], todate[2]);
        var d1 = new Date(sincedate[0], sincedate[1], sincedate[2]);

        var months;
        months = (d2.getFullYear() - d1.getFullYear()) * 12;
        months -= d1.getMonth() + 1;
        months += d2.getMonth();
        months <= 0 ? 0 : months;

        if (this.model.get("dotb_employment_type_id_c") == "permanent_contract") {
            if (months < 36) {
                this.model.set({"dotb_employed_until_c": ""});
            }
            else {
                this.model.set({"dotb_is_in_probation_period_c": ""});
                this.model.set({"dotb_employed_until_c": ""});
            }
        }

        if (this.model.get("dotb_employment_type_id_c") == "fixed_term" || this.model.get("dotb_employment_type_id_c") == "temporary_contract") {
            this.model.set({"dotb_has_thirteenth_salary_c": ""});
            this.model.set({"dotb_monthly_gross_income_c": ""});
            this.model.set({"dotb_is_in_probation_period_c": ""});
        }
        if (this.model.get("dotb_employment_type_id_c") == "self_employed") {
            this.model.set({"dotb_has_thirteenth_salary_c": ""});
            this.model.set({"dotb_monthly_gross_income_c": ""});
            this.model.set({"dotb_is_in_probation_period_c": ""});
            this.model.set({"dotb_employed_until_c": ""});
        }
        if (this.model.get("dotb_employment_type_id_c") == "disabled_gets_pension" || this.model.get("dotb_employment_type_id_c") == "retirement") {
            this.model.set({"dotb_has_thirteenth_salary_c": ""});
            this.model.set({"dotb_monthly_gross_income_c": ""});
            this.model.set({"dotb_is_in_probation_period_c": ""});
            this.model.set({"dotb_employed_since_c": ""});
            this.model.set({"dotb_employed_until_c": ""});
        }
        if (this.model.get("dotb_employment_type_id_c") != "yes" && this.model.get("dotb_has_second_job_c") != "yes") {
            this.model.set({"dotb_second_job_description_c": ""});
            this.model.set({"dotb_second_job_employer_npa_c": ""});
            this.model.set({"dotb_second_job_gross_income_c": ""});
            this.model.set({"dotb_second_job_since_c": ""});
            this.model.set({"dot_second_job_employer_name_c": ""});
            this.model.set({"dot_second_job_employer_town_c": ""});
            this.model.set({"dotb_second_job_has_13th_c": ""});
        }
    },
    /**
     * Show warning if user selects active status
     */

    showWarningAlert: function (event) {
        if (this.show_warning_alert) {
            var self = this;
            var prevStatus = event._previousAttributes.credit_request_status_id_c;
            if (this.model.get('credit_request_status_id_c') == '10_active' && prevStatus != '10_active' && prevStatus != 'undefined') {
                app.alert.show("active_change_confirmation", {
                    level: 'confirmation',
                    messages: app.lang.get('LBL_STATUS_CHANGE_CONFIRMATION', this.module),
                    onConfirm: function () {

                    },
                    onCancel: function () {
                        self.model.set("credit_request_status_id_c", prevStatus);
                    }
                });
            }
            //this.model.set('credit_request_substatus_id_c','');
        }
        
        this.show_warning_alert = true;
    },
    makeReadOnly: function (rendered) {
        var self = this;
        var status = this.model.get("credit_request_status_id_c");
        if (typeof status !== 'undefined')
            status = status.trim();
        if (status == "11_closed" || status == "10_active") {
            if (this.meta && this.meta.panels) {
                var panels = this.meta.panels;
                _.each(panels, function (panel) {
                    _.each(panel.fields, function (field, index) {
                        field.readonly = true;
                    }, this);
                }, this);
                if (rendered != true) {
                    this.render();
                }
                $('.front-view input').attr("disabled", true);
                $('.front-view textarea').attr("disabled", true);
                $('.front-view input').css("background", '#fff');
                $('.front-view textarea').css("background", '#fff');
                app.alert.show("readonly-lead-msg", {
                    level: 'info',
                    messages: 'Dieser Lead wurde geschlossen und kann nicht länger bearbeitet werden!',
                    autoClose: false,
                });
            }
            if (self.layout && self.layout._components && self.layout._components[0]) {
                self.layout._components[0].templat = 'detail'
                self.layout._components[0].render();
            }
        }
    },
    pastCreditAlert: function () {
        var past_credit = this.model.get('dotb_had_past_credit_c');
        if (past_credit == 'yes') {
            var rch = this.model.getRelatedCollection('leads_dotb5_credit_history_1');
            rch.once('reset', function (collection) {
                if (collection.length === 0) {
                    app.alert.show("past-credit-msg", {
                        level: 'info',
                        messages: 'Bitte bestehende Kredite in SubPanel Kredithistorie erfassen', //' Please add additional addresse(s) for 3 years back.',
                        autoClose: false
                    });
                }
            }, this);
            rch.fetch({
                relate: true
            });
        }
        if (past_credit == 'no') {
            app.alert.dismiss("past-credit-msg");
        }
    },
    changeSalutation: function (e) {
        var self = this;
        var salutationMapping = {
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
        }
        var key = self.model.get("dotb_correspondence_language_c") + "_" + self.model.get("dotb_gender_id_c");
        self.model.set("salutation", salutationMapping[key]);

    },
    civilStatusAlert: function () {
        if (this.civil_change) {
            var status = this.model.get('dotb_civil_status_id_c');
            if (status == 'married') {
                app.alert.show("civil-status-msg", {
                    level: 'info',
                    messages: ' Bitte Daten zum Partner erfassen',
                    autoClose: true
                });
            }

        }
        this.civil_change = true;
    },
    partnerAlert: function () {
        if (this.partner) {
            var status1 = this.model.get('dotb_partner_agreement_c');
            if (status1 == 'yes') {
                app.alert.show("partner-msg", {
                    level: 'info',
                    messages: 'Bitte Partnerinformationen über das SubPanel Partner erfassen.',
                    autoClose: false
                });
            }

        }
        this.partner = true;
    },
    setLeadStatus: function () {
        this.model.set("credit_request_status_id_c", '07_creating_contract');
    },
    refreshActivities: function () {
        var linkName = 'historical_summary';
        if (this.model) {
            var activitiesCollection = this.model.getRelatedCollection(linkName);
            activitiesCollection.fetch({relate: true});
        }
        app.events.trigger('refreshActivitiesDashlet');
    },
    refreshApplications: function () {
        var linkName = 'leads_opportunities_1';
        if (this.model) {
            var applicationsCollection = this.model.getRelatedCollection(linkName);
            applicationsCollection.fetch({relate: true});
        }
    },
    residenceAlert: function () {
        var lead_address_since = this.model.get('dotb_resident_since_c');
        if (this.date_change) {
            /*var rch = this.model.getRelatedCollection('leads_dot10_addresses_1');
             rch.fetch({relate: true,
             success: function () {
             var i,totalMonths = 0;
             for(i = 0 ; i<rch.models.length ; i++){
             var since = rch.models[i].attributes.dotb_resident_since_c;
             var till = rch.models[i].attributes.dotb_resident_till_c;
             
             var todate= till.split("-");
             var sincedate = since.split("-");        
             
             var d2 = new Date(todate[0], todate[1], todate[2]);
             var d1 = new Date(sincedate[0], sincedate[1], sincedate[2]);
             
             var months = 0;
             months = (d2.getFullYear() - d1.getFullYear()) * 12;
             months -= d1.getMonth() + 1;
             months += d2.getMonth();
             months=months+1;
             months <= 0 ? 0 : months;
             totalMonths += (months);
             
             since = null;
             till = null;
             
             }
             */
            var totalMonths = 0;
            var leadBean = app.data.createBean('Leads', {id: this.model.id});
            leadBean.fetch({
                success: function () {
                    if (leadBean.get('address_months_c') != '')
                        totalMonths = leadBean.get('address_months_c');

                    if (lead_address_since) {
                        var now = new Date();
                        lead_address_since = lead_address_since.split("-");
                        lead_address_since = new Date(lead_address_since[0], lead_address_since[1], lead_address_since[2]);
                        var lead_address_since_months = now.getMonth() - lead_address_since.getMonth() + (12 * (now.getFullYear() - lead_address_since.getFullYear()));
                        lead_address_since_months = lead_address_since_months + 1;
                        lead_address_since_months <= 0 ? 0 : lead_address_since_months;
                        totalMonths = parseInt(totalMonths) + parseInt(lead_address_since_months);
                    }

                    if (totalMonths < 36) {

                        app.alert.show("residence-msg", {
                            level: 'info',
                            messages: 'Alte Adressen erfassen (3 Jahre)',
                            autoClose: false
                        });

                    }
                },
                error: function () { /* do stuff */
                }
            });
            //}

            // });



        }
        this.date_change = true;
    },
    SetDatepicker: function () {
        $(".datepicker").css("margin-top", "20px");

    },
    primaryResidentSince: function () {
        var primary_resident_since = this.model.get('primary_resident_since_c');
        alert(primary_resident_since);
        var today = new Date();
        alert(today);
        alert(today.getTime());
        //alert(primary_resident_since.getTime());
    },
    altResidentSince: function () {
        var alt_resident_since = this.model.get('alt_resident_since_c');

    },
    setWorkprmit: function () {
        var nationality = this.model.get('dotb_iso_nationality_code_c');
    },
    render: function () {
        var self = this;
        this.makeReadOnly(true);
        this.pastCreditAlert();
        this._super('render');
        this.residenceAlert();
        if ((this.model.get("credit_request_status_id_c") == "11_closed" && this.model.get("credit_request_substatus_id_c") != "") || this.model.get("credit_request_status_id_c") == "10_active") {
            if (self.buttons && self.buttons.main_dropdown) {
                self.buttons.main_dropdown.hide();
            }
        }
//        $("[data-subpanel-link='leads_leads_1']").hide(); 
//        var beanId = this.model.get('id');
//        var url = app.api.buildURL('ConvLead/GetRelatedLeadPartner');
//        app.api.call('create', url, {
//            id: beanId,
//        }, {
//            success: _.bind(function (yes) {
//                if(yes){
//                $("[data-subpanel-link='leads_contacts_1']").hide();    
//                $("[data-subpanel-link='leads_leads_1']").show();    
//                }else{
//                $("[data-subpanel-link='leads_leads_1']").hide();      
//                }
//            }, this),
//        });
    },
    setRoute: function (action) {
        if (!this.meta.hashSync) {
            return;
        }
        var model = this.model;
        if (model !== null)
            if (model.hasOwnProperty('id')) {
                app.router.navigate(app.router.buildRoute(this.module, this.model.id, action), {
                    trigger: false
                });
            }
    },
    validationComplete: function (isValid) {
        var self = this;
        self.toggleButtons(true);
        var related_tasks = App.data.createBeanCollection('Tasks');
        var lead_status = self.model.get('credit_request_status_id_c');
        if (lead_status != '11_closed' && lead_status != '10_active' && lead_status != '00_pendent_geschlossen') {
            var saveMe = false;
            var related_tasks = App.data.createBeanCollection('Tasks');
            var filters = [
                {"parent_id": self.model.get('id')},
            ];
            related_tasks.fetch({
                "filter": filters,
                success: function (models, options) {
                    if (models.length == 0) {
                        app.alert.show("close-task-error", {
                            level: 'error',
                            messages: 'ERR_LEAD_CAN_NOT_BE_SAVE',
                            autoClose: false
                        });
                        return;
                    } else {
                        var dup = _.find(related_tasks.models, function (model) {
                            var status = model.get('status');
                            if (status != 'closed') {
                                saveMe = true;
                            }
                        });
                        if (saveMe) {
                            if (isValid) {
                                self.handleSave();
                            }
                        } else {
                            app.alert.show("close-task-error", {
                                level: 'error',
                                messages: 'ERR_LEAD_CAN_NOT_BE_SAVE',
                                autoClose: false
                            });
                            return;
                        }
                    }

                }
            });
        } else {
            if (isValid) {
                self.handleSave();
            }
        }
    },
    handleSave: function () {
        var self = this;
        if (self && self.layout && self.layout._components[0] && self.layout._components[0].getField("credit_request_status_id_c")) {
            self.layout._components[0].getField("credit_request_status_id_c").render();
        }
        this._super('handleSave');
        this.makeReadOnly();
    },
    getCustomSaveOptions: function (options) {
        var self = this;
        var successCallBack = options.success;
        options.success = function () {
            app.events.trigger('record-saved');
            if (_.isFunction(successCallBack)) {
                successCallBack();
            }
            var docPanel = self.getField("leads_documents");
            if (docPanel) {
                docPanel.fetchRecords();
            }
        }
        return options;
    },
    sendDocuments: function () {
        // before moving to Send Documents View if the Document-Preview is already selected then hide this view
        $('.document-preview').hide();
        $('.dashboard-pane').show();
        
        // Close the Custom opened preview
        if($('.preview-pane')) {
            $('.fa-times').click();
        }
        
        app.drawer.open({
            layout: 'send_documnets',
            context: {
                id: this.model.get('id'),
                email: this.model.get('email1')
            }
        });
    },
    emailToCrif: function () {
        var lead_id = this.model.get('id');
        var emailTemplate = app.data.createBean('EmailTemplates', {id: '7b86ea24-c0c7-ffe9-04d8-57ac4a106863'});
        var toAddress = [];
        toAddress.push({
            email: 'support.ch@crif.com',
            name: 'support.ch@crif.com',
        });
        var options = options = ({
            to_addresses: toAddress,
        });
        App.alert.dismissAll();
        app.drawer.open({
            layout: 'compose',
            context: {
                create: true,
                module: 'Emails',
                templateModel: emailTemplate,
                prepopulate: {
                    subject: 'Anmeldung DeltaVista',
                    to_addresses: toAddress,
                    placement: 'bottom',
                    action: 'email',
                    email_to_crif: true,
                    lead_id: lead_id,
                    preFillAttachement: true,
                }
            }
        });
    },
    emailMissingDoc: function () {
        var self = this;
        app.alert.show('missing-doc-loading', {level: 'process'});
        var lead_id = self.model.get('id');
        var url = App.api.buildURL("Emails/EmailParser/1/lead/" + lead_id, null, null);
        App.api.call('read', url, null, {
            success: function (response) {
                app.alert.dismiss('missing-doc-loading');
                if (response == 'no_doc_found') {
                    app.alert.show("no_doc_found", {
                        level: 'info',
                        messages: app.lang.get('LBL_NO_MISSING_DOC', 'Leads'),
                        autoClose: false
                    });
                } else {
                    var emailTemplate = app.data.createBean('EmailTemplates', {id: 'b00c13b0-96c8-6ae8-6b28-57b1a8916ef5'});
                    var toAddress = [];
                    toAddress.push({
                        email: self.model.get('email1'),
                        name: self.model.get('email1'),
                    });
                    app.drawer.open({
                        layout: 'compose',
                        context: {
                            create: true,
                            module: 'Emails',
                            templateModel: emailTemplate,
                            prepopulate: {
                                subject: 'Missing Documents',
                                to_addresses: toAddress,
                                email_missing_docs: true,
                                lead_id: lead_id,
                                placement: 'bottom',
                                action: 'email',
                                preFillAttachement: true,
                            }
                        }
                    });
                }
            }
        });
    },
    // Credit Check Request DeltaVista
    deltavistaRequest: function () {
        var beanId = this.model.get('id');
        var self_req = this;
        
        app.alert.show("delta-process", {
            level: 'process',
            messages: 'Loading',
            autoClose: true
        });
        
        app.api.call('GET', app.api.buildURL('Leads/' + beanId + '/createCreditCheckRequestForLead/Deltavista'), null, {
            success: function (data) {
                //If the status is ok
                if (data.success == 'ok') {
                    app.alert.show("server-sucess", {
                        level: 'success',
                        messages: 'The credit check request has been sent to Delta Vista',
                        autoClose: false
                    });
                    
                    //Save the updated values in model
                    self_req.model.set('has_deltavista_response_c',data.has_response);
                    self_req.model.set('deltavista_request_id_c', data.request_id);
                    self_req.model.save();
                    
                } else if (data.success == 'ko') {
                    if (data.msg == 'pending_request') {
                        //First case: a request already exists
                        app.alert.show("credit-check-error", {
                            level: 'error',
                            messages: 'ERR_CREDIT_CHECK_REQUEST_PENDING',
                            autoClose: false
                        });
                    } else if (data.msg == 'missing') {
                        //Second case : some fields missing
                        app.alert.show("credit-check-error", {
                            level: 'error',
                            messages: data.details,
                            autoClose: false
                        });
                    } else {
                        //Other cases: generic message
                        app.alert.show("credit-check-error", {
                            level: 'error',
                            messages: 'ERR_GENERIC_SERVER_ERROR',
                            autoClose: false
                        });
                    }
                }
            },
            error: function (error) {
                app.alert.show("server-error", {
                    level: 'error',
                    messages: 'ERR_GENERIC_SERVER_ERROR',
                    autoClose: false
                });
                app.error.handleHttpError(error);
            }
        });
    },
    // Credit Check Request Intrum
    intrumRequest: function () {
        var beanId = this.model.get('id');
        var self_intm = this;
        
        app.alert.show("intrum-process", {
            level: 'process',
            messages: 'Loading',
            autoClose: true
        });
        
        app.api.call('GET', app.api.buildURL('Leads/' + beanId + '/createCreditCheckRequestForLead/Intrum'), null, {
            success: function (data) {
                //Si le statut est ok
                if (data.success == 'ok') {
                    app.alert.show("server-sucess", {
                        level: 'success',
                        messages: 'The credit check request has been sent to Intrum',
                        autoClose: false
                    });
                    
                    //Save the updated values in model
                    self_intm.model.set('has_intrum_response_c',data.has_response);
                    self_intm.model.set('intrum_request_id_c', data.request_id);
                    self_intm.model.save();
                    
                } else if (data.success == 'ko') {
                    if (data.msg == 'pending_request') {
                        //Premier cas : une requête existe déjà
                        app.alert.show("credit-check-error", {
                            level: 'error',
                            messages: 'ERR_CREDIT_CHECK_REQUEST_PENDING',
                            autoClose: false
                        });
                    } else if (data.msg == 'missing') {
                        //Second cas : certains champs manquent
                        app.alert.show("credit-check-error", {
                            level: 'error',
                            messages: data.details,
                            autoClose: false
                        });
                    } else {
                        //Autres cas : message générique
                        app.alert.show("credit-check-error", {
                            level: 'error',
                            messages: 'ERR_GENERIC_SERVER_ERROR',
                            autoClose: false
                        });
                    }
                }
            },
            error: function (error) {
                app.alert.show("server-error", {
                    level: 'error',
                    messages: 'ERR_GENERIC_SERVER_ERROR',
                    autoClose: false
                });
                app.error.handleHttpError(error);
            }
        });
    },
    initializePdfGeneration: function () {
        app.alert.show('processing_pdf_generation', {level: 'process'});

        // use lead bean here
        this.storeLead();
        app.api.call(
                'read',
                app.api.buildURL('Leads', 'getRelatedContact', {id: this.model.get('id')}),
                null,
                {
                    success: _.bind(this.storeRelatedPartner, this),
                    error: function () {
                        app.alert.dismiss('processing_pdf_generation');
                    }
                }
        );
    },
    storeLead: function () {
        this.pdfContact = this.model.attributes;
        this.context.trigger('script:lead:retrieved');
    },
    storeRelatedPartner: function (data) {
        this.pdfPartnerContact = data;
        this.context.trigger('script:related_partner_contact:retrieved');
    },
    checkLaunchPDFGen: function (data) {
        if (this.pdfContact.id !== undefined
                && this.pdfPartnerContact._module !== undefined
                ) {
            var latest_related_address = '';
            var related_addresses = this.model.getRelatedCollection("leads_dot10_addresses_1");
            related_addresses.fetch({relate: true});
            if (related_addresses.length > 0) {
                var beanId = this.model.get('id');
                var url = app.api.buildURL('ConvLead/GetLatestAddress');
                app.api.call('create', url, {
                    id: beanId,
                }, {
                    success: _.bind(function (latest_address) {
                        latest_related_address = latest_address;
                        this.generatePdf(latest_related_address);
                    }, this),
                });
            } else {
                this.generatePdf('');
            }
        }
    },
    
    concatCorrespondanceAddress: function(){
        var address = '';
        if(!_.isEmpty(this.model.get('correspondence_address_postalcode'))) {
            address+= this.model.get('correspondence_address_postalcode')+' ';
        }
        
        if(!_.isEmpty(this.model.get('correspondence_address_city'))) {
            address+= this.model.get('correspondence_address_city');
        }
        
        return address;
    },
    
    generatePdf: function (latest_related_address) {
        var correspondanceAddress = this.concatCorrespondanceAddress();
        var contactData = this.pdfContact;
        var partnerData = this.pdfPartnerContact;
        var amount = this.model.get('credit_amount_c');
        var duration = this.model.get('credit_duration_c');
        if (duration == 0 || duration == '') {
            duration = 1;
        }
        var repay = amount / duration;

        var concatenated_alt_address =
                contactData.alt_address_street +
                ', ' +
                contactData.alt_address_postalcode +
                ' ' +
                contactData.alt_address_city +
                ', ' +
                contactData.alt_address_country;

        concatenated_alt_address = '';
        concatenated_alt_address += contactData.alt_address_street;
        if (concatenated_alt_address.length > 0
                && (contactData.alt_address_postalcode + contactData.alt_address_city + contactData.alt_address_country).length > 0
                ) {
            concatenated_alt_address += ', ';
        }
        concatenated_alt_address += contactData.alt_address_postalcode;
        if ((contactData.alt_address_postalcode + '').length > 0
                && (contactData.alt_address_city + '').length > 0
                ) {
            concatenated_alt_address += ' ';
        }
        concatenated_alt_address += contactData.alt_address_city;
        if ((contactData.alt_address_postalcode + contactData.alt_address_city).length > 0
                && (contactData.alt_address_country + '').length > 0
                ) {
            concatenated_alt_address += ', ';
        }
        concatenated_alt_address += contactData.alt_address_country;

        var children_birth_years = '';
        if ((contactData.children_birth_years_c).length > 0) {
            // children_birth_years += contactData.children_birth_years_c.replace("-*#*-", ",");
            children_birth_years = contactData.children_birth_years_c;
            children_birth_years = children_birth_years.split('-*#*-');
            children_birth_years = children_birth_years.toString();
            children_birth_years = children_birth_years.replace(new RegExp(',20', 'g'), ',');
            children_birth_years = children_birth_years.substr(1);
            children_birth_years = children_birth_years.substr(1);
        }
        
        var model = {
            lead_id: this.model.get('id'),
            document_id: this.model.get('leads_documents_1documents_idb'),
            language: this.model.get('dotb_correspondence_language_c'),
            /* credit_amount:
                    this.model.get('credit_amount_c'), */
            /*ppi_id:
                    this.model.get('ppi_id_c'),*/
            contact_dotb_gender_id:
                    contactData.dotb_gender_id_c,
            contact_last_name:
                    contactData.last_name,
            contact_first_name:
                    contactData.first_name,
            contact_primary_address_street:
                    contactData.primary_address_street,
            concatenation_contact_primary_address_postalcode_city:
                    contactData.primary_address_postalcode + ' ' + contactData.primary_address_city,
            truncated_contact_dotb_resident_since:
                    contactData.dotb_resident_since_c,
            contact_primary_address_state:
                    contactData.primary_address_state,
            contact_birthdate:
                    contactData.birthdate,
            translated_contact_dotb_civil_status_id:
                    contactData.dotb_civil_status_id_c,
            contact_email1:
                    contactData.email1,
            concatenation_contact_alt_address_street_postalcode_city_country:
                    latest_related_address,
            translated_contact_dotb_iso_nationality_code:
                    contactData.dotb_iso_nationality_code_c,
            contact_dotb_work_permit_type_id:
                    contactData.dotb_work_permit_type_id_c,
            truncated_contact_dotb_work_permit_since:
                    contactData.dotb_work_permit_since_c,
            dotb_iso_country_list:
                    contactData.dotb_iso_nationality_code_c,
            contact_phone_home:
                    contactData.phone_other,
            contact_phone_mobile:
                    contactData.phone_mobile,
            contact_phone_work:
                    contactData.phone_work,
            contact_current_occupation:
                    contactData.current_occupation_c,
            contact_other_expences:
                    contactData.dotb_additional_expenses_c,
            contact_dotb_employer_name:
                    contactData.dotb_employer_name_c,
            concatenation_contact_dotb_employer_npa_dotb_employer_town:
                    contactData.dotb_employer_npa_c + ' ' + contactData.dotb_employer_town_c,
            truncated_contact_dotb_employed_since:
                    contactData.dotb_employed_since_c,
            contact_previous_employer:
                    contactData.previous_employer_c,
            truncated_contact_previous_employed_since:
                    contactData.previous_employed_since_c,
            contact_dotb_monthly_net_income:
                    contactData.dotb_monthly_net_income_c,
            contact_dotb_has_thirteenth_salary:
                    contactData.dotb_has_thirteenth_salary_c,
            contact_dotb_second_job_gross_income:
                    contactData.dotb_monthly_net_income_nb_c,
            contact_dotb_second_job_has_13th:
                    contactData.dotb_second_job_has_13th_c,
            contact_dotb_additional_income_desc:
                    contactData.dotb_rent_or_alimony_income_c,
            calculate_contact_housing_situation_parents:
                    contactData.dotb_housing_situation_id_c,
            calculate_contact_housing_situation_mortgage:
                    contactData.dotb_is_home_owner_c,
            contact_dotb_housing_costs:
                    contactData.dotb_housing_costs_rent_c,
            contact_dotb_aliments:
                    contactData.dotb_aliments_c,
            contact_foreign_address:
                    correspondanceAddress,
            calculate_contact_dotb_has_enforcements_dotb_past_enforcements:
                    contactData.dotb_has_enforcements_c && contactData.dotb_past_enforcements_c,
            contact_no_of_dependent_children:
                    contactData.no_of_dependent_children_c,
            contact_children_birth_years:
                    children_birth_years,
            partner_last_name: '',
            partner_first_name: '',
            partner_maiden_name: '',
            partner_birthdate: '',
            translated_partner_dotb_iso_nationality_code: '',
            partner_dotb_work_permit_type_id: '',
            partner_dotb_work_permit_since: '',
            partner_phone_home: '',
            partner_phone_mobile: '',
            partner_phone_work: '',
            partner_dotb_employer_name: '',
            concatenation_partner_dotb_employer_npa_town: '',
            partner_dotb_employed_since: '',
            partner_dotb_monthly_net_income: '',
            partner_dotb_has_thirteenth_salary: '',
            partner_dotb_additional_income_desc: '',
            partner_dotb_second_job_has_13th: '',
            partner_heutiger_beruf: '',
            partner_second_job_gross_income: '',
            partner_dobt_alimente: ''
        }
        if (contactData.dotb_iso_nationality_code_c == 'ch') {
            model.contact_dotb_work_permit_type_id = '';
            model.truncated_contact_dotb_work_permit_since = '';
        }
        if (partnerData.id !== undefined && partnerData.id.length > 0) {
            model.partner_last_name = partnerData.last_name;
            model.partner_first_name = partnerData.first_name;
            model.partner_maiden_name = partnerData.maiden_name_c;
            model.partner_birthdate = partnerData.birthdate;
            model.translated_partner_dotb_iso_nationality_code = partnerData.dotb_iso_nationality_code;
            if (partnerData.dotb_iso_nationality_code != 'ch') {
                model.partner_dotb_work_permit_type_id = partnerData.dotb_work_permit_type_id;
                model.partner_dotb_work_permit_since = partnerData.dotb_work_permit_since;
            }
            model.partner_phone_home = partnerData.phone_other;
            model.partner_phone_mobile = partnerData.phone_mobile;
            model.partner_phone_work = partnerData.phone_work;
            model.partner_heutiger_beruf = partnerData.current_occupation_c;
            model.partner_dotb_employer_name = partnerData.dotb_employer_name;
            model.concatenation_partner_dotb_employer_npa_town = partnerData.dotb_employer_npa + ' ' + partnerData.dotb_employer_town;
            model.partner_dotb_employed_since = partnerData.dotb_employed_since;
            model.partner_dotb_monthly_net_income = partnerData.dotb_monthly_net_income;
            model.partner_dotb_has_thirteenth_salary = partnerData.dotb_has_thirteenth_salary;
            model.partner_dotb_additional_income_desc = partnerData.dotb_rent_or_alimony_income;
            model.partner_dotb_second_job_has_13th = partnerData.dotb_second_job_has_13th;
            model.partner_second_job_gross_income = partnerData.dotb_monthly_net_income_nb_c;
            model.partner_dobt_alimente = partnerData.dotb_aliments;
        }
        
        /* if (this.model.get('ppi_id_c') === 'monthly_installment_only') {
            model.ppi_id = 1;
            model.ppi_plus = '';
        } else if (this.model.get('ppi_id_c') === 'monthly_installment_and_fixed_costs') {
            model.ppi_id = '';
            model.ppi_plus = 1;
        } else {
            model.ppi_id = '';
            model.ppi_plus = '';
        } */
        
        model.calculate_contact_housing_situation_parents = (contactData.dotb_housing_situation_id_c == 'by_parents');
        model.calculate_contact_housing_situation_mortgage = (contactData.dotb_is_home_owner_c == 'yes');
        model.calculate_contact_housing_situation_shared = '';
		if(contactData.dotb_housing_situation_id_c == 'alone' || contactData.dotb_housing_situation_id_c == 'flat_share' || contactData.dotb_housing_situation_id_c == 'married_couple' || contactData.dotb_housing_situation_id_c == 'single_parent')
			model.calculate_contact_housing_situation_shared = '1';
        model.calculate_contact_housing_situation_shared_part = (contactData.dotb_is_rent_split_c == 'yes' );
//        model.calculate_contact_housing_situation_shared = (
//                contactData.dotb_housing_situation_id_c == 'single_parent'
//                || contactData.dotb_housing_situation_id_c == 'alone'
//                || (contactData.dotb_housing_situation_id_c == 'married_couple'
//                        && (partnerData.dotb_monthly_net_income == null
//                                || parseInt(partnerData.dotb_monthly_net_income) == 0
//                                )
//                        )
//                );
//        model.calculate_contact_housing_situation_shared_part = (
//                contactData.dotb_housing_situation_id_c == 'flat_share'
//                || (contactData.dotb_housing_situation_id_c == 'married_couple'
//                        && partnerData.dotb_monthly_net_income != null
//                        && parseInt(partnerData.dotb_monthly_net_income) > 0
//                        )
//                );

        model.calculate_contact_dotb_has_enforcements_dotb_past_enforcements = (
                contactData.dotb_has_enforcements_c
                && contactData.dotb_past_enforcements_c
                );
        this.pdfContact = {};
        this.pdfPartnerContact = {};

        app.api.call(
                'create',
                app.api.buildURL('Leads/generatePDF/cembra'),
                {
                    model: model
                },
        {
            success: _.bind(this.updateRelatedDocument, this),
            error: function (error) {
                app.alert.dismiss('processing_pdf_generation');
                app.alert.show("server-error", {
                    level: 'error',
                    messages: 'ERR_GENERIC_SERVER_ERROR',
                    autoClose: false
                });
                app.error.handleHttpError(error);
            }
        }
        );
    },
    updateRelatedDocument: function (data) {
        app.alert.dismiss('processing_pdf_generation');
        app.alert.show('success', {
            level: 'success',
            autoClose: true
        });
        App.events.trigger('refreshDocumentPanel');
    },
    generateEnyBriefingPDF: function () {
        app.alert.show("app-process", {
            level: 'process',
            messages: 'Loading',
            autoClose: true
        });
        app.api.call(
                'create',
                app.api.buildURL('Leads/generateBriefingPdf/eny_finance'),
                {
                    lead_id: this.model.id
                },
        {
            success: _.bind(this.updateRelatedDocument, this),
            error: function (error) {
                app.alert.dismiss('processing_pdf_generation');
                app.alert.show("server-error", {
                    level: 'error',
                    messages: 'ERR_GENERIC_SERVER_ERROR',
                    autoClose: false
                });
                app.error.handleHttpError(error);
            }
        }
        );

//		app.bwc.login(null, _.bind(function () {
//           var url = "?action=generateBriefingPdf&bank=eny_finance&module=Leads&lead_id=" + this.model.id;
//           app.api.fileDownload(url, {
//               error: function (data) {
//                   app.error.handleHttpError(data, {});
//               }
//           }, {
//               iframe: this.$el
//           });
//       }, this));
    },
    generateBobBriefingPDF: function () {
        app.alert.show("app-process", {
            level: 'process',
            messages: 'Loading',
            autoClose: true
        });
        app.api.call(
                'create',
                app.api.buildURL('Leads/generateBriefingPdf/bob'),
                {
                    lead_id: this.model.id
                },
        {
            success: _.bind(this.updateRelatedDocument, this),
            error: function (error) {
                app.alert.dismiss('processing_pdf_generation');
                app.alert.show("server-error", {
                    level: 'error',
                    messages: 'ERR_GENERIC_SERVER_ERROR',
                    autoClose: false
                });
                app.error.handleHttpError(error);
            }
        }
        );
//        app.bwc.login(null, _.bind(function () {
//            var url = "?action=generateBriefingPdf&bank=bob&module=Leads&lead_id=" + this.model.id;
//            app.api.fileDownload(url, {
//                error: function (data) {
//                    app.error.handleHttpError(data, {});
//                }
//            }, {
//                iframe: this.$el
//            });
//        }, this));
    },
    generateBankNowCasaBriefingPDF: function () {
        app.alert.show("app-process", {
            level: 'process',
            messages: 'Loading',
            autoClose: true
        });
        app.api.call(
                'create',
                app.api.buildURL('Leads/generateBriefingPdf/bank_now_casa'),
                {
                    lead_id: this.model.id
                },
        {
            success: _.bind(this.updateRelatedDocument, this),
            error: function (error) {
                app.alert.dismiss('processing_pdf_generation');
                app.alert.show("server-error", {
                    level: 'error',
                    messages: 'ERR_GENERIC_SERVER_ERROR',
                    autoClose: false
                });
                app.error.handleHttpError(error);
            }
        }
        );
    },
    refreshActivitiesAferSendDocuments: function () {
        var self = this;
        var linkName = 'historical_summary';
        var activitiesCollection = self.model.getRelatedCollection(linkName);
        activitiesCollection.fetch({relate: true});
        app.events.trigger('refreshActivitiesDashlet');
    },
    convertToPDFPreview: function () {
        var self = this;
        var record_id = self.model.get('id');
        var module_name = self.model.module;

        if (!_.isEmpty(record_id) && !_.isEmpty(module_name)) {
            app.alert.show('initialize', {
                level: 'process',
                messages: 'Loading',
            });

            var url = app.api.buildURL('convertToPDFPreview/' + record_id + '/' + module_name, null, null, null);
            app.api.call('GET', url, null, {
                success: function (data) {
                    app.alert.dismissAll();
                    if (data) {
                        app.alert.show("server-sucess", {
                            level: 'success',
                            messages: 'Attachments are Successfully converted into PDF',
                            autoClose: false
                        });

                        self.refreshDocumentsAfterManualConversion();
                    }
                    else {
                        app.alert.show("server-sucess", {
                            level: 'info',
                            messages: 'No attachments found to be converted into PDF',
                            autoClose: false
                        });

                        self.refreshDocumentsAfterManualConversion();
                    }
                },
                error: function (error) {
                    aapp.alert.dismissAll();
                    app.alert.show("server-error", {
                        level: 'error',
                        messages: 'ERR_GENERIC_SERVER_ERROR',
                        autoClose: false
                    });
                }
            });
        }
    },
    refreshDocumentsAfterManualConversion: function () {
        // var self = this;
        // var linkName =  'leads_documents_1';
        // var documentCollection = self.model.getRelatedCollection(linkName);
        // documentCollection.fetch({relate: true});
		
        // to refresh document panel
        App.events.trigger('refreshDocumentPanel');
    },
    refreshLeadSubpanelOnLead: function(){
        var self = this;
        var linkName =  'leads_leads_1';
        var leadItself = self.model.getRelatedCollection(linkName);
        leadItself.fetch({relate: true});
    },
    updateSendEmailButtonForCRIF: function() {
        var self = this;
        app.alert.show("app-process", {
            level: 'process',
            messages: 'Loading',
            autoClose: true
        });
        var record_id = self.model.get('id');
        app.api.call( 'create', app.api.buildURL('Leads/remindarTaskForCRIF/'+record_id), { module_create: 'Tasks', module: 'Leads' },
        {
            success: _.bind(self.updateActivitiesSubpanel, this),
            error: function (error) {
                app.alert.dismissAll();
                app.alert.show("server-error", {
                    level: 'error',
                    messages: 'ERR_GENERIC_SERVER_ERROR',
                    autoClose: false
                });
                app.error.handleHttpError(error);
            }
        }
        );
    },
    
    updateActivitiesSubpanel: function(){
        app.alert.dismissAll();
        var linkName = 'historical_summary';
        var subpanelCollection = this.model.getRelatedCollection(linkName);
        subpanelCollection.fetch({relate: true});
        app.events.trigger('refreshActivitiesDashlet');
    },
    
    removeExtraCategories: function() {
        var self = this;
        var record_id = self.model.get('id');
        var moduleName = self.module;
        
        app.alert.show("app-process", {
            level: 'process',
            messages: 'Loading',
            autoClose: true
        });
        
        var url = app.api.buildURL('Leads/removeExtraCategories/' + record_id+'/'+moduleName, null, null, null);
        app.api.call('GET', url, null, {
            success: function (data) {
                app.alert.dismissAll();
                app.alert.show("server-sucess", {
                    level: data.level,
                    messages: data.message,
                    autoClose: true
                });
                
                if(_.isEqual(data.level,'success')) {
                    self.refreshDocumentsAfterManualConversion();
                }
            },
            error: function (error) {
                aapp.alert.dismissAll();
                app.alert.show("server-error", {
                    level: 'error',
                    messages: 'ERR_GENERIC_SERVER_ERROR',
                    autoClose: false
                });
            }
        });
    },
    rciPortal: function () {
        var self = this;
        app.alert.show('rci-portal-loading', {level: 'process'});
        var lead_id = self.model.get('id');
        app.api.call('create', app.api.buildURL('Leads/rciPortal/' + lead_id), {lead_id: lead_id, module: 'Leads'}, {
            success: function (rciForm) {
                app.alert.dismissAll();
                $(".rci_portal_form").remove();
                $(rciForm).appendTo('body').submit();
            },
            error: function (error) {
                app.alert.dismissAll();
                app.alert.show("server-error", {
                    level: 'error',
                    messages: 'ERR_GENERIC_SERVER_ERROR',
                    autoClose: false
                });
                app.error.handleHttpError(error);
            }
        });
    },
    /*
     * Unregistering Delete ShortCut from Detail View
     */
    registerShortcuts: function () {
        this._super('registerShortcuts');
        app.shortcuts.unregister('Record:Delete', this);
    }
});
