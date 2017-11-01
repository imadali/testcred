({
    /**
     * CRED-804 : Adjustment of listener for ingoing leads
     */
    configJSON : [],
    configData : [],
    freshData : 'new',
    events: {
        'click #save_config': 'performValidation',
    },
    
    initialize: function (options) {
        this._super('initialize', [options]);
    },
    _renderHtml: function () {
        this._super('_renderHtml');
    },
    
    loadData : function() {
        var count = 1;
        var url = App.api.buildURL("retrieveLeadListenerConfig", null, null);
        App.api.call('read', url, null, {
            success: _.bind(function (response) {
                this.configData = JSON.parse(response.configuration);
                _.each(this.configData, _.bind(function (value) {
                     _.each(value, _.bind(function (val, key) {
                         _.each(val, _.bind(function (valuez, keyz) {
                            this.model.set('day_'+key+'_'+count,valuez.day);
                            this.model.set('time_to_'+key+'_'+count,valuez.to);
                            this.model.set('time_from_'+key+'_'+count,valuez.from);
                            this.model.set('emails_'+key+'_'+count,valuez.email);
                            this.model.set('enable_'+key+'_'+count,valuez.enable);
                            count +=1;
                        },this));
                        count = 1;
                     },this));
                },this));


                this.freshData = 'old';
                $('[name="emails_monday_1"]').attr('style','width:97%');
                $('[name="emails_monday_2"]').attr('style','width:97%');
                $('[name="emails_tuesday_1"]').attr('style','width:97%');
                $('[name="emails_tuesday_2"]').attr('style','width:97%');
                $('[name="emails_wednesday_1"]').attr('style','width:97%');
                $('[name="emails_wednesday_2"]').attr('style','width:97%');
                $('[name="emails_thursday_1"]').attr('style','width:97%');
                $('[name="emails_thursday_2"]').attr('style','width:97%');
                $('[name="emails_friday_1"]').attr('style','width:97%');
                $('[name="emails_friday_2"]').attr('style','width:97%');
                $('[name="emails_saturday_1"]').attr('style','width:97%');
                $('[name="emails_saturday_2"]').attr('style','width:97%');
                $('[name="emails_sunday_1"]').attr('style','width:97%');
                $('[name="emails_sunday_2"]').attr('style','width:97%');
            }, this),
            error: function () {

            }
        });
    },

    performValidation: function () {
        var allValidated = false;
        var emailValidation = [];
        
        if (this.model.get('enable_monday_1') && (_.isEmpty(this.model.get('time_from_monday_1')) ||
                _.isEmpty(this.model.get('time_to_monday_1')) || _.isEmpty(this.model.get('emails_monday_1'))) 
        ) {
            allValidated = true;
        } else if(this.model.get('enable_monday_1') && this.model.get('emails_monday_1')) {
             emailValidation.push(this.validateEmail(this.model.get('emails_monday_1')));
        }
        
        if (this.model.get('enable_monday_2') && (_.isEmpty(this.model.get('time_from_monday_2')) ||
                _.isEmpty(this.model.get('time_to_monday_2')) || _.isEmpty(this.model.get('emails_monday_2')))
        ) {
            allValidated = true;
        } else if(this.model.get('enable_monday_2') && this.model.get('emails_monday_2')) {
             emailValidation.push(this.validateEmail(this.model.get('emails_monday_2')));
        }
        
        if (this.model.get('enable_tuesday_1') && (_.isEmpty(this.model.get('time_from_tuesday_1')) ||
                _.isEmpty(this.model.get('time_to_tuesday_1')) || _.isEmpty(this.model.get('emails_tuesday_1')))
        ) {
            allValidated = true;
        } else if(this.model.get('enable_tuesday_1') && this.model.get('emails_tuesday_1')) {
             emailValidation.push(this.validateEmail(this.model.get('emails_tuesday_1')));
        }
        
        if (this.model.get('enable_tuesday_2') && (_.isEmpty(this.model.get('time_from_tuesday_2')) ||
                _.isEmpty(this.model.get('time_to_tuesday_2')) || _.isEmpty(this.model.get('emails_tuesday_2')))
        ) {
            allValidated = true;
        } else if(this.model.get('enable_tuesday_2') && this.model.get('emails_tuesday_2')) {
             emailValidation.push(this.validateEmail(this.model.get('emails_tuesday_2')));
        }
        
        if (this.model.get('enable_wednesday_1') && (_.isEmpty(this.model.get('time_from_wednesday_1')) ||
                _.isEmpty(this.model.get('time_to_wednesday_1')) || _.isEmpty(this.model.get('emails_wednesday_1')))
        ) {
            allValidated = true;
        } else if(this.model.get('enable_wednesday_1') && this.model.get('emails_wednesday_1')) {
             emailValidation.push(this.validateEmail(this.model.get('emails_wednesday_1')));
        }
        
        if (this.model.get('enable_wednesday_2') && (_.isEmpty(this.model.get('time_from_wednesday_2')) ||
                _.isEmpty(this.model.get('time_to_wednesday_2')) || _.isEmpty(this.model.get('emails_wednesday_2')))
        ) {
            allValidated = true;
        } else if(this.model.get('enable_wednesday_2') && this.model.get('emails_wednesday_2')) {
             emailValidation.push(this.validateEmail(this.model.get('emails_wednesday_2')));
        }
        
        if (this.model.get('enable_thursday_1') && (_.isEmpty(this.model.get('time_from_thursday_1')) ||
                _.isEmpty(this.model.get('time_to_thursday_1')) || _.isEmpty(this.model.get('emails_thursday_1')))
        ) {
            allValidated = true;
        } else if(this.model.get('enable_thursday_1') && this.model.get('emails_thursday_1')) {
             emailValidation.push(this.validateEmail(this.model.get('emails_thursday_1')));
        }
        
        if (this.model.get('enable_thursday_2') && (_.isEmpty(this.model.get('time_from_thursday_2')) ||
                _.isEmpty(this.model.get('time_to_thursday_2')) || _.isEmpty(this.model.get('emails_thursday_2')))
        ) {
            allValidated = true;
        } else if(this.model.get('enable_thursday_2') && this.model.get('emails_thursday_2')) {
             emailValidation.push(this.validateEmail(this.model.get('emails_thursday_2')));
        }
        
        if (this.model.get('enable_friday_1') && (_.isEmpty(this.model.get('time_from_friday_1')) ||
                _.isEmpty(this.model.get('time_to_friday_1')) || _.isEmpty(this.model.get('emails_friday_1')))
        ) {
            allValidated = true;
        } else if(this.model.get('enable_friday_1') && this.model.get('emails_friday_1')) {
             emailValidation.push(this.validateEmail(this.model.get('emails_friday_1')));
        }
        
        if (this.model.get('enable_friday_2') && (_.isEmpty(this.model.get('time_from_friday_2')) ||
                _.isEmpty(this.model.get('time_to_friday_2')) || _.isEmpty(this.model.get('emails_friday_2')))
        ) {
            allValidated = true;
        } else if(this.model.get('enable_friday_2') && this.model.get('emails_friday_2')) {
            emailValidation.push(this.validateEmail(this.model.get('emails_friday_2')));
        }
        
        if (this.model.get('enable_saturday_1') && (_.isEmpty(this.model.get('time_from_saturday_1')) ||
                _.isEmpty(this.model.get('time_to_saturday_1')) || _.isEmpty(this.model.get('emails_saturday_1')))
        ) {
            allValidated = true;
        } else if(this.model.get('enable_saturday_1') && this.model.get('emails_saturday_1')) {
             emailValidation.push(this.validateEmail(this.model.get('emails_saturday_1')));
        }
        
        if (this.model.get('enable_saturday_2') && (_.isEmpty(this.model.get('time_from_saturday_2')) ||
                _.isEmpty(this.model.get('time_to_saturday_2')) || _.isEmpty(this.model.get('emails_saturday_2')))
        ) {
            allValidated = true;
        } else if(this.model.get('enable_saturday_2') && this.model.get('emails_saturday_2')) {
             emailValidation.push(this.validateEmail(this.model.get('emails_saturday_2')));
        }
        
        if (this.model.get('enable_sunday_1') && (_.isEmpty(this.model.get('time_from_sunday_1')) ||
                _.isEmpty(this.model.get('time_to_sunday_1')) || _.isEmpty(this.model.get('emails_sunday_1')))
        ) {
            allValidated = true;
        } else if(this.model.get('enable_sunday_1') && this.model.get('emails_sunday_1')) {
             emailValidation.push(this.validateEmail(this.model.get('emails_sunday_1')));
        }
        
        if (this.model.get('enable_sunday_2') && (_.isEmpty(this.model.get('time_from_sunday_2')) ||
                _.isEmpty(this.model.get('time_to_sunday_2')) || _.isEmpty(this.model.get('emails_sunday_2')))
        ) {
            allValidated = true;
        } else if(this.model.get('enable_sunday_2') && this.model.get('emails_sunday_2')) {
             emailValidation.push(this.validateEmail(this.model.get('emails_sunday_2')));
        }
        
        if(allValidated) {
            app.alert.show("missing-data", {
                level: 'error',
                messages: app.lang.get('LBL_ALL_FIELDS_CHECK', 'Leads'),
                autoClose: true
            });
        }

        if(!_.contains(emailValidation, false) && allValidated == false) {
            this.saveConfiguration();
            emailValidation = [];
        }
        
     },
    
    saveConfiguration: function () {
        var config_one = {};
        var config_two = {};
        this.configJSON = [];
        config_one = {'day': this.model.get('day_monday_1'), 'to': this.model.get('time_to_monday_1'),
            'from': this.model.get('time_from_monday_1'), 'email': this.model.get('emails_monday_1'), 'enable': this.model.get('enable_monday_1') ? 1 : 0};
        config_two = {'day': this.model.get('day_monday_2'), 'to': this.model.get('time_to_monday_2'),
            'from': this.model.get('time_from_monday_2'), 'email': this.model.get('emails_monday_2'), 'enable': this.model.get('enable_monday_2') ? 1 : 0};
        this.configJSON.push({'monday': [config_one,config_two]});

        config_one = {'day': this.model.get('day_tuesday_1'), 'to': this.model.get('time_to_tuesday_1'),
            'from': this.model.get('time_from_tuesday_1'), 'email': this.model.get('emails_tuesday_1'), 'enable': this.model.get('enable_tuesday_1') ? 1 : 0};
        config_two = {'day': this.model.get('day_tuesday_2'), 'to': this.model.get('time_to_tuesday_2'),
            'from': this.model.get('time_from_tuesday_2'), 'email': this.model.get('emails_tuesday_2'), 'enable': this.model.get('enable_tuesday_2') ? 1 : 0};
        this.configJSON.push({'tuesday': [config_one,config_two]});

        config_one = {'day': this.model.get('day_wednesday_1'), 'to': this.model.get('time_to_wednesday_1'),
            'from': this.model.get('time_from_wednesday_1'), 'email': this.model.get('emails_wednesday_1'), 'enable': this.model.get('enable_wednesday_1') ? 1 : 0};
        config_two = {'day': this.model.get('day_wednesday_2'), 'to': this.model.get('time_to_wednesday_2'),
            'from': this.model.get('time_from_wednesday_2'), 'email': this.model.get('emails_wednesday_2'), 'enable': this.model.get('enable_wednesday_2') ? 1 : 0};
        this.configJSON.push({'wednesday': [config_one,config_two]});

        config_one = {'day': this.model.get('day_thursday_1'), 'to': this.model.get('time_to_thursday_1'),
            'from': this.model.get('time_from_thursday_1'), 'email': this.model.get('emails_thursday_1'), 'enable': this.model.get('enable_thursday_1') ? 1 : 0};
        config_two = {'day': this.model.get('day_thursday_2'), 'to': this.model.get('time_to_thursday_2'),
            'from': this.model.get('time_from_thursday_2'), 'email': this.model.get('emails_thursday_2'), 'enable': this.model.get('enable_thursday_2') ? 1 : 0};
        this.configJSON.push({'thursday': [config_one,config_two]});

        config_one = {'day': this.model.get('day_friday_1'), 'to': this.model.get('time_to_friday_1'),
            'from': this.model.get('time_from_friday_1'), 'email': this.model.get('emails_friday_1'), 'enable': this.model.get('enable_friday_1') ? 1 : 0};
        config_two = {'day': this.model.get('day_friday_2'), 'to': this.model.get('time_to_friday_2'),
            'from': this.model.get('time_from_friday_2'), 'email': this.model.get('emails_friday_2'), 'enable': this.model.get('enable_friday_2') ? 1 : 0};
        this.configJSON.push({'friday': [config_one,config_two]});

        config_one = {'day': this.model.get('day_saturday_1'), 'to': this.model.get('time_to_saturday_1'),
            'from': this.model.get('time_from_saturday_1'), 'email': this.model.get('emails_saturday_1'), 'enable': this.model.get('enable_saturday_1') ? 1 : 0};
        config_two = {'day': this.model.get('day_saturday_2'), 'to': this.model.get('time_to_saturday_2'),
            'from': this.model.get('time_from_saturday_2'), 'email': this.model.get('emails_saturday_2'), 'enable': this.model.get('enable_saturday_2') ? 1 : 0};
        this.configJSON.push({'saturday': [config_one,config_two]});

        config_one = {'day': this.model.get('day_sunday_1'), 'to': this.model.get('time_to_sunday_1'),
            'from': this.model.get('time_from_sunday_1'), 'email': this.model.get('emails_sunday_1'), 'enable': this.model.get('enable_sunday_1') ? 1 : 0};
        config_two = {'day': this.model.get('day_sunday_2'), 'to': this.model.get('time_to_sunday_2'),
            'from': this.model.get('time_from_sunday_2'), 'email': this.model.get('emails_sunday_2'), 'enable': this.model.get('enable_sunday_2') ? 1 : 0};
        this.configJSON.push({'sunday': [config_one,config_two]});
        
        var configData = JSON.stringify(this.configJSON);
        var url = App.api.buildURL("saveLeadListenerConfig", null, null);
                
        app.api.call('create', url, {'configuration': configData, 'type': this.freshData}, {
            success: _.bind(function (response) {
                App.alert.dismiss('saving-message');
                app.alert.show("success", {
                    level: 'success',
                    messages: app.lang.get('LBL_DATA_SAVED_SUCCESS', 'Leads'),
                    autoClose: true
                });
                this.configJSON = [];

                SUGAR.App.router.navigate("Administration", {trigger: true});
            }, this),
            error: function () {
                app.alert.show("success", {
                    level: 'error',
                    messages: app.lang.get('LBL_DATA_SAVED_FAIL', 'Leads'),
                    autoClose: true
                });
                this.configJSON = [];
            }
        });

    },
    
    validateEmail: function (email) {
        var keepChecking = false;
        if (email.indexOf(';') > -1) {
            var emails = [];
            emails = email.split(";");
            _.each(emails, function (value) {
                if (!_.isEmpty(value)) {
                    if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(value)) {
                        keepChecking= true;
                    }
                }
            });
        } else {
            if (!_.isEmpty(email)) {
                if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)) {
                    keepChecking= false;
                } else {
                    keepChecking= true;
                }
            }
        }

        if(keepChecking == true) {
            App.alert.dismiss('incorrect-email');
            app.alert.show("incorrect-email", {
                level: 'error',
                messages: app.lang.get('LBL_INVALID_EMAIL', 'Leads'),
                autoClose: true
            });
            return false;
        } else {
            return true;
        }

    }
})
