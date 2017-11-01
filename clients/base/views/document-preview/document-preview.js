({
    className: 'document-preview',
    displayImagesInfo: {},
    documents_ID: [],
    displayEmpty : false,
    previewSingleDoc: false,
    previewAllDoc: false,
    scrollPosition: 0,
    swapeDirections: null,
    events: { 
        'click #merge': 'mergeDocuments',
        'click #open_in_new_tab': 'openInNewTab',
        'click #close_merge_view' : 'closeMergeView',
        'click #mark_all_pages' : 'markAllPagesSelected',
        'click #show_full_document' : 'showFullDocument',
        
        'click #rotate_clock_wise' : 'rotateClockWiseImage',
        'click #anti_rotate_clock_wise' : 'rotateAntiClockWiseImage',
        'click #swap_up' : 'swapPagesUpward',
        'click #swap_down' : 'swapPagesDownward',
    },
    
    disableCrossBtn : null,
    preview_module_name : null,
    preview_record_id : null,
    preventRender: false,
      
    initialize: function(options) {
        this._super('initialize', [options]);
    },
    _renderHtml: function() {
        this._super('_renderHtml');
        this.triverseImageDiv();
    },
    rotateClockWiseImage: function(e) {
        var self = this;
        self.rotateImage(e, 'clockwise');
    },
    rotateAntiClockWiseImage: function(e) {
        var self = this;
        self.rotateImage(e, 'anti-clockwise');
    },
    rotateImage: function(e,rotation) {
        var self = this;
        self.scrollPosition=$('.sidebar-content').scrollTop();
        self.swapeDirections = null;
        var url = app.api.buildURL('rotateImageOnDocPreview', null, null, null);
        
        app.alert.show('initialize', {
            level: 'process',
            messages: 'loading'
        });
        
        var custom_doc_id = $(e.currentTarget).attr('custom_document_id');
        var doc_id = $(e.currentTarget).attr('document_id');
        var image_path = JSON.stringify($(".zoom_in_zoom_out[document_id='"+custom_doc_id+"']").attr('src'));
        
        app.api.call('create', url, {'image_path': image_path, 'rotation': rotation }, {
            success: function () {
                app.alert.dismiss('initialize');
                if (self.previewSingleDoc) {
                    var module_name = 'Documents';
                    self.previewPDFDocRender(module_name, doc_id);
                } else if (self.previewAllDoc) {
                    var id = self.model.get('id');
                    self.previewPDFDocRender(self.module, id);
                } else {
                    self.scrollPosition = $('.drawer.active .sidebar-content').scrollTop();
                    if ($('.leadconvert-document-preview').length > 0) {
                        self.previewRender(self.documents_ID);
                    } else {
                        app.events.trigger('previewSendDocuments');
                    }
                }
                /*
                 * The following logic is used to refresh imags without refreshing preview
                 *
                var src = $(".zoom_in_zoom_out[document_id='"+custom_doc_id+"']").attr('src');
                src = src.split("?");
                src = src[0];
                var new_src=src+'?'+Math.random();
                $('.zoom_in_zoom_out[document_id="'+custom_doc_id+'"]').attr('src',new_src);
                */
            },
            error: function (error) {
                app.alert.show("server-error", {
                    level: 'error',
                    messages: error,
                    autoClose: false
                });
            }
        });
    },
    scrollPreview: function (view) {
        var scrollup = this.scrollPosition;
        if (this.swapeDirections == 'up') {
            scrollup = scrollup - 250;
        } else if (this.swapeDirections == 'down') {
            scrollup = scrollup + 250;
        }
        
        if (view == 'main') {
             $('.sidebar-content').scrollTop(scrollup);
        } else {
            $('.drawer.active .sidebar-content').scrollTop(scrollup);
        }
    },
    triverseImageDiv: function () {
        var self = this;
        var doc_ids = [];
        $('.zoom_in_zoom_out').each(function () {
            
            var doc_id = $(this).attr('document_id');
            var temp_doc_id = doc_id.split('_');
            temp_doc_id=temp_doc_id[0];
            doc_ids.push(temp_doc_id);
           
            var current_element = $(".zoom_in_zoom_out[document_id='" + doc_id + "']");
            current_element.smoothZoom({
                pan_BUTTONS_SHOW: true,
                zoom_BUTTONS_SHOW: true,
                zoom_OUT_TO_FIT: true,
                background_COLOR: "transparent",
                border_SIZE: 0,
                responsive: true,
                responsive_maintain_ratio: true,
                initial_ZOOM: 0,
                zoom_MIN: 100,
                zoom_MAX: 600,
                initial_POSITION: "0 0",
                height: 430,
                transformSet: true
            });
        });
        doc_ids = $.unique(doc_ids);
        self.documents_ID = doc_ids;
        self.scrollPreview('main');
        /**
         * CRED-718 : The image in the preview is getting out of 
         * the boundry line
         */
        if (!_.isUndefined($('.noSel.smooth_zoom_preloader').attr('style'))) {
            var style = $('.noSel.smooth_zoom_preloader').attr('style');
            var index = style.indexOf("width");
            var widthString = style.substring(index, index+13);
            style = style.replace(widthString, '');
            $('.noSel.smooth_zoom_preloader').attr('style', style);
        }
        
    },
    render: function() {
        var self1 = this;
        self1._super('render');
        
        if (!_.isEmpty(self1.displayImagesInfo) && !_.isUndefined(self1.displayImagesInfo)) {
            $("#close_merge_view").removeClass('disabled');
            $("#merge").removeClass('disabled');
            $('#mark_all_pages').removeClass('disabled');
        }
        else{
            $('#close_merge_view').addClass('disabled');
            $('#merge').addClass('disabled');
            $('#mark_all_pages').addClass('disabled');
        }

        // to hide/disable merge/close of preview PDF on Send Document view
        if($('#hide_preview_buttons').val()) {
            $('#merge').hide();
            $('#close_merge_view').hide();
        } 

        app.alert.dismiss('initialize');
        app.alert.show('initialize', {
            level: 'process',
            messages: 'loading',
        });
        
        if(self1.model  &&  _.isEmpty(self1.displayImagesInfo) &&  !self1.preventRender ) {
            var record_id;
            var module_name;
            
            // For Preview of Documents on Leads Record View
            if(!_.isEmpty(self1.preview_module_name) && !_.isEmpty(self1.preview_record_id)){
                record_id = self1.preview_record_id;
                module_name = self1.preview_module_name;
            }
            else if(self1.displayEmpty){
                record_id = '';
                module_name = '';
            }
            else if(self1.model.get('id')){
                record_id = self1.model.get('id');
                module_name  = self1.module;
            }
            
            if(record_id && module_name) {
                var url = app.api.buildURL('convertDocToPDF/'+record_id+'/'+module_name, null, null, null);
                app.api.call('GET', url, null, {
                    success: function (data) {
                        app.alert.dismiss('initialize');
                        if(_.isEqual(data, false) ){
                            self1.preventRender = true;
                            self1.displayImagesInfo = {};
                        }else{
                            self1.preventRender = false;
                            self1.displayImagesInfo = data;
                        }
                        self1.render();
                    }
                }); 
            }
            else{
                app.alert.dismiss('initialize');
                self1.preventRender = true;
                self1.displayImagesInfo = {};
                self1.render();
            }
        }
        else{
            app.alert.dismiss('initialize');
            self1.preventRender = false;
        }
    },   
    
    mergeDocuments: function () {
        var self =  this;
        $('#merge').addClass('disabled');
        $('#mark_all_pages').addClass('disabled');
        var pdf_info = [];
        var readyForMerged = ($('input[name=images_checkbox]:checked').length > 0) ? true : false;
        
        $('input[name=images_checkbox]:checked').each(function () {
            pdf_info.push({'page_number': $(this).attr('page_number'), 'total_pages': $(this).attr('total_pages'), 'pdf_file_path': $(this).attr('pdf_file_path') , 'document_id': $(this).attr('document_id') });
        });
         
        if (!readyForMerged ) {
            $('#merge').removeClass('disabled');
            $('#mark_all_pages').removeClass('disabled');
            app.alert.dismissAll();
            app.alert.show('merge-initialize', {
                level: 'info',
                messages: app.lang.get('LBL_MERGE_NOT_POSSIBLE', 'Leads'),
                autoClose: true,
                autoCloseDelay: 4000,
            });
        } 
        else {
            var url = app.api.buildURL('mergeSelectedPagesIntoPDF', null, null, null);
            var module_name = self.model.module;
            var record_id = self.model.get('id');
            
            app.alert.show('initialize', {
                level: 'process',
                messages: 'loading'
            });
            
            app.api.call('create', url, {'pdf_info': pdf_info, 'module_name': module_name, record_id: record_id }, {
                success: function (response) {
                    app.alert.dismissAll();
                    app.alert.show('merge-document', {
                        level: response.level,
                        messages: response.message,
                        autoClose: true,
                        autoCloseDelay: 5000,
                    });
                    
                    $('#merge').removeClass('disabled');
                    $('#mark_all_pages').removeClass('disabled');
                    
                    if(!_.isEqual(response.level,'error')){
                        app.events.trigger('refreshDocumentPanel');

                        self.displayImagesInfo = '';
                        self.preview_module_name = self.model.module;
                        self.preview_record_id = self.model.get('id');

                        self.render();
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
        }
    },
    
    openInNewTab: function(e){
        var page_number = $(e.currentTarget).attr('page_number');
        var pdf_file_path = $(e.currentTarget).attr('pdf_file_path');
        
        window.open('index.php?entryPoint=customMergeDownload&num='+page_number+'&file='+pdf_file_path,'_blank');
    },
    
    previewRender: function(data){
        var self1 = this;
        if(!_.isEmpty(data)){
            var d1 = data;
            var mod_name = 'Documents';
            var url = app.api.buildURL('convertDocToPDF/'+JSON.stringify(d1)+'/'+mod_name, null, null, null);
                app.api.call('GET', url, null, {
                    success: function (data) {
                        app.alert.dismiss('initialize');
                        self1.displayImagesInfo = data;
                        
                        if(!$('.document-preview').is(":visible")){
                            $('.document-preview').show();
                        }
                        
                        self1.render();
                        self1.scrollPreview('other_view');
                    }
                }); 
        }
    },
    
    previewPDFDocRender: function(module_name, record_id){
        var self1 = this;
        self1.displayImagesInfo = '';
        self1.preview_module_name = module_name;
        self1.preview_record_id = record_id;
        
        if(!$('.document-preview').is(":visible")){
            $('.document-preview').show();
        }
        
        self1.render();
    },
    
    previewEmptyView: function(){
        var self1 = this;
        self1.displayImagesInfo = '';
        self1.preview_module_name = '';
        self1.preview_record_id = '';
        self1.displayEmpty = true;
        
        self1.render();
    },
    
    closeMergeView: function(){
        $('.document-preview').html('');
        $('.document-preview').hide();
        $('.dashboard-pane').show();
    },
    
    markAllPagesSelected: function(e){
        if($('input[name=images_checkbox]')){
            $('input[name=images_checkbox]').attr('checked',true);
        }
    },
	    
    showFullDocument: function (e) {
        var doc_id = $(e.currentTarget).attr('doc_id');
        var pdf_path = $(e.currentTarget).attr('pdf_file_path');
        window.open('index.php?entryPoint=customMergeDownload&doc_id=' + doc_id + '&pdf_path=' + pdf_path, '_blank');
    },
    
    swapPagesUpward: function(e){
        this.swapeDirections = 'up';
        var custom_doc_id = $(e.currentTarget).attr('custom_document_id');
        var page = custom_doc_id.split("_")[1];
        if(page == 0){
            app.alert.show("page-limit", {
                level: 'error',
                messages: app.lang.getAppString('LBL_NO_PAGE_BEYOND'),
                autoClose: false
            });
        }
        else{
            this.swapPages(e , 'up');
        }
    },
    
    swapPagesDownward : function(e){
        this.swapeDirections = 'down';
        this.swapPages(e , 'down');
    },
    
    swapPages: function(e , direction){
        var self = this;
        self.scrollPosition=$('.sidebar-content').scrollTop();
        try {
            var url = app.api.buildURL('swapPagesInDocument', null, null, null);
            app.alert.show('swap-message', {
                level: 'process',
                messages: app.lang.getAppString('LBL_PAGE_ORDER_CHANGE'),
            });
            var doc_id = $(e.currentTarget).attr('document_id');
            var custom_doc_id = $(e.currentTarget).attr('custom_document_id');                                                                                              
            var image_path = JSON.stringify($(".zoom_in_zoom_out[document_id='"+custom_doc_id+"']").attr('src'));

            var doc_info = custom_doc_id.split("_");
            var page = parseInt(doc_info[1]);

            if(direction == "up" && page != 0){
                page = page -1;
            }
            if(direction == "down"){
                page = page + 1;
            }
            
            app.api.call('create', url, {'image_path': image_path, 'direction': direction }, {
                success: function (response) {
                    app.alert.dismiss('swap-message');
                    app.alert.show("page-error", {
                        level: response.level,
                        messages: response.message,
                        autoClose: false
                    });
                    if (response.level == 'success') {
                        app.alert.dismiss('initialize');
                        if (self.previewSingleDoc) {
                            var module_name = 'Documents';
                            self.previewPDFDocRender(module_name, doc_id);
                        } else if (self.previewAllDoc) {
                            var id = self.model.get('id');
                            self.previewPDFDocRender(self.module, id);
                        } else {
                            self.scrollPosition = $('.drawer.active .sidebar-content').scrollTop();
                            if ($('.leadconvert-document-preview').length > 0) {
                                self.previewRender(self.documents_ID);
                            } else {
                                app.events.trigger('previewSendDocuments');
                            }
                        }
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
        }
        catch(err) {
            console.error(err.message);
        }
    }
    
})
