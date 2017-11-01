({
    className: 'yourmodule-setup',
    plugins: ['Editable'],
    currentStep: 1,
    status: null,
    subStatus: null,
    nextStep: null,
    templat: null,
    documentTracking: null,
    renderComplete: null,
    relContractName: null,
    relContractId: null,
    edit_mode_enable: false,
    lead_id : null,
    description : null,
    provision : null,
    first_name : null,
    last_name : null,
    assigned_user_name : null,
    assigned_user_id : null,
    events: {
        'click .move-to-selected-step:not(.active)': 'moveToSelectedStep',
        'click [name="lq_auto_excute"]:not(.disabled)': 'autoExecute',
        'click #lq_create_task:not(.disabled)': 'createTask',
        'click #save:not(.disabled)': 'saveModel',
        'click #cancel:not(.disabled)': 'cancelSave',
        'click #lq_create_contract:not(.disabled)': 'preContract',
        'click #lq_create_application:not(.disabled)': 'createApplication',
    },
    initialize: function (opts) {
        self2view = this;
        app.view.View.prototype.initialize.call(this, opts);
        this._super('initialize', [opts]);
        app.events.on('createContract', _.bind(this.createContract, this));
        //this.model.on("change:credit_request_status_id_c", this.setEditMode, this);
        this.status = this.meta.panels[0].fields[0];
        this.subStatus = this.meta.panels[0].fields[1];
        this.nextStep = this.meta.panels[0].fields[2];
        this.documentList = app.lang.getAppListStrings("dotb_document_category_list");
        this.statusList = app.lang.getAppListStrings("status_list");
        this.templat = "edit";
        Handlebars.registerPartial('lq-wizard.header', app.template.get('lq-wizard.header.Leads'));
        Handlebars.registerPartial('lq-wizard.footer', app.template.get('lq-wizard.footer.Leads'));
        this.setStep(1);
        this.getSelectedStepTemplate(self2view.currentStep);
        this.makeReadOnly();
        this.getRelContractInfo();
    },
    _render: function () {
        this._super('_render');
        $('.cstm-wizard-panel [data-name=credit_request_status_id_c]').css('pointer-events', 'none');
        $('.move-to-selected-step').removeClass('active');
        $('#' + this.steps[this.currentStep].template).addClass('active');
        $('a#lq-wizard-step' + self2view.currentStep).addClass('active');
        //this.switchOptions();
        this.setNBSOptions();
        /**
         * CRED-906 : Workflows with next best step 11 not dropping
         *  on Edit screen for substatus
         */
        if(this.model.get('editMode') === true) {
            $('a[name=save_button]').click();
        }
        return this;
    },
    setEditMode: function () {
        if (this.edit_mode_enable) {
            $('[name=edit_button]').click();
        }
        this.edit_mode_enable = true;
    },
    makeReadOnly: function () {
        var self = this;
        self.model.fetch({
            success: function () {
                if ((self.model.get("credit_request_status_id_c") == "11_closed" && self.model.get("credit_request_substatus_id_c") != "") || self.model.get("credit_request_status_id_c") == "10_active") {
                    self.templat = "detail";
                    self._render();
                }
            }
        });
    },
    getRelContractInfo: function () {
        var self = this;
        var subpanelCollection = self.model.getRelatedCollection('contracts_leads_1');
        subpanelCollection.fetch({
            relate: true,
            success: function () {
                if (subpanelCollection.models && subpanelCollection.models.length > 0) {
                    self.relContractId = subpanelCollection.models[0].get('id');
                    self.relContractName = subpanelCollection.models[0].get('name');
                }
            }
        });
    },
    bindDataChange: function () {
        var self = this;
        this.model.on('change', function (fieldType) {
            if (fieldType.changed._module == "Leads" || fieldType._changing == false) {
                self.setNBSOptions();
            }
            if (fieldType && fieldType.changed && fieldType.changed.credit_request_status_id_c) {
                self.setNBSOptions();
            }
        }, this);
    },
    _dispose: function () {
        this.model.off("change:credit_request_status_id_c");
        this.model.off('change');
        app.events.off('createContract');
        this._super('_dispose');
    },
    autoExecute: function () {
        var self = this;
        var previous_status;
        previous_status = self.model.get("credit_request_status_id_c");
        var emailAddress = self.model.get("email");
        if (!emailAddress || !emailAddress[0] || !emailAddress[0].email_address) {
            app.alert.show('initial-info-loading', {
                level: 'error',
                title: 'Please add primary email address',
                autoClose: true,
            });
            return;
        }
        var url = App.api.buildURL('AutoExecuteWF/AutoExecute');
        if (self.model.get("lq_next_best_steps_c") == '' || !self.model.get("lq_next_best_steps_c")) {
            app.alert.show('initial-info-loading', {
                level: 'error',
                title: 'Please select next best step',
                autoClose: true,
            });
        } else {
            app.alert.show('auto_exec_confirmation', {
                level: 'confirmation',
                messages: App.lang.getAppListStrings("next_best_steps_msgs")[self.model.get("credit_request_status_id_c") + "_" + self.model.get("lq_next_best_steps_c")],
                onConfirm: function () {
                    app.alert.show('auto_executing_msg', {
                        level: 'process',
                        title: 'Executing Tasks, Please wait...',
                        autoClose: false,
                    });
                    app.api.call('create', url, {
                        id: self.model.id,
                        nextBestStep: self.model.get("lq_next_best_steps_c"),
                        status: self.model.get("credit_request_status_id_c")
                    }, {
                        success: _.bind(function (result) {
                            var response=result['response'];
                            response=response.trim();
                            App.alert.dismissAll();
                            if (response == "no_due_date") {
                                app.alert.show('no_due_date_alert', {
                                    level: 'error',
                                    title: 'Das Auszahlungsdatum ist nicht bekannt, bitte erfassen.',
                                    autoClose: false,
                                });
                                return;
                            } else if (response == "app_has_empty_values") {
                                app.alert.show('app_has_empty_values_alert', {
                                    level: 'error',
                                    title: 'Bitte im Antrag die Felder "von Kunde gewünscht" befüllen. Danke.',
                                    autoClose: false,
                                });
                                return;
                            } else if (response == "add_substatus") {
                                app.alert.show('add_substatus_alert', {
                                    level: 'error',
                                    title: app.lang.get('LBL_ADD_SUBSTATUS', 'Leads'),
                                    autoClose: false,
                                });
                                return;
                            } else if (response == "non_rejected_app") {
                                app.alert.show('non-rejected-apps', {
                                    level: 'info',
                                    title: 'Multiple NON REJECTED applications are found, Click <a href="#Tasks/' + result['task_id'] + '"><b><i>here</i></b></a> to set Credti-Amount in the Task',
                                    autoClose: false,
                                });
                                self.model.set("credit_request_status_id_c", "07_creating_contract");
                                /**
                                * CRED-856: Removed return so that for multiple applications task drawer can be opened.
                                */
                                // return;
                            }
                            app.alert.show('auto_executing_success', {
                                level: 'success',
                                title: 'Executed Successfully',
                                autoClose: true,
                            });
                            /*
                             * Opening the newly created task in edit view to populate values from the related application if there are multiple applications linked to a lead
                             */
                            if (!_.isEmpty(result['task_id'])) {
                                var app_approval_rule = false;
                                var task_assigned_user = false;
                                var trigger_lead_save = false;
                                var tsk_id = '';
                                tsk_id = result['task_id'];
                                if(result.hasOwnProperty('app_approval_rule')){
                                    app_approval_rule = result['app_approval_rule'];
                                }
                                if(result.hasOwnProperty('set_task_assigned_user')){
                                    task_assigned_user = result['set_task_assigned_user'];
                                }
                                if(response == "closed_substatus"){
                                    trigger_lead_save = true;
                                }
                                app.alert.show('opening-task', {
                                    level: 'process',
                                    title: 'Opening Created Task...',
                                    autoClose: false,
                                });
                                app.api.call(
                                    'read',
                                    app.api.buildURL('Tasks', 'read', {id: result['task_id']}),
                                    null,
                                    {
                                        success: function (data) {
                                            app.alert.dismiss('opening-task');
                                            bean = app.data.createBean(data._module, data);
                                            app.drawer.open({
                                                layout: 'quickedit',
                                                context: {
                                                    create: true,
                                                    model: bean,
                                                    module: bean.get('_module'),
                                                    from_wf :  true,
                                                    task_id : tsk_id,
                                                    app_approval_rule : app_approval_rule,
                                                    task_assigned_user : task_assigned_user,
                                                    trigger_lead_save : trigger_lead_save
                                                }
                                            },
                                            /**
                                             * CRED-840 : Page not scrolled to the bottom (Activties subpanel)
                                             *  when application drawer is closed
                                             */
                                            function () {
                                                var position = $('[data-subpanel-link="historical_summary"]').position().top;
                                                $('.main-pane').scrollTop(position);
                                            });
                                        },
                                        error: function () {
                                            return;
                                        }
                                    }
                                );
                            }
                            self.model.fetch({
                                success: function () {
                                    self.setNBSOptions();
                                    self.model.set("lq_next_best_steps_c", "");

                                    /**
                                     * If status is 11-Closed then go to edit mode
                                     * to force the user to add substatus
                                     */
                                    if (self.model.get("credit_request_status_id_c") == "11_closed") {
                                        app.alert.show('auto_executing_success', {
                                            level: 'success',
                                            title: 'Lead is closed, Please select SubStatus',
                                            autoClose: false,
                                        });
                                        if (self.layout && self.layout._components[1] && self.layout._components[1].editClicked) {
                                            self.layout._components[1].editClicked();
                                        }
                                    }

                                    /**
                                     *	Refresh Activities subpanel and
                                     *	Scroll to actvities subpanel
                                     */
                                    var linkName = 'historical_summary';
                                    var activitiesCollection = self.model.getRelatedCollection(linkName);
                                    activitiesCollection.fetch({relate: true});
                                    app.events.trigger('refreshActivitiesDashlet');
                                    if(previous_status == '06_sales_conversation'){
                                        App.events.trigger('refreshDocumentPanel');
                                    }
                                    /* refresh applications subpanel so that updated user approval is shown for status 04 -> 04 Antrag einreichen ,06,07,08 -> 05 Neuer Antrag bei Bank einreichen*/
                                    var applicationsCollection = self.model.getRelatedCollection('leads_opportunities_1');
                                    applicationsCollection.fetch({relate: true});

                                    /**
                                     * CRED-389/770 : Refersing Applications Subpanel after execution of workflow
                                     */
                                    if (self.model.get("credit_request_status_id_c") == '10_active' || 
                                            self.model.get("credit_request_status_id_c") == '09_payout') {
                                        var linkName = 'leads_opportunities_1';
                                        var appCollection = self.model.getRelatedCollection(linkName);
                                        appCollection.fetch({relate: true});
                                    }
                                    var position = $('[data-subpanel-link="' + linkName + '"]').position().top;
                                    $('.main-pane').scrollTop(position);
                                    if(response=='closed'){
                                        self.model.set("credit_request_status_id_c", "11_closed");
                                        $('a[name=edit_button]').click();
                                        /**
                                         * CRED-906 : Workflows with next best step 11 not dropping
                                         *  on Edit screen for substatus
                                         */
                                        self.model.set('editMode',true);
                                    }
                                    if(response=='00_pendent_geschlossen'){
                                        self.model.set("credit_request_status_id_c", "00_pendent_geschlossen");
                                        $('a[name=edit_button]').click();
                                        $('a[name=save_button]').click();
                                    }
                                    /**
                                     * CRED-816/817/818/819/820/521 : Moving Open Tasks to Contacts
                                     * When Status changed to 11_closed
                                     */
                                    if (response == 'closed_substatus') {
                                        self.model.set("credit_request_status_id_c", "11_closed");
                                        self.model.set("credit_request_substatus_id_c", "waiver");
                                        if(!_.isEmpty(result['task_id']))
                                            $('a[name=edit_button]').click();
                                    }
                                }
                            });
                        }, this)
                    });
                }
            });
        }

    },
    setStep: function (step) {
        this.currentStep = step;
    },
    getSelectedStepTemplate: function (step) {
        this.currentStep = step;
        this.template = app.template.getView('lq-wizard.' + this.steps[this.currentStep].template, 'Leads');
        this.steps[this.currentStep].initialize(self2view);
        if (!this.template) {
            app.error.handleRenderError(this, 'view_render_denied');
        }
    },
    cancelSave: function () {
        this.templat = "detail";
        this.render();
    },
    saveModel: function () {
        this.model.set('credit_request_status_id_c', this.model.get('credit_request_status'));
        this.model.set('credit_request_substatus_id_c', this.model.get('credit_request_substatus'));
        this.model.save();

        app.alert.show('initial-info-loading', {
            level: 'process',
            title: 'Saving',
            autoClose: true,
        });
        $("#save").hide();
        this.templat = "detail";
        this.render();
        setTimeout(function () {
            app.alert.show('after-saving', {
                level: 'success',
                title: 'Success Saved',
                autoClose: true,
            });

        }, 3000);


    },
    handleEdit: function () {

        this.templat = "edit";
        this.render();
    },
    createTask: function () {
        var self = this;
        var status = self.model.get('credit_request_status_id_c');
        status=status.trim();
        if (status == '') {
            app.alert.show("status-empty", {
                level: 'confirmation',
                messages: app.lang.get('LBL_NO_TASK_FOR_STATUS_EMPTY', 'dotb6_contact_activities'),
                autoClose: false,
                onConfirm: function(){
                    self.openCustomTaskDrawer();
                },
                onCancel: function(){
                }
            });
        } else{
           self.openCustomTaskDrawer();
        }
    },
    openCustomTaskDrawer: function(){
        var self = this;
        App.drawer.open({
                layout: 'create',
                context: {
                    create: true,
                    module: 'Tasks'
                }
            }, function () {
            self.reloadSubpanel(self, 'historical_summary');
            app.events.trigger('refreshActivitiesDashlet');
        });  
    },
    createApplication: function () {
        var self = this;
        var appModel = App.data.createBean('Opportunities', {});
        var applicationFieldsMap = {
            'name': 'name',
            'description': 'description',
            'lead_source': 'lead_source',
            'mkto_id': 'mkto_id',
            'mkto_sync': 'mkto_sync',
            'credit_amount_c': 'amount',
            'assigned_user_name': 'assigned_user_name',
            'assigned_user_id': 'assigned_user_id',
        };
        for (var prop in applicationFieldsMap) {
            appModel.set(applicationFieldsMap[prop], self.model.get(prop));
        }
        appModel.set('lead_first_name', self.model.get('first_name') + ' ' + self.model.get('last_name'));
		
		var full_name = self.model.get('first_name');
		if(full_name != '')
			full_name = full_name + " " + self.model.get('last_name');
		else 
			full_name = self.model.get('last_name');
		appModel.set('leads_opportunities_1_name', full_name);
		appModel.set('leads_opportunities_1leads_ida', self.model.get('id'));
        //appModel.set('create_approval_task',true);
        app.drawer.open({
            layout: 'create',
            context: {
                module: "Opportunities",
                model: appModel,
                create: true
            },
        }, function (context, model) {
            //$('[data-name="team_name"]').show();
            var linkName = 'leads_opportunities_1';
            var subpanelCollection = self.model.getRelatedCollection(linkName);
            subpanelCollection.fetch({
                relate: true
            });

            linkName = 'historical_summary';
            var subpanelCollection = self.model.getRelatedCollection(linkName);
            subpanelCollection.fetch({
                relate: true
            });
            app.events.trigger('refreshActivitiesDashlet');
            //set lead status '07_creating_contract' if auto_assign_task is checked
            if (appModel.attributes.auto_assign_task) {
                self.model.set("credit_request_status_id_c", '07_creating_contract');
            }
        });
    },
    preContract: function () {
        var self = this;
        this.lead_id = self.model.get('id');
        this.description = self.model.get('description');
        this.provision = self.model.get('provision_c');
        this.first_name = self.model.get('first_name');
        this.last_name = self.model.get('last_name');
        this.assigned_user_name = self.model.get('assigned_user_name');
        this.assigned_user_id = self.model.get('assigned_user_id');
        app.alert.show('creating-contract-msg', {
            level: 'process',
            title: 'Creating Contract please wait...',
            autoClose: true,
        });
        var url = app.api.buildURL('ConvLead/CreateContract');
        app.api.call('create', url, {
            id: self.model.id,
        }, {
            success: _.bind(function (result) {
                var response = result['response'];
                if (response == 'no_approved') {
                    app.alert.dismissAll();
                    app.alert.show('creating-contract-no_approved', {
                        level: 'error',
                        title: 'No Application is approved, Please do it manually',
                        autoClose: true,
                    });
                } else if (response == 'multi_approved') {
                    app.alert.dismissAll();
                    app.drawer.open({
                        layout: 'choose_app',
                        context: {
                            id: self.model.get('id'),
                            granted_apps: result['granted_apps']
                        }
                    }, function (context, model) {
                        app.drawer.close();
                    });
                } else {
                    app.alert.dismissAll();
                    self.createContract(result);
                }
            }, this),
            error: _.bind(function (response) {
                app.alert.show('creating-contract-error', {
                    level: 'error',
                    title: 'An error occurred while processing your request',
                    autoClose: false,
                });
            }, this),
        });
    },
    createContract: function (response) {
        var self = this;
        var modelPrefil = app.data.createBean("Contracts");
        modelPrefil.set({
            opportunity_id: response['opportunity_id'],
            account_id: response['account_id'],
            contracts_leads_1_id: self.lead_id,
            contracts_leads_1leads_idb:  self.lead_id,
            description: self.description,
            provision_c: self.provision == 0.00 ? '' : self.provision,
            name: self.first_name + ' ' + response['account_name'] + ' ' + response['interest_rate'] + ' ' + response['duration'] + ' ' + response['ppi'] + ' ' + response['current_date_time'] + ' Vertrag',
            start_date: response['current_date'],
            customer_signed_date: response['current_date'],
            company_signed_date: response['current_date'],
            status: 'inprogress',
            create_contact: true,
            lead_first_name: self.first_name + ' ' + self.last_name,
            provider_id_c: response['provider_id_c'],
            assigned_user_name: self.assigned_user_name,
            assigned_user_id: self.assigned_user_id,
            provider_contract_no: response['provider_contract_no']
        });
        
        /**
         * CRED-995 : Application- and Contract-Handling
         */
        if(response['provider_id_c'] == 'bank_now_flex') {
            modelPrefil.set('credit_amount_flex', response['customer_credit_amount_flex']);
            modelPrefil.set('credit_duration_flex', response['customer_credit_duration_flex']);
            modelPrefil.set('interest_rate_flex', response['customer_interest_rate_flex']);
            modelPrefil.set('customer_credit_amount_flex', response['customer_credit_amount_flex']);
            modelPrefil.set('customer_credit_duration_flex', response['customer_credit_duration_flex']);
            modelPrefil.set('customer_interest_rate_flex', response['customer_interest_rate_flex']);
            modelPrefil.set('customer_first_payment_flex', response['customer_contract_first_payment_flex']);
            modelPrefil.set('first_payment_flex', response['customer_contract_first_payment_flex']);
            modelPrefil.set('customer_ppi_flex',response['customer_ppi_c']);
            modelPrefil.set('contract_ppi_plus_flex',response['contract_ppi_plus']);
            modelPrefil.set('contract_transfer_fee_flex',response['contract_transfer_fee']);
            modelPrefil.set('soko_flex', response['soko']);
            
            if (response['ppi'] == "PPI") {
                modelPrefil.set('ppi_flex', true);
            }
            else {
                modelPrefil.set('ppi_flex', false);
            }
        } else {
            modelPrefil.set('credit_amount_c', response['customer_credit_amount_c']);
            modelPrefil.set('credit_duration_c', response['customer_credit_duration_c']);
            modelPrefil.set('interest_rate_c', response['customer_interest_rate_c']);
            modelPrefil.set('customer_credit_amount_c', response['customer_credit_amount_c']);
            modelPrefil.set('customer_credit_duration_c', response['customer_credit_duration_c']);
            modelPrefil.set('customer_interest_rate_c', response['customer_interest_rate_c']);
            modelPrefil.set('customer_ppi_c', response['customer_ppi_c']);
            modelPrefil.set('contract_ppi_plus', response['contract_ppi_plus']);
            modelPrefil.set('contract_transfer_fee', response['contract_transfer_fee']);
            modelPrefil.set('dotb_soko_c', response['soko']);

            if (response['ppi'] == "PPI") {
                modelPrefil.set('ppi_c', true);
            }
            else {
                modelPrefil.set('ppi_c', false);
            }
        }
        
        
        app.drawer.open({
            layout: 'create',
            context: {
                create: true,
                module: 'Contracts',
                model: modelPrefil,
            }
        }, function (context, model) {
            //relate to lead
            var relContract = App.data.createRelatedBean(model, self.lead_id, 'contracts_leads_1');
            relContract.save(null, {relate: true});
            self.createContact(self, model.id);

            /**
             * Create Credit History Record
             */
            var url = app.api.buildURL('ConvLead/CreateCrHistory');
            app.api.call('create', url, {
                id: self.model.id,
                contractId: model.id,
            }, {
                success: _.bind(function (response) {
                    var CrCollection = self.model.getRelatedCollection('leads_dotb5_credit_history_1');
                    CrCollection.fetch({
                        relate: true
                    });
                    /**
                    * CRED-806 : Refreshing the page to resolve issue of unlinking of contract
                    */
                    app.router.refresh();
                }, this),
                error: _.bind(function (response) {
                }, this),
            });
            self.model.fetch({
                success: function (response) {
                    self.relContractId = model.id;
                    self.relContractName = model.get("name");
                    self.model.set("contract_created_c", true);
                    self.render();
                }
            });
            app.drawer.close();
        });
    },
    createContact: function (self, contractId) {
        app.alert.show('creating-contact-msg', {
            level: 'process',
            title: 'Creating Contact please wait...',
            autoClose: true,
        });
        var url = app.api.buildURL('ConvLead/CreateContact');
        app.api.call('create', url, {
            id: self.model.id,
            contractId: contractId,
        }, {
            success: _.bind(function (response) {
                if (response && response.id && response.name) {
                    app.alert.show('creating-contact-duplicate', {
                        level: 'info',
                        title: 'Duplicate contact found, Contract is created with',
                        messages: '<a href="#Contacts/' + response.id + '">' + response.name + '</a>',
                        autoClose: false,
                    });
                } else {
                    app.alert.dismissAll();
                    app.alert.show('creating-contact-success', {
                        level: 'success',
                        title: 'Contact created successfully',
                        autoClose: true,
                    });
                }
            }, this),
            error: _.bind(function (response) {
                app.alert.show('creating-contact-error', {
                    level: 'error',
                    title: 'Faild to create contact',
                    autoClose: false,
                });
            }, this),
        });
    },
    bindChangeEvents: function () {
        var self = this;
        self.model.on("change:credit_request_status_id_c", _.bind(self.setNBSOptions, self));
    },
    setNBSOptions: function () {
        var self = this;
        var parent_val = self.model.attributes.credit_request_status_id_c;
        //self.getField("lq_next_best_steps_c").items = app.lang.getAppListStrings("lq_nbs_" + parent_val);
        self.getField("lq_next_best_steps_c").def.options = "lq_nbs_" + parent_val;
        self.getField("lq_next_best_steps_c").items = null;
        self.getField("lq_next_best_steps_c").render();
        self.model.set("lq_next_best_steps_c", "");
    },
    reloadSubpanel: function (self, link) {
        var subpanelCollection = self.model.getRelatedCollection(link);
        subpanelCollection.fetch({
            relate: true
        });
    },
    moveToSelectedStep: function (event) {
        var self = this;
        var $currentTarget = $(event.currentTarget);
        this.getSelectedStepTemplate($currentTarget.attr('step'));
        if (this.currentStep == 2) {
            this.showDocumnetsPanel();
        }
        this.render();
    },
    showDocumnetsPanel: function () {
        var parent = $('[data-panelname="LBL_RECORDVIEW_PANEL5"]').parent().attr("id").split("view")[0];
        $('.tab.' + parent + ' a').trigger('click');
        var position = $('[data-panelname="LBL_RECORDVIEW_PANEL5"]').position().top;
        //$('.main-pane').scrollTop(position);
        var childs = $('[data-panelname="LBL_RECORDVIEW_PANEL5"]').children("div");
        $(childs[0]).removeClass('panel-inactive');
        $(childs[0]).addClass('panel-active');
        $(childs[1]).show();
    },
    steps: {
        1: {
            template: "initial",
            message: "Initial information",
            initialize: function (self) {
                app.alert.dismissAll();
                app.alert.show('initial-info-loading', {
                    level: 'process',
                    title: 'Loading initial information status',
                    //autoClose: false,
                    autoClose: true
                });
                self.model.set('lq_status', "Valid");
                self.render();
            },
            postRender: function (self) {
            },
        },
        2: {
            template: "documents",
            message: "Documents view",
            initialize: function (self) {
                app.alert.dismissAll();
                app.alert.show('doc-info-loading', {
                    level: 'process',
                    title: 'Loading Documents information',
                    autoClose: true
                });
                var url = "rest/v10/Leads/GetRelatedDocuments/" + self.model.id;
                app.api.call('GET', url, null, {
                    success: function (response) {
                        self.documentTracking = response;
                        for (i = 0; i < response.length; i++) {
                            self.documentTracking[i].category = self.documentList[self.documentTracking[i].category];
                            self.documentTracking[i].status = self.statusList[self.documentTracking[i].status];
                        }
                        self.render();
                    },
                    error: function (error) {
                    }
                });
            },
            postRender: function (self) {
            },
        },
        3: {
            template: "application",
            message: "Application view",
            initialize: function (self) {
                app.alert.dismissAll();
                app.alert.show('app-info-loading', {
                    level: 'process',
                    title: 'Loading Application information',
                    autoClose: true
                });
            },
            postRender: function (self) {
            },
        },
        4: {
            template: "contract",
            message: "Contract View",
            initialize: function (self) {
                app.alert.dismissAll();
                app.alert.show('contract-info-loading', {
                    level: 'process',
                    title: 'Loading Contract information',
                    autoClose: true
                });
            },
            postRender: function (self) {
            },
        }
    }
})