({

    extendsFrom: 'RecordView',

    zipJSON: {},

    initialize: function (options) {
        this._super('initialize', [options]);

        //add listener for custom button
        this.context.on('button:sync_to_evalanche:click', this.sync_to_evalanche, this);
    },

    sync_to_evalanche: function() {
        //example of getting field data from current record
        var AcctID = this.model.get('id');

        //jQuery AJAX call to Zippopotamus REST API
        $.ajax({
            beforeSend: function (request)
            {
                app.alert.show('eva-sync-ok', {
                    level: 'success',
                    messages: 'Please wait... Sync.is running, be patient...',
                    autoClose: false
                });

                request.setRequestHeader("OAuth-Token", SUGAR.App.api.getOAuthToken());
            },
            url: 'rest/v10/K_EvaCampaigns/sync_to_Evalanche?record=' + AcctID,
            success: function(data) {
		    var obj = JSON.parse(data);

                    app.alert.show('eva-sync-ok', {
                        level: obj.success?'success':'error',
                        messages: obj.msg,
                        autoClose: true
                    });
                }
            });
    }
})