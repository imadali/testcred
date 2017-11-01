/**
 * CRED-943 : Sharing Dashboards
 */
({
    extendsFrom: 'DashboardHeaderpaneView',
    
    events: {
        'click [name=edit_button]': 'editClicked',
        'click [name=cancel_button]': 'cancelClicked',
        'click [name=create_cancel_button]': 'createCancelClicked',
        'click [name=delete_button]': 'deleteClicked',
        'click [name=add_button]': 'addClicked',
        'click [name=collapse_button]': 'collapseClicked',
        'click [name=expand_button]': 'expandClicked',
        'click [name=share_dashboard_button]': 'shareDashboard',
    },
    
    initialize: function(options) {
        this._super('initialize', [options]);
    },
    
    shareDashboard: function() {
        app.drawer.open({
            layout: 'share-dashboard',
            context: {                
                id: this.model.get('id'),
                module: this.module,
            }
        },function(){
            
        });
    }
})
