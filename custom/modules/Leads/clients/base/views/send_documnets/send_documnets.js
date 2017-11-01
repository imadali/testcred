({
    collectionslip: null,
    email_to: '',
    lead_id: '',
	layoutPreview : null,
    documentsID : [],
    sendEmailButtonClicked : false,
	
    initialize: function (options) {
        this._super('initialize', [options]);
        var self = this;
        this.meta = _.extend({}, app.metadata.getView(this.module, 'record'), this.meta);
        //var record_id = options.context.attributes.id;
        this.context.on('button:close_button:click', this.closeButton, this);
        var beanId = this.options.context.attributes.id;
        this.email_to = this.options.context.attributes.email;
        this.lead_id = beanId;
        app.events.on('previewSendDocuments', _.bind(this.previewSelectedDocuments, this));
        app.events.on('updateSendEmailButton', _.bind(this.updateSendEmailButton, this));
        //app.events.on('openSendDocAttachmentInNewTab', _.bind(this.openSendDocAttachmentInNewTab, this));
        
        app.alert.show('doc-info-loading', {
            level: 'process',
            title: 'Loading Documents information',
            autoClose: true
        });
        var url = "rest/v10/Leads/GetRelatedDocumentsId/" + beanId;
        app.api.call('GET', url, null, {
            success: function (response) {
                var no_file = true;
                for (var key in response) {
                    no_file = false;
                    
                    var html_category = '';
                    var html_notes = '';
                    var html_month = '';
                    var i =0 ,j=0, k=0;
                    for(var k1 in response[key]['category']){
                        if(i!==0){html_category+='<br>';}
                        i++;
                        html_category+='<span style="display:block;" class="ellipsis_inline" title="'+response[key]['category'][k1]+'">'+response[key]['category'][k1]+'</span>';
                    }
                    
                    for(var k2 in response[key]['notes']){
                        if(j!==0){html_notes+='<br>';}
                        j++;
                        html_notes+='<span style="display:block;" class="ellipsis_inline" title="'+response[key]['notes'][k2]+'">'+response[key]['notes'][k2]+'</span>';
                    }
                    for(var k3 in response[key]['month']){
                        if(k!==0){html_month+='<br>';}
                        k++;
                        html_month+='<span style="display:block;" class="ellipsis_inline" title="'+response[key]['month'][k3]+'">'+response[key]['month'][k3]+'</span>';
                    }
                    
                    $("#select_documents").append('<tr>'+
                                                    '<td>'+
                                                    '<input type="checkbox" checked name="document_preview_selected" id="' + key + '">'+
                                                    '&nbsp;&nbsp;<a title="'+response[key]['name']+'"  class="ellipsis_inline" href="#Documents/' + key + '">' + response[key]['name'] + '</a></td>'+
                                                    '<td>'+html_category+'</td>'+
                                                    '<td>'+html_month+'</td>'+
                                                    '<td>'+html_notes+'</td></tr>');
                }
                if (no_file) {
                    $("#select_documents").append('<tr><td></td><td style="width: 23%;">No Document found</td></tr>');
                }
                $("#select_documents table").css("clear", "both !important");
                $("#comm_chan td").css("padding-left", "20px");
                $(".drawer").css("background-color", "#f6f6f6");
            },
            error: function (error) {
                console.log("Error" + error);
            }
        });
        var url = "rest/v10/Leads/getRelatedApp/" + beanId;
        app.api.call('GET', url, null, {
            success: function (data) {
                var no_file = true;
                for (var key in data) {
                    no_file = false;
                    var text = data[key].split("=-=-=");
                    var name = text[0];
                    var bank = text[1];
                    $("#select_app").append('<tr><td><input bank="' + bank + '" type="radio" name="selected_app" value="' + key + '"><span style="margin-left: 20px;"><a href="#Opportunities/' + key + '">' + name + '</a></span></td></tr>');
                }
                if (no_file) {
                    $("#select_app").append('<tr><td>No Related Application was found</td></tr>');
                }
                $("#select_app td").css("padding-left", "20px");
            },
            error: function (error) {
                console.log("Error" + error);
            }
        });

    },
    updateSendEmailButton: function(){
        this.sendEmailButtonClicked = true;  
    },
    events: {
        'click input[name=select_all]': 'selectAll',
        'click a[name=close_button]': 'close',
        'click a[name=compose_email]': 'composeEmail',
		'click input[name=document_preview_selected]' : 'previewSelectedDocuments',
    },
    close: function (evt) {
        app.drawer.close();
		app.events.trigger('refreshActivitiesAferSendDocuments');
    },
    selectAll: function (evt) {
        var select_all = $('#select_all input[type=checkbox]:checked').val();
    },
    composeEmail: function (evt) {
		var self = this; 
        var send_via = $("input[name=send_via]:checked").val();
        var selected_app = $("input[name=selected_app]:checked").val();
        var bank = $("input[name=selected_app]:checked").attr('bank');
        var selected_document = $("input[name=document_preview_selected]:checked").length;
        var selected_document_pages = $("input[name=images_checkbox]:checked").length;
        var documentModel = [], id = '', ids = '';
        var attachments = [];
		
		var pdf_info = [];
        $('input[name=images_checkbox]:checked').each(function () {
            pdf_info.push({'page_number': $(this).attr('page_number'), 'pdf_file_path': $(this).attr('pdf_file_path') });
        });
        $('#select_documents input[type=checkbox]:checked').each(function () {
            id = $(this).attr('id');
            if (ids == '') {
                ids = id;
            } else {
                ids = ids + '_' + id;
            }
            /*attachments.push({
                id: id
            });
            documentModel.push(app.data.createBean('Documents', {id: id}));*/
        });
		
        $('#comm_chan_err').hide();
        if (send_via == 'email') {
            if (selected_app == '') {
                app.alert.show('error', {level: 'error', messages: "Please select an application", autoClose: true, autoCloseDelay: 8000});
            }
			/*else if(!(selected_document && selected_document_pages) ){
                app.alert.show('error', 
                {level: 'error',
                    messages: app.lang.get('LBL_SELECT_AT_LEAST_ONE_PAGE', 'Leads'),
                    autoClose: true,
                    autoCloseDelay: 8000,
                });
            }*/
            else {
                app.alert.show('loading', {level: 'process', title: "Please Wait!", autoClose: false, autoCloseDelay: 8000});
                var lead_id = this.lead_id;
                var url = App.api.buildURL('Leads/getRelatedBank');
                app.api.call('create', url, {
                    app_id: selected_app,
                    lead_id: this.lead_id,
                    pdf_info: JSON.stringify(pdf_info),
                }, {
                    success: _.bind(function (serverData) {
                        var emailTemplate = app.data.createBean('EmailTemplates', {id: serverData.email_template});
                        var toAddress = [];

                        if (!_.isEmpty(serverData.document_id)) {
                            // For attaching dummy merged document to Send Email
                            documentModel.push(app.data.createBean('Documents', {id: serverData.document_id}));
                        }
                        
                        var bankEmails=serverData.emails;
                        
                        bankEmails.forEach(function (bankEmail) {
                            toAddress.push({
                                email: bankEmail,
                                name: bankEmail
                            });
                        });
                        
                        App.alert.dismissAll();

                        var templateModelVal = null, subjectVal = '';
                        if (bank != 'cash_gate_cashmoney') {
                            templateModelVal = emailTemplate;
                            subjectVal = serverData.subject;
                        }
                        app.drawer.close();
                        app.drawer.open({
                            layout: 'compose',
                            context: {
                                create: true,
                                module: 'Emails',
                                documentModel: documentModel,
                                templateModel: templateModelVal,
                                prepopulate: {
                                    subject: subjectVal,
                                    to_addresses: toAddress,
                                    selected_app: selected_app,
                                    lead_id: lead_id,
                                    attachments: attachments,
                                    doc_id: serverData.document_id,
                                    placement: 'bottom',
                                    action: 'email',
                                    preFillAttachement: true,
                                }
                            }
                        },
                        function () {
                            if (self.sendEmailButtonClicked) {
                                self.sendEmailButtonClicked = false;
                                app.drawer.close();
                                app.events.trigger('refreshActivitiesAferSendDocuments');
                            }
                        });
                    }, this),
                    error: function (error) {
                        App.alert.dismissAll();
                        app.alert.show("server-error", {
                            level: 'error',
                            messages: error.message,
                            autoClose: false
                        });
                    },
                });

            }
        } else if (send_via == 'ftp') {
            if(!(selected_document && selected_document_pages) ){
                app.alert.show('error', 
                {level: 'error',
                    messages: app.lang.get('LBL_SELECT_AT_LEAST_ONE_PAGE', 'Leads'),
                    autoClose: true,
                    autoCloseDelay: 8000,
                });
            } else {
                app.alert.show('loading', {level: 'process', title: "Please Wait! It may take time", autoClose: false, autoCloseDelay: 8000});
                var params = {
                    // ids: ids,
                    leadId: this.lead_id,
					pdf_info : pdf_info
                },
                url = app.api.buildURL('Leads/checkConnection', null, null, params);
                app.api.call('read', url, null, {
                    success: function (serverData) {
                        app.alert.dismissAll();
                        if (serverData == true) {
                            app.alert.show('invalid_credentials', {level: 'success', messages: "Documents have been uploaded successfully", autoClose: true, autoCloseDelay: 8000});
                            app.drawer.close();
                        } else if(serverData == -1){
                            app.alert.show('invalid_credentials', {level: 'warning', messages: 'Unable to Upload Document, Please try again.', autoClose: true, autoCloseDelay: 8000});
                        } else {
                            app.alert.show('invalid_credentials', {level: 'warning', messages: serverData, autoClose: true, autoCloseDelay: 8000});
                        }
                        //callback(null, fields, errors);
                    },
                });
            }
        } else {
            app.alert.show('error', {level: 'error', messages: "Please select a communication channel", autoClose: true, autoCloseDelay: 8000});
            // $('#comm_chan_err').show();
        }

    },
	
	_renderHtml: function() {
        this._super('_renderHtml');
        var self = this;
        // Rendering Documents Preview
        var leads_bean = app.data.createBean('Leads', {id: this.lead_id});
        leads_bean.fetch();

        if(leads_bean){
            if(!$('.drawer.active').find('.document-preview').length) {
                    $('.drawer.active').find('.preview-pane').after("<div class='document-preview'></div>");
            }else {
                $('.drawer.active').find('.document-preview').show();
            }

            if($('.drawer.active').find('.document-preview')){
                $el = $('.drawer.active').find('.document-preview');
                ctx = App.context.getContext();
                self.layoutPreview = App.view.createView({name:'document-preview', context: ctx, module: self.module , model: leads_bean}); 
                self.layoutPreview.setElement($($el));
                self.layoutPreview.render();
            }
        }
    },
    
    previewSelectedDocuments: function(e){
        var self1 = this;
        // getting all the ids of checked documents
        if($('input:checkbox[name=document_preview_selected]:checked')){
            self1.documentsID = [];
            $('input:checkbox[name=document_preview_selected]:checked').each(function()
            {  
                self1.documentsID.push($(this).attr('id'));  
            });
        }
        
        if(self1.layoutPreview && $('.drawer.active').find('.document-preview') && !_.isEmpty(self1.documentsID)){
            self1.layoutPreview.previewRender(self1.documentsID);
        }
        else{
            self1.layoutPreview.previewEmptyView();
        }        
    },
    
    _dispose : function(){
        app.events.off('updateSendEmailButton');
        //app.events.off('openSendDocAttachmentInNewTab');
        this._super('_dispose');
    }
})