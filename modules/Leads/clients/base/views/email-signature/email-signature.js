({
    custom_salutation: '',
    custom_payoff: '',
    salutation_data : [],
    payOff_data : [],
    salutation_retrieve: '',
    payOff_data_retrieve: '',
    data_type : false,
    
    events: {
        'click #save_settings': 'saveSettings',
    },
            
    initialize: function (options) {
        var self = this;
        app.view.View.prototype.initialize.call(this, options);
        
        if (self.options.meta.panels && this.options.meta.panels[0] && self.options.meta.panels[0].fields) {
            self.custom_salutation = this.options.meta.panels[0].fields;
        }
        if (self.options.meta.panels && this.options.meta.panels[1] && self.options.meta.panels[1].fields) {
            self.custom_payoff = this.options.meta.panels[1].fields;
        }
    },
    
    _renderHtml: function() {
        
        var self = this;
        self._super('_renderHtml');
        
        app.alert.show('initialize', {
            level: 'process',
            messages: 'loading',
        });
        
        var url = App.api.buildURL("retrieveSaluationAndPayOff", null, null);
        App.api.call('read', url, null, {
            success: function (response) {
                app.alert.dismissAll();
                if(!_.isEqual(response,false) && response.saluation && response.payOff ){
                    
                    self.data_type = true;
                    self.salutation_retrieve = JSON.parse(response.saluation);
                    self.payOff_data_retrieve = JSON.parse(response.payOff);
                   
                    _.each(self.salutation_retrieve, function (value, key) {
                        if(_.isEqual(key,0) && $('input[name="signature_saluation_de"]') ){
                            $('input[name="signature_saluation_de"]').val(value.sal);
                        }
                        else if(_.isEqual(key,1) && $('input[name="signature_saluation_fr"]') ){
                            $('input[name="signature_saluation_fr"]').val(value.sal);
                        }
                        else if(_.isEqual(key,2) && $('input[name="signature_saluation_it"]') ){
                            $('input[name="signature_saluation_it"]').val(value.sal);
                        }
                        else if(_.isEqual(key,3) && $('input[name="signature_saluation_en"]') ){
                            $('input[name="signature_saluation_en"]').val(value.sal);
                        }
                    });
                    
                    _.each(self.payOff_data_retrieve, function (value, key) {
                        if(_.isEqual(key,0) && $('input[name="signature_payoff_de"]') ){
                            $('input[name="signature_payoff_de"]').val(value.payOff);
                        }
                        else if(_.isEqual(key,1) && $('input[name="signature_payoff_fr"]') ){
                            $('input[name="signature_payoff_fr"]').val(value.payOff);
                        }
                        else if(_.isEqual(key,2) && $('input[name="signature_payoff_it"]') ){
                            $('input[name="signature_payoff_it"]').val(value.payOff);
                        }
                        else if(_.isEqual(key,3) && $('input[name="signature_payoff_en"]') ){
                            $('input[name="signature_payoff_en"]').val(value.payOff);
                        }
                    });
                }
                
            }
        });
    },
    
    saveSettings: function() {
        var self = this;
        var reqCheck = true;

        // For fetching fields of Saluation
        var fieldsSalOnView = ['signature_saluation_de','signature_saluation_fr','signature_saluation_it','signature_saluation_en'];
        _.each(fieldsSalOnView, function (field, index) {
            var val = '';
            reqCheck = false;
            
            if($('input[name="'+field+'"]').val()){
                val = $('input[name="'+field+'"]').val();
                reqCheck = true;
            }
            
            self.salutation_data.push( {'sal': val} );
        });
        
        // For fetching fields of PayOff
        var fieldsPayOffOnView = ['signature_payoff_de','signature_payoff_fr','signature_payoff_it','signature_payoff_en'];
        _.each(fieldsPayOffOnView, function (field, index) {
            var val = '';
            reqCheck = false;
            
            if($('input[name="'+field+'"]').val()){
                val = $('input[name="'+field+'"]').val();
                reqCheck = true;
            }
            
            self.payOff_data.push({'payOff': val});
        }); 
        
        
        if(reqCheck) {
            var salutation_data = JSON.stringify(self.salutation_data);
            var payOff_data = JSON.stringify(self.payOff_data);

            var url = app.api.buildURL('saveSaluationAndPayOff', null, null, null);
            app.api.call('create', url, {'salutation': salutation_data, 'payOff': payOff_data, 'data_type': self.data_type }, {
                success: function (response) {
                    app.alert.show("success", {
                        level: 'success',
                        messages: 'Data Saved with Success',
                        autoClose: true
                    });
                    
                    SUGAR.App.router.navigate("Administration", {trigger: true});
                }
            });
            
            
        } else{
            app.alert.show("error", {
                level: 'error',
                messages: 'Please Enter Data in all the fields',
                autoClose: false
            });
        }
        
        self.payOff_data = [];
        self.salutation_data = [];
    },  
})
