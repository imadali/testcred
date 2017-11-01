({
    extendsFrom: 'BaseEmailsComposeView',
    selected_app: '',
    lead_id: '',
    assigned_user_id: '',
    language: '',
    email_missing_docs: false,
    email_to_crif: false,
    initialize: function (options) {
        this._super("initialize", [options]);
    }, _render: function () {
        this._super("_render");
        if (this.createMode) {
            this.setTitle(app.lang.get('LBL_COMPOSEEMAIL', this.module));
        }
        if (this.model.isNotEmpty) {
            var prepopulateValues = this.context.get('prepopulate');
            if (!_.isEmpty(prepopulateValues)) {
                this.prepopulate(prepopulateValues);
                this.selected_app = prepopulateValues.selected_app;
                this.lead_id = prepopulateValues.lead_id;
                this.email_missing_docs = prepopulateValues.email_missing_docs;
                this.email_to_crif = prepopulateValues.email_to_crif;
                this.templateDrawerCallback(this.context.get('templateModel'));
                if (prepopulateValues.preFillAttachement) {
                    var models = this.context.get('documentModel');
                    var self = this;
                    _.each(models, function (model, fieldName) {
                        self.documentDrawerCallback(model);

                    });

                    if (this.email_missing_docs || this.email_to_crif) {
                        $('.alert-btn-confirm').click();
                        var emailModels = this.context.get('templateModel');
                        emailModels.fetch({
                            success: function () {
                                self.insertTemplate(emailModels);
                            }

                        });
                    }

                }
            }
            this.addSenderOptions();
            if (this.model.isNew()) {
                this._updateEditorWithSignature(this._lastSelectedSignature);
            }
        }
        this.notifyConfigurationStatus();

    },
    saveModel: function (status, pendingMessage, successMessage, errorMessage) {
        var self = this;
        var myURL,
        sendModel = this.initializeSendEmailModel();     
        //sendModelTemp = sendModel;
        if (this.lead_id != '' && typeof this.lead_id !== "undefined") {
            var tempAttachments = sendModel.get('attachments');            
            //sendModel.attributes.parent_id = this.selected_app;
            //sendModel.attributes.parent_type = "Opportunities";            
            sendModel.attributes.parent_id = this.lead_id;
            sendModel.attributes.parent_type = "Leads";
            sendModel.attributes.related = {id: this.lead_id, type: "Leads"};
        }
        this.setMainButtonsDisabled(true);
        app.alert.show('mail_call_status', {level: 'process', title: pendingMessage});

        sendModel.set('status', status);
        myURL = app.api.buildURL('Mail');
        app.api.call('create', myURL, sendModel, {
            success: function () {
                app.alert.dismiss('mail_call_status');
                app.alert.show('mail_call_status', {autoClose: true, level: 'success', messages: successMessage});
                app.drawer.close(sendModel);
                app.events.trigger('updateSendEmailButton');
                
                if(self.email_to_crif){
                    app.events.trigger('updateSendEmailButtonForCRIF');
                }
            },
            error: function (error) {
                var msg = {level: 'error'};
                if (error && _.isString(error.message)) {
                    msg.messages = error.message;
                }
                app.alert.dismiss('mail_call_status');
                app.alert.show('mail_call_status', msg);
            },
            complete: _.bind(function () {
                if (!this.disposed) {
                    this.setMainButtonsDisabled(false);
                }
            }, this)
        });
    },
    prepopulate: function (values) {
        var self = this;
        _.defer(function () {
            _.each(values, function (value, fieldName) {
                switch (fieldName) {
                    case 'related':
                        self._populateForModules(value);
                        self.populateRelated(value);
                        break;
                    default:
                        self.model.set(fieldName, value);
                }
            });

        });
    },
    /**
     * Inserts the template into the editor.
     *
     * @param {Data.Bean} template
     */
    insertTemplate: function (template) {
        var subject,notes;
        var self = this;
        if(self.context.parent.get('model').get('assigned_user_id')) {
            self.assigned_user_id = self.context.parent.get('model').get('assigned_user_id');
        }
        if (this.email_missing_docs) {
            var url = App.api.buildURL("Emails/EmailParser/" + template.get("id") + "/lead/" + this.lead_id, null, null);
            App.api.call('read', url, null, {
                success: function (response) {
                    self.model.set('html_body', response);
                }
            });
            $('.alert-btn-confirm').click();
        } else if (this.email_to_crif) {
            var url = App.api.buildURL("Emails/CrifEmailParser/" + template.get("id") + "/lead/" + this.lead_id, null, null);
            App.api.call('read', url, null, {
                success: function (response) {
                    self.model.set('html_body', response);
                }
            });
            $('.alert-btn-confirm').click();
        } else if(self.assigned_user_id){
            
            /* Considering German as default Language 
               if not of the Language on Lead is selected
            */
            self.language = 'de';
            
            if(self.context.parent.get('model').get('dotb_correspondence_language')) {
                self.language = self.context.parent.get('model').get('dotb_correspondence_language');
            } else if(self.context.parent.get('model').get('dotb_correspondence_language_c')) {
                self.language = self.context.parent.get('model').get('dotb_correspondence_language_c');
            }

            var url = App.api.buildURL("Emails/signatureParserApi/" + template.get("id") + "/" + self.assigned_user_id+'/'+self.language, null, null);
            App.api.call('read', url, null, {
                success: function (response) {
                    
                    self.model.set('html_body', response);
                    subject = template.get('subject');
                    //fill template subject
                    if (subject) {
                        self.model.set('subject', subject);
                    }
                },
                error: function (error) {
                    app.alert.show("server-error", {
                        level: 'error',
                        messages: error,
                        autoClose: false
                    });
                }
            });
        } else {
        //attach template notes
        this.model.set('html_body', template.get('body_html'));
            notes = app.data.createBeanCollection('Notes');
            if (template.id != '' && typeof template.id != 'undefined') {
                notes.fetch({
                    'filter': {
                        'filter': [
                            {'parent_id': {'$equals': template.id}},
                            {'parent_type': {'$equals': 'Emails'}}
                        ]
                    },
                    success: _.bind(function (data) {
                        if (self.disposed === true)
                            return; //if view is already disposed, bail out
                        if (!_.isEmpty(data.models)) {
                            self.insertTemplateAttachments(data.models);
                        }
                    }, this),
                    error: _.bind(function (error) {
                        self._showServerError(error);
                    }, this)
                });
            }
        }
        // currently adds the html signature even when the template is text-only
        this._updateEditorWithSignature(this._lastSelectedSignature);
    },
    
    send: function () {
        var sendEmail = _.bind(function () {
        this.saveModel(
                    'ready',
                    app.lang.get('LBL_EMAIL_SENDING', this.module),
                    app.lang.get('LBL_EMAIL_SENT', this.module),
                    app.lang.get('LBL_ERROR_SENDING_EMAIL', this.module)
                    );
        }, this);

        if (!this.isFieldPopulated('to_addresses') &&
                !this.isFieldPopulated('cc_addresses') &&
                !this.isFieldPopulated('bcc_addresses')
                ) {
            this.model.trigger('error:validation:to_addresses');
            app.alert.show('send_error', {
                level: 'error',
                messages: 'LBL_EMAIL_COMPOSE_ERR_NO_RECIPIENTS'
            });
        } else if (!this.isFieldPopulated('subject') && !this.isFieldPopulated('html_body')) {
            app.alert.show('send_confirmation', {
                level: 'confirmation',
                messages: app.lang.get('LBL_NO_SUBJECT_NO_BODY_SEND_ANYWAYS', this.module),
                onConfirm: sendEmail
            });
        } else if (!this.isFieldPopulated('subject')) {
            app.alert.show('send_confirmation', {
                level: 'confirmation',
                messages: app.lang.get('LBL_SEND_ANYWAYS', this.module),
                onConfirm: sendEmail
            });
        } else if (!this.isFieldPopulated('html_body')) {
            app.alert.show('send_confirmation', {
                level: 'confirmation',
                messages: app.lang.get('LBL_NO_BODY_SEND_ANYWAYS', this.module),
                onConfirm: sendEmail
            });
        } else {
            var html_body = this.model.get('html_body');
                html_body = html_body.replace(/<p/g, '<p  style="font-size:x-small;font-family:arial, helvetica, sans-serif;" ');
                html_body = html_body.replace(/<span/g, '<span  style="font-size:x-small;font-family:arial, helvetica, sans-serif;" ');
                html_body = html_body.replace(/<div/g, '<div  style="font-size:x-small;font-family:arial, helvetica, sans-serif;" ');
				final_html_body = '<div  style="font-size:x-small;font-family:arial, helvetica, sans-serif;" >'+html_body+'</div>';
				
                this.model.set('html_body', final_html_body);
                sendEmail();
                
                /*
                 * Please do not delete the following code. We would need it in future to handle all font sizes
                 *
                html_body = html_body.replace(/style="font-size:xx-small/g, '<p style="font-size:8px');
                html_body = html_body.replace(/style="font-size:x-small/g, '<p style="font-size:10px');
                html_body = html_body.replace(/style="font-size:small/g, '<p style="font-size:12px');
                html_body = html_body.replace(/style="font-size:medium/g, '<p style="font-size:14px');
                html_body = html_body.replace(/style="font-size:large/g, '<p style="font-size:18px');
                html_body = html_body.replace(/style="font-size:x-large/g, '<p style="font-size:24px');
                html_body = html_body.replace(/style="font-size:xx-large/g, '<p style="font-size:36px');*/
        }
    },
})
