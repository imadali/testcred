({
    /**
     * CRED-943 : Sharing Dashboards
     */
    activeUsers : [],
    selectedUsers : [],
    
    events: {
        'click #close_drawer' : 'closeDrawer',
        'click #share_drawer' : 'shareDashboard'
    },
    
    initialize: function (options) {
        this._super('initialize', [options]);       
    },
    
    _render: function () {
        this._super('_render');   
    },
    
    loadData: function() {
        var self2model = this;
        
        app.alert.show("user-load", {
            level: 'process',
            title: app.lang.get('LBL_DATA_FETCHING', 'Home'),
            autoClose: false
        });
        
        var users = app.data.createBeanCollection('Users');
        users.fetch({
               fields: ['id', 'user_name','first_name','last_name'],
               filter:[{'status':'Active'}],
               limit: -1,
               params: { order_by: 'first_name:asc,last_name:asc' },
               success: function() {
                   App.alert.dismiss('user-load');
                   _.each(users.models, _.bind(function(model){
                       self2model.activeUsers.push({id : model.get('id'), name : model.get('first_name')+' '+model.get('last_name'), user_name : model.get('user_name')})
                   }),this);
                   self2model.render();
               }
        });
        

    },
    
    closeDrawer : function() {
        app.drawer.close();
    },
    
    shareDashboard : function() {
        var self2view = this;
        self2view.selectedUsers = [];
        
        app.alert.show("dash-share", {
            level: 'process',
            title: app.lang.get('LBL_SHARING_IN_PROGRESS', 'Home'),
            autoClose: false
        });

        
        $('input[name="share_dash"]:checked').each(function() {
            self2view.selectedUsers.push(this.id);
        });

        var url = App.api.buildURL("copyDashboardMeta", null, null);     
        app.api.call('create', url, {selectedUsers: this.selectedUsers, dashbaord: this.context.get('id'), module: this.context.get('module')}, {
            success: _.bind(function (response) {
                if (response == true) {
                    App.alert.dismiss('dash-share');
                    app.alert.show("dash-share", {
                        level: 'success',
                        messages: app.lang.get('LBL_SHARING_SUCCESSFUL', 'Home'),
                        autoClose: true
                    });
                    self2view.selectedUsers = [];
                    App.drawer.close();
                } else {
                    app.alert.show("dash-fail", {
                        level: 'error',
                        messages: app.lang.get('LBL_SHARING_FAIL', 'Home'),
                        autoClose: true
                    });
                    self2view.selectedUsers = [];
                }
            }, this),
            error: function () {
                app.alert.show("dash-fail", {
                    level: 'error',
                    messages: app.lang.get('LBL_SHARING_FAIL', 'Home'),
                    autoClose: true
                });
                self2view.selectedUsers = [];
            }
        });
    }

})