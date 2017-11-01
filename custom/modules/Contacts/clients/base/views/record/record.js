({
    extendsFrom: 'ContactsRecordView',
    custom_change: false,
    civil_change: false,
    date_change: false,
    partner: false,
    initialize: function (options) {
        //app.view.invokeParent(this, {type: 'view', name: 'record', method: 'initialize', args:[options]});
        this._super('initialize', [options]);
        //for lead qualification rule
        
        // listener for enabling inline edit for front-view fields
        app.events.on('enableEdiForFrontViewForContacts', _.bind(this.enableEdiForFrontViewForContacts, this));
        app.events.on('refreshLeadSubpanelOnContact', _.bind(this.refreshLeadSubpanelOnContact, this));

        this.model.addValidationTask('Custom Validation Logic', _.bind(this._requiredFieldsForPartner, this));
        this.model.on("change:dotb_had_past_credit", this.pastCreditAlert, this);
        this.model.on("change:dotb_civil_status_id", this.civilStatusAlert, this);
        this.model.on("change:dotb_resident_since", this.residenceAlert, this);
        this.context.on('button:create_lead:click', this.create_lead, this);
        this.model.on("change:dotb_partner_agreement_c", this.partnerAlert, this);
        this.model.on("change:dotb_gender_id", this.changeSalutation, this);
        this.model.on("change:dotb_civil_status_id", this.changeSalutation, this);
        this.model.on("change:dotb_correspondence_language", this.changeSalutation, this);
        this.model.on("change:birthdate", this.ageCalculation, this);
        this.model.on("change:dotb_rent_alimony_income_c", this.emptyIncomeDescField, this);
        
        //this.model.on("change:relative_type_c", this.hidePartnerTab, this);
        //Function Binding for Auto-populating  country from postal code
        this.events['blur input[name=primary_address_postalcode]'] = 'populateAddress';
        this.events['blur input[name=correspondence_address_postalcode]'] = 'populateCorrespondenceAddress';
        this.events['blur input[name=dotb_bank_zip_code]'] = 'populateBankAddress';
        this.events['blur input[name=dotb_employer_npa]'] = 'populateEmployerAddress';
        this.events['blur input[name=dotb_second_job_employer_npa]'] = 'populateSecondEmployerAddress';
        
        //Binding Function to Unregister Delete ShortCut
        this.on('render', this.registerShortcuts, this);

    },
    emptyIncomeDescField: function () {
        var alimony = this.model.get("dotb_rent_alimony_income_c");
        if (alimony == "no" || alimony == "") {
            this.model.set({"dotb_additional_income_desc": ""});
        }

    },
    enableEdiForFrontViewForContacts: function(){
        var self = this;
        self.setButtonStates(self.STATE.EDIT);
    },
    refreshLeadSubpanelOnContact: function(){
        var self = this;
        var linkName =  'leads';
        var leadItself = self.model.getRelatedCollection(linkName);
        leadItself.fetch({relate: true});
    },
    _dispose: function () {
        app.events.off('enableEdiForFrontViewForContacts');
        app.events.off('refreshLeadSubpanelOnContact');
        this.model.off("change:dotb_had_past_credit");
        this.model.off("change:dotb_civil_status_id");
        this.model.off("change:dotb_resident_since");
        this.context.off('button:create_lead:click');
        this.model.off("change:dotb_partner_agreement_c");
        this.model.off("change:dotb_gender_id");
        this.model.off("change:dotb_civil_status_id");
        this.model.off("change:birthdate");
        this.model.off("change:relative_type_c");
        this.model.off("change:dotb_rent_alimony_income_c");
        this._super('_dispose');
    },
    refreshDocumentsAfterManualConversion: function () {
        if($('.document-preview').is(":visible")) {
            $('.document-preview').html('');
            $('.document-preview').hide();
            $('.dashboard-pane').show();
        }
        // to refresh document panel
        App.events.trigger('refreshDocumentPanel');
    },
    pastCreditAlert: function () {
        if (this.custom_change) {
            var past_credit = this.model.get('dotb_had_past_credit');
            if (past_credit == 'yes') {
                app.alert.show("past-credit-msg", {
                    level: 'info',
                    messages: 'Bitte bestehende Kredite in SubPanel Kredithistorie erfassen', //' Please add additional addresse(s) for 3 years back.',
                    autoClose: true
                });
            }
        }
        this.custom_change = true;
    },
    populateLocation: function (postalcode, city, country) {
        var self = this;
        var postalCode = this.model.get(postalcode);
        var postalCodes = ["9485", "9486", "9487", "9488", "9498", "9489", "9490", "9491", "9492", "9493", "9494", "9495", "9496", "9497"];
        
        var list1 = ['primary_address_city','correspondence_address_city'];
        var list2 = ['dotb_bank_city_name','dotb_employer_town', 'dotb_second_job_employer_town'];
        
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
                primary_city_html+='<option value="">'+app.lang.get('LBL_PLEASE_CHOOSE_OPTION','Contacts')+'</option>';
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
        this.populateLocation('dotb_bank_zip_code', 'dotb_bank_city_name', '');
    },
    populateEmployerAddress: function () {
        this.populateLocation('dotb_employer_npa', 'dotb_employer_town', '');
    },
    populateSecondEmployerAddress: function () {
        this.populateLocation('dotb_second_job_employer_npa', 'dotb_second_job_employer_town', '');
    },
    hidePartnerTab: function () {
        $("li.tab.more").hide();
        $("li.tab.LBL_RECORDVIEW_PANEL31").hide();
        $("#tabContent #LBL_RECORDVIEW_PANEL31view49").hide();
        $("li.tab.LBL_RECORDVIEW_PANEL10 a").click();
    },
    render: function (argument) {
        this._super('render');
        this.hidePartnerTab();
        this.residenceAlert();
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
    civilStatusAlert: function () {
        if (this.civil_change) {
            var status = this.model.get('dotb_civil_status_id');
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
                    autoClose: true
                });
            }

        }
        this.partner = true;
    },
    residenceAlert: function () {
        var contact_address_since = this.model.get('dotb_resident_since');
        if (this.date_change) {
            /*var d = new Date(),
             month = '' + (d.getMonth() + 1),
             day = '' + d.getDate(),
             year = d.getFullYear();
             
             if (month.length < 2)
             month = '0' + month;
             if (day.length < 2)
             day = '0' + day;
             
             var x = [year, month, day].join('-');
             var since = this.model.get('dotb_resident_since');
             
             var todate = x.split("-");
             var sincedate = since.split("-");
             
             var d2 = new Date(todate[0], todate[1], todate[2]);
             var d1 = new Date(sincedate[0], sincedate[1], sincedate[2]);
             
             var months;
             months = (d2.getFullYear() - d1.getFullYear()) * 12;
             months -= d1.getMonth() + 1;
             months += d2.getMonth();
             months <= 0 ? 0 : months;
             
             if (months < 36) {
             app.alert.show("residence-msg", {
             level: 'info',
             messages: 'Alte Adressen erfassen (3 Jahre)',
             autoClose: true
             });
             }
             */
            var totalMonths = 0;
            var contact_bean = app.data.createBean('Contacts', {id: this.model.id});
            contact_bean.fetch({
                success: function () {
                    var months = contact_bean.get('address_months_c');
                    if (months != '')
                        totalMonths = months;

                    if (contact_address_since) {
                        var now = new Date();
                        contact_address_since = contact_address_since.split("-");
                        contact_address_since = new Date(contact_address_since[0], contact_address_since[1], contact_address_since[2]);
                        var lead_address_since_months = now.getMonth() - contact_address_since.getMonth() + (12 * (now.getFullYear() - contact_address_since.getFullYear()));
                        lead_address_since_months = lead_address_since_months + 1;
                        lead_address_since_months <= 0 ? 0 : lead_address_since_months;

                        totalMonths = parseInt(totalMonths) + parseInt(lead_address_since_months);
                    }
                    //console.log("totalMonths BEFORE ALERT: " +totalMonths);
                    if (totalMonths < 36) {
                        app.alert.show("residence-msg", {
                            level: 'info',
                            messages: 'Alte Adressen erfassen (3 Jahre)',
                            autoClose: false
                        });
                    }
                }
            });
        }
        this.date_change = true;
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

            if (dob == undefined || dob == '') {
                errors['birthdate'] = "Birthdate is required";
                callback(null, fields, errors);
            }
        }
        callback(null, fields, errors);
    },
    create_lead: function (ignore_duplicate) {
        var self = this;
        var url = app.api.buildURL('ConvLead/CreateLead');
        app.alert.show('creating-lead-process', {
            level: 'process',
            title: 'Creating Lead...',
            autoClose: false,
        });
        app.api.call('create', url, {
            id: self.model.id,
            ignore_duplicate:ignore_duplicate,
        }, {
            success: _.bind(function (response) {
                if (response && response.id) {
                    app.alert.dismissAll();
                    app.alert.show('creating-contact-duplicate', {
                        level: 'confirmation',
                        title: 'Duplicate Lead found <a href="#Leads/' + response.id + '">' + response.name + '</a>',
                        messages: app.lang.get('LBL_UPLICATE_CONFIRM', self.module),
                        autoClose: false,
                        onConfirm: function () {
                            app.drawer.open({
                            layout:'leadconvert-document-preview',
                            context:{
                                        create: true,
                                        module: self.module,
                                        model: self.model,
                                   }
                            }, function () {
                                self.refreshLeadSubpanelOnContact();
                           });
                        },
                        onCancel: function () {
                            //Do Nothing
                        }
                    });

                }
                else{
                    app.alert.dismissAll();
                    app.drawer.open({
                    layout:'leadconvert-document-preview',
                    context:{
                                create: true,
                                module: self.module,
                                model: self.model,
                           }
                    }, function () {
                        self.refreshLeadSubpanelOnContact();
                    });
                }
            }, this),
            error: _.bind(function (response) {
                app.alert.dismissAll();
                app.alert.show('creating-lead-error', {
                    level: 'error',
                    title: 'Faild to create Lead',
                    autoClose: false,
                });
            }, this),
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