({
    collectionslip: null,
    lead_id: '',
    initialize: function (options) {
        this._super('initialize', [options]);
        this.meta = _.extend({}, app.metadata.getView(this.module, 'record'), this.meta);
        this.context.on('button:close_button:click', this.closeButton, this);
        this.lead_id = this.options.context.attributes.id;
        this.granted_apps = this.options.context.attributes.granted_apps;
    },
    events: {
        'click a[name=close_button]': 'close',
        'click a[name=create_contract]': 'createContract',
    },
    close: function (evt) {
        app.drawer.close();
    },
    createContract: function (evt) {
        var selected_app = $("input[name=selected_app]:checked").val();
        if (selected_app == '') {
            app.alert.show('error', {level: 'error', messages: "Please select an application", autoClose: true, autoCloseDelay: 8000});
        } else {
            var url = app.api.buildURL('ConvLead/CreateContract');
            app.api.call('create', url, {
                app_id: selected_app,
            }, {
                success: _.bind(function (result) {
                    app.events.trigger('createContract',result);
                }, this),
                error: _.bind(function (response) {
                    app.alert.show('creating-contract-error', {
                        level: 'error',
                        title: 'An error occurred while processing your request',
                        autoClose: false,
                    });
                }, this),
            });
        }

    },
    _renderHtml: function () {
        var self = this;
        this._super('_renderHtml');
        app.alert.show('app-info-loading', {
            level: 'process',
            title: 'Loading Applications',
            autoClose: true
        });
        for (var app_id in self.granted_apps) {
            var app_name = self.granted_apps[app_id];
            $("#select_app").append('<tr><td><input type="radio" name="selected_app" value="' + app_id + '"><span style="margin-left: 20px;"><a href="#Opportunities/' + app_id + '">' + app_name + '</a></span></td></tr>');
        }

        $("#select_app td").css("padding-left", "20px");
    },
    _dispose: function () {
        app.events.off('updateSendEmailButton');
        this._super('_dispose');
    }
})